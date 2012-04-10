//
//  StatsViewController.h
//  GuessThatFriend
//
//  Created by Arjan Singh Nirh on 4/9/12.
//  Copyright (c) 2012 University of Illinois at Urbana-Champaign. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface StatsViewController : UIViewController 
 
{
UITableView *friendsTable;
UILabel *responseLabel;

NSString *questionString;
NSString *correctFacebookId;
NSMutableArray *optionsList;
}

@end
