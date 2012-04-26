//
//  FriendStatsObject.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 4/10/12.
//  Copyright (c) 2012 __MyCompanyName__. All rights reserved.
//

#import "FriendStatsObject.h"
#import "GuessThatFriendAppDelegate.h"

@implementation FriendStatsObject

@synthesize name, picture, correctCount, totalCount;

- (FriendStatsObject *)initWithName:(NSString *)friendName andImagePath:(NSString *)imagePath andCorrectCount:(int)cCount andTotalCount:(int)tCount {
    
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *) [[UIApplication sharedApplication] delegate];
    UIImage* image = [delegate getPicture:imagePath];
    
    self.name = friendName;
    self.picture = image;
    self.correctCount = cCount;
    self.totalCount = tCount;
    
    return [super init];
}

- (void)dealloc {
    [name release];
    [picture release];
    
	[super dealloc];
}

@end
