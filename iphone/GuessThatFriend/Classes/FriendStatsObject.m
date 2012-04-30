//
//  FriendStatsObject.m
//  GuessThatFriend
//
//  Created on 4/10/12.
//  Copyright (c) 2012 __MyCompanyName__. All rights reserved.
//

#import "FriendStatsObject.h"
#import "GuessThatFriendAppDelegate.h"

@implementation FriendStatsObject

@synthesize name, picture, correctCount, totalCount;
@synthesize fastestCorrectRT, averageRT;

- (FriendStatsObject *)initWithName:(NSString *)friendName andImagePath:(NSString *)imagePath andCorrectCount:(int)cCount andTotalCount:(int)tCount andFastestRT:(int)fRT andAverageRT:(int)aRT {
    
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *) [[UIApplication sharedApplication] delegate];
    UIImage *image = [delegate getPicture:imagePath];
    
    self.name = friendName;
    self.picture = image;
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
    [name release];
    [picture release];
    
	[super dealloc];
}

@end
