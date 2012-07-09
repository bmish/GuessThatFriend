//
//  QuizSettings.h
//  GuessThatFriend
//
//  Created on 2/15/12.
//
//

#import <Foundation/Foundation.h>

//	This is a singleton object.

#define QUESTION_COUNT  10
#define MIN_AVAILABLE_QUESTION_COUNT 5

@interface QuizSettings : NSObject {
    int questionCount;
    int optionCount;
    int categoryID;
    NSString *topicFacebookId;
}

@property int questionCount;
@property int optionCount;
@property int categoryID;
@property (nonatomic) NSString *topicFacebookId;

+ (id) quizSettingObject;

@end
