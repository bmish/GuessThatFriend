//
//  MultipleChoiceQuizViewController.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012. All rights reserved.
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
@synthesize responseLabel;
@synthesize scoreLabel;
@synthesize scoreLabelString;

// The designated initializer.  Override if you create the controller programmatically and want to perform customization that is not appropriate for viewDidLoad.
/*
 - (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
 self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
 if (self) {
 // Custom initialization.
 }
 return self;
 }
 */

/*
 // Implement loadView to create a view hierarchy programmatically, without using a nib.
 - (void)loadView {
 }
 */

/*
 // Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
 - (void)viewDidLoad {
 [super viewDidLoad];
 }
 */

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
    self.responseLabel = nil;
}

- (void)dealloc {
	[friendsTable release];
	[questionString release];
	[optionsList release];
	[correctFacebookId release];
    [responseLabel release];
    [scoreLabel release];
    
    [scoreLabelString release];
    
    [super dealloc];
}

#pragma mark -
#pragma mark Table View Data Source Methods

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
	return [self.optionsList count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    //enable the tableView from being further selected (previously disabled after selected)
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
    
	return cell;
}

#pragma mark -
#pragma mark Table Delegate Methods

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	
    NSUInteger selectedRow = [indexPath row];
    Option *option = [optionsList objectAtIndex:selectedRow];
    
    UITableViewCell *cell = [tableView cellForRowAtIndexPath:indexPath];
    
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate*) [[UIApplication sharedApplication] delegate];
    
    delegate->totalNumOfQuestions++;
    
    // Check if selected option is correct
    if ([option.subject.facebookId isEqualToString:correctFacebookId]) {
        responseLabel.text = @"Correct";
        cell.backgroundColor = [UIColor greenColor];
        //Adding 1 to the running count for correct answers
        //correctAnswers++;
        delegate->correctAnswers++;
    }
    else {
        responseLabel.text = @"Wrong";
        cell.backgroundColor = [UIColor redColor];
    }
    
    scoreLabelString = [NSMutableString stringWithFormat:@"%i/%i", delegate->correctAnswers, delegate->totalNumOfQuestions];
    
    self.scoreLabel.text = scoreLabelString;
    
    //construct the request string
    NSMutableString *getRequest;
    
    getRequest = [NSMutableString stringWithString:@BASE_URL_ADDR];
    [getRequest appendString:@"?cmd=submitQuestions"];
    [getRequest appendFormat:@"&facebookAccessToken=%@", delegate.facebook.accessToken];
    [getRequest appendFormat:@"&facebookIdOfQuestion%i=%@", questionID, option.subject.facebookId];
    
    NSLog(@"Request: %@\n", getRequest);
    
    NSURLRequest *request = [NSURLRequest requestWithURL:[NSURL URLWithString:getRequest]];
    NSLog(@"%@\n", request);
    NSData *response = [NSURLConnection sendSynchronousRequest:request returningResponse:nil error:nil];
    NSString *responseString = [[NSString alloc] initWithData: response encoding:NSUTF8StringEncoding];
    
    [responseString release];
    
    [tableView deselectRowAtIndexPath:indexPath animated:YES];
    
    //disables the tableView from being further selected
    tableView.allowsSelection = NO;
}

- (CGFloat) tableView: (UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
	return 65;		// 65 is the fixed height of each cell, it is set in the nib.
}

@end
