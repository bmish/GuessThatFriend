//
//  QuizManager.h
//  GuessThatFriend
//
//  Created on 2/15/12.
//
//

#import <Foundation/Foundation.h>

@class QuizSettings;
@class Question;
@class QuizSettings;

@interface QuizManager : NSObject {
    NSMutableArray *questionArray;
    NSString *bufferedFBToken;
    int maxQuestionIdSeen;
    NSMutableData *responseData;
    
    BOOL isRequestInProgress;
    BOOL isQuestionNeeded;
}

@property (nonatomic) NSMutableArray *questionArray;
@property (nonatomic) NSString *bufferedFBToken;

- (QuizManager *)initWithFBToken:(NSString *)token;
- (Question *)getNextQuestionFromArray;
- (void)requestQuestionsFromServer;
- (void)requestNextQuestionAsync;

+ (BOOL)isQuestionShowing;

@end
