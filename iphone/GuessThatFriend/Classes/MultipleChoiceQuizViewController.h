//
//  MultipleChoiceQuizViewController.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "QuizBaseViewController.h"

@interface MultipleChoiceQuizViewController : QuizBaseViewController <UITableViewDataSource, UITableViewDelegate> {	
	UITableView *friendsTable;
	
	NSString *questionString;
	NSMutableArray *friendsList;
}

@property (nonatomic, retain) IBOutlet UITableView *friendsTable;

@property (nonatomic, retain) NSString *questionString;
@property (nonatomic, retain) NSArray *friendsList;

@end
