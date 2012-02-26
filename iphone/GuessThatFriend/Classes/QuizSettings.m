//
//  QuizSettings.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012. All rights reserved.
//

#import "QuizSettings.h"

@implementation QuizSettings

@synthesize questionCount;
@synthesize option;
@synthesize friendFacebookID;

- (void)dealloc {
    [friendFacebookID release];
    
	[super dealloc];
}

@end
