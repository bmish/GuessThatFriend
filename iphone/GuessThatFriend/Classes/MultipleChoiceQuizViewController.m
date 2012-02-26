    //
//  MultipleChoiceQuizViewController.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import "MultipleChoiceQuizViewController.h"
#import "MultipleChoiceQuestion.h"
#import "QuizManager.h"
#import "FBFriendCustomCell.h"
#import "Option.h"
#import "QuizFeedbackViewController.h"

@implementation MultipleChoiceQuizViewController

@synthesize friendsTable;
@synthesize questionString;
@synthesize friendsList;
@synthesize quizFeedbackViewController;

- (IBAction)submitAnswers:(id)sender {
	//TODO: Get the answers from GUI and send them to the server. Then wait
	// for the result from the server, and display the result.
    
    if (quizFeedbackViewController == nil) {
        quizFeedbackViewController = [[QuizFeedbackViewController alloc] 
                                      initWithNibName:@"QuizFeedbackViewController" bundle:nil];
    }
    
    if(self.modalViewController) {
		[self dismissModalViewControllerAnimated:NO];
	}
	
	[self presentModalViewController:quizFeedbackViewController animated:YES];
}

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
	QuizManager *quizManager = [[QuizManager alloc] initWithQuizSettings:nil];
	MultipleChoiceQuestion *multipleChoiceQuestion = [quizManager getNextQuestion];
	
	self.questionString = multipleChoiceQuestion.text;
	friendsList = [[NSMutableArray alloc] initWithArray: multipleChoiceQuestion.options copyItems:YES];
    
	[questionTextView setText:[@"Question:\n" stringByAppendingString: self.questionString]];
	
	[multipleChoiceQuestion release];
	[quizManager release];
	
	[super viewWillAppear:animated];
}

/*
// Override to allow orientations other than the default portrait orientation.
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
    // Return YES for supported orientations.
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}
*/

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
	self.friendsList = nil;
    self.quizFeedbackViewController = nil;
}

- (void)dealloc {
	[friendsTable release];
	[questionString release];
	[friendsList release];
    [quizFeedbackViewController release];
	
    [super dealloc];
}

#pragma mark -
#pragma mark Table View Data Source Methods

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
	return [self.friendsList count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
	
	FBFriendCustomCell *cell = (FBFriendCustomCell *)[tableView dequeueReusableCellWithIdentifier:@"FBFriendCustomCellIdentifier"];
	
	if(cell == nil) {
		NSArray *nib = [[NSBundle mainBundle] loadNibNamed:@"FBFriendCustomCell" owner:self options:nil];
		cell = [nib objectAtIndex:0];
	}
	
	NSUInteger row = [indexPath row];
	
	Option *friend = [friendsList objectAtIndex:row];
	cell.picture.image = friend.image;
	cell.name.text = friend.name;
	
	return cell;
}

#pragma mark -
#pragma mark Table Delegate Methods

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	
	UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Attention" 
													message:@"Please toggle the control on the right" 
												   delegate:nil 
										  cancelButtonTitle:@"OK" 
										  otherButtonTitles:nil];
	[alert show];
	[alert release];
}

- (CGFloat) tableView: (UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
	return 65;		// 65 is the fixed height of each cell, it is set in the nib.
}

@end
