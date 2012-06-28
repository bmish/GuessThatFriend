//
//  HistoryViewController.m
//  GuessThatFriend
//
//  Created on 4/30/12.

//

#import "StatsHistoryViewController.h"
#import "GuessThatFriendAppDelegate.h"
#import "JSONKit.h"
#import "HistoryStatsObject.h"
#import "StatsHistoryCustomCell.h"
#import "Subject.h"

@implementation StatsHistoryViewController

- (void)viewDidLoad {
    type = @"history";
    [super viewDidLoad];
}

- (void)viewDidUnload {
    [super viewDidUnload];
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
    
    NSArray *historyArray = [responseDictionary objectForKey:@"questions"];
    NSEnumerator *historyEnumerator = [historyArray objectEnumerator];
    NSDictionary *curHistory;
    
    // Go through all HISTORY
    while (curHistory = [historyEnumerator nextObject]) {
        NSString *questionString = [curHistory objectForKey:@"text"];
        NSDictionary *topicDict = [curHistory objectForKey:@"topicSubject"];
        NSDictionary *correctDict = [curHistory objectForKey:@"correctSubject"];
        NSString *correctName= [correctDict objectForKey:@"name"];
        NSDictionary *chosenDict = [curHistory objectForKey:@"chosenSubject"];
        NSString *chosenName= [chosenDict objectForKey:@"name"];
        
        NSString *answeredDate = [curHistory objectForKey:@"answeredAt"];
        NSString *rtStr = [curHistory objectForKey:@"responseTime"];
        int rt = [rtStr intValue];
        
        Subject *subject = [[Subject alloc] initWithName:[topicDict objectForKey:@"name"] andFacebookId:[topicDict objectForKey:@"facebookId"]];
        
        
        HistoryStatsObject *statsObj = [[HistoryStatsObject alloc] initWithQuestion:questionString andSubject:subject andCorrectAnswer:correctName andYourAnswer:chosenName andDate:answeredDate andResponseTime:rt];
        
        [list addObject:statsObj];
        
    }
    
    return YES;
}

- (void)viewWillAppear:(BOOL)animated {
    [super viewWillAppear:animated];
}

/* Everytime this view will appear, we ask the server for stats jason */
- (void)viewDidAppear:(BOOL)animated {
    [super viewDidAppear:animated];
}

#pragma mark -
#pragma mark Table View Data Source Methods

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
	return [self.list count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    
	StatsHistoryCustomCell *cell = (StatsHistoryCustomCell *)[tableView dequeueReusableCellWithIdentifier:@"StatsHistoryCustomCellIdentifier"];
	
	if(cell == nil) {
		NSArray *nib = [[NSBundle mainBundle] loadNibNamed:@"StatsHistoryCustomCell" owner:self options:nil];
		cell = [nib objectAtIndex:0];
	}
	
	NSUInteger row = [indexPath row];
	
    HistoryStatsObject *obj = [list objectAtIndex:row];
    
    cell.text.text = obj.question;
    cell.correctAnswer.text = obj.correctAnswer;
    cell.chosenAnswer.text = obj.yourAnswer;
    cell.date.text = obj.date;
    cell.picture = obj.picture;
    [cell addSubview:cell.picture];
    cell.responseTime.text = [NSString stringWithFormat:@"in %0.2fs", obj.responseTime];
    
	return cell;
}

#pragma mark -
#pragma mark Table Delegate Methods

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	
    [tableView deselectRowAtIndexPath:indexPath animated:NO];
    
    return;
}

- (CGFloat) tableView: (UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
	return 75;		// 75 is the fixed height of each cell, it is set in the nib.
}

@end
