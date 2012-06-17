//
//  FillInBlankQuizViewController.m
//  GuessThatFriend
//
//  Created on 2/15/12.
//  // 2012. All rights reserved.
//

#import "FillInBlankQuizViewController.h"

@implementation FillInBlankQuizViewController

@synthesize answerTextField;

- (void)didReceiveMemoryWarning {
    // Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
    
    // Release any cached data, images, etc. that aren't in use.
}

- (void)viewDidUnload {
    [super viewDidUnload];
    // Release any retained subviews of the main view.
    // e.g. self.myOutlet = nil;
	
	self.answerTextField = nil;
}

- (void)dealloc {
	[answerTextField release];
	
    [super dealloc];
}

@end
