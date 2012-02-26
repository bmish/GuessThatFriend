//
//  Option.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import "Option.h"

@implementation Option

@synthesize name;
@synthesize image;

- (Option *)initWithName:(NSString *)friendName andImagePath:(NSString *)imagePath {
	self.name = friendName;
	self.image = [UIImage imageNamed:imagePath];

	return [super init];
}

- (id)copyWithZone:(NSZone *)zone {
	Option *friendCopy = [[Option allocWithZone:zone] init];
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
