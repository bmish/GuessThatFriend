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

@property (nonatomic) IBOutlet UITableView *friendsTable;
@property (nonatomic) NSString *questionString;
@property (nonatomic) NSString *correctFacebookId;
@property (nonatomic) NSArray *optionsList;
@property (nonatomic) NSMutableString *scoreLabelString;

@end
