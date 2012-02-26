//
//  FillInBlankQuizViewController.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "QuizBaseViewController.h"

@interface FillInBlankQuizViewController : QuizBaseViewController {	
	UITextField *answerTextField;
}

@property (nonatomic, retain) IBOutlet UITextField *answerTextField;

@end
