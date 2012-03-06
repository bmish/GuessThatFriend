//
//  Subject.m
//  GuessThatFriend
//
//  Created by Bryan Mishkin on 2/25/12.
//  Copyright (c) 2012. All rights reserved.
//

#import "Subject.h"

@implementation Subject

@synthesize facebookId, name, picture, link;

- (Subject *)initWithName:(NSString *)friendName andImagePath:(NSString *)imagePath {
    self.name = friendName;
	self.picture = [UIImage imageNamed:imagePath];
    
	return [super init];
}

- (void)dealloc {
    [facebookId release];
    [name release];
    [picture release];
    [link release];
	[super dealloc];
}

@end
