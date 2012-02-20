//
//  QuizFeedbackViewController.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface QuizFeedbackViewController : UIViewController {
	UITextView *feedbackTextView;
	UIButton *nextButton;
}

@property (nonatomic, retain) IBOutlet UITextView *feedbackTextView;
@property (nonatomic, retain) IBOutlet UIButton *nextButton;

- (IBAction)goToNext:(id)sender;

@end
