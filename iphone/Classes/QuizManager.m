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
#import "MultipleChoiceQuizViewController.h"

@implementation QuizManager

@synthesize questionArray;
@synthesize bufferedFBToken;

- (QuizManager *)initWithFBToken:(NSString *)paramFBToken {
    if (self = [super init]) {
        isRequestInProgress = NO;
        isQuestionNeeded = NO;
    
        questionArray = [[NSMutableArray alloc] initWithCapacity:20];
        bufferedFBToken = paramFBToken;
        
        maxQuestionIdSeen = 0;
    
        [self requestQuestionsFromServer];
    }
    
	return self;
}

- (void)connection:(NSURLConnection *)connection didReceiveResponse:(NSURLResponse *)response {
    responseData = [[NSMutableData alloc] init];
}

- (void)connection:(NSURLConnection *)connection didReceiveData:(NSData *)data {
    [responseData appendData:data];
}

- (void)connection:(NSURLConnection *)connection didFailWithError:(NSError *)error {
    [[QuizManager sharedAppDelegate].spinner stopAnimating];
    isRequestInProgress = NO;
}

- (void)connectionDidFinishLoading:(NSURLConnection *)connection {
    // Use responseData.
    NSMutableString *responseString = [[NSMutableString alloc] initWithData:responseData
                                                        encoding:NSASCIIStringEncoding];
    
    [self receivedQuestionResponse:responseString];
    
    // Release connection vars.
    [[QuizManager sharedAppDelegate].spinner stopAnimating];
    isRequestInProgress = NO;
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
}

- (NSMutableString *)createRequestString {
    QuizSettings *quizSettings = [QuizSettings quizSettingObject];
    
    NSMutableString *getRequest = [NSMutableString stringWithString:@API_URL_ADDRESS];
    [getRequest appendString:@"?cmd=getQuestions"];
    [getRequest appendFormat:@"&facebookAccessToken=%@", bufferedFBToken];
    [getRequest appendFormat:@"&questionCount=%i", quizSettings.questionCount];
    [getRequest appendFormat:@"&optionCount=%i", quizSettings.optionCount];
    if (quizSettings.categoryID > 0) {
        [getRequest appendFormat:@"&categoryId=%i", quizSettings.categoryID];
    }
    [getRequest appendFormat:[GuessThatFriendAppDelegate getVersionParametersStringForRequestURL]];
    
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
    (void)[[NSURLConnection alloc] initWithRequest:request delegate:self];
    
    
    // Start animating the spinner if no question is showing right now.
    if (![QuizManager isQuestionShowing]) {
        [[QuizManager sharedAppDelegate].spinner startAnimating];
    }
}
        
+ (BOOL)isQuestionShowing {
    MultipleChoiceQuizViewController *viewController = (MultipleChoiceQuizViewController *)([QuizManager sharedAppDelegate].viewController);
    
    return !viewController.friendsTable.hidden;
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
        NSString *correctFacebookId = [correctSubject objectForKey:@"facebookId"];
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
            
            Option *option = [[Option alloc] initWithName:subjectName andFacebookId:subjectFacebookId];
            [optionArray addObject:option];
        }
        
        // Create a Subject object for the topic of the question.
        
        Question *question = [[MCQuestion alloc] initQuestionWithOptions:optionArray];
        question.text = text;
        question.correctFacebookId = correctFacebookId;
        question.questionId = questionId;
        question.topicImageURLString = [Subject getPictureURLStringFromFacebookID:topicFacebookId];
        
        
        [questionArray addObject:question];
        
        
        questionsCount++;
    }
    
    // No questions retrieved?
    if (questionsCount == 0) {
        return NO;
    }
    
    return YES;
}

- (BOOL)isNewlyFetchedQuestion:(int)questionId {
    return questionId > maxQuestionIdSeen;
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

- (Question *)getNextUnseenQuestionFromArray {
    if (!questionArray || [questionArray count] == 0) {
        return NULL;
    }
    
    // Pick out the next question that we haven't seen yet.
    // (Duplicates can sometimes be found in our list because we request 
    // new questions before all the current ones have been answered).
    Question *question = NULL;
    do {
        question = [questionArray objectAtIndex:0];
        [questionArray removeObjectAtIndex:0];
    } while (![self isNewlyFetchedQuestion:question.questionId] && [questionArray count] > 0);
    
    // Update the max questionId that we have seen.
    if (question) {
        maxQuestionIdSeen = question.questionId;
    }
    
    return question;
}

- (Question *)getNextQuestionFromArray {
    // Request more questions if we're running low.
    if (questionArray.count < MIN_AVAILABLE_QUESTION_COUNT) {
        [self requestQuestionsFromServer];
    } 
    
    // Get the next existing question from our list.
    if (questionArray.count > 0) {
        Question *question = [self getNextUnseenQuestionFromArray];
        
        return question;
    }
    
    return NULL;
}


+ (GuessThatFriendAppDelegate*) sharedAppDelegate {
    return (GuessThatFriendAppDelegate*)[[UIApplication sharedApplication] delegate];
}

@end
