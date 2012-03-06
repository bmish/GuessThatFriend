//
//  QuizBaseViewController.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface QuizBaseViewController : UIViewController {
	int questionID;
	
	UITextView *questionTextView;
	UIButton *nextButton;
}

@property int questionID;
@property (nonatomic, retain) IBOutlet UITextView *questionTextView;
@property (nonatomic, retain) IBOutlet UIButton *nextButton;

- (IBAction)submitAnswers:(id)sender;
- (IBAction)finishQuiz:(id)sender;

@end
