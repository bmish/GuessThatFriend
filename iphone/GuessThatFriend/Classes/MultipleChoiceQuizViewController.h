//
//  MultipleChoiceQuizViewController.h
//  GuessThatFriend
//
//  Created on 2/15/12.
//
//

#import <UIKit/UIKit.h>
#import "QuizBaseViewController.h"

@interface MultipleChoiceQuizViewController : QuizBaseViewController <UITableViewDataSource, UITableViewDelegate> {	
    
    UITableView *friendsTable;
    
    NSMutableString *scoreLabelString;
	
    NSString *questionString;
    NSString *correctFacebookId;
    NSMutableArray *optionsList;
}

@property (nonatomic, retain) IBOutlet UITableView *friendsTable;
@property (nonatomic, retain) NSString *questionString;
@property (nonatomic, retain) NSString *correctFacebookId;
@property (nonatomic, retain) NSArray *optionsList;
@property (nonatomic, retain) NSMutableString *scoreLabelString;

@end
