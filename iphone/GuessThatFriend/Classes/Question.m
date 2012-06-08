//
//  Question.m
//  
//
//  Created on 2/25/12.
//
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
