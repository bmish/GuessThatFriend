//
//  FriendStatsObject.m
//  GuessThatFriend
//
//  Created on 4/10/12.
//

#import "FriendStatsObject.h"
#import "GuessThatFriendAppDelegate.h"
#import "Subject.h"

@implementation FriendStatsObject

@synthesize subject, correctCount, totalCount;
@synthesize fastestCorrectResponseTime, averageResponseTime;

- (FriendStatsObject *)initWithSubject:(Subject *)mySubject andCorrectCount:(int)cCount andTotalCount:(int)tCount andFastestRT:(int)fRT andAverageRT:(int)aRT {
    
    self.subject = mySubject;
    self.correctCount = cCount;
    self.totalCount = tCount;
    self.fastestCorrectResponseTime = fRT == 0 ? 0.0 : ((float)fRT) / 1000.0;
    if (self.fastestCorrectResponseTime < 0) {
        self.fastestCorrectResponseTime *= -1;
    }
    self.averageResponseTime = aRT == 0 ? 0.0 : ((float)aRT) / 1000.0;
    if (self.averageResponseTime < 0) {
        self.averageResponseTime *= -1;
    }
    
    return [super init];
}


@end
