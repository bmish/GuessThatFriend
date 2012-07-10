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
    if (self = [super init]) {
        self.subject = [[Subject alloc] initWithName:friendName andFacebookId:facebookId];
    }
    
	return self;
}

- (id)copyWithZone:(NSZone *)zone {
	Option *friendCopy = [[Option allocWithZone:zone] init];
    
	return friendCopy;
}


@end
