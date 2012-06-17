//
//  QuizBaseViewController.h
//  GuessThatFriend
//
//  Created on 2/15/12.
//
//

#import <UIKit/UIKit.h>

@class SettingsViewController;

@interface QuizBaseViewController : UIViewController {
	int questionID;
    bool isQuestionAnswered;
	UILabel *questionLabel;
    UIImageView *topicImage;
    SettingsViewController *settingsViewController;
}

@property int questionID;
@property bool isQuestionAnswered;
@property (nonatomic, retain) IBOutlet UILabel *questionLabel;
@property (nonatomic, retain) IBOutlet UIImageView *topicImage;
@property (nonatomic, retain) SettingsViewController *settingsViewController;

@end
