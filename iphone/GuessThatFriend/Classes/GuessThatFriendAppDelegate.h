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
@class SettingsViewController;

@interface GuessThatFriendAppDelegate : NSObject 
<UIApplicationDelegate, FBSessionDelegate> {
    
    UIWindow *window;
    UINavigationController *navController;
    UIBarButtonItem *settingsItem;
    UIBarButtonItem *doneItem;
    QuizBaseViewController *viewController;
    SettingsViewController *settingsViewController;
    Facebook *facebook;
}

@property (nonatomic, retain) IBOutlet UIWindow *window;
@property (nonatomic, retain) IBOutlet UINavigationController *navController;
@property (nonatomic, retain) IBOutlet UIBarButtonItem *settingsItem;
@property (nonatomic, retain) IBOutlet UIBarButtonItem *doneItem;
@property (nonatomic, retain) QuizBaseViewController *viewController;
@property (nonatomic, retain) SettingsViewController *settingsViewController;
@property (nonatomic, retain) Facebook *facebook;

- (IBAction)settingsItemPressed:(id)sender;
- (IBAction)doneItemPressed:(id)sender;

@end

