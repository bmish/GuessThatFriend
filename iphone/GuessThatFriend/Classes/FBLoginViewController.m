//
//  FBLoginViewController.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/2/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import "FBLoginViewController.h"
#import "MainMenuViewController.h"

@implementation FBLoginViewController

@synthesize emailField;
@synthesize passwordField;
@synthesize loginButton;
@synthesize backButton;
@synthesize mainMenuViewController;

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

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
	UIImage *buttonImageNormal = [UIImage imageNamed:@"whiteButton.png"];
	UIImage *stretchableButtonImageNormal = [buttonImageNormal stretchableImageWithLeftCapWidth:12 topCapHeight:0];
	[loginButton setBackgroundImage:stretchableButtonImageNormal forState:UIControlStateNormal];
	[backButton setBackgroundImage:stretchableButtonImageNormal forState:UIControlStateNormal];
	
	UIImage *buttonImagePressed = [UIImage imageNamed:@"blueButton.png"];
	UIImage *stretchableButtonImagePressed = [buttonImagePressed stretchableImageWithLeftCapWidth:12 topCapHeight:0];
	[loginButton setBackgroundImage:stretchableButtonImagePressed forState:UIControlStateHighlighted];
	[backButton setBackgroundImage:stretchableButtonImagePressed forState:UIControlStateHighlighted];
	
    [super viewDidLoad];
}

/*
// Override to allow orientations other than the default portrait orientation.
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
    // Return YES for supported orientations.
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}
*/

- (IBAction)textFieldDoneEditing:(id)sender {
	[emailField resignFirstResponder];
	[passwordField resignFirstResponder];
}

- (IBAction)switchViewToGoBack:(id)sender {
	if(self.modalViewController) {
		[self dismissModalViewControllerAnimated:NO];
	}
	
    if ([self respondsToSelector:@selector(presentingViewController)]){
        [self.presentingViewController dismissModalViewControllerAnimated:YES];
    } else {
        [self.parentViewController dismissModalViewControllerAnimated:YES];
    }
}

- (IBAction)switchViewToMenu:(id)sender {
	if(mainMenuViewController == nil) {
		MainMenuViewController *mainMenuController = [[MainMenuViewController alloc] initWithNibName:@"MainMenuViewController" bundle:nil];
		self.mainMenuViewController = mainMenuController;
		[mainMenuController release];
	}
	
	if(self.modalViewController) {
		[self dismissModalViewControllerAnimated:NO];
	}
	
	[self presentModalViewController:mainMenuViewController animated:YES];
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
	
	self.emailField = nil;
	self.passwordField = nil;
	self.loginButton = nil;
	self.backButton = nil;
	self.mainMenuViewController = nil;
}

- (void)dealloc {
	[emailField release];
	[passwordField release];
	[loginButton release];
	[backButton release];
	[mainMenuViewController release];
	
    [super dealloc];
}

@end
