//
//  StatsBaseViewController.m
//  GuessThatFriend
//
//  Created on 4/19/12.
//

#import "StatsBaseViewController.h"
#import "GuessThatFriendAppDelegate.h"
#import "UIBarButtonItem+Image.h"

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
    delegate.navController.navigationBar.topItem.hidesBackButton = YES;
    
    UIImage *backImage = [UIImage imageNamed:@"Button_Back.png"];
    UIBarButtonItem *leftCornerButton = [[UIBarButtonItem alloc]
                                         initWithImage:backImage
                                         title:@"" target:self
                                         action:@selector(backItemPressed:)];
    delegate.navController.navigationBar.topItem.leftBarButtonItem = leftCornerButton;
    
    [super viewDidAppear:animated];
}

- (void)dealloc {
    [table release];
    [list release];
    
    [super dealloc];
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
