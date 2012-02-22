//
//  MainMenu.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/8/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import "MainMenuViewController.h"
#import "SettingsViewController.h"
#import "MultipleChoiceQuizViewController.h"
#import "QuizManager.h"

@implementation MainMenuViewController

@synthesize quizModeButton;
@synthesize survivalModeButton;
@synthesize timeModeButton;
@synthesize settingsButton;
@synthesize multipleChoiceQuizViewController;
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

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
	UIImage *buttonImageNormal = [UIImage imageNamed:@"whiteButton.png"];
	UIImage *stretchableButtonImageNormal = [buttonImageNormal stretchableImageWithLeftCapWidth:12 topCapHeight:0];
	[quizModeButton setBackgroundImage:stretchableButtonImageNormal forState:UIControlStateNormal];
	[survivalModeButton setBackgroundImage:stretchableButtonImageNormal forState:UIControlStateNormal];
	[timeModeButton setBackgroundImage:stretchableButtonImageNormal forState:UIControlStateNormal];
	[settingsButton setBackgroundImage:stretchableButtonImageNormal forState:UIControlStateNormal];
	
	UIImage *buttonImagePressed = [UIImage imageNamed:@"blueButton.png"];
	UIImage *stretchableButtonImagePressed = [buttonImagePressed stretchableImageWithLeftCapWidth:12 topCapHeight:0];
	[quizModeButton setBackgroundImage:stretchableButtonImagePressed forState:UIControlStateHighlighted];
	[survivalModeButton setBackgroundImage:stretchableButtonImagePressed forState:UIControlStateHighlighted];
	[timeModeButton setBackgroundImage:stretchableButtonImagePressed forState:UIControlStateHighlighted];
	[settingsButton setBackgroundImage:stretchableButtonImagePressed forState:UIControlStateHighlighted];
	
    [super viewDidLoad];
}

/*
// Override to allow orientations other than the default portrait orientation.
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
    // Return YES for supported orientations.
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}
*/

- (IBAction)switchViewToRegularQuiz:(id)sender {
	//TODO: finish implementation
	
	if (multipleChoiceQuizViewController == nil) {
		multipleChoiceQuizViewController = [[MultipleChoiceQuizViewController alloc] 
                                            initWithNibName:@"MultipleChoiceQuizViewController" bundle:nil];
	}
	
	if(self.modalViewController) {
		[self dismissModalViewControllerAnimated:NO];
	}
	
	[self presentModalViewController:multipleChoiceQuizViewController animated:YES];
}

- (IBAction)switchViewToSurvivalQuiz:(id)sender {
	
}

- (IBAction)switchViewToTimeQuiz:(id)sender {
	
}

- (IBAction)switchViewToSettings:(id)sender {
	if(settingsViewController == nil) {
		SettingsViewController *settingsController = [[SettingsViewController alloc] 
                                                      initWithNibName:@"SettingsViewController" bundle:nil];
		self.settingsViewController = settingsController;
		[settingsController release];
	}
	
	if(self.modalViewController) {
		[self dismissModalViewControllerAnimated:NO];
	}
	
	[self presentModalViewController:settingsViewController animated:YES];
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
	
	self.quizModeButton = nil;
	self.survivalModeButton = nil;
	self.timeModeButton = nil;
	self.settingsButton = nil;
	self.multipleChoiceQuizViewController = nil;
	self.settingsViewController = nil;
}

- (void)dealloc {
	[quizModeButton release];
	[survivalModeButton release];
	[timeModeButton release];
	[settingsButton release];
	[multipleChoiceQuizViewController release];
	[settingsViewController release];
	
    [super dealloc];
}

@end
