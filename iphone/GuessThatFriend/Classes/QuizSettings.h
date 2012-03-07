//
//  QuizSettings.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012. All rights reserved.
//

#import <Foundation/Foundation.h>

//	This is a singleton object.

@interface QuizSettings : NSObject {
    int questionCount;
    int option;
    int categoryID;
    NSString *friendFacebookID;
}

@property int questionCount;
@property int option;
@property int categoryID;
@property (nonatomic, retain) NSString *friendFacebookID;

@end
