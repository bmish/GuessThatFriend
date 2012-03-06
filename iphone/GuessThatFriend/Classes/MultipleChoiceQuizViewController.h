//
//  MultipleChoiceQuizViewController.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "QuizBaseViewController.h"

@interface MultipleChoiceQuizViewController : QuizBaseViewController <UITableViewDataSource, UITableViewDelegate> {	
    
    UITableView *friendsTable;
	
    NSString *questionString;
    NSMutableArray *optionsList;
}

@property (nonatomic, retain) IBOutlet UITableView *friendsTable;
@property (nonatomic, retain) NSString *questionString;
@property (nonatomic, retain) NSArray *optionsList;

@end
