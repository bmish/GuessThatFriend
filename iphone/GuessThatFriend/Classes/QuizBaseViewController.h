//
//  QuizBaseViewController.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012. All rights reserved.
//

#import <UIKit/UIKit.h>

@class SettingsViewController;

@interface QuizBaseViewController : UIViewController {
	int questionID;
	UITextView *questionTextView;
    UIImageView *topicImage;
    SettingsViewController *settingsViewController;
}

@property int questionID;
@property (nonatomic, retain) IBOutlet UITextView *questionTextView;
@property (nonatomic, retain) SettingsViewController *settingsViewController;
@property (nonatomic, retain) IBOutlet UIImageView *topicImage;

@end
