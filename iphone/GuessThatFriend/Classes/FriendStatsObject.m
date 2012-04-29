//
//  FriendStatsObject.m
//  GuessThatFriend
//
//  Created on 4/10/12.
//  Copyright (c) 2012 __MyCompanyName__. All rights reserved.
//

#import "FriendStatsObject.h"

@implementation FriendStatsObject

@synthesize name, picture, correctCount, totalCount;

- (FriendStatsObject *)initWithName:(NSString *)friendName andImagePath:(NSString *)imagePath andCorrectCount:(int)cCount andTotalCount:(int)tCount {
    
    // Download the subject's image from the 'imagePath'
    NSURL *url = [NSURL URLWithString:imagePath];
    UIImage *image = [UIImage imageWithData: [NSData dataWithContentsOfURL:url]]; 
    
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
