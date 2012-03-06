//
//  GuessThatFriendAppDelegate.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/2/12.
//  Copyright 2012. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "FBConnect.h"

@class QuizBaseViewController;

@interface GuessThatFriendAppDelegate : NSObject 
<UIApplicationDelegate, FBSessionDelegate> {
    
    UIWindow *window;
    UINavigationController *navController;
    QuizBaseViewController *viewController;
    Facebook *facebook;
}

@property (nonatomic, retain) IBOutlet UIWindow *window;
@property (nonatomic, retain) UINavigationController *navController;
@property (nonatomic, retain) QuizBaseViewController *viewController;
@property (nonatomic, retain) Facebook *facebook;

@end

