//
//  QuizManager.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>

@class QuizSettings;
@class MultipleChoiceQuestion;

@interface QuizManager : NSObject {
	unsigned int numQuestions;
	unsigned int numCorrect;
}

@property unsigned int numQuestions;
@property unsigned int numCorrect;

- (QuizManager *)initWithQuizSettings:(QuizSettings *)settings;
- (void)requestQuizFromServer;
- (MultipleChoiceQuestion *)getNextQuestion;

@end
