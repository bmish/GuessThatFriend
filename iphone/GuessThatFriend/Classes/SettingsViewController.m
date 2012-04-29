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

@synthesize quizOptionSegmentedControl;
@synthesize quizOptionLabel;
@synthesize targetFriendEditButton;
@synthesize targetFriendLabel;
@synthesize categoryEditButton;
@synthesize categoryLabel;
@synthesize logoutButton;

// The designated initializer.  Override if you create the controller programmatically and want to perform customization that is not appropriate for viewDidLoad.
/*
- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization.
    }
    return self;
}
*/

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
	[targetFriendEditButton setBackgroundImage:stretchableButtonImageNormal forState:UIControlStateNormal];
	[categoryEditButton setBackgroundImage:stretchableButtonImageNormal forState:UIControlStateNormal];
    
	UIImage *buttonImagePressed = [UIImage imageNamed:@"blueButton.png"];
	UIImage *stretchableButtonImagePressed = [buttonImagePressed stretchableImageWithLeftCapWidth:12 topCapHeight:0];
	[logoutButton setBackgroundImage:stretchableButtonImagePressed forState:UIControlStateHighlighted];
	[targetFriendEditButton setBackgroundImage:stretchableButtonImagePressed forState:UIControlStateHighlighted];
	[categoryEditButton setBackgroundImage:stretchableButtonImagePressed forState:UIControlStateHighlighted];
    
    [super viewDidLoad];
}

- (IBAction)quizOptionSegmentedControlChanged:(id)sender {
    int selected = quizOptionSegmentedControl.selectedSegmentIndex;
    QuizSettings *quizSettings = [QuizSettings quizSettingObject];
    
    NSString *option;
    switch (selected) {
            
        /* From the API: 
           -1: Random
           0: Fill in the blank
           2-6: Multiple choice
        */
        
        case 0:
            option = [NSString stringWithString:@"Random"];
            quizSettings.option = -1;
            break;
        case 1:
            option = [NSString stringWithString:@"Fill in the blank"];
            quizSettings.option = 0;
            break;
        case 2:
            option = [NSString stringWithString:@"2 choices"];
            quizSettings.option = 2;
            break;
        case 3:
            option = [NSString stringWithString:@"3 choices"];
            quizSettings.option = 3;
            break;
        case 4:
            option = [NSString stringWithString:@"4 choices"];
            quizSettings.option = 4;
            break;
        case 5:
            option = [NSString stringWithString:@"5 choices"];
            quizSettings.option = 5;
            break;
        case 6:
            option = [NSString stringWithString:@"6 choices"];
            quizSettings.option = 6;
            break;
        default:
            break;
    }
    
    quizOptionLabel.text = option;
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
    
    self.quizOptionSegmentedControl = nil;
    self.quizOptionLabel = nil;
    self.targetFriendEditButton = nil;
    self.targetFriendLabel = nil;
    self.categoryEditButton = nil;
    self.categoryLabel = nil;
}

- (void)dealloc {
    [quizOptionSegmentedControl release];
    [quizOptionLabel release];
    [targetFriendEditButton release];
    [targetFriendLabel release];
    [categoryEditButton release];
    [categoryLabel release];
    [logoutButton release];
	
    [super dealloc];
}

@end
