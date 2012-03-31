//
//  QuizSettings.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012. All rights reserved.
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
        //Should initialize
    }
    return self;
}

- (void)dealloc {
    [friendFacebookID release];
    
	[super dealloc];
}

@end
