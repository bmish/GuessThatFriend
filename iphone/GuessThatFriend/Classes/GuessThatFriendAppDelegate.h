//
//  GuessThatFriendAppDelegate.h
//  GuessThatFriend
//
//  Created on 2/2/12.
//  Copyright 2012. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "FBConnect.h"

@class QuizBaseViewController;
@class QuizManager;

@interface GuessThatFriendAppDelegate : NSObject <UIApplicationDelegate, FBSessionDelegate> {
    
    UIWindow *window;
    UINavigationController *navController;
    UITabBarController *tabController;
    
    UIBarButtonItem *settingsItem;
    UIBarButtonItem *doneItem;
    QuizBaseViewController *viewController;
    Facebook *facebook;
    UIButton *nextButton;
    
    NSDate *responseTimer;

    // For Keeping the Score.
    // @see MultipleChoiceQuizViewController
    @public
    int correctAnswers;
    int totalNumOfQuestions;
    
    QuizManager *quizManager;
    
    BOOL statsNeedsUpdate;
}

@property (nonatomic, retain) IBOutlet UIWindow *window;
@property (nonatomic, retain) IBOutlet UINavigationController *navController;
@property (nonatomic, retain) IBOutlet UITabBarController *tabController;

@property (nonatomic, retain) IBOutlet UIBarButtonItem *settingsItem;
@property (nonatomic, retain) IBOutlet UIBarButtonItem *doneItem;

@property (nonatomic, retain) IBOutlet QuizBaseViewController *viewController;
@property (nonatomic, retain) Facebook *facebook;
@property (nonatomic, retain) UIButton *nextButton;
@property (nonatomic, retain) QuizManager *quizManager;
@property (nonatomic, retain) NSDate *responseTimer;

@property BOOL statsNeedsUpdate;

@end
