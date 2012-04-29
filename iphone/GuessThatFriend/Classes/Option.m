//
//  Option.m
//  GuessThatFriend
//
//  Created on 2/15/12.
//  Copyright 2012. All rights reserved.
//

#import "Option.h"

@implementation Option

@synthesize optionId, question, subject;

- (Option *)initWithName:(NSString *)friendName andImagePath:(NSString *)imagePath andFacebookId:(NSString *)facebookId andLink:(NSString *)link {
	
    self.subject = [[Subject alloc] initWithName:friendName andImagePath:imagePath andFacebookId:facebookId andLink:link];
    
	return [super init];
}

- (id)copyWithZone:(NSZone *)zone {
	Option *friendCopy = [[Option allocWithZone:zone] init];
	friendCopy.subject.name = subject.name; // TODO
	friendCopy.subject.picture = subject.picture; // TODO
    
	return friendCopy;
}

- (void)dealloc {
    [question release];
    [subject release];
	[super dealloc];
}

@end
