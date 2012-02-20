//
//  QuizBaseViewController.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface QuizBaseViewController : UIViewController {
	int questionID;
	
	UITextView *questionTextView;
	UIButton *submitButton;
}

@property int questionID;
@property (nonatomic, retain) IBOutlet UITextView *questionTextView;
@property (nonatomic, retain) IBOutlet UIButton *submitButton;

- (IBAction)submitAnswers:(id)sender;

@end
