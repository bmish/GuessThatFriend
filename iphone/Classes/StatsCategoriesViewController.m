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
    type = @"categories";
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
        
        NSString *name = [categoryDict objectForKey:@"facebookName"];
        
        CategoryStatsObject *statsObj = [[CategoryStatsObject alloc] initWithName:name andCorrectCount:correctCount andTotalCount:totalCount andCorrectRT:fastestCorrectRT andAverageRT:averageTR];
        
        [list addObject:statsObj];
        
        [statsObj release];
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
