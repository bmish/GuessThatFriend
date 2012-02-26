//
//  Question.h
//  
//
//  Created by Bryan Mishkin on 2/25/12.
//  Copyright (c) 2012 University of Illinois. All rights reserved.
//

#import "Category.h"
#import "Option.h"

@interface Question : NSObject {
    int questionId;
    Category *category;     // Category of this question (like movies or books).
    Subject *subject;       // Subject of the question (a person or page).
	NSString *text;         // Question text.
    Option *correctOption;  // The correct answer to this question.
}

@property (nonatomic, assign) int questionId;
@property (nonatomic, retain) Category *category;
@property (nonatomic, retain) Subject *subject;
@property (nonatomic, retain) NSString *text;
@property (nonatomic, retain) Option *correctOption;

@end
