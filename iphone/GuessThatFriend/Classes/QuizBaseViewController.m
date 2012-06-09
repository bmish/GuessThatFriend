//
//  QuizBaseViewController.m
//  GuessThatFriend
//
//  Created on 2/15/12.
//
//

#import "QuizBaseViewController.h"
#import "GuessThatFriendAppDelegate.h"
#import "SettingsViewController.h"

@implementation QuizBaseViewController

@synthesize questionID;
@synthesize isQuestionAnswered;
@synthesize questionLabel;
@synthesize topicImage;
@synthesize settingsViewController;

- (IBAction)settingsItemPressed:(id)sender {
    if(settingsViewController == nil) {
		SettingsViewController *settingsController = [[SettingsViewController alloc] 
                                                      initWithNibName:@"SettingsViewController" bundle:nil];
		self.settingsViewController = settingsController;
		[settingsController release];
	}
    
    [self.navigationController pushViewController:self.settingsViewController animated:YES];
}

- (IBAction)viewStatsItemPressed:(id)sender {
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    [self.navigationController pushViewController:delegate.tabController animated:YES];
}

- (void)viewWillAppear:(BOOL)animated {
    self.questionLabel.backgroundColor = [UIColor whiteColor];
    self.questionLabel.adjustsFontSizeToFitWidth = YES;
    self.questionLabel.numberOfLines = 5;
    self.isQuestionAnswered = false;
}

- (void)viewDidAppear:(BOOL)animated {
    [super viewDidAppear:animated];
    
    // Set up the two bar items on this view.
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    
    // Choose what the initial title is.
    if ([delegate.navController.navigationBar.topItem.title length] == 0) {
        delegate.navController.navigationBar.topItem.title = @"0 / 0";
    }
    
    // About button.
    UIButton *aboutButton = [UIButton buttonWithType:UIButtonTypeInfoLight];
	[aboutButton addTarget:self
                        action:@selector(settingsItemPressed:)
              forControlEvents:UIControlEventTouchUpInside];
	UIBarButtonItem *aboutBarButtonItem = [[UIBarButtonItem alloc] initWithCustomView:aboutButton];
	self.navigationItem.leftBarButtonItem = aboutBarButtonItem;
	[aboutBarButtonItem release];
    
    // Statistics button.
    UIBarButtonItem *statsButton = [[UIBarButtonItem alloc] initWithTitle:@"Statistics" style:UIBarButtonItemStylePlain target:self action:@selector(viewStatsItemPressed:)];
    self.navigationItem.rightBarButtonItem = statsButton;
    [statsButton release];
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
	
	self.questionLabel = nil;	
    self.topicImage = nil;
    self.settingsViewController = nil;
}

- (void)dealloc {
	[questionLabel release];
    [settingsViewController release];
	[topicImage release];
    
    [super dealloc];
}

@end
