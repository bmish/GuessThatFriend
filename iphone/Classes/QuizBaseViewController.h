//
//  QuizBaseViewController.h
//  GuessThatFriend
//
//  Created on 2/15/12.
//
//

#import <UIKit/UIKit.h>
#import "HJManagedImageV.h"

@class SettingsViewController;

@interface QuizBaseViewController : UIViewController {
	int questionID;
    bool isQuestionAnswered;
	UILabel *questionLabel;
    HJManagedImageV *topicImage;
    SettingsViewController *settingsViewController;
}

@property int questionID;
@property bool isQuestionAnswered;
@property (nonatomic) IBOutlet UILabel *questionLabel;
@property (nonatomic) IBOutlet HJManagedImageV *topicImage;
@property (nonatomic) SettingsViewController *settingsViewController;

@end
