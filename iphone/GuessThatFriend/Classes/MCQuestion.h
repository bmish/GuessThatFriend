//
//  MultipleChoiceQuestion.h
//  GuessThatFriend
//
//  Created on 2/15/12.
//
//

#import <Foundation/Foundation.h>

#import "Question.h"

@interface MCQuestion : Question {
	NSArray *options;
}

@property (nonatomic, retain) NSArray *options;

- (MCQuestion *)initQuestionWithOptions:(NSArray *)questionOptions;

@end
