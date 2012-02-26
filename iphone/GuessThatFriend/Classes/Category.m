//
//  Category.m
//  GuessThatFriend
//
//  Created by Bryan Mishkin on 2/25/12.
//  Copyright (c) 2012 University of Illinois. All rights reserved.
//

#import "Category.h"

@implementation Category

@synthesize categoryId, facebookName, prettyName;

- (void)dealloc {
    [facebookName release];
    [prettyName release];
	[super dealloc];
}

@end