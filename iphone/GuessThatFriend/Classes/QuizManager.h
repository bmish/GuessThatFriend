//
//  QuizManager.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012. All rights reserved.
//

#import <Foundation/Foundation.h>

@class QuizSettings;
@class Question;
@class QuizSettings;

@interface QuizManager : NSObject {
    NSMutableArray *questionArray;
    
    QuizSettings *bufferedQuizSettings;
    NSString *bufferedToken;
    
	unsigned int numQuestions;
	unsigned int numCorrect;
}

@property (nonatomic, retain) NSMutableArray *questionArray;
@property (nonatomic, retain) QuizSettings *bufferedQuizSettings;
@property (nonatomic, retain) NSString *bufferedToken;
@property unsigned int numQuestions;
@property unsigned int numCorrect;

- (QuizManager *)initWithQuizSettings:(QuizSettings *)settings andFBToken:(NSString *)token;
- (Question *)getNextQuestion;

@end
