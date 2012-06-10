//
//  StatsFriendsViewController.m
//  GuessThatFriend
//
//  Created on 4/9/12.
//

#import "StatsFriendsViewController.h"
#import "StatsFriendCustomCell.h"
#import "GuessThatFriendAppDelegate.h"
#import "JSONKit.h"
#import "FriendStatsObject.h"
#import "Subject.h"

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
        NSString *fastestRTStr = [curFriend objectForKey:@"fastestCorrectResponseTime"];
        NSString *averageRTStr = [curFriend objectForKey:@"averageResponseTime"];
        
        int correctCount = [correctCountStr intValue];
        int totalCount = [totalCountStr intValue];
        int fastestRT = [fastestRTStr intValue];
        int averageRT = [averageRTStr intValue];
        
        Subject *subject = [[Subject alloc] initWithName:[subjectDict objectForKey:@"name"] andFacebookId:[subjectDict objectForKey:@"facebookId"]];
        
        FriendStatsObject *statsObj = [[FriendStatsObject alloc] initWithSubject:subject andCorrectCount:correctCount andTotalCount:totalCount andFastestRT:fastestRT andAverageRT:averageRT];
        
        [list addObject:statsObj];
        
        [statsObj release];
    }
    
    return YES;
}

- (void)requestStatisticsFromServer:(BOOL)useSampleData {
    
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
                
        // Send the GET request to the server.
        NSURLRequest *request = [NSURLRequest requestWithURL:[NSURL URLWithString:getRequest]];
        
        NSData *response = [NSURLConnection sendSynchronousRequest:request returningResponse:nil error:nil];
        NSString *responseString = [[NSString alloc] initWithData:response encoding:NSUTF8StringEncoding];
                
        // Initialize array of questions from the server's response.
        success = [self createStatsFromServerResponse:responseString];
        
        [responseString release];
    }
}

- (void)getStatisticsThread {
    
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    
    // Only update the stats when we have to.
    if (delegate.statsFriendsNeedsUpdate) {
        [self requestStatisticsFromServer:NO];
        delegate.statsFriendsNeedsUpdate = NO;
    }
    
    threadIsRunning = NO;
}

- (void)viewWillAppear:(BOOL)animated {
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    
    if (delegate.statsFriendsNeedsUpdate && threadIsRunning == NO) {
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

/* Everytime this view will appear, we ask the server for stats jason */
- (void)viewDidAppear:(BOOL)animated {
    
    while (threadIsRunning) {
    }
    [spinner stopAnimating];
    [table reloadData];
    
    [super viewDidAppear:animated];
}

- (oneway void)release {
    if (![NSThread isMainThread]) {
        [self performSelectorOnMainThread:@selector(release) withObject:nil waitUntilDone:NO];
    } else {
        [super release];
    }
}

#pragma mark -
#pragma mark Table View Data Source Methods

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
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
    cell.picture.image = obj.subject.picture;
	
    // Make sure the name is valid.
    NSString *name = obj.subject.name;
    if (name == (id)[NSNull null] || name.length == 0) {
        name = @"Something is wrong";
    }
    cell.name.text = name;
    
    float percentage = (float)obj.correctCount / obj.totalCount;
    cell.percentageLabel.text = [NSString stringWithFormat:@"%i/%i", obj.correctCount, obj.totalCount]; 
	[cell.progressBar setProgress:percentage animated:NO];
    
    cell.fastestCorrectRT.text = [NSString stringWithFormat:@"%0.2fs", obj.fastestCorrectRT];
    cell.averageRT.text = [NSString stringWithFormat:@"%0.2fs", obj.averageRT];
    
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
