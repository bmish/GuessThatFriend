//
//  GuessThatFriendAppDelegate.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/2/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "FBConnect.h"

@class GuessThatFriendViewController;

@interface GuessThatFriendAppDelegate : NSObject 
<UIApplicationDelegate, FBSessionDelegate> {
    
    UIWindow *window;
    GuessThatFriendViewController *viewController;
    Facebook *facebook;
}

@property (nonatomic, retain) IBOutlet UIWindow *window;
@property (nonatomic, retain) IBOutlet GuessThatFriendViewController *viewController;
@property (nonatomic, retain) Facebook *facebook;

@end

