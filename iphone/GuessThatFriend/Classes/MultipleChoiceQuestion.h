//
//  MultipleChoiceQuestion.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface MultipleChoiceQuestion : NSObject {
	NSString *question;
	NSMutableArray *friends;
}

@property (nonatomic, retain) NSString *question;
@property (nonatomic, retain) NSMutableArray *friends;

- (MultipleChoiceQuestion *)initQuestion;

@end
