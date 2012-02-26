//
//  MultipleChoiceQuestion.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>

#import "Question.h"

@interface MultipleChoiceQuestion : Question {
	NSMutableArray *options;
}

- (MultipleChoiceQuestion *)initQuestion;

@end
