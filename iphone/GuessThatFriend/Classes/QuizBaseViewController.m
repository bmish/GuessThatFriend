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
#import "StatsViewController.h"

@implementation QuizBaseViewController

@synthesize questionID;
@synthesize questionTextView;
@synthesize settingsViewController;
@synthesize topicImage;
@synthesize statsViewController;

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

    [UIView beginAnimations:@"settings" context: nil];
    [UIView setAnimationCurve: UIViewAnimationCurveEaseInOut];
    [UIView setAnimationDuration:0.75];
    [delegate.navController pushViewController:self.settingsViewController animated:YES];
    [UIView setAnimationTransition:UIViewAnimationTransitionFlipFromRight forView:delegate.navController.view cache:NO];
    [UIView commitAnimations];
}

- (IBAction)viewStatsItemPressed:(id)sender {
    //NSLog(@"THIS IS SPARTA!");
    
    if(statsViewController == nil) {
		StatsViewController *statsController = [[StatsViewController alloc] 
                                                      initWithNibName:@"StatsViewController" bundle:nil];
		self.statsViewController = statsController;
		[statsController release];
	}
    
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    
    [UIView beginAnimations:@"stats" context: nil];
    [UIView setAnimationCurve: UIViewAnimationCurveEaseInOut];
    [UIView setAnimationDuration:0.75];
    [delegate.navController pushViewController:self.statsViewController animated:YES];
    [UIView setAnimationTransition:UIViewAnimationTransitionFlipFromRight forView:delegate.navController.view cache:NO];
    [UIView commitAnimations];

    
}

- (void)viewDidAppear:(BOOL)animated {
    
    // Set up the two bar items on this view.
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    
    delegate.navController.navigationBar.topItem.title = @"GTF";
    
    UIBarButtonItem *leftCornerButton = [[UIBarButtonItem alloc] 
                                         initWithTitle:@"Settings" 
                                         style:UIBarButtonItemStylePlain target:self 
                                         action:@selector(settingsItemPressed:)];
    delegate.navController.navigationBar.topItem.leftBarButtonItem = leftCornerButton;
    [leftCornerButton release];
    
    UIBarButtonItem *rightCornerButton = [[UIBarButtonItem alloc] 
                                          initWithTitle:@"Statistics" 
                                          style:UIBarButtonItemStylePlain target:self 
                                          action:@selector(viewStatsItemPressed:)];
    delegate.navController.navigationBar.topItem.rightBarButtonItem = rightCornerButton;
    [rightCornerButton release];
    
    [super viewDidAppear:animated];
}

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
    [super viewDidLoad];
}

/*
// Override to allow orientations other than the default portrait orientation.
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
    // Return YES for supported orientations.
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}
*/

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
    self.statsViewController = nil;
}

- (void)dealloc {
	[questionTextView release];
    [settingsViewController release];
	[topicImage release];
    [statsViewController release];
    
    [super dealloc];
}

@end
