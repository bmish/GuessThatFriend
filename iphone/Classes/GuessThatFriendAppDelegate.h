//
//  GuessThatFriendAppDelegate.h
//  GuessThatFriend
//
//  Created on 2/2/12.
//
//

#import <UIKit/UIKit.h>
#import "FBConnect.h"
#import "Question.h"

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
    NSMutableDictionary* plistImageDict;
    QuizManager *quizManager;
    
    BOOL statsFriendsNeedsUpdate;
    BOOL statsCategoriesNeedsUpdate;
    BOOL statsHistoryNeedsUpdate;
    
    UIActivityIndicatorView *spinner;
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
@property (nonatomic, retain) NSMutableDictionary* plistImageDict;

@property BOOL statsFriendsNeedsUpdate;
@property BOOL statsCategoriesNeedsUpdate;
@property BOOL statsHistoryNeedsUpdate;

@property (nonatomic, retain) UIActivityIndicatorView *spinner;

- (UIImage *) getPicture:(NSString*)imageURL;

- (void)fbLogout;

- (void)setupNextQuestion:(Question *)nextQuestion;

@end
