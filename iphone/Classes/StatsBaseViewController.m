//
//  StatsBaseViewController.m
//  GuessThatFriend
//
//  Created on 4/19/12.
//

#import "StatsBaseViewController.h"
#import "GuessThatFriendAppDelegate.h"

@implementation StatsBaseViewController

@synthesize list;
@synthesize table;
@synthesize threadIsRunning;

- (void)getStatisticsThread {
    // Not used.
    // Should be implemented by inherited classes.
}

- (void)requestStatisticsFromServer:(BOOL)useSampleData {
    // Not used.
    // Should be implemented by inherited classes.
}

- (IBAction)backItemPressed:(id)sender {
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    [delegate.navController popViewControllerAnimated:YES];
}

- (void)viewDidLoad {
    [super viewDidLoad];
    list = [[NSMutableArray alloc] initWithCapacity:10];
}

- (void)viewDidUnload {
    [super viewDidUnload];
    self.table = nil;
}

- (void)viewDidAppear:(BOOL)animated {
    
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    
    delegate.navController.navigationBar.topItem.title = @"Statistics";
    delegate.navController.navigationBar.topItem.hidesBackButton = NO;
    
    [super viewDidAppear:animated];
}

- (void)dealloc {
    [table release];
    [list release];
    
    [super dealloc];
}

+ (NSMutableString *)getRequestStringWithType:(NSString *)type {
    // Make a real request.
    
    NSMutableString *getRequest = [NSMutableString stringWithString:@BASE_URL_ADDR];
    [getRequest appendString:@"?cmd=getStatistics"];
    
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)
    [[UIApplication sharedApplication] delegate];
    
    [getRequest appendFormat:@"&facebookAccessToken=%@", delegate.facebook.accessToken];
    [getRequest appendFormat:@"&type="];
    [getRequest appendString:type];
    
    return getRequest;
}

#pragma mark -
#pragma mark Table View Data Source Methods

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
	// Not used.
    return 0;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
	// Not used.
	return nil;
}

#pragma mark -
#pragma mark Table Delegate Methods

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	// Not used.
    return;
}

- (CGFloat) tableView: (UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
    // Not used.
	return 0;
}

@end
