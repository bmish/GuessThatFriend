//
//  HistoryStatsObject.m
//  GuessThatFriend
//
//  Created on 4/30/12.
//

#import "HistoryStatsObject.h"
#import "GuessThatFriendAppDelegate.h"
#import "Subject.h"

@implementation HistoryStatsObject

@synthesize question, subject, correctAnswer, yourAnswer, date, responseTime;

- (HistoryStatsObject *)initWithQuestion:(NSString *)text andSubject:(Subject *)theSubject andCorrectAnswer:(NSString *)cAnswer andYourAnswer:(NSString *)yAnswer andDate:(NSString *)theDate andResponseTime:(int)rt {
    
    self.question = text;
    self.subject = theSubject;
    self.correctAnswer = cAnswer;
    self.yourAnswer = yAnswer;
    self.date = theDate;
    self.responseTime = ((float)rt) / 1000.0;
    if (self.responseTime < 0) {
        self.responseTime *= -1;
    }
    
    return [super init];
}


@end
