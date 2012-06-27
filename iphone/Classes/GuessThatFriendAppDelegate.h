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

@property (nonatomic) IBOutlet UIWindow *window;
@property (nonatomic) IBOutlet UINavigationController *navController;
@property (nonatomic) IBOutlet UITabBarController *tabController;

@property (nonatomic) IBOutlet UIBarButtonItem *settingsItem;
@property (nonatomic) IBOutlet UIBarButtonItem *doneItem;

@property (nonatomic) IBOutlet QuizBaseViewController *viewController;
@property (nonatomic) Facebook *facebook;
@property (nonatomic) UIButton *nextButton;
@property (nonatomic) QuizManager *quizManager;
@property (nonatomic) NSDate *responseTimer;
@property (nonatomic) NSMutableDictionary* plistImageDict;

@property BOOL statsFriendsNeedsUpdate;
@property BOOL statsCategoriesNeedsUpdate;
@property BOOL statsHistoryNeedsUpdate;

@property (nonatomic) UIActivityIndicatorView *spinner;

- (UIImage *) getPicture:(NSString*)imageURL;

- (void)fbLogout;

- (void)setupNextQuestion:(Question *)nextQuestion;

@end
