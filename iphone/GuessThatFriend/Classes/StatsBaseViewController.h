//
//  StatsBaseViewController.h
//  GuessThatFriend
//
//  Created on 4/19/12.
//  Copyright (c) 2012 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>

#define SAMPLE_GET_STATISTICS_FRIENDS_ADDR          "http://guessthatfriend.jasonsze.com/api/examples/json/getStatistics-friends.json"
#define SAMPLE_GET_STATISTICS_CATEGORIES_ADDR       "http://guessthatfriend.jasonsze.com/api/examples/json/getStatistics-categories.json"
#define SAMPLE_GET_STATISTICS_HISTORY_ADDR          "http://guessthatfriend.jasonsze.com/api/examples/json/getStatistics-history.json"
#define BASE_URL_ADDR                               "http://guessthatfriend.jasonsze.com/api/"

@interface StatsBaseViewController : UIViewController <UITableViewDataSource, UITableViewDelegate> {
    UITableView *friendsTable;
    NSMutableArray *friendsList;
    
    UIActivityIndicatorView *spinner;
    BOOL threadIsRunning;
}

@property (nonatomic, retain) IBOutlet UITableView *friendsTable;
@property (nonatomic, retain) NSMutableArray *friendsList;

@end
