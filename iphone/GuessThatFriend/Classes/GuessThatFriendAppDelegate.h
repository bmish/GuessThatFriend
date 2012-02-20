//
//  GuessThatFriendAppDelegate.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/2/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>

@class GuessThatFriendViewController;

@interface GuessThatFriendAppDelegate : NSObject <UIApplicationDelegate> {
    UIWindow *window;
    GuessThatFriendViewController *viewController;
}

@property (nonatomic, retain) IBOutlet UIWindow *window;
@property (nonatomic, retain) IBOutlet GuessThatFriendViewController *viewController;

@end

