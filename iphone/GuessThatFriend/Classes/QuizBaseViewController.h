//
//  QuizBaseViewController.h
//  GuessThatFriend
//
//  Created on 2/15/12.
//  Copyright 2012. All rights reserved.
//

#import <UIKit/UIKit.h>

@class SettingsViewController;
@class StatsViewController;

@interface QuizBaseViewController : UIViewController {
	int questionID;
	UITextView *questionTextView;
    UIImageView *topicImage;
    SettingsViewController *settingsViewController;
}

@property int questionID;
@property (nonatomic, retain) IBOutlet UITextView *questionTextView;
@property (nonatomic, retain) IBOutlet UIImageView *topicImage;
@property (nonatomic, retain) SettingsViewController *settingsViewController;

@end
