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
    UILabel *responseLabel;
	
    NSString *questionString;
    NSString *correctFacebookId;
    NSMutableArray *optionsList;
}

@property (nonatomic, retain) IBOutlet UITableView *friendsTable;
@property (nonatomic, retain) IBOutlet UILabel *responseLabel;
@property (nonatomic, retain) NSString *questionString;
@property (nonatomic, retain) NSString *correctFacebookId;
@property (nonatomic, retain) NSArray *optionsList;

@end
