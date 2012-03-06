//
//  SettingsViewController.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/13/12.
//  Copyright 2012. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface SettingsViewController : UIViewController {
    UISegmentedControl *quizOptionSegmentedControl;
    UILabel *quizOptionLabel;
    UIButton *targetFriendEditButton;
    UILabel *targetFriendLabel;
    UIButton *categoryEditButton;
    UILabel *categoryLabel;
	UIButton *logoutButton;
}

@property (nonatomic, retain) IBOutlet UISegmentedControl *quizOptionSegmentedControl;
@property (nonatomic, retain) IBOutlet UILabel *quizOptionLabel;
@property (nonatomic, retain) IBOutlet UIButton *targetFriendEditButton;
@property (nonatomic, retain) IBOutlet UILabel *targetFriendLabel;
@property (nonatomic, retain) IBOutlet UIButton *categoryEditButton;
@property (nonatomic, retain) IBOutlet UILabel *categoryLabel;
@property (nonatomic, retain) IBOutlet UIButton *logoutButton;

- (IBAction)switchViewToFBLogin:(id)sender;
- (IBAction)quizOptionSegmentedControlChanged:(id)sender;

@end
