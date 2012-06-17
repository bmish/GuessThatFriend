//
//  Option.h
//  GuessThatFriend
//
//  Created on 2/15/12.
//
//

#import <Foundation/Foundation.h>
#import "Subject.h"

@class Question;        // Forward class to avoid circular dependency between Question and Option.

@interface Option : NSObject <NSCopying> {
    int optionId;
    Question *question;
    Subject *subject;   // The subject of this option (a person or page).
}

@property (nonatomic, assign) int optionId;
@property (nonatomic, retain) Question *question;
@property (nonatomic, retain) Subject *subject;

- (Option *)initWithName:(NSString *)friendName
           andFacebookId:(NSString *)facebookId;
- (id)copyWithZone:(NSZone *)zone;

@end
