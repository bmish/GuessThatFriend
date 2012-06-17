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

@synthesize question, picture, correctAnswer, yourAnswer, date, responseTime;

- (HistoryStatsObject *)initWithQuestion:(NSString *)text andSubject:(Subject *)subject andCorrectAnswer:(NSString *)cAnswer andYourAnswer:(NSString *)yAnswer andDate:(NSString *)theDate andResponseTime:(int)rt {
    
    self.question = text;
    self.picture = subject.picture;
    self.correctAnswer = cAnswer;
    self.yourAnswer = yAnswer;
    self.date = theDate;
    self.responseTime = ((float)rt) / 1000.0;
    if (self.responseTime < 0) {
        self.responseTime *= -1;
    }
    
    return [super init];
}

- (void)dealloc {
    [question release];
    [picture release];
    [correctAnswer release];
    [yourAnswer release];
    [date release];
    
	[super dealloc];
}

@end
