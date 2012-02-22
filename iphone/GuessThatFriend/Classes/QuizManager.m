//
//  QuizManager.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import "QuizManager.h"
#import "MultipleChoiceQuestion.h"

@implementation QuizManager

@synthesize numQuestions;
@synthesize numCorrect;

- (QuizManager *)initWithQuizSettings:(QuizSettings *)settings {
	//TODO: actual implementation
	
	[self requestQuizFromServer];
	
	return [super init];
}

- (void)requestQuizFromServer {
	//TODO: actual implementation
	
	numQuestions = 1;
	numCorrect = 0;
}

// Call should free the returned object.
- (MultipleChoiceQuestion *)getNextQuestion {
	//TODO: actual implementation
	
	MultipleChoiceQuestion *question = [[MultipleChoiceQuestion alloc] initQuestion];
	
	return question;
}

- (void)dealloc {	
	[super dealloc];
}

@end