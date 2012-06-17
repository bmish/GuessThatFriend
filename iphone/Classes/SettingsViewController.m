//
//  SettingsViewController.m
//  GuessThatFriend
//
//  Created on 2/13/12.
//
//

#import "SettingsViewController.h"
#import "GuessThatFriendAppDelegate.h"
#import "QuizSettings.h"

@implementation SettingsViewController

@synthesize shareButton;
@synthesize logoutButton;

- (IBAction)backItemPressed:(id)sender {
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    [delegate.navController popViewControllerAnimated:YES];
}

- (void)viewDidAppear:(BOOL)animated {
    
    // Set up the two bar items on this view.
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    
    delegate.navController.navigationBar.topItem.title = @"About";
    delegate.navController.navigationBar.topItem.hidesBackButton = NO;
     
    [super viewDidAppear:animated];
}

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
	UIImage *buttonImageNormal = [UIImage imageNamed:@"whiteButton.png"];
	UIImage *stretchableButtonImageNormal = [buttonImageNormal stretchableImageWithLeftCapWidth:12 topCapHeight:0];
    [shareButton setBackgroundImage:stretchableButtonImageNormal forState:UIControlStateNormal];
	[logoutButton setBackgroundImage:stretchableButtonImageNormal forState:UIControlStateNormal];
    
	UIImage *buttonImagePressed = [UIImage imageNamed:@"blueButton.png"];
	UIImage *stretchableButtonImagePressed = [buttonImagePressed stretchableImageWithLeftCapWidth:12 topCapHeight:0];
    [shareButton setBackgroundImage:stretchableButtonImagePressed forState:UIControlStateHighlighted];
	[logoutButton setBackgroundImage:stretchableButtonImagePressed forState:UIControlStateHighlighted];
    
    [super viewDidLoad];
}

// Share our app on user's facebook page.
- (IBAction)shareOnFacebook:(id)sender {
    // The URL to share.
    NSString *urlString = @"https://wiki.engr.illinois.edu/display/cs428sp12/GuessThatFriend";
    
    // The title to be displayed on Facebook.
    NSString *title = @"GuessThatFriend!";

    // Create the URL string which will tell Facebook you want to share that specific page.
    NSString *shareUrlString = [NSString stringWithFormat:@"http://www.facebook.com/sharer.php?u=%@&t=%@", urlString , title];
    
    NSURL *url = [[NSURL alloc] initWithString:shareUrlString];
    
    [[UIApplication sharedApplication] openURL:url];
    
    [url release];
}

- (void)facebookLogout {
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    
    [delegate fbLogout];
}

- (IBAction)switchViewToFBLogin:(id)sender {
    [self facebookLogout];
    
    [self backItemPressed:nil];
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
    
    self.shareButton = nil;
    self.logoutButton = nil;
}

- (void)dealloc {
    [shareButton release];
    [logoutButton release];
	
    [super dealloc];
}

@end
