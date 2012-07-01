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
    type = @"friends";
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
        
    }
    
    return YES;
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
    
    cell.picture = [[HJManagedImageV alloc] initWithFrame:CGRectMake(0,0,54,54)];
    cell.picture.url = [obj.subject getPictureURL];
	[GuessThatFriendAppDelegate manageImage:cell.picture];
    [cell addSubview:cell.picture];
    
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
