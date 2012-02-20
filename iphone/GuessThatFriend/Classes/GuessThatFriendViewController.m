//
//  GuessThatFriendViewController.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/2/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import "GuessThatFriendViewController.h"
#import "FBLoginViewController.h"
#import "AboutViewController.h"

@implementation GuessThatFriendViewController

@synthesize loginButton;
@synthesize aboutButton;
@synthesize fbLoginViewController;
@synthesize aboutViewController;

/*
// The designated initializer. Override to perform setup that is required before the view is loaded.
- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}
*/

/*
// Implement loadView to create a view hierarchy programmatically, without using a nib.
- (void)loadView {
}
*/

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
	UIImage *buttonImageNormal = [UIImage imageNamed:@"whiteButton.png"];
	UIImage *stretchableButtonImageNormal = [buttonImageNormal stretchableImageWithLeftCapWidth:12 topCapHeight:0];
	[loginButton setBackgroundImage:stretchableButtonImageNormal forState:UIControlStateNormal];
	[aboutButton setBackgroundImage:stretchableButtonImageNormal forState:UIControlStateNormal];
	
	UIImage *buttonImagePressed = [UIImage imageNamed:@"blueButton.png"];
	UIImage *stretchableButtonImagePressed = [buttonImagePressed stretchableImageWithLeftCapWidth:12 topCapHeight:0];
	[loginButton setBackgroundImage:stretchableButtonImagePressed forState:UIControlStateHighlighted];
	[aboutButton setBackgroundImage:stretchableButtonImagePressed forState:UIControlStateHighlighted];
	
    [super viewDidLoad];
}

/*
// Override to allow orientations other than the default portrait orientation.
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
    // Return YES for supported orientations
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}
*/

- (IBAction)switchViewToLogin:(id)sender {
	if(fbLoginViewController == nil) {
		FBLoginViewController *loginController = [[FBLoginViewController alloc] initWithNibName:@"FBLoginViewController" bundle:nil];
		self.fbLoginViewController = loginController;
		[loginController release];
	}
	
	if(self.modalViewController) {
		[self dismissModalViewControllerAnimated:NO];
	}
	
	[self presentModalViewController:fbLoginViewController animated:YES];
}

- (IBAction)switchViewToAbout:(id)sender {
	if(aboutViewController == nil) {
		AboutViewController *aboutController = [[AboutViewController alloc] initWithNibName:@"AboutViewController" bundle:nil];
		self.aboutViewController = aboutController;
		[aboutController release];
	}
	
	if(self.modalViewController) {
		[self dismissModalViewControllerAnimated:NO];
	}
	
	[self presentModalViewController:aboutViewController animated:YES];
}

- (void)didReceiveMemoryWarning {
	// Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
	
	// Release any cached data, images, etc that aren't in use.
}

- (void)viewDidUnload {
	// Release any retained subviews of the main view.
	// e.g. self.myOutlet = nil;
	
	self.loginButton = nil;
	self.aboutButton = nil;
	self.fbLoginViewController = nil;
	self.aboutViewController = nil;
}

- (void)dealloc {
	[loginButton release];
	[aboutButton release];
	[fbLoginViewController release];
	[aboutViewController release];
	
    [super dealloc];
}

@end
