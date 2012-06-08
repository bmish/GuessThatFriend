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

#define BASE_URL_ADDR               "http://guessthatfriend.jasonsze.com/api/"
#define SAMPLE_GET_QUESTIONS_ADDR   "http://guessthatfriend.jasonsze.com/api/examples/json/getQuestions.json"

@implementation QuizManager

@synthesize questionArray;
@synthesize bufferedFBToken;
@synthesize threadRunning;

- (QuizManager *)initWithFBToken:(NSString *)paramFBToken andUseSampleData:(BOOL)paramUseSampleData {
    
    questionArrayLock = [[NSCondition alloc] init];
    
    [questionArrayLock lock];
    questionArray = [[NSMutableArray alloc] initWithCapacity:20];
    [questionArrayLock unlock];
    
    useSampleData = paramUseSampleData;
    bufferedFBToken = paramFBToken;
    
	[self requestQuestionsFromServer];
    
	return [super init];
}

- (void)requestQuestionsFromServer {
    // Keep requesting until success is true.
    BOOL success = NO;
    while (success == NO) {
        // Create GET request.
        NSMutableString *getRequest;
        
        if (useSampleData) { // Retrieve sample data.
            getRequest = [NSMutableString stringWithString:@SAMPLE_GET_QUESTIONS_ADDR];
        } else { // Make a real request.
            QuizSettings *quizSettings = [QuizSettings quizSettingObject];
            
            getRequest = [NSMutableString stringWithString:@BASE_URL_ADDR];
            [getRequest appendString:@"?cmd=getQuestions"];
            [getRequest appendFormat:@"&facebookAccessToken=%@", bufferedFBToken];
            [getRequest appendFormat:@"&questionCount=%i", quizSettings.questionCount];
            [getRequest appendFormat:@"&optionCount=%i", quizSettings.option];
            [getRequest appendFormat:@"&categoryId=%i", quizSettings.categoryID];
        }
                
        // Send the GET request to the server.
        NSURLRequest *request = [NSURLRequest requestWithURL:[NSURL URLWithString:getRequest]];
        
        NSData *response = [NSURLConnection sendSynchronousRequest:request returningResponse:nil error:nil];
        NSString *responseString = [[NSString alloc] initWithData:response encoding:NSUTF8StringEncoding];
                
        // Initialize array of questions from the server's response.
        success = [self createQuestionsFromServerResponse:responseString];
        
        [responseString release];
    }
}

- (BOOL)createQuestionsFromServerResponse:(NSString *)response {
    
    // Parse the JSON response.
    NSDictionary *responseDictionary = [response objectFromJSONString];
    
    // Check if valid JSON response
    if (responseDictionary == nil) {
        [self requestQuestionsFromServer];  // Just ask for more questions
        return NO;
    }
    
    BOOL success = [[responseDictionary objectForKey:@"success"] boolValue];
    if (success == false) {
        return NO;
    }
    
    NSArray *questionsArray = [responseDictionary objectForKey:@"questions"];
    
    NSEnumerator *questionEnumerator = [questionsArray objectEnumerator];
    NSDictionary *curQuestion;
    
    //Go through all QUESTIONS ie the QUESTIONS ARRAY
    while (curQuestion = [questionEnumerator nextObject]) {
        NSString *text = [curQuestion objectForKey:@"text"];
        NSArray *options = [curQuestion objectForKey:@"options"];
        NSDictionary *correctSubject = [curQuestion objectForKey:@"correctSubject"];
        NSString *correctFbId = [correctSubject objectForKey:@"facebookId"];
        NSDictionary *topicDict = [curQuestion objectForKey:@"topicSubject"];
        NSString *topicPicture = [topicDict objectForKey:@"picture"];
        
        int questionId = [[curQuestion objectForKey:@"questionId"] intValue]; 
        
        NSEnumerator *optionEnumerator = [options objectEnumerator];
        NSDictionary *curOption;
        NSMutableArray *optionArray = [[NSMutableArray alloc] initWithCapacity:8];
        
        //Go through all OPTIONS for current Question ie the OPTIONS ARRAY
        while (curOption = [optionEnumerator nextObject]) {
            NSDictionary *subjectDict = [curOption objectForKey:@"topicSubject"];
            NSString *subjectName = [subjectDict objectForKey:@"name"];
            NSString *subjectImageURL = [subjectDict objectForKey:@"picture"];
            NSString *subjectFacebookId = [subjectDict objectForKey:@"facebookId"];
            NSString *subjectLink = [subjectDict objectForKey:@"link"];
            
            Option *option = [[Option alloc] initWithName:subjectName andImagePath:subjectImageURL andFacebookId:subjectFacebookId andLink:subjectLink];
            [optionArray addObject:option];
            [option release];
        }
        
        Question *question = [[MCQuestion alloc] initQuestionWithOptions:optionArray];
        question.text = text;
        question.correctFacebookId = correctFbId;
        question.questionId = questionId;
        question.topicImage = topicPicture;
        
        [optionArray release];
        
        [questionArrayLock lock];
        [questionArray addObject:question];
        [questionArrayLock signal];
        [questionArrayLock unlock];
        
        [question release];
    }
    
    return YES;
}

// getQuestionThread handles the getQuestion prior to running out of questions.
// called in getNextQuestion.
- (void)getQuestionThread {
    [self requestQuestionsFromServer];
    threadRunning = NO;
}

// Call should also free the returned object.
- (Question *)getNextQuestion {
    
    if (questionArray.count < 3 && threadRunning == NO) { // modified it into go fetch question on the 2nd last question
        
        threadRunning = YES;
        
        NSThread *getQuestionThread = [[NSThread alloc] initWithTarget:self selector:@selector(getQuestionThread) object:nil];
        [getQuestionThread start];
    }
    
    [questionArrayLock lock];
    while (questionArray.count == 0) {
        [questionArrayLock wait];
    }
    
    Question *question = [questionArray objectAtIndex:questionArray.count - 1];
    [question retain];
    [questionArray removeLastObject];
    
    [questionArrayLock unlock];
    
	return question;
}

- (void)dealloc {
    [questionArray release];
    [bufferedFBToken release];
    [questionArrayLock release];
    
	[super dealloc];
}

@end
