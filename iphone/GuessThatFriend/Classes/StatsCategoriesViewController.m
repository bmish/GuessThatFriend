//
//  StatsCategoriesViewController.m
//  GuessThatFriend
//
//  Created on 4/30/12.

//

#import "StatsCategoriesViewController.h"
#import "GuessThatFriendAppDelegate.h"
#import "JSONKit.h"
#import "CategoryStatsObject.h"
#import "StatsCategoryCustomCell.h"

@implementation StatsCategoriesViewController

- (void)viewDidLoad {
    [super viewDidLoad];
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
    
    NSArray *categoriesArray = [responseDictionary objectForKey:@"categories"];
    NSEnumerator *categoryEnumerator = [categoriesArray objectEnumerator];
    NSDictionary *curCategory;
    
    // Go through all CATEGORIES
    while (curCategory = [categoryEnumerator nextObject]) {
        NSDictionary *categoryDict = [curCategory objectForKey:@"category"];
        NSString *correctCountStr = [curCategory objectForKey:@"correctAnswerCount"];
        NSString *totalCountStr = [curCategory objectForKey:@"totalAnswerCount"];
        NSString *fastestCorrectRTStr = [curCategory objectForKey:@"fastestCorrectResponseTime"];
        NSString *averageTRStr = [curCategory objectForKey:@"averageResponseTime"];
        
        int correctCount = [correctCountStr intValue];
        int totalCount = [totalCountStr intValue];
        int fastestCorrectRT = [fastestCorrectRTStr intValue];
        int averageTR = [averageTRStr intValue];
        
        NSString *name = [categoryDict objectForKey:@"prettyName"];
        
        CategoryStatsObject *statsObj = [[CategoryStatsObject alloc] initWithName:name andCorrectCount:correctCount andTotalCount:totalCount andCorrectRT:fastestCorrectRT andAverageRT:averageTR];
        
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
            getRequest = [NSMutableString stringWithString:@SAMPLE_GET_STATISTICS_CATEGORIES_ADDR];
        } else {
            // Make a real request.
            
            getRequest = [NSMutableString stringWithString:@BASE_URL_ADDR];
            [getRequest appendString:@"?cmd=getStatistics"];
            
            GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)
            [[UIApplication sharedApplication] delegate];
            
            [getRequest appendFormat:@"&facebookAccessToken=%@", delegate.facebook.accessToken];
            [getRequest appendFormat:@"&type=categories"];
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
    if (delegate.statsCategoriesNeedsUpdate) {
        [self requestStatisticsFromServer:NO];
        delegate.statsCategoriesNeedsUpdate = NO;
    }
    
    threadIsRunning = NO;    
}

- (void)viewWillAppear:(BOOL)animated {
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    
    if (delegate.statsCategoriesNeedsUpdate && threadIsRunning == NO) {
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
	
	StatsCategoryCustomCell *cell = (StatsCategoryCustomCell *)[tableView dequeueReusableCellWithIdentifier:@"StatsCategoryCustomCellIdentifier"];
	
	if(cell == nil) {
		NSArray *nib = [[NSBundle mainBundle] loadNibNamed:@"StatsCategoryCustomCell" owner:self options:nil];
		cell = [nib objectAtIndex:0];
	}
	
	NSUInteger row = [indexPath row];
	
    CategoryStatsObject *obj = [list objectAtIndex:row];
    
    // Make sure the name is valid.
    NSString *name = obj.name;
    if (name == (id)[NSNull null] || name.length == 0) {
        name = @"Something is wrong";
    }
    cell.name.text = name;
    
    float percentage = (float)obj.correctCount / obj.totalCount;
    cell.percentageLabel.text = [NSString stringWithFormat:@"%i/%i", obj.correctCount, obj.totalCount]; 
	[cell.progressBar setProgress:percentage animated:NO];
    
    cell.fastestCorrectRT.text = [NSString stringWithFormat:@"%0.2fs", obj.fastestCorrectResponseTime];
    cell.averageRT.text = [NSString stringWithFormat:@"%0.2fs", obj.averageResponseTime];
    
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
