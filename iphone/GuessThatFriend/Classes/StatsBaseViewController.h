//
//  StatsBaseViewController.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 4/19/12.
//  Copyright (c) 2012 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface StatsBaseViewController : UIViewController <UITableViewDataSource, UITableViewDelegate> {
    UITableView *friendsTable;
    NSMutableArray *friendsList;
}

@property (nonatomic, retain) IBOutlet UITableView *friendsTable;
@property (nonatomic, retain) NSMutableArray *friendsList;

@end
