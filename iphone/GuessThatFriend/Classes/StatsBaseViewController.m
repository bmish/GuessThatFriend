//
//  StatsBaseViewController.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 4/19/12.
//  Copyright (c) 2012 __MyCompanyName__. All rights reserved.
//

#import "StatsBaseViewController.h"
#import "GuessThatFriendAppDelegate.h"
#import "UIBarButtonItem+Image.h"

@implementation StatsBaseViewController

@synthesize friendsList;
@synthesize friendsTable;

/*
- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}
*/

- (IBAction)backItemPressed:(id)sender {
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    
    [UIView beginAnimations:@"back from stats" context:nil];
    [UIView setAnimationCurve:UIViewAnimationCurveEaseInOut];
    [UIView setAnimationDuration:0.75];
    [delegate.navController popViewControllerAnimated:YES];
    [UIView setAnimationTransition:UIViewAnimationTransitionFlipFromLeft forView:delegate.navController.view cache:NO];
    [UIView commitAnimations];
}

- (void)viewDidLoad {
    [super viewDidLoad];
    friendsList = [[NSMutableArray alloc] initWithCapacity:10];
}

- (void)viewDidUnload {
    [super viewDidUnload];
    self.friendsTable = nil;
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
    [friendsTable release];
    [friendsList release];
    
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
