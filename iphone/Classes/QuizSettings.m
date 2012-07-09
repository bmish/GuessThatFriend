//
//  QuizSettings.m
//  GuessThatFriend
//
//  Created on 2/15/12.
//
//

#import "QuizSettings.h"

static QuizSettings *quizSettingsObject = nil;

@implementation QuizSettings

@synthesize questionCount;
@synthesize optionCount;
@synthesize categoryID;
@synthesize topicFacebookId;

+ (id) quizSettingObject {
    @synchronized(self) {
        if (quizSettingsObject == nil) {
            quizSettingsObject = [[self alloc] init];
        }
    }
    return quizSettingsObject;
}

- (id)init {
    if (self = [super init]) {
        // Initialize quiz settings
        self.questionCount = QUESTION_COUNT;
        self.optionCount = 4;
        self.categoryID = -1;
        self.topicFacebookId = nil;
    }
    return self;
}


@end
