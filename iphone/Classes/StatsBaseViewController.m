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
@synthesize type;

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

    // Do any additional setup after loading the view from its nib.
    
    // Create the spinner (center it later).
    spinner = [[UIActivityIndicatorView alloc] 
               initWithActivityIndicatorStyle:UIActivityIndicatorViewStyleGray];
    spinner.hidesWhenStopped = YES;
    [self.view addSubview:spinner];
    
    isRequestInProgress = NO;
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


- (void)connection:(NSURLConnection *)connection didReceiveResponse:(NSURLResponse *)response {
    responseData = [[NSMutableData alloc] init];
}

- (void)connection:(NSURLConnection *)connection didReceiveData:(NSData *)data {
    [responseData appendData:data];
}

- (void)connection:(NSURLConnection *)connection didFailWithError:(NSError *)error {
    [spinner stopAnimating];
    isRequestInProgress = NO;
    [GuessThatFriendAppDelegate alertDownloadingContentFailed];
}

- (void)connectionDidFinishLoading:(NSURLConnection *)connection {
    // Use responseData.
    NSMutableString *responseString = [[NSMutableString alloc] initWithData:responseData
                                                                    encoding:NSASCIIStringEncoding];
    
    // Release connection vars.
    [spinner stopAnimating];
    isRequestInProgress = NO;
    
    // Initialize array of questions from the server's response.
    if ([self createStatsFromServerResponse:responseString]) {
        [table reloadData];
    } else {
        [GuessThatFriendAppDelegate alertDownloadingContentFailed];
    }
}

- (BOOL)createStatsFromServerResponse:(NSString *)response {
    return NO;
}

- (NSMutableString *)getRequestString{
    // Make a real request.
    
    NSMutableString *getRequest = [NSMutableString stringWithString:@API_URL_ADDRESS];
    [getRequest appendString:@"?cmd=getStatistics"];
    
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)
    [[UIApplication sharedApplication] delegate];
    
    [getRequest appendFormat:@"&facebookAccessToken=%@", delegate.facebook.accessToken];
    [getRequest appendFormat:@"&type=%@", type];
    [getRequest appendFormat:[GuessThatFriendAppDelegate getVersionParametersStringForRequestURL]];
    
    return getRequest;
}

- (void)requestStatisticsFromServerAsync {
    if (isRequestInProgress) { // Only one request allowed at a time.
        return;
    }
    
    isRequestInProgress = YES;
    [spinner startAnimating];
    
    // Send request.
    NSMutableString *getRequest = [self getRequestString];
    NSURLRequest *request = [NSURLRequest requestWithURL:[NSURL URLWithString:getRequest]
                                             cachePolicy:NSURLRequestReloadIgnoringLocalCacheData
                                         timeoutInterval:60];
    (void)[[NSURLConnection alloc] initWithRequest:request delegate:self];
}

- (void)viewWillAppear:(BOOL)animated {
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    
    if ([type isEqualToString:@"friends"] && delegate.statsFriendsNeedsUpdate) {
        delegate.statsFriendsNeedsUpdate = NO;
        [self requestStatisticsFromServerAsync];
    } else if ([type isEqualToString:@"categories"] && delegate.statsCategoriesNeedsUpdate) {
        delegate.statsCategoriesNeedsUpdate = NO;
        [self requestStatisticsFromServerAsync];
    } else if ([type isEqualToString:@"history"] && delegate.statsHistoryNeedsUpdate) {
        delegate.statsHistoryNeedsUpdate = NO;
        [self requestStatisticsFromServerAsync];
    }
    
    // Center the spinner.
    spinner.center = self.view.center;
    
    [super viewWillAppear:animated];
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
