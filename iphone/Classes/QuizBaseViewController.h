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
@property (nonatomic) IBOutlet UILabel *questionLabel;
@property (nonatomic) IBOutlet UIImageView *topicImage;
@property (nonatomic) SettingsViewController *settingsViewController;

@end
