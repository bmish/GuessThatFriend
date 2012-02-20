//
//  SettingsViewController.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/13/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface SettingsViewController : UIViewController {
	UIButton *logoutButton;
	UIButton *backButton;
}

@property (nonatomic, retain) IBOutlet UIButton *logoutButton;
@property (nonatomic, retain) IBOutlet UIButton *backButton;

- (IBAction)switchViewToFBLogin:(id)sender;
- (IBAction)switchViewToGoBack:(id)sender;

@end
