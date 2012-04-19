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
    
    BOOL useSampleData;
    
    // Lock for protecting questionArray.
    NSCondition *questionArrayLock;
    
    BOOL threadRunning; // Used to ensure only one thread at a time.
}

@property (nonatomic, retain) NSMutableArray *questionArray;
@property (nonatomic, retain) NSString *bufferedFBToken;

- (QuizManager *)initWithFBToken:(NSString *)token andUseSampleData:(BOOL)useSampleData;
- (Question *)getNextQuestion;
- (void)getQuestionThread;

@end
