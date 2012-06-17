//
//  Question.h
//  
//
//  Created on 2/25/12.
//
//

#import "Category.h"
#import "Subject.h"

@class Option;              // Forward class to avoid circular dependency between Question and Option.

@interface Question : NSObject {
    int questionId;
    Category *category;     // Category of this question (like movies or books).
    Subject *subject;       // Subject of the question (a person or page).
	NSString *text;         // Question text.
    NSString *correctFacebookId;  // The correct answer to this question.
    Option *chosenOption;   // The answer that the user chose (if the question has been answered).
    NSString *topicImage;
}

@property (nonatomic, assign) int questionId;
@property (nonatomic, retain) Category *category;
@property (nonatomic, retain) Subject *subject;
@property (nonatomic, retain) NSString *text;
@property (nonatomic, retain) NSString *topicImage;
@property (nonatomic, retain) NSString *correctFacebookId;
@property (nonatomic, retain) Option *chosenOption;

@end
