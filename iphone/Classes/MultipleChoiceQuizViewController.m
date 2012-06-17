//
//  MultipleChoiceQuizViewController.m
//  GuessThatFriend
//
//  Created on 2/15/12.
//
//

#import "MultipleChoiceQuizViewController.h"
#import "MCQuestion.h"
#import "QuizManager.h"
#import "FBFriendCustomCell.h"
#import "Option.h"
#import "GuessThatFriendAppDelegate.h"

#define BASE_URL_ADDR               "http://guessthatfriend.jasonsze.com/api/"

@implementation MultipleChoiceQuizViewController

@synthesize friendsTable;
@synthesize questionString;
@synthesize optionsList;
@synthesize correctFacebookId;
@synthesize scoreLabelString;

- (void)viewWillAppear:(BOOL)animated {
	[super viewWillAppear:animated];
}

- (void)didReceiveMemoryWarning {
    // Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
    
    // Release any cached data, images, etc. that aren't in use.
}

- (void)viewDidUnload {
    [super viewDidUnload];
    // Release any retained subviews of the main view.
    // e.g. self.myOutlet = nil;
	
	self.friendsTable = nil;
	self.questionString = nil;
	self.optionsList = nil;
    self.scoreLabelString = nil;
}

- (void)dealloc {
	[friendsTable release];
	[questionString release];
	[optionsList release];
	[correctFacebookId release];
    
    [scoreLabelString release];
    
    [super dealloc];
}

#pragma mark -
#pragma mark Table View Data Source Methods

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
	return [self.optionsList count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    // enable the tableView from being further selected (previously disabled after selected)
    tableView.allowsSelection = YES;
    
	FBFriendCustomCell *cell = (FBFriendCustomCell *)[tableView dequeueReusableCellWithIdentifier:@"FBFriendCustomCellIdentifier"];
	
	if(cell == nil) {
		NSArray *nib = [[NSBundle mainBundle] loadNibNamed:@"FBFriendCustomCell" owner:self options:nil];
		cell = [nib objectAtIndex:0];
	}
	
	NSUInteger row = [indexPath row];
	
	Option *option = [optionsList objectAtIndex:row];
	cell.picture.image = option.subject.picture;
	cell.name.text = option.subject.name;
    
    // disable the checkmark.
    cell.accessoryType = UITableViewCellAccessoryNone;
    
	return cell;
}

#pragma mark -
#pragma mark Table Delegate Methods

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
    
    // Disable scrolling, this is re-enabled when user click next button.
    [self.friendsTable setScrollEnabled:NO];
    
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *) [[UIApplication sharedApplication] delegate];
    
    // Find response time for question answered.
    NSTimeInterval responseTimeInterval = [delegate.responseTimer timeIntervalSinceNow];
    
    // responseTimer is before the current date, so negate the interval.
    responseTimeInterval *= -1;
    
    NSUInteger selectedRow = [indexPath row];
    Option *option = [optionsList objectAtIndex:selectedRow];
    
    UITableViewCell *cell = [tableView cellForRowAtIndexPath:indexPath];
    
    for (int section = 0; section < [tableView numberOfSections]; section++) {
        for (int row = 0; row < [tableView numberOfRowsInSection:section]; row++) {
            NSIndexPath* cellPath = [NSIndexPath indexPathForRow:row inSection:section];
            UITableViewCell* cellItr = [tableView cellForRowAtIndexPath:cellPath];
            //do stuff with 'cell'
            Option *optionItr = [optionsList objectAtIndex:row];
            if ([optionItr.subject.facebookId isEqual:[NSNull null]]) {
            } else if ([optionItr.subject.facebookId isEqualToString:correctFacebookId]) {
                cellItr.accessoryType = UITableViewCellAccessoryCheckmark;
            }
        }
    }
    
    delegate->totalNumOfQuestions++;
    
    // Check if selected option is correct
    if ([option.subject.facebookId isEqualToString:correctFacebookId]) {
        cell.backgroundColor = [UIColor greenColor];
        // Adding 1 to the running count for correct answers
        delegate->correctAnswers++;
    }
    else {
        cell.backgroundColor = [UIColor redColor];
    }
    
    scoreLabelString = [NSMutableString stringWithFormat:@"%i / %i", delegate->correctAnswers, delegate->totalNumOfQuestions];
    
    delegate.navController.navigationBar.topItem.title = scoreLabelString;
    self.isQuestionAnswered = true;
    
    // construct the request string
    NSMutableString *getRequest;
    
    getRequest = [NSMutableString stringWithString:@BASE_URL_ADDR];
    [getRequest appendString:@"?cmd=submitQuestions"];
    [getRequest appendFormat:@"&facebookAccessToken=%@", delegate.facebook.accessToken];
    [getRequest appendFormat:@"&facebookIdOfQuestion%i=%@", questionID, option.subject.facebookId];
    [getRequest appendFormat:@"&responseTimeOfQuestion%i=%i", questionID, (int)(responseTimeInterval * 1000)];
    
    NSURLRequest *request = [NSURLRequest requestWithURL:[NSURL URLWithString:getRequest]];
    
    // The follow creates a aSynchronousRequest so that the UI would not be hogged
    [NSURLConnection connectionWithRequest:request delegate:nil];
    
    [tableView deselectRowAtIndexPath:indexPath animated:YES];
    
    // disables the tableView from being further selected
    tableView.allowsSelection = NO;
    
    // New question answered, the stats is dirty now.
    delegate.statsFriendsNeedsUpdate = YES;
    delegate.statsCategoriesNeedsUpdate = YES;
    delegate.statsHistoryNeedsUpdate = YES;
}

- (CGFloat) tableView: (UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
	return 65;		// 65 is the fixed height of each cell, it is set in the nib.
}

@end
