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

@implementation QuizBaseViewController

@synthesize questionID;
@synthesize questionTextView;
@synthesize nextButton;
@synthesize settingsViewController;

// Not implemented, this should be implemented by sub-classes.
- (IBAction)submitAnswers:(id)sender {
    //TODO:
}

- (IBAction)finishQuiz:(id)sender {
    //TODO: implementation
}

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

- (IBAction)doneItemPressed:(id)sender {
    NSLog(@"TODO: implementation");
}

- (void)viewDidAppear:(BOOL)animated {
    
    // Set up the two bar items on this view.
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    
    delegate.navController.navigationBar.topItem.title = @"GTF!";
    
    UIBarButtonItem *leftCornerButton = [[UIBarButtonItem alloc] 
                                         initWithTitle:@"Settings" 
                                         style:UIBarButtonItemStylePlain target:self 
                                         action:@selector(settingsItemPressed:)];
    delegate.navController.navigationBar.topItem.leftBarButtonItem = leftCornerButton;
    [leftCornerButton release];
    
    UIBarButtonItem *rightCornerButton = [[UIBarButtonItem alloc] 
                                          initWithTitle:@"  Done  " 
                                          style:UIBarButtonItemStylePlain target:self 
                                          action:@selector(doneItemPressed:)];
    delegate.navController.navigationBar.topItem.rightBarButtonItem = rightCornerButton;
    [rightCornerButton release];
    
    [super viewDidAppear:animated];
}

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
	UIImage *buttonImageNormal = [UIImage imageNamed:@"whiteButton.png"];
	UIImage *stretchableButtonImageNormal = [buttonImageNormal stretchableImageWithLeftCapWidth:12 topCapHeight:0];
	[nextButton setBackgroundImage:stretchableButtonImageNormal forState:UIControlStateNormal];
	
	UIImage *buttonImagePressed = [UIImage imageNamed:@"blueButton.png"];
	UIImage *stretchableButtonImagePressed = [buttonImagePressed stretchableImageWithLeftCapWidth:12 topCapHeight:0];
	[nextButton setBackgroundImage:stretchableButtonImagePressed forState:UIControlStateHighlighted];
    
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
	self.nextButton = nil;
    self.settingsViewController = nil;
}

- (void)dealloc {
	[questionTextView release];
	[nextButton release];
    [settingsViewController release];
	
    [super dealloc];
}

@end
