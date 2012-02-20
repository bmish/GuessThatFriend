//
//  MainMenu.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/8/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>

@class MultipleChoiceQuizViewController;
@class SettingsViewController;

@interface MainMenuViewController: UIViewController {
	UIButton *quizModeButton;
	UIButton *survivalModeButton;
	UIButton *timeModeButton;
	UIButton *settingsButton;
	
	MultipleChoiceQuizViewController *multipleChoiceQuizViewController;
	
	SettingsViewController *settingsViewController;
}

@property (nonatomic, retain) IBOutlet UIButton *quizModeButton;
@property (nonatomic, retain) IBOutlet UIButton *survivalModeButton;
@property (nonatomic, retain) IBOutlet UIButton *timeModeButton;
@property (nonatomic, retain) IBOutlet UIButton *settingsButton;

@property (nonatomic, retain) MultipleChoiceQuizViewController *multipleChoiceQuizViewController;
@property (nonatomic, retain) SettingsViewController *settingsViewController;

- (IBAction)switchViewToRegularQuiz:(id)sender;
- (IBAction)switchViewToSurvivalQuiz:(id)sender;
- (IBAction)switchViewToTimeQuiz:(id)sender;

- (IBAction)switchViewToSettings:(id)sender;

@end
