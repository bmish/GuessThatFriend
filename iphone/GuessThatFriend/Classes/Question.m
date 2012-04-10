//
//  Question.m
//  
//
//  Created by Bryan Mishkin on 2/25/12.
//  Copyright (c) 2012. All rights reserved.
//

#import "Question.h"

@implementation Question

@synthesize questionId, category, subject, text, correctFacebookId, chosenOption, topicImage;

- (void)dealloc {
    [category release];
    [subject release];
    [text release];
    [correctFacebookId release];
    [chosenOption release];
    [topicImage release];
    
	[super dealloc];
}

@end
