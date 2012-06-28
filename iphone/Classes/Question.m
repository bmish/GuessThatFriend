//
//  Question.m
//  
//
//  Created on 2/25/12.
//
//

#import "Question.h"

@implementation Question

@synthesize questionId, category, subject, text, correctFacebookId, chosenOption, topicImageURLString;

- (NSURL *)getTopicImageURL {
    return [NSURL URLWithString:topicImageURLString];
}

@end
