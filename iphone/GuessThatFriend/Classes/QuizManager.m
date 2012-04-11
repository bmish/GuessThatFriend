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
@synthesize bufferedFBToken;
@synthesize numQuestions;
@synthesize numCorrect;

- (QuizManager *)initWithFBToken:(NSString *)paramFBToken andUseSampleData:(BOOL)paramUseSampleData {
	
    questionArray = [[NSMutableArray alloc] initWithCapacity:1];
    
    useSampleData = paramUseSampleData;
    bufferedFBToken = paramFBToken;
    numQuestions = 0;
	numCorrect = 0;
	[self requestQuestionsFromServer];
	
	return [super init];
}

- (void)requestQuestionsFromServer {
    
    if (questionArray.count > 0) {
        return;
    }
    
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
    
    NSLog(@"Request string: %@", getRequest);
    
    // Send the GET request to the server.
    NSURLRequest *request = [NSURLRequest requestWithURL:[NSURL URLWithString:getRequest]];

    NSData *response = [NSURLConnection sendSynchronousRequest:request returningResponse:nil error:nil];
    NSString *responseString = [[NSString alloc] initWithData:response encoding:NSUTF8StringEncoding];
    
    NSLog(@"RESPONSE STRING: %@ \n",responseString);

    // Initialize array of questions from the server's response.
    [self createQuestionsFromServerResponse:responseString];
    
    [responseString release];
}

- (void)createQuestionsFromServerResponse:(NSString *)response {
    
    numQuestions = 0;
	numCorrect = 0;
    
    // Parse the JSON response.
    NSDictionary *responseDictionary = [response objectFromJSONString];
    
    //Check if valid JSON response
    if (responseDictionary == nil) {
        [self requestQuestionsFromServer];                  //Just ask for more questions
        return;
    }
        
    NSArray *questionsArray = [responseDictionary objectForKey:@"questions"];
    
    NSEnumerator *questionEnumerator = [questionsArray objectEnumerator];
    NSDictionary *curQuestion;
    
    //Go through all QUESTIONS
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
        
        //Go through all OPTIONS for current Question
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
    [bufferedFBToken release];
    
	[super dealloc];
}

@end
