//
//  StatsFriendsViewController.m
//  GuessThatFriend
//
//  Created on 4/9/12.
//  Copyright (c) 2012 University of Illinois at Urbana-Champaign. All rights reserved.
//

#import "StatsFriendsViewController.h"
#import "StatsFriendCustomCell.h"
#import "GuessThatFriendAppDelegate.h"
#import "JSONKit.h"
#import "FriendStatsObject.h"

@implementation StatsFriendsViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view from its nib.
}

- (void)viewDidUnload {
    [super viewDidUnload];
}

- (void)dealloc {
    [super dealloc];
}

- (BOOL)createStatsFromServerResponse:(NSString *)response {
    
    // Parse the JSON response.
    NSDictionary *responseDictionary = [response objectFromJSONString];
    
    BOOL success = [[responseDictionary objectForKey:@"success"] boolValue];
    if (success == false) {
        return NO;
    }
    
    // Empty the current list
    [list removeAllObjects];
    
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
        
        [list addObject:statsObj];
        
        [statsObj release];
    }
    
    return YES;
}

- (void)requestStatisticsFromServer:(BOOL)useSampleData{
    
    BOOL success = NO;
    
    while (success == NO) {
        // Create GET request.
        NSMutableString *getRequest;
        
        if (useSampleData) {    // Retrieve sample data.
            getRequest = [NSMutableString stringWithString:@SAMPLE_GET_STATISTICS_FRIENDS_ADDR];
        } else { 
            // Make a real request.
            
            getRequest = [NSMutableString stringWithString:@BASE_URL_ADDR];
            [getRequest appendString:@"?cmd=getStatistics"];
            
            GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)
            [[UIApplication sharedApplication] delegate];
            
            [getRequest appendFormat:@"&facebookAccessToken=%@", delegate.facebook.accessToken];
            [getRequest appendFormat:@"&type=friends"];
        }
        
        NSLog(@"STATS Request string: %@", getRequest);
        
        // Send the GET request to the server.
        NSURLRequest *request = [NSURLRequest requestWithURL:[NSURL URLWithString:getRequest]];
        
        NSData *response = [NSURLConnection sendSynchronousRequest:request returningResponse:nil error:nil];
        NSString *responseString = [[NSString alloc] initWithData:response encoding:NSUTF8StringEncoding];
        
        NSLog(@"STATS RESPONSE STRING: %@ \n",responseString);
        
        // Initialize array of questions from the server's response.
        success = [self createStatsFromServerResponse:responseString];
        
        [responseString release];
    }
}

- (void)getStatisticsThread {
    
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    
    // Only update the stats when we have to.
    if (delegate.statsNeedsUpdate) {
        [self requestStatisticsFromServer:NO];
        delegate.statsNeedsUpdate = NO;
    }
    [spinner stopAnimating];
    [table reloadData];
    
    threadIsRunning = NO;

    NSLog(@"Inside Thread!");
}

/* Everytime this view will appear, we ask the server for stats jason */
- (void)viewWillAppear:(BOOL)animated {
    
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    
    if (delegate.statsNeedsUpdate && threadIsRunning == NO) {
        //SPINNER
        spinner = [[UIActivityIndicatorView alloc] 
                   initWithActivityIndicatorStyle:UIActivityIndicatorViewStyleGray];
        spinner.center = CGPointMake(160, 200);
        spinner.hidesWhenStopped = YES;
        [self.view addSubview:spinner];
        [spinner startAnimating];
        [spinner release];
        //SPINNER
        
        threadIsRunning = YES;
        
        [NSThread detachNewThreadSelector:@selector(getStatisticsThread) toTarget:self withObject:nil];
    }
    
    [super viewWillAppear:animated];
}

#pragma mark -
#pragma mark Table View Data Source Methods

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    NSLog(@"Table cell count: %i", [self.list count]);
	return [self.list count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
	
	StatsFriendCustomCell *cell = (StatsFriendCustomCell *)[tableView dequeueReusableCellWithIdentifier:@"StatsFriendCustomCellIdentifier"];
	
	if(cell == nil) {
		NSArray *nib = [[NSBundle mainBundle] loadNibNamed:@"StatsFriendCustomCell" owner:self options:nil];
		cell = [nib objectAtIndex:0];
	}
	
	NSUInteger row = [indexPath row];
	
    FriendStatsObject *obj = [list objectAtIndex:row];
    cell.picture.image = obj.picture;
	
    // Make sure the name is valid.
    NSString *name = obj.name;
    if (name == (id)[NSNull null] || name.length == 0) {
        name = @"Something is wrong";
    }
    cell.name.text = name;
    
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
