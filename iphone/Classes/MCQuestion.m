//
//  MultipleChoiceQuestion.m
//  GuessThatFriend
//
//  Created on 2/15/12.
//
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
