//
//  StatsViewController.h
//  GuessThatFriend
//
//  Created by Arjan Singh Nirh on 4/9/12.
//  Copyright (c) 2012 University of Illinois at Urbana-Champaign. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "StatsBaseViewController.h"

@interface StatsViewController : StatsBaseViewController {
    UIActivityIndicatorView *spinner;
}

- (void)getStatisticsThread;

@end
