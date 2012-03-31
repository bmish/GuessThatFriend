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
    
    NSString *bufferedFBToken;
    
	unsigned int numQuestions;
	unsigned int numCorrect;
    
    BOOL useSampleData;
}

@property (nonatomic, retain) NSMutableArray *questionArray;
@property (nonatomic, retain) NSString *bufferedFBToken;
@property unsigned int numQuestions;
@property unsigned int numCorrect;

- (QuizManager *)initWithFBToken:(NSString *)token andUseSampleData:(BOOL)useSampleData;
- (Question *)getNextQuestion;

@end
