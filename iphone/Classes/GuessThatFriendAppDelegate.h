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

@class QuizBaseViewController;
@class QuizManager;

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

+ (void)downloadingContentFailed;

+ (BOOL) manageImage:(HJManagedImageV *)image;

@end
