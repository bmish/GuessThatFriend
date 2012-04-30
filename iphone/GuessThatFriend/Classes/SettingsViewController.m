//
//  SettingsViewController.m
//  GuessThatFriend
//
//  Created on 2/13/12.
//  Copyright 2012. All rights reserved.
//

#import "SettingsViewController.h"
#import "GuessThatFriendAppDelegate.h"
#import "QuizSettings.h"
#import "UIBarButtonItem+Image.h"

@implementation SettingsViewController

@synthesize logoutButton;

- (IBAction)backItemPressed:(id)sender {
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    
    [UIView beginAnimations:@"back from settings" context:nil];
    [UIView setAnimationCurve:UIViewAnimationCurveEaseInOut];
    [UIView setAnimationDuration:0.75];
    [delegate.navController popViewControllerAnimated:YES];
    [UIView setAnimationTransition:UIViewAnimationTransitionFlipFromRight forView:delegate.navController.view cache:NO];
    [UIView commitAnimations];
}

- (void)viewDidAppear:(BOOL)animated {
    
    // Set up the two bar items on this view.
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    
    delegate.navController.navigationBar.topItem.title = @"Settings";
    delegate.navController.navigationBar.topItem.hidesBackButton = YES;
    
    UIImage *backImage = [UIImage imageNamed:@"Button_Back.png"];
    UIBarButtonItem *leftCornerButton = [[UIBarButtonItem alloc] 
                                         initWithImage:backImage 
                                         title:@"" target:self
                                         action:@selector(backItemPressed:)];
    delegate.navController.navigationBar.topItem.leftBarButtonItem = leftCornerButton;
     
    [super viewDidAppear:animated];
}

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
	UIImage *buttonImageNormal = [UIImage imageNamed:@"whiteButton.png"];
	UIImage *stretchableButtonImageNormal = [buttonImageNormal stretchableImageWithLeftCapWidth:12 topCapHeight:0];
	[logoutButton setBackgroundImage:stretchableButtonImageNormal forState:UIControlStateNormal];
    
	UIImage *buttonImagePressed = [UIImage imageNamed:@"blueButton.png"];
	UIImage *stretchableButtonImagePressed = [buttonImagePressed stretchableImageWithLeftCapWidth:12 topCapHeight:0];
	[logoutButton setBackgroundImage:stretchableButtonImagePressed forState:UIControlStateHighlighted];
    
    [super viewDidLoad];
}

- (IBAction)switchViewToFBLogin:(id)sender {
    // TODO: implementation.
}

- (void)didReceiveMemoryWarning {
    // Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
    
    // Release any cached data, images, etc. that aren't in use.
}

- (void)viewDidUnload {
    [super viewDidUnload];
    // Release any retained subviews of the main view.
    // e.g. self.myOutlet = nil;
    
    self.logoutButton = nil;
}

- (void)dealloc {
    [logoutButton release];
	
    [super dealloc];
}

@end
