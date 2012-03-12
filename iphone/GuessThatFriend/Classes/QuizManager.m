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
@synthesize bufferedToken;
@synthesize numQuestions;
@synthesize numCorrect;

- (QuizManager *)initWithQuizSettings:(QuizSettings *)settings andFBToken:(NSString *)token {
	
    questionArray = [[NSMutableArray alloc] initWithCapacity:10];
    self.bufferedQuizSettings = settings;
	[self requestQuizFromServer:settings FBToken:token];
	
	return [super init];
}

- (void)requestQuizFromServer:(QuizSettings *)settings FBToken:(NSString *)token {
    
    // Create GET request.
    NSMutableString *getRequest;
    
    if ([token length] == 0) { // Retrieve sample data.
        getRequest = [NSMutableString stringWithString:@SAMPLE_GET_QUESTIONS_ADDR];
    } else { // Make a real request.
        getRequest = [NSMutableString stringWithString:@BASE_URL_ADDR];
        [getRequest appendString:@"?cmd=getQuestions"];
        [getRequest appendFormat:@"&facebookAccessToken=%@", token];
        [getRequest appendFormat:@"&questionCount=%i", settings.questionCount];
        [getRequest appendFormat:@"&optionCount=%i", settings.option];
        [getRequest appendFormat:@"&categoryId=%i", settings.categoryID];
    }
    
    // Send the GET request to the server.
    NSURLRequest *request = [NSURLRequest requestWithURL:[NSURL URLWithString:getRequest]];
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
        
        NSEnumerator *optionEnumerator = [options objectEnumerator];
        NSDictionary *curOption;
        NSMutableArray *optionArray = [[NSMutableArray alloc] initWithCapacity:8];
        while (curOption = [optionEnumerator nextObject]) {
            NSDictionary *subjectDict = [curOption objectForKey:@"subject"];
            NSString *subjectName = [subjectDict objectForKey:@"name"];
            NSString *subjectImageURL = [subjectDict objectForKey:@"picture"];
            
            Option *option = [[Option alloc] initWithName:subjectName andImagePath:subjectImageURL];
            [optionArray addObject:option];
            [option release];
        }
        
        Question *question = [[MCQuestion alloc] initQuestionWithOptions:optionArray];
        question.text = text;
        
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
        [self requestQuizFromServer:bufferedQuizSettings FBToken:bufferedToken];
    }
    
    Question *question = [questionArray objectAtIndex:questionArray.count - 1];
    [question retain];
    [questionArray removeLastObject];
		
	return question;
}

- (void)dealloc {
    [questionArray release];
    [bufferedQuizSettings release];
    [bufferedToken release];
    
	[super dealloc];
}

@end
