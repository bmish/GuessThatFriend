//
//  Friend.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import "Friend.h"

@implementation Friend

@synthesize name;
@synthesize image;

- (Friend *)initWithName:(NSString *)friendName andImagePath:(NSString *)imagePath {
	self.name = friendName;
	self.image = [UIImage imageNamed:imagePath];

	return [super init];
}

- (id)copyWithZone:(NSZone *)zone {
	Friend *friendCopy = [[Friend allocWithZone:zone] init];
	friendCopy.name = name;
	friendCopy.image = image;
	return friendCopy;
}

- (void)dealloc {
	[name release];
	[image release];
	
	[super dealloc];
}

@end
