//
//  QuizBaseViewController.m
//  GuessThatFriend
//
//  Created on 2/15/12.
//
//

#import "QuizBaseViewController.h"
#import "GuessThatFriendAppDelegate.h"

@implementation QuizBaseViewController

@synthesize questionID;
@synthesize isQuestionAnswered;
@synthesize questionLabel;
@synthesize topicImage;

- (IBAction)viewStatsItemPressed:(id)sender {
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    [self.navigationController pushViewController:delegate.tabController animated:YES];
}

- (IBAction)logoutButtonPressed:(id)sender {
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    
    [delegate fbLogout];
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
    
    // Logout button.
    UIBarButtonItem *logoutButton = [[UIBarButtonItem alloc] initWithTitle:@"Logout" style:UIBarButtonItemStylePlain target:self action:@selector(logoutButtonPressed:)];
    self.navigationItem.leftBarButtonItem = logoutButton;
    
    // Statistics button.
    UIBarButtonItem *statsButton = [[UIBarButtonItem alloc] initWithTitle:@"Statistics" style:UIBarButtonItemStylePlain target:self action:@selector(viewStatsItemPressed:)];
    self.navigationItem.rightBarButtonItem = statsButton;
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
}


@end
