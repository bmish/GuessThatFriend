//
//  MultipleChoiceQuestion.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012. All rights reserved.
//

#import <Foundation/Foundation.h>

#import "Question.h"

@interface MCQuestion : Question {
	NSMutableArray *options;
}

@property (nonatomic, retain) NSMutableArray *options;

- (MCQuestion *)initQuestion;

@end
