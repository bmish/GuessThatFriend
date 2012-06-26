//
//  QuizManager.m
//  GuessThatFriend
//
//  Created on 2/15/12.
//
//

#import "QuizManager.h"
#import "QuizSettings.h"
#import "MCQuestion.h"
#import "JSONKit.h"
#import "Option.h"
#import "GuessThatFriendAppDelegate.h"

#define BASE_URL_ADDR "http://guessthatfriend.jasonsze.com/api/"

@implementation QuizManager

@synthesize questionArray;
@synthesize bufferedFBToken;

- (QuizManager *)initWithFBToken:(NSString *)paramFBToken {
    isRequestInProgress = NO;
    isQuestionNeeded = NO;
    
    questionArray = [[NSMutableArray alloc] initWithCapacity:20];
    bufferedFBToken = paramFBToken;
    
    [self requestQuestionsFromServer];    
    
	return [super init];
}

- (void)connection:(NSURLConnection *)connection didReceiveResponse:(NSURLResponse *)response {
    responseData = [[NSMutableData alloc] init];
}

- (void)connection:(NSURLConnection *)connection didReceiveData:(NSData *)data {
    [responseData appendData:data];
}

- (void)connection:(NSURLConnection *)connection didFailWithError:(NSError *)error {
    [responseData release];
    [connection release];
    // Show error message
}

- (void)connectionDidFinishLoading:(NSURLConnection *)connection {
    // Use responseData.
    NSMutableString *responseString = [[[NSMutableString alloc] initWithData:responseData
                                                        encoding:NSASCIIStringEncoding] autorelease];
    
    // Release connection vars.
    [responseData release];
    [connection release];
    
    [self receivedQuestionResponse:responseString];
}

- (void)receivedQuestionResponse:(NSMutableString *)responseString {
    // Create questions from the response.
    [self createQuestionsFromServerResponse:responseString];
    
    // If a question is needed, deliver one.
    if (isQuestionNeeded) {
        isQuestionNeeded = NO;
        Question *nextQuestion = [self getNextQuestionFromArray];
        [[QuizManager sharedAppDelegate] setupNextQuestion:nextQuestion];
    }
    
    isRequestInProgress = NO;
}

- (NSMutableString *)createRequestString {
    QuizSettings *quizSettings = [QuizSettings quizSettingObject];
    
    NSMutableString *getRequest = [NSMutableString stringWithString:@BASE_URL_ADDR];
    [getRequest appendString:@"?cmd=getQuestions"];
    [getRequest appendFormat:@"&facebookAccessToken=%@", bufferedFBToken];
    [getRequest appendFormat:@"&questionCount=%i", quizSettings.questionCount];
    [getRequest appendFormat:@"&optionCount=%i", quizSettings.option];
    [getRequest appendFormat:@"&categoryId=%i", quizSettings.categoryID];
    
    return getRequest;
}

- (void)requestQuestionsFromServer {
    if (isRequestInProgress) { // Only one request allowed at a time.
        return;
    }
    
    isRequestInProgress = YES;
    NSMutableString *getRequest = [self createRequestString];
    NSURLRequest *request = [NSURLRequest requestWithURL:[NSURL URLWithString:getRequest]
                                             cachePolicy:NSURLRequestReloadIgnoringLocalCacheData
                                         timeoutInterval:60];
    [[NSURLConnection alloc] initWithRequest:request delegate:self];
}

- (BOOL)createQuestionsFromServerResponse:(NSString *)response {
    // Parse the JSON response.
    NSDictionary *responseDictionary = [response objectFromJSONString];
    
    // Check for valid JSON response.
    if (responseDictionary == nil) {
        return NO;
    }
    
    // Check the success field.
    BOOL success = [[responseDictionary objectForKey:@"success"] boolValue];
    if (success == false) {
        return NO;
    }
    
    // Process the questions.
    NSArray *questionsArray = [responseDictionary objectForKey:@"questions"];
    NSEnumerator *questionEnumerator = [questionsArray objectEnumerator];
    NSDictionary *curQuestion;
    int questionsCount = 0;
    
    // Go through all questions in the JSON response.
    while (curQuestion = [questionEnumerator nextObject]) {
        NSString *text = [curQuestion objectForKey:@"text"];
        NSArray *options = [curQuestion objectForKey:@"options"];
        NSDictionary *correctSubject = [curQuestion objectForKey:@"correctSubject"];
        NSString *correctFbId = [correctSubject objectForKey:@"facebookId"];
        NSDictionary *topicDict = [curQuestion objectForKey:@"topicSubject"];
        NSString *topicFacebookId = [topicDict objectForKey:@"facebookId"];
        
        int questionId = [[curQuestion objectForKey:@"questionId"] intValue];
        
        // Ignore this question if we've already seen it.
        if (![self isNewlyFetchedQuestion:questionId]) {
            continue;
        }
        
        NSEnumerator *optionEnumerator = [options objectEnumerator];
        NSDictionary *curOption;
        NSMutableArray *optionArray = [[NSMutableArray alloc] initWithCapacity:8];
        
        // Go through all options for current question.
        while (curOption = [optionEnumerator nextObject]) {
            NSDictionary *subjectDict = [curOption objectForKey:@"topicSubject"];
            NSString *subjectName = [subjectDict objectForKey:@"name"];
            NSString *subjectFacebookId = [subjectDict objectForKey:@"facebookId"];
            
            Option *option = [[[Option alloc] initWithName:subjectName andFacebookId:subjectFacebookId] autorelease];
            [optionArray addObject:option];
        }
        
        // Create a Subject object for the topic of the question.
        
        Question *question = [[MCQuestion alloc] initQuestionWithOptions:optionArray];
        question.text = text;
        question.correctFacebookId = correctFbId;
        question.questionId = questionId;
        question.topicImage = [Subject getPictureURLFromFacebookID:topicFacebookId];
        
        [optionArray release];
        
        [questionArray addObject:question];
        
        [question release];
        
        questionsCount++;
    }
    
    // No questions retrieved?
    if (questionsCount == 0) {
        return NO;
    }
    
    return YES;
}

// True if new question has a greater ID than any existing question.
- (BOOL)isNewlyFetchedQuestion:(int)questionId {
    if (!questionArray || questionArray.count == 0) {
        return true;
    }
    
    Question *lastQuestion = [questionArray objectAtIndex:questionArray.count - 1];
    return questionId > lastQuestion.questionId;
}

- (void)requestNextQuestionAsync {
    isQuestionNeeded = YES;
    
    // Use an existing question if possible.
    Question *question = [self getNextQuestionFromArray];
    if (question) {
        isQuestionNeeded = NO;
        [[QuizManager sharedAppDelegate] setupNextQuestion:question];
    }
}

- (Question *)getNextQuestionFromArray {
    // Request more questions if we're running low.
    if (questionArray.count < MIN_AVAILABLE_QUESTION_COUNT) {
        [self requestQuestionsFromServer];
    } 
    
    // Get one of the existing questions.
    if (questionArray.count > 0) { 
        Question *question = [[questionArray objectAtIndex:0] retain];
        [questionArray removeObjectAtIndex:0];
        
        return question;
    }
    
    return NULL;
}

- (void)dealloc {
    [questionArray release];
    [bufferedFBToken release];
    
	[super dealloc];
}

+ (GuessThatFriendAppDelegate*) sharedAppDelegate {
    return (GuessThatFriendAppDelegate*)[[UIApplication sharedApplication] delegate];
}

@end
