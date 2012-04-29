//
//  StatsViewController.h
//  GuessThatFriend
//
//  Created on 4/9/12.
//  Copyright (c) 2012 University of Illinois at Urbana-Champaign. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "StatsBaseViewController.h"

@interface StatsViewController : StatsBaseViewController {
    UIActivityIndicatorView *spinner;
    BOOL threadIsRunning;
}

- (void)getStatisticsThread;

@end
