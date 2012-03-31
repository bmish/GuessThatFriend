//
//  QuizManager.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012. All rights reserved.
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
@synthesize bufferedQuizSettings;
@synthesize bufferedFBToken;
@synthesize numQuestions;
@synthesize numCorrect;

- (QuizManager *)initWithQuizSettings:(QuizSettings *)settings andFBToken:(NSString *)paramFBToken andUseSampleData:(BOOL)paramUseSampleData {
	
    questionArray = [[NSMutableArray alloc] initWithCapacity:10];
    self.bufferedQuizSettings = settings;
    useSampleData = paramUseSampleData;
    bufferedFBToken = paramFBToken;
    numQuestions = 0;
	numCorrect = 0;
	[self requestQuestionsFromServer];
	
	return [super init];
}

- (void)requestQuestionsFromServer{
    
    // Create GET request.
    NSMutableString *getRequest;
    
    if (useSampleData) { // Retrieve sample data.
        getRequest = [NSMutableString stringWithString:@SAMPLE_GET_QUESTIONS_ADDR];
    } else if (bufferedQuizSettings != nil) { // Make a real request.
        getRequest = [NSMutableString stringWithString:@BASE_URL_ADDR];
        [getRequest appendString:@"?cmd=getQuestions"];
        [getRequest appendFormat:@"&facebookAccessToken=%@", bufferedFBToken];
        [getRequest appendFormat:@"&questionCount=%i", bufferedQuizSettings.questionCount];
        [getRequest appendFormat:@"&optionCount=%i", bufferedQuizSettings.option];
        [getRequest appendFormat:@"&categoryId=%i", bufferedQuizSettings.categoryID];
    } else { // Settings is nil.
        return;
    }
    
    
    // Send the GET request to the server.
    NSURLRequest *request = [NSURLRequest requestWithURL:[NSURL URLWithString:getRequest]];
    NSLog(@"%@ \n",request);
    NSData *response = [NSURLConnection sendSynchronousRequest:request returningResponse:nil error:nil];
    NSString *responseString = [[NSString alloc] initWithData:response encoding:NSUTF8StringEncoding];
    
    // Initialize array of questions from the server's response.
    [self createQuestionsFromServerResponse:responseString];
    
    [responseString release];
}

- (void)createQuestionsFromServerResponse:(NSString *)response {
    
    numQuestions = 0;
	numCorrect = 0;
    
    // Parse the JSON response.
    NSDictionary *responseDictionary = [response objectFromJSONString];
    NSArray *questionsArray = [responseDictionary objectForKey:@"questions"];
    
    NSEnumerator *questionEnumerator = [questionsArray objectEnumerator];
    NSDictionary *curQuestion;
    while (curQuestion = [questionEnumerator nextObject]) {
        NSString *text = [curQuestion objectForKey:@"text"];
        NSArray *options = [curQuestion objectForKey:@"options"];
        NSString *correctFbId = [curQuestion objectForKey:@"correctFacebookId"];
        int questionId = (int) [curQuestion objectForKey:@"questionId"];    
        
        NSEnumerator *optionEnumerator = [options objectEnumerator];
        NSDictionary *curOption;
        NSMutableArray *optionArray = [[NSMutableArray alloc] initWithCapacity:8];
        while (curOption = [optionEnumerator nextObject]) {
            NSDictionary *subjectDict = [curOption objectForKey:@"subject"];
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
        
        [optionArray release];
        
        [questionArray addObject:question];
        [question release];
        
        numQuestions++;
    }
}

// Call should free the returned object.
- (Question *)getNextQuestion {
    if (questionArray.count == 0) {
        // Need more questions from the server.
        [self requestQuestionsFromServer];
    }
    
    NSLog(@"%d, \n", questionArray.count);
    Question *question = [questionArray objectAtIndex:questionArray.count - 1];
    [question retain];
    [questionArray removeLastObject];
		
	return question;
}

- (void)dealloc {
    [questionArray release];
    [bufferedQuizSettings release];
    [bufferedFBToken release];
    
	[super dealloc];
}

@end
