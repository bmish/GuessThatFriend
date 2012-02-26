//
//  SettingsViewController.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/13/12.
//  Copyright 2012. All rights reserved.
//

#import "SettingsViewController.h"

@implementation SettingsViewController

@synthesize noQuestionsSlider;
@synthesize noQuestionsLabel;
@synthesize quizOptionSegmentedControl;
@synthesize quizOptionLabel;
@synthesize targetFriendEditButton;
@synthesize targetFriendLabel;
@synthesize categoryEditButton;
@synthesize categoryLabel;
@synthesize logoutButton;
@synthesize backButton;

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
	[logoutButton setBackgroundImage:stretchableButtonImageNormal forState:UIControlStateNormal];
	[backButton setBackgroundImage:stretchableButtonImageNormal forState:UIControlStateNormal];
	[targetFriendEditButton setBackgroundImage:stretchableButtonImageNormal forState:UIControlStateNormal];
	[categoryEditButton setBackgroundImage:stretchableButtonImageNormal forState:UIControlStateNormal];
    
	UIImage *buttonImagePressed = [UIImage imageNamed:@"blueButton.png"];
	UIImage *stretchableButtonImagePressed = [buttonImagePressed stretchableImageWithLeftCapWidth:12 topCapHeight:0];
	[logoutButton setBackgroundImage:stretchableButtonImagePressed forState:UIControlStateHighlighted];
	[backButton setBackgroundImage:stretchableButtonImagePressed forState:UIControlStateHighlighted];
	[targetFriendEditButton setBackgroundImage:stretchableButtonImagePressed forState:UIControlStateHighlighted];
	[categoryEditButton setBackgroundImage:stretchableButtonImagePressed forState:UIControlStateHighlighted];
    
    [super viewDidLoad];
}

- (IBAction)noQuestionSliderChanged:(id)sender {
    int value = (int)noQuestionsSlider.value;
    noQuestionsLabel.text = [NSString stringWithFormat:@"%d", value];
}

- (IBAction)quizOptionSegmentedControlChanged:(id)sender {
    int selected = quizOptionSegmentedControl.selectedSegmentIndex;
    NSString *option;
    switch (selected) {
        case 0:
            option = [NSString stringWithString:@"Random"];
            break;
        case 1:
            option = [NSString stringWithString:@"Fill in blanks"];
            break;
        case 2:
            option = [NSString stringWithString:@"2 choices"];
            break;
        case 3:
            option = [NSString stringWithString:@"3 choices"];
            break;
        case 4:
            option = [NSString stringWithString:@"4 choices"];
            break;
        case 5:
            option = [NSString stringWithString:@"5 choices"];
            break;
        case 6:
            option = [NSString stringWithString:@"6 choices"];
            break;
        default:
            break;
    }
    quizOptionLabel.text = option;
}

/*
// Override to allow orientations other than the default portrait orientation.
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
    // Return YES for supported orientations.
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}
*/

- (IBAction)switchViewToFBLogin:(id)sender {
	UIViewController *root = self;
    if ([self respondsToSelector:@selector(presentingViewController)]){
        while (root.presentingViewController != nil) {
            root = root.presentingViewController;
        }
    } else {
        while (root.parentViewController != nil) {
            root = root.parentViewController;
        }
    }
	
	[root dismissModalViewControllerAnimated:YES];
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

- (void)didReceiveMemoryWarning {
    // Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
    
    // Release any cached data, images, etc. that aren't in use.
}

- (void)viewDidUnload {
    [super viewDidUnload];
    // Release any retained subviews of the main view.
    // e.g. self.myOutlet = nil;
	
    self.noQuestionsSlider = nil;
    self.noQuestionsLabel = nil;
    self.quizOptionSegmentedControl = nil;
    self.quizOptionLabel = nil;
    self.targetFriendEditButton = nil;
    self.targetFriendLabel = nil;
    self.categoryEditButton = nil;
    self.categoryLabel = nil;
    self.logoutButton = nil;
    self.backButton = nil;
    
	self.logoutButton = nil;
	self.backButton = nil;
}

- (void)dealloc {
    [noQuestionsSlider release];
    [noQuestionsLabel release];
    [quizOptionSegmentedControl release];
    [quizOptionLabel release];
    [targetFriendEditButton release];
    [targetFriendLabel release];
    [categoryEditButton release];
    [categoryLabel release];
    [logoutButton release];
    [backButton release];
    
	[logoutButton release];
	[backButton release];
	
    [super dealloc];
}

@end
