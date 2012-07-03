//
//  StatsBaseViewController.h
//  GuessThatFriend
//
//  Created on 4/19/12.

//

#import <Foundation/Foundation.h>

@interface StatsBaseViewController : UIViewController <UITableViewDataSource, UITableViewDelegate> {
    UITableView *table;
    NSMutableArray *list;
    
    UIActivityIndicatorView *spinner;
    
    NSMutableData *responseData;
    BOOL isRequestInProgress;
    
    NSString *type;
}

@property (nonatomic) IBOutlet UITableView *table;
@property (nonatomic) NSMutableArray *list;
@property (nonatomic) NSString *type;

- (NSMutableString *)getRequestString;

@end
