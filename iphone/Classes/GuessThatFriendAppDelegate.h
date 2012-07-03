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
#import "HJObjManager.h"
#import "HJManagedImageV.h"
#import "Reachability.h"

@class QuizBaseViewController;
@class QuizManager;

#define FACEBOOK_APP_ID     "178461392264777"
#define BASE_URL_ADDR       "http://guessthatfriend.jasonsze.com/api/"
#define IMAGE_CACHE_FILE_COUNT_LIMIT 1000
#define IMAGE_CACHE_AGE_LIMIT_SECONDS 60*60*24*7*2; // Two weeks.

@interface GuessThatFriendAppDelegate : NSObject <UIApplicationDelegate, FBSessionDelegate> {
    
    UIWindow *window;
    UINavigationController *navController;
    UITabBarController *tabController;
    
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
    
    BOOL statsFriendsNeedsUpdate;
    BOOL statsCategoriesNeedsUpdate;
    BOOL statsHistoryNeedsUpdate;
    
    UIActivityIndicatorView *spinner;
    
    HJObjManager* objMan;
    
    Reachability* hostReach;
}

@property (nonatomic) IBOutlet UIWindow *window;
@property (nonatomic) IBOutlet UINavigationController *navController;
@property (nonatomic) IBOutlet UITabBarController *tabController;

@property (nonatomic) IBOutlet UIBarButtonItem *doneItem;

@property (nonatomic) IBOutlet QuizBaseViewController *viewController;
@property (nonatomic) Facebook *facebook;
@property (nonatomic) UIButton *nextButton;
@property (nonatomic) QuizManager *quizManager;
@property (nonatomic) NSDate *responseTimer;

@property BOOL statsFriendsNeedsUpdate;
@property BOOL statsCategoriesNeedsUpdate;
@property BOOL statsHistoryNeedsUpdate;

@property (nonatomic) UIActivityIndicatorView *spinner;

@property (nonatomic) HJObjManager* objMan;

- (void)fbLogout;

- (void)setupNextQuestion:(Question *)nextQuestion;

+ (void)alertDownloadingContentFailed;

+ (BOOL) manageImage:(HJManagedImageV *)image;

@end
