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
    
    // Create the spinner (center it later).
    spinner = [[UIActivityIndicatorView alloc] 
               initWithActivityIndicatorStyle:UIActivityIndicatorViewStyleGray];
    spinner.hidesWhenStopped = YES;
    [self.view addSubview:spinner];
    [spinner release];
    
    isRequestInProgress = NO;
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
        
        Subject *subject = [[[Subject alloc] initWithName:[subjectDict objectForKey:@"name"] andFacebookId:[subjectDict objectForKey:@"facebookId"]] autorelease];
        
        FriendStatsObject *statsObj = [[FriendStatsObject alloc] initWithSubject:subject andCorrectCount:correctCount andTotalCount:totalCount andFastestRT:fastestRT andAverageRT:averageRT];
        
        [list addObject:statsObj];
        
        [statsObj release];
    }
    
    return YES;
}

- (void)connection:(NSURLConnection *)connection didReceiveResponse:(NSURLResponse *)response {
    responseData = [[NSMutableData alloc] init];
}

- (void)connection:(NSURLConnection *)connection didReceiveData:(NSData *)data {
    [responseData appendData:data];
}

- (void)connection:(NSURLConnection *)connection didFailWithError:(NSError *)error {
    [responseData release];
    [connection release];
    [spinner stopAnimating];
    isRequestInProgress = NO;
}

- (void)connectionDidFinishLoading:(NSURLConnection *)connection {
    // Use responseData.
    NSMutableString *responseString = [[[NSMutableString alloc] initWithData:responseData
                                                                    encoding:NSASCIIStringEncoding] autorelease];

    // Release connection vars.
    [responseData release];
    [connection release];
    [spinner stopAnimating];
    isRequestInProgress = NO;
    
    // Initialize array of questions from the server's response.
    [self createStatsFromServerResponse:responseString];
    [table reloadData];
}

- (void)requestStatisticsFromServerAsync {
    if (isRequestInProgress) { // Only one request allowed at a time.
        return;
    }
    
    isRequestInProgress = YES;
    [spinner startAnimating];
    
    // Send request.
    NSMutableString *getRequest = [StatsBaseViewController getRequestStringWithType:@"friends"];
    NSURLRequest *request = [NSURLRequest requestWithURL:[NSURL URLWithString:getRequest]
                                             cachePolicy:NSURLRequestReloadIgnoringLocalCacheData
                                         timeoutInterval:60];
    [[NSURLConnection alloc] initWithRequest:request delegate:self];
}

- (void)viewWillAppear:(BOOL)animated {
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    if (delegate.statsFriendsNeedsUpdate) {
        delegate.statsFriendsNeedsUpdate = NO;
        [self requestStatisticsFromServerAsync];
    }
    
    // Center the spinner.
    spinner.center = self.view.center;
    
    [super viewWillAppear:animated];
}

- (void)viewDidAppear:(BOOL)animated {
    [super viewDidAppear:animated];
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
