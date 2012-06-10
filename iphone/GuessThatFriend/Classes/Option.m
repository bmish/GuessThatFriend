//
//  Option.m
//  GuessThatFriend
//
//  Created on 2/15/12.
//
//

#import "Option.h"

@implementation Option

@synthesize optionId, question, subject;

- (Option *)initWithName:(NSString *)friendName andFacebookId:(NSString *)facebookId {
	
    self.subject = [[Subject alloc] initWithName:friendName andFacebookId:facebookId];
    
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
