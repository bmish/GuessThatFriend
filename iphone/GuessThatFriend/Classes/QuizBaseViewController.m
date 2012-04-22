//
//  QuizBaseViewController.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012. All rights reserved.
//

#import "QuizBaseViewController.h"
#import "GuessThatFriendAppDelegate.h"
#import "SettingsViewController.h"
#import "UIBarButtonItem+Image.h"

@implementation QuizBaseViewController

@synthesize questionID;
@synthesize questionTextView;
@synthesize topicImage;
@synthesize settingsViewController;

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

/*
// Implement loadView to create a view hierarchy programmatically, without using a nib.
- (void)loadView {
}
*/

- (IBAction)settingsItemPressed:(id)sender {
    if(settingsViewController == nil) {
		SettingsViewController *settingsController = [[SettingsViewController alloc] 
                                                      initWithNibName:@"SettingsViewController" bundle:nil];
		self.settingsViewController = settingsController;
		[settingsController release];
	}
    
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];

    [UIView beginAnimations:@"settings" context:nil];
    [UIView setAnimationCurve:UIViewAnimationCurveEaseInOut];
    [UIView setAnimationDuration:0.75];
    [delegate.navController pushViewController:self.settingsViewController animated:YES];
    [UIView setAnimationTransition:UIViewAnimationTransitionFlipFromLeft forView:delegate.navController.view cache:NO];
    [UIView commitAnimations];
}

- (IBAction)viewStatsItemPressed:(id)sender {
    
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    
    [UIView beginAnimations:@"stats" context:nil];
    [UIView setAnimationCurve:UIViewAnimationCurveEaseInOut];
    [UIView setAnimationDuration:0.75];
    [delegate.navController pushViewController:delegate.tabController animated:YES];
    [UIView setAnimationTransition:UIViewAnimationTransitionFlipFromRight forView:delegate.navController.view cache:NO];
    [UIView commitAnimations];
}

- (void)viewDidAppear:(BOOL)animated {
    [super viewDidAppear:animated];
    
    // Set up the two bar items on this view.
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    
    // Choose what the initial title is.
    if ([delegate.navController.navigationBar.topItem.title length] == 0) {
        delegate.navController.navigationBar.topItem.title = @"0/0";
    }
    
    UIImage *settingsImage = [UIImage imageNamed:@"Button_Setting.png"];
    UIBarButtonItem *leftCornerButton = [[UIBarButtonItem alloc]
                                         initWithImage:settingsImage
                                         title:@"" target:self
                                         action:@selector(settingsItemPressed:)];
    delegate.navController.navigationBar.topItem.leftBarButtonItem = leftCornerButton;
    
    UIImage *statsImage = [UIImage imageNamed:@"Button_Statistic.png"];
    UIBarButtonItem *rightCornerButton = [[UIBarButtonItem alloc]
                                          initWithImage:statsImage
                                          title:@"" target:self
                                          action:@selector(viewStatsItemPressed:)];
    delegate.navController.navigationBar.topItem.rightBarButtonItem = rightCornerButton;
}

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
    [super viewDidLoad];
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
	
	self.questionTextView = nil;	
    self.topicImage = nil;
    self.settingsViewController = nil;
}

- (void)dealloc {
	[questionTextView release];
    [settingsViewController release];
	[topicImage release];
    
    [super dealloc];
}

@end
