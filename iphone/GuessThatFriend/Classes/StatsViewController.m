//
//  StatsViewController.m
//  GuessThatFriend
//
//  Created by Arjan Singh Nirh on 4/9/12.
//  Copyright (c) 2012 University of Illinois at Urbana-Champaign. All rights reserved.
//

#import "StatsViewController.h"
#import "StatsCustomCell.h"
#import "GuessThatFriendAppDelegate.h"
#import "JSONKit.h"
#import "FriendStatsObject.h"

#define SAMPLE_GET_STATISTICS_ADDR   "http://guessthatfriend.jasonsze.com/api/examples/json/getStatistics.json"
#define BASE_URL_ADDR               "http://guessthatfriend.jasonsze.com/api/"

@implementation StatsViewController

@synthesize friendsList;
@synthesize friendsTable;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
        friendsList = [[NSMutableArray alloc] initWithCapacity:10];
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

- (void)createStatsFromServerResponse:(NSString *)response {
    
    // Empty the current list
    [friendsList removeAllObjects];
    
    // Parse the JSON response.
    NSDictionary *responseDictionary = [response objectFromJSONString];
    
    NSArray *friendsArray = [responseDictionary objectForKey:@"friends"];
    
    NSEnumerator *friendEnumerator = [friendsArray objectEnumerator];
    NSDictionary *curFriend;
    
    // Go through all FRIENDS
    while (curFriend = [friendEnumerator nextObject]) {
        NSDictionary *subjectDict = [curFriend objectForKey:@"subject"];
        NSString *correctCountStr = [curFriend objectForKey:@"correctAnswerCount"];
        NSString *totalCountStr = [curFriend objectForKey:@"totalAnswerCount"];
        
        int correctCount = [correctCountStr intValue];
        int totalCount = [totalCountStr intValue];
        
        NSString *name = [subjectDict objectForKey:@"name"];
        NSString *picURL = [subjectDict objectForKey:@"picture"];
        
        FriendStatsObject *statsObj = [[FriendStatsObject alloc] initWithName:name andImagePath:picURL andCorrectCount:correctCount andTotalCount:totalCount];
        
        [friendsList addObject:statsObj];
        
        [statsObj release];
    }
}

- (void)requestStatisticsFromServer:(BOOL)useSampleData{
    
    // Create GET request.
    NSMutableString *getRequest;
    
    if (useSampleData) {    // Retrieve sample data.
        getRequest = [NSMutableString stringWithString:@SAMPLE_GET_STATISTICS_ADDR];
    } else { 
        // Make a real request.
        
        getRequest = [NSMutableString stringWithString:@BASE_URL_ADDR];
        [getRequest appendString:@"?cmd=getStatistics"];
        
        GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate*)
        [[UIApplication sharedApplication] delegate];
        
        [getRequest appendFormat:@"&facebookAccessToken=%@", delegate.facebook.accessToken];
        [getRequest appendFormat:@"&type=listAnswerCounts"];
    }
    
    NSLog(@"STATS Request string: %@", getRequest);
    
    // Send the GET request to the server.
    NSURLRequest *request = [NSURLRequest requestWithURL:[NSURL URLWithString:getRequest]];
    
    NSData *response = [NSURLConnection sendSynchronousRequest:request returningResponse:nil error:nil];
    NSString *responseString = [[NSString alloc] initWithData:response encoding:NSUTF8StringEncoding];
    
    NSLog(@"STATS RESPONSE STRING: %@ \n",responseString);
    
    // Initialize array of questions from the server's response.
    [self createStatsFromServerResponse:responseString];
    
    [responseString release];
}

/* Everytime this view will appear, we ask the server for stats jason */
- (void)viewWillAppear:(BOOL)animated {
    [self requestStatisticsFromServer:NO];
    
    [super viewWillAppear:animated];
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
    [delegate.navController popViewControllerAnimated:NO];
}

#pragma mark -
#pragma mark Table View Data Source Methods

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
	return [self.friendsList count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
	
	StatsCustomCell *cell = (StatsCustomCell *)[tableView dequeueReusableCellWithIdentifier:@"StatsCustomCellIdentifier"];
	
	if(cell == nil) {
		NSArray *nib = [[NSBundle mainBundle] loadNibNamed:@"StatsCustomCell" owner:self options:nil];
		cell = [nib objectAtIndex:0];
	}
	
	NSUInteger row = [indexPath row];
	
    FriendStatsObject *obj = [friendsList objectAtIndex:row];
    cell.picture.image = obj.picture;
	cell.name.text = obj.name;
    float percentage = (float)obj.correctCount / obj.totalCount;
    cell.percentageLabel.text = [NSString stringWithFormat:@"%i/%i", obj.correctCount, obj.totalCount]; 
	[cell.progressBar setProgress:percentage animated:NO];
    
	return cell;
}

#pragma mark -
#pragma mark Table Delegate Methods

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	
    [tableView deselectRowAtIndexPath:indexPath animated:NO];

    return;
}

- (CGFloat) tableView: (UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
	return 55;		// 55 is the fixed height of each cell, it is set in the nib.
}

@end
