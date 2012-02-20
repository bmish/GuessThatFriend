//
//  GuessThatFriendViewController.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/2/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>

@class FBLoginViewController;
@class AboutViewController;

@interface GuessThatFriendViewController : UIViewController {
	UIButton *loginButton;
	UIButton *aboutButton;
	
	FBLoginViewController *fbLoginViewController;
	AboutViewController *aboutViewController;
}

@property (nonatomic, retain) IBOutlet UIButton *loginButton;
@property (nonatomic, retain) IBOutlet UIButton *aboutButton;

@property (nonatomic, retain) FBLoginViewController *fbLoginViewController;
@property (nonatomic, retain) AboutViewController *aboutViewController;

- (IBAction)switchViewToLogin:(id)sender;
- (IBAction)switchViewToAbout:(id)sender;

@end
