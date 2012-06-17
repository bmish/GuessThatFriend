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
@synthesize fastestCorrectRT, averageRT;

- (FriendStatsObject *)initWithSubject:(Subject *)mySubject andCorrectCount:(int)cCount andTotalCount:(int)tCount andFastestRT:(int)fRT andAverageRT:(int)aRT {
    
    self.subject = mySubject;
    self.correctCount = cCount;
    self.totalCount = tCount;
    self.fastestCorrectRT = ((float)fRT) / 1000.0;
    if (self.fastestCorrectRT < 0) {
        self.fastestCorrectRT *= -1;
    }
    self.averageRT = ((float)aRT) / 1000.0;
    if (self.averageRT < 0) {
        self.averageRT *= -1;
    }
    
    return [super init];
}

- (void)dealloc {
    [subject release];
    
	[super dealloc];
}

@end
