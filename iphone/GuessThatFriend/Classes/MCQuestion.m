//
//  MultipleChoiceQuestion.m
//  GuessThatFriend
//
//  Created on 2/15/12.
//  Copyright 2012. All rights reserved.
//

#import "MCQuestion.h"
#import "Option.h"

@implementation MCQuestion

@synthesize options;

- (MCQuestion *)initQuestionWithOptions:(NSArray *)questionOptions {
    self.options = questionOptions;
	return [super init];
}

- (void)dealloc {
	[options release];
	
	[super dealloc];
}

@end
