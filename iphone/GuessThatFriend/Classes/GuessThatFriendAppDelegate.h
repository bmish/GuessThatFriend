//
//  GuessThatFriendAppDelegate.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/2/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "FBConnect.h"

@class MultipleChoiceQuizViewController;

@interface GuessThatFriendAppDelegate : NSObject 
<UIApplicationDelegate, FBSessionDelegate> {
    
    UIWindow *window;
    MultipleChoiceQuizViewController *viewController;
    Facebook *facebook;
}

@property (nonatomic, retain) IBOutlet UIWindow *window;
@property (nonatomic, retain) IBOutlet MultipleChoiceQuizViewController *viewController;
@property (nonatomic, retain) Facebook *facebook;

@end

