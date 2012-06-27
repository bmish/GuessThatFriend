//
//  Option.h
//  GuessThatFriend
//
//  Created on 2/15/12.
//
//

#import <Foundation/Foundation.h>
#import "Subject.h"
#import "Question.h"

@class Question;        // Forward class to avoid circular dependency between Question and Option.

@interface Option : NSObject <NSCopying> {
    int optionId;
    Question *question;
    Subject *subject;   // The subject of this option (a person or page).
}

@property (nonatomic, assign) int optionId;
@property (nonatomic) Question *question;
@property (nonatomic, strong) Subject *subject;

- (Option *)initWithName:(NSString *)friendName
           andFacebookId:(NSString *)facebookId;
- (id)copyWithZone:(NSZone *)zone;

@end
