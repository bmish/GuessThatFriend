//
//  Option.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import "Option.h"

@implementation Option

@synthesize optionId, subject;

- (Option *)initWithName:(NSString *)friendName andImagePath:(NSString *)imagePath {
	self.subject = [[Subject alloc] initWithName:friendName andImagePath:imagePath];

	return [super init];
}

- (id)copyWithZone:(NSZone *)zone {
	Option *friendCopy = [[Option allocWithZone:zone] init];
	friendCopy.subject.name = subject.name; // TODO
	friendCopy.subject.picture = subject.picture; // TODO
	return friendCopy;
}

- (void)dealloc {
    [subject release];
	[super dealloc];
}

@end
