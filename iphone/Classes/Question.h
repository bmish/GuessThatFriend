//
//  Question.h
//  
//
//  Created on 2/25/12.
//
//

#import "Category.h"
#import "Subject.h"
#import "Option.h"

@class Option;              // Forward class to avoid circular dependency between Question and Option.

@interface Question : NSObject {
    int questionId;
    Category *category;     // Category of this question (like movies or books).
    Subject *subject;       // Subject of the question (a person or page).
	NSString *text;         // Question text.
    NSString *correctFacebookId;  // The correct answer to this question.
    Option *chosenOption;   // The answer that the user chose (if the question has been answered).
    NSString *topicImageURL;
}

@property (nonatomic, assign) int questionId;
@property (nonatomic) Category *category;
@property (nonatomic) Subject *subject;
@property (nonatomic) NSString *text;
@property (nonatomic) NSString *topicImageURL;
@property (nonatomic) NSString *correctFacebookId;
@property (nonatomic) Option *chosenOption;

@end
