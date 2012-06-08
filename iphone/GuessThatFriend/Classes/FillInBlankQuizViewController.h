//
//  FillInBlankQuizViewController.h
//  GuessThatFriend
//
//  Created on 2/15/12.
//
//

#import <UIKit/UIKit.h>
#import "QuizBaseViewController.h"

@interface FillInBlankQuizViewController : QuizBaseViewController {	
	UITextField *answerTextField;
}

@property (nonatomic, retain) IBOutlet UITextField *answerTextField;

@end
