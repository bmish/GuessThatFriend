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
@synthesize option;
@synthesize categoryID;
@synthesize friendFacebookID;

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
        
        questionCount = QUESTION_COUNT;
        option = 4;
        categoryID = 2;
        friendFacebookID = nil;
    }
    return self;
}

- (void)dealloc {
    [friendFacebookID release];
    
	[super dealloc];
}

@end
