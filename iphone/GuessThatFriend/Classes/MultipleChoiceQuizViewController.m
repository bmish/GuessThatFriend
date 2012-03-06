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

@implementation MultipleChoiceQuizViewController

@synthesize friendsTable;
@synthesize questionString;
@synthesize optionsList;

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
	MCQuestion *multipleChoiceQuestion = [quizManager getNextQuestion];
	
	self.questionString = multipleChoiceQuestion.text;
    self.optionsList = [NSArray arrayWithArray:multipleChoiceQuestion.options];
    
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
	self.optionsList = nil;
}

- (void)dealloc {
	[friendsTable release];
	[questionString release];
	[optionsList release];
	
    [super dealloc];
}

#pragma mark -
#pragma mark Table View Data Source Methods

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
	return [self.optionsList count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
	
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
	
	UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Attention" 
													message:@"Please use the button on the right" 
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
