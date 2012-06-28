//
//  GuessThatFriendAppDelegate.m
//  GuessThatFriend
//
//  Created on 2/2/12.
//
//

#import "GuessThatFriendAppDelegate.h"
#import "QuizBaseViewController.h"
#import "MultipleChoiceQuizViewController.h"
#import "SettingsViewController.h"
#import "QuizManager.h"
#import "QuizSettings.h"
#import "Question.h"
#import "MCQuestion.h"
#import "FillBlankQuestion.h"

#define BASE_URL_ADDR       "http://guessthatfriend.jasonsze.com/api/"

#define IMAGE_CACHE_FILE_COUNT_LIMIT 1000
#define IMAGE_CACHE_AGE_LIMIT_SECONDS 60*60*24*7*2; // Two weeks.

@implementation GuessThatFriendAppDelegate

@synthesize window;
@synthesize navController;
@synthesize tabController;
@synthesize settingsItem;
@synthesize doneItem;
@synthesize viewController;
@synthesize facebook;
@synthesize nextButton;
@synthesize quizManager;
@synthesize responseTimer;
@synthesize statsFriendsNeedsUpdate;
@synthesize statsCategoriesNeedsUpdate;
@synthesize statsHistoryNeedsUpdate;
@synthesize spinner;
@synthesize objMan;

- (void)nextButtonPressed:(id)sender {
    // If the current question was skipped by the user, let the API know.
    if ([self didUserSkipCurrentQuestion]) {
        [self notifyAPIThatCurrentQuestionWasSkipped];
    }
    
    // Request the next question.
	[quizManager requestNextQuestionAsync];
}

- (void)setupNextQuestion:(Question *)nextQuestion {
    MultipleChoiceQuizViewController *quizViewController = (MultipleChoiceQuizViewController *)viewController;
    
    // Determine the type of this question.
    if ([nextQuestion isKindOfClass:[MCQuestion class]]) { // Multiple Choice Question.
        [quizViewController.friendsTable setScrollEnabled:YES];
        
        MCQuestion *mcQuestion = (MCQuestion *)nextQuestion;
        
        quizViewController.questionString = mcQuestion.text;
         
        HJManagedImageV *topicImage = [[HJManagedImageV alloc] initWithFrame:CGRectMake(220,9,80,80)];
        topicImage.url = [mcQuestion getTopicImageURL];
        [GuessThatFriendAppDelegate manageImage:topicImage];
        [quizViewController.view addSubview:topicImage];
                
        quizViewController.topicImage = topicImage;
        
        quizViewController.correctFacebookId = mcQuestion.correctFacebookId;
        quizViewController.optionsList = [NSArray arrayWithArray:mcQuestion.options];
        [quizViewController.friendsTable reloadData];
        quizViewController.questionID = mcQuestion.questionId;
        quizViewController.isQuestionAnswered = false;
        [quizViewController.questionLabel setText: quizViewController.questionString];
        
        quizViewController.questionLabel.hidden = false;
        quizViewController.friendsTable.hidden = false;
        self.nextButton.hidden = false;
        quizViewController.topicImage.hidden = false;
    } else { // No question so hide question text.
        quizViewController.questionLabel.hidden = true;
        quizViewController.friendsTable.hidden = true;
        self.nextButton.hidden = false;
        quizViewController.topicImage.hidden = true;
        
        [GuessThatFriendAppDelegate downloadingContentFailed];
    }
    
    // Start timer for this question.
    responseTimer = [NSDate date];
}


+ (void)downloadingContentFailed {
    UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Downloading Content Failed" message:@"Please try again later." delegate:self cancelButtonTitle:@"OK" otherButtonTitles:nil];
    [alert show];
}

- (bool) didUserSkipCurrentQuestion {
    MultipleChoiceQuizViewController *quizViewController = (MultipleChoiceQuizViewController *)viewController;
    
    return quizViewController.questionID > 0 && !quizViewController.isQuestionAnswered;
}

- (void) notifyAPIThatCurrentQuestionWasSkipped {
    MultipleChoiceQuizViewController *quizViewController = (MultipleChoiceQuizViewController *)viewController;
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *) [[UIApplication sharedApplication] delegate];
    
    // Construct the request string.
    NSMutableString *getRequest;
    getRequest = [NSMutableString stringWithString:@BASE_URL_ADDR];
    [getRequest appendString:@"?cmd=submitQuestions"];
    [getRequest appendFormat:@"&facebookAccessToken=%@", delegate.facebook.accessToken];
    [getRequest appendFormat:@"&skippedQuestionIds[]=%i", quizViewController.questionID];
    
    // Create an aSynchronousRequest so that the UI isn't hogged.
    NSURLRequest *request = [NSURLRequest requestWithURL:[NSURL URLWithString:getRequest]];
    [NSURLConnection connectionWithRequest:request delegate:nil];
}

+ (BOOL) manageImage:(HJManagedImageV *)image {
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *) [[UIApplication sharedApplication] delegate];
    return [delegate.objMan manage:image];
}

#pragma mark -
#pragma mark Application lifecycle

- (void)setNavBarBackground {
    if([[UINavigationBar class] respondsToSelector:@selector(appearance)])  //iOS >= 5.0 
    {
        [[UINavigationBar appearance] setFrame:CGRectMake(0, 20, 320, 44)];
        [[UINavigationBar appearance] setBackgroundImage:[UIImage imageNamed:@"NavBar.png"] forBarMetrics:UIBarMetricsDefault]; 
    }
}

- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions {
    
    // Prepare image caching.
    objMan = [[HJObjManager alloc] init];
    NSString* cacheDirectory = [NSHomeDirectory() stringByAppendingString:@"/Library/Caches/imgcache/facebookProfilePictures/"] ;
    HJMOFileCache* fileCache = [[HJMOFileCache alloc] initWithRootPath:cacheDirectory];
    objMan.fileCache = fileCache;
    objMan.fileCache.fileCountLimit = IMAGE_CACHE_FILE_COUNT_LIMIT;
	objMan.fileCache.fileAgeLimit = IMAGE_CACHE_AGE_LIMIT_SECONDS;
	[objMan.fileCache trimCacheUsingBackgroundThread];
    
    [self setNavBarBackground];
    
    statsFriendsNeedsUpdate = YES;
    statsCategoriesNeedsUpdate = YES;
    statsHistoryNeedsUpdate = YES;
        
    responseTimer = [NSDate date];
    
    // Set up the 'Next' button.
    nextButton = [UIButton buttonWithType:UIButtonTypeCustom];
    CGRect buttonRect = CGRectMake(-5, 354, 330, 60);
    nextButton.frame = buttonRect;
    [nextButton setTitle:@"" forState:UIControlStateNormal];
    [nextButton addTarget:self action:@selector(nextButtonPressed:) forControlEvents:UIControlEventTouchUpInside];
    
    // UIBUtton nextButton is enabled / disabled! @see code after alloc-ing QuizManager
    
    // Set button images.
    UIImage *buttonImageNormal = [UIImage imageNamed:@"Button_Next.png"];
	[nextButton setBackgroundImage:buttonImageNormal forState:UIControlStateNormal];
	
	UIImage *buttonImagePressed = [UIImage imageNamed:@"Button_NextPressed.png"];
	[nextButton setBackgroundImage:buttonImagePressed forState:UIControlStateHighlighted];
    
    // Add the view controller's view as the root view controller of the navigation controller.
    if (viewController == nil) {
        viewController = [[MultipleChoiceQuizViewController alloc] 
                          initWithNibName:@"MultipleChoiceQuizViewController" bundle:nil];
    }
    
    [viewController.view addSubview:self.nextButton];
    
    NSArray *viewControllers = [NSArray arrayWithObjects: viewController, nil];
    [self.navController setViewControllers:viewControllers animated:NO];
    
    [self.window addSubview:navController.view];
    [self.window makeKeyAndVisible];
    
    // Setup the spinner.
    spinner = [[UIActivityIndicatorView alloc] 
               initWithActivityIndicatorStyle:UIActivityIndicatorViewStyleGray];
    spinner.center = self.window.center;
    spinner.hidesWhenStopped = YES;
    [self.window addSubview:spinner];
    
    // Hide everything until the first question is ready.
    MultipleChoiceQuizViewController *quizViewController = (MultipleChoiceQuizViewController *)viewController;
    quizViewController.questionLabel.hidden = true;
    quizViewController.friendsTable.hidden = true;
    self.nextButton.hidden = true;
    
    // Setup for Facebook login.
    [self fbLogin];
    
    // Initializing the values for the score keeping
    // @see MultipleChoiceQuestionViewController
    correctAnswers = 0;
    totalNumOfQuestions = 0;
    
    return YES;
}

- (void)fbLogin {
    facebook = [[Facebook alloc] initWithAppId:@"178461392264777" andDelegate:self];
    
    // Check for previsouly saved access token.
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    if ([defaults objectForKey:@"FBAccessTokenKey"] 
        && [defaults objectForKey:@"FBExpirationDateKey"]) {
        facebook.accessToken = [defaults objectForKey:@"FBAccessTokenKey"];
        facebook.expirationDate = [defaults objectForKey:@"FBExpirationDateKey"];
    }
    
    // Check if the session is valid. If not, ask user to log in.
    if (![facebook isSessionValid]) {
        NSArray *permissions = [[NSArray alloc] initWithObjects:
                                @"user_likes", 
                                @"friends_likes",
                                nil];
        [facebook authorize:permissions];
    }
    
    if(facebook.accessToken != nil){
        // Now we have facebook token, use it to initialize the quiz manager.
        quizManager = [[QuizManager alloc] initWithFBToken:facebook.accessToken];
        
        // Request the first question.
        [quizManager requestNextQuestionAsync];
    }
}

- (void)fbLogout {
    [facebook logout:self];
    
    NSHTTPCookieStorage *cookies = [NSHTTPCookieStorage sharedHTTPCookieStorage];
    for (NSHTTPCookie *cookie in [[NSHTTPCookieStorage sharedHTTPCookieStorage] cookies]) {
        [cookies deleteCookie:cookie];
    }
}

// Pre 4.2 support
- (BOOL)application:(UIApplication *)application handleOpenURL:(NSURL *)url {
    return [facebook handleOpenURL:url]; 
}

// For 4.2+ support
- (BOOL)application:(UIApplication *)application openURL:(NSURL *)url
  sourceApplication:(NSString *)sourceApplication annotation:(id)annotation {
    return [facebook handleOpenURL:url]; 
}

- (void)fbDidLogin {
    // Save the user's access token to UserDefaults.
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    [defaults setObject:[facebook accessToken] forKey:@"FBAccessTokenKey"];
    [defaults setObject:[facebook expirationDate] forKey:@"FBExpirationDateKey"];
    [defaults synchronize];
    
    // Now we have facebook token, use it to initialize the quiz manager.
    quizManager = [[QuizManager alloc] initWithFBToken:facebook.accessToken];
    
    // Request the first question.
	[quizManager requestNextQuestionAsync];
}

- (void)fbDidNotLogin:(BOOL)cancelled {
    
}

- (void)fbDidLogout {
    NSString *appDomain = [[NSBundle mainBundle] bundleIdentifier];
    [[NSUserDefaults standardUserDefaults] removePersistentDomainForName:appDomain];
    
    [self fbLogin];
}

- (void)fbDidExtendToken:(NSString*)accessToken
               expiresAt:(NSDate*)expiresAt {
    
}

- (void)fbSessionInvalidated {
    
}

- (void)applicationWillResignActive:(UIApplication *)application {
    /*
     Sent when the application is about to move from active to inactive state. This can occur for certain types of temporary interruptions (such as an incoming phone call or SMS message) or when the user quits the application and it begins the transition to the background state.
     Use this method to pause ongoing tasks, disable timers, and throttle down OpenGL ES frame rates. Games should use this method to pause the game.
     */
}

- (void)applicationDidEnterBackground:(UIApplication *)application {
    /*
     Use this method to release shared resources, save user data, invalidate timers, and store enough application state information to restore your application to its current state in case it is terminated later. 
     If your application supports background execution, called instead of applicationWillTerminate: when the user quits.
     */
}

- (void)applicationWillEnterForeground:(UIApplication *)application {
    /*
     Called as part of  transition from the background to the inactive state: here you can undo many of the changes made on entering the background.
     */
}

- (void)applicationDidBecomeActive:(UIApplication *)application {
    /*
     Restart any tasks that were paused (or not yet started) while the application was inactive. If the application was previously in the background, optionally refresh the user interface.
     */
}

- (void)applicationWillTerminate:(UIApplication *)application {
    /*
     Called when the application is about to terminate.
     See also applicationDidEnterBackground:.
     */
    
    // Store all the un-answered questions for next time.
    
}

#pragma mark -
#pragma mark Memory management

- (void)applicationDidReceiveMemoryWarning:(UIApplication *)application {
    /*
     Free up as much memory as possible by purging cached data objects that can be recreated (or reloaded from disk) later.
     */
}

@end

// For setting up nav bar background image for iOS < 5.0
@implementation UINavigationBar (CustomImage)
- (void)drawRect:(CGRect)rect {
    UIImage *image = [UIImage imageNamed: @"NavBar.png"];
    [image drawInRect:CGRectMake(0, 0, self.frame.size.width, self.frame.size.height)];
}
@end
