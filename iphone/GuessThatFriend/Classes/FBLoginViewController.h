//
//  FBLoginViewController.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/2/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>

@class MainMenuViewController;

@interface FBLoginViewController : UIViewController {
	UITextField *emailField;
	UITextField *passwordField;
	UIButton *loginButton;
	UIButton *backButton;
	
	MainMenuViewController *mainMenuViewController;
}

@property (nonatomic, retain) IBOutlet UITextField *emailField;
@property (nonatomic, retain) IBOutlet UITextField *passwordField;
@property (nonatomic, retain) IBOutlet UIButton *loginButton;
@property (nonatomic, retain) IBOutlet UIButton *backButton;

@property (nonatomic, retain) MainMenuViewController *mainMenuViewController;

- (IBAction)textFieldDoneEditing:(id)sender;
- (IBAction)switchViewToGoBack:(id)sender;
- (IBAction)switchViewToMenu:(id)sender;

@end
