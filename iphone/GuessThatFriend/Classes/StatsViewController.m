//
//  StatsViewController.m
//  GuessThatFriend
//
//  Created by Arjan Singh Nirh on 4/9/12.
//  Copyright (c) 2012 University of Illinois at Urbana-Champaign. All rights reserved.
//

#import "StatsViewController.h"
#import "GuessThatFriendAppDelegate.h"

@implementation StatsViewController

@synthesize friendsList;
@synthesize friendsTable;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    // Do any additional setup after loading the view from its nib.
}

- (void)viewDidUnload
{
    [super viewDidUnload];
    self.friendsTable = nil;
}

- (void)dealloc {
    [friendsTable release];
    [friendsList release];
    [super dealloc];
    
}

- (void)viewDidAppear:(BOOL)animated {
    
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    
    delegate.navController.navigationBar.topItem.title = @"Statistics";
    
    delegate.navController.navigationBar.topItem.hidesBackButton = YES;
    
    UIBarButtonItem *leftCornerButton = [[UIBarButtonItem alloc] 
                                         initWithTitle:@"Back" 
                                         style:UIBarButtonItemStylePlain target:self 
                                         action:@selector(backItemPressed:)];
    delegate.navController.navigationBar.topItem.leftBarButtonItem = leftCornerButton;
    [leftCornerButton release];
    
    [super viewDidAppear:animated];
}

- (IBAction)backItemPressed:(id)sender {
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    [delegate.navController popViewControllerAnimated:YES];
}

#pragma mark -
#pragma mark Table View Data Source Methods

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
	return [self.friendsList count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
	/*
	FBFriendCustomCell *cell = (FBFriendCustomCell *)[tableView dequeueReusableCellWithIdentifier:@"FBFriendCustomCellIdentifier"];
	
	if(cell == nil) {
		NSArray *nib = [[NSBundle mainBundle] loadNibNamed:@"FBFriendCustomCell" owner:self options:nil];
		cell = [nib objectAtIndex:0];
	}
	
	NSUInteger row = [indexPath row];
	
	Option *option = [optionsList objectAtIndex:row];
	cell.picture.image = option.subject.picture;
	cell.name.text = option.subject.name;
	//cell = option.subject.link;
	//	cell.name.text = option.subject.facebookId;
	return cell;
     */
    return nil;
}

#pragma mark -
#pragma mark Table Delegate Methods

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	
    /*
    NSUInteger selectedRow = [indexPath row];
    Option *option = [optionsList objectAtIndex:selectedRow];
    
    [tableView deselectRowAtIndexPath:indexPath animated:YES];
    
    UITableViewCell *cell = [tableView cellForRowAtIndexPath:indexPath];
    UIView *bgColorView = [[UIView alloc] init];
    
    //Check if selected option is correct
    if ([option.subject.facebookId isEqualToString:correctFacebookId]){
        responseLabel.text = @"Correct";
        cell.backgroundColor = [UIColor greenColor];
        //[bgColorView setBackgroundColor:[UIColor greenColor]];
    }
    else {
        responseLabel.text = @"Wrong";
        cell.backgroundColor = [UIColor redColor];
        // [bgColorView setBackgroundColor:[UIColor redColor]];
    }
    
    // [cell setSelectedBackgroundView:bgColorView];
    [bgColorView release];
    
    //construct the request string
    NSMutableString *getRequest;
    
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate*) [[UIApplication sharedApplication] delegate];
    
    getRequest = [NSMutableString stringWithString:@BASE_URL_ADDR];
    [getRequest appendString:@"?cmd=submitQuestions"];
    [getRequest appendFormat:@"&facebookAccessToken=%@", delegate.facebook.accessToken];
    [getRequest appendFormat:@"&facebookIdOfQuestion%i=%@", questionID, option.subject.facebookId];
    
    NSLog(@"Request: %@\n", getRequest);
    
    
    
    
    NSURLRequest *request = [NSURLRequest requestWithURL:[NSURL URLWithString:getRequest]];
    NSLog(@"%@\n", request);
    NSData *response = [NSURLConnection sendSynchronousRequest:request returningResponse:nil error:nil];
    NSString *responseString = [[NSString alloc] initWithData: response encoding:NSUTF8StringEncoding];
    
    [responseString release];
    */
	
}

- (CGFloat) tableView: (UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
	return 65;		// 65 is the fixed height of each cell, it is set in the nib.
}




@end
