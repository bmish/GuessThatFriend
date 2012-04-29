//
//  GuessThatFriendAppDelegate.m
//  GuessThatFriend
//
//  Created on 2/2/12.
//  Copyright 2012. All rights reserved.
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

#define IMAGEPLISTPATH     @"imageCachePlist"
#define IMAGEPLISTFULLPATH @"/imageCachePlist.plist"

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
@synthesize statsNeedsUpdate;
@synthesize plistImageDict;

- (void)nextButtonPressed:(id)sender {
	Question *nextQuestion = [quizManager getNextQuestion];
	
    // Determine the type of this question.
    if ([nextQuestion isKindOfClass:[MCQuestion class]]) {      // Multiple Choice Question.
        
        MultipleChoiceQuizViewController *quizViewController = (MultipleChoiceQuizViewController *)viewController;
        MCQuestion *mcQuestion = (MCQuestion *)nextQuestion;
        
        quizViewController.questionString = mcQuestion.text;

        UIImage *topicImage = [self getPicture:mcQuestion.topicImage];        
        quizViewController.topicImage.image = topicImage;
        
        quizViewController.correctFacebookId = mcQuestion.correctFacebookId;
        quizViewController.optionsList = [NSArray arrayWithArray:mcQuestion.options];
        [quizViewController.friendsTable reloadData];
        quizViewController.questionID = mcQuestion.questionId;
        [quizViewController.questionTextView setText: quizViewController.questionString];
                
    } else {                                                    // Fill in blank Question.
        
    }
    
    [nextQuestion release];
    
    // Start timer for this question.
    [responseTimer release];
    responseTimer = [NSDate date];
    [responseTimer retain];    
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
    
    [self setNavBarBackground];
    
    [self initImagePlist];
    statsNeedsUpdate = YES;
        
    responseTimer = [NSDate date];
    [responseTimer retain];
    
    // Set up the 'Next' button.
    nextButton = [UIButton buttonWithType:UIButtonTypeCustom];
    CGRect buttonRect = CGRectMake(-5, 354, 330, 60);
    nextButton.frame = buttonRect;
    [nextButton setTitle:@"" forState:UIControlStateNormal];
    [nextButton addTarget:self action:@selector(nextButtonPressed:) forControlEvents:UIControlEventTouchUpInside];
    
    //UIBUtton nextButton is enabled / disabled! @see code after alloc-ing QuizManager
    
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
    
    // Setup for Facebook login.
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
        [permissions release];
    }
    else {
        //???
    }

    if(facebook.accessToken !=nil){
        // Now we have facebook token, use it to initialize the quiz manager.
        quizManager = [[QuizManager alloc] initWithFBToken:facebook.accessToken andUseSampleData:NO];
    
        [self nextButtonPressed:nil];
    }
    
    //Initializing the values for the score keeping
    //@see MultipleChoiceQuestionViewController
    correctAnswers = 0;
    totalNumOfQuestions = 0;
    
    return YES;
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
    quizManager = [[QuizManager alloc] initWithFBToken:facebook.accessToken andUseSampleData:NO];
    
    [self nextButtonPressed:nil];
    
}

- (void)fbDidNotLogin:(BOOL)cancelled {
    
}

- (void)fbDidLogout {

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
}

#pragma mark -
#pragma mark Memory management

- (void)applicationDidReceiveMemoryWarning:(UIApplication *)application {
    /*
     Free up as much memory as possible by purging cached data objects that can be recreated (or reloaded from disk) later.
     */
}

- (void)dealloc {
    [viewController release];
    [navController release];
    [tabController release];
    [settingsItem release];
    [doneItem release];
    [window release];
    [nextButton release];
    [quizManager release];
    [responseTimer release];
    [plistImageDict release];
    
    [super dealloc];
}

- (UIImage *)getPicture:(NSString *)imageURL{
    
    NSString *resourceDocPath = [[NSString alloc] initWithString:[[[[NSBundle mainBundle]  resourcePath] stringByDeletingLastPathComponent] stringByAppendingPathComponent:@"Documents"]];
    
    // See if it exists in plist, if not then download
    NSString *imageLocalPath = [plistImageDict objectForKey:imageURL];    
    UIImage *returnImage;
    NSString *path = [resourceDocPath stringByAppendingPathComponent:IMAGEPLISTFULLPATH];
    
    // value not found, need to download and save to plist
    if (imageLocalPath == nil) {
        // Generate image file local path to save image in.
        NSArray *stringComps = [imageURL componentsSeparatedByString:@"/"];
        NSString *filePath = [resourceDocPath stringByAppendingPathComponent:[stringComps objectAtIndex:3]];

        // Download the image.
        NSURL *url = [NSURL URLWithString:imageURL];
        returnImage = [UIImage imageWithData: [NSData dataWithContentsOfURL:url]];
        
        // Save image locally
        NSData *data = [NSData dataWithData:UIImageJPEGRepresentation(returnImage, 1.0f)];  // 1.0f = 100% quality
        [data writeToFile:filePath atomically:YES];
        
        // now add to plist & save plist
        [plistImageDict setObject:filePath forKey:imageURL];
        [plistImageDict writeToFile:path atomically: YES];
    }
    else {
        // Load UIImage from local path
        returnImage = [UIImage imageWithContentsOfFile:imageLocalPath];
    }
    
    return returnImage;
}

- (void)initImagePlist {
    NSString *resourceDocPath = [[NSString alloc] initWithString:[[[[NSBundle mainBundle]  resourcePath] stringByDeletingLastPathComponent] stringByAppendingPathComponent:@"Documents"]];
    
    NSString *plistPath = [resourceDocPath stringByAppendingPathComponent:IMAGEPLISTFULLPATH];
    
    if([[NSFileManager defaultManager] fileExistsAtPath:plistPath] == NO) {
        NSString *path = [[NSBundle mainBundle] pathForResource:IMAGEPLISTPATH ofType:@"plist"];
        plistImageDict = [[NSMutableDictionary alloc] initWithContentsOfFile:path];
        [plistImageDict writeToFile:plistPath atomically:YES];
    }
    else {
        plistImageDict = [[NSMutableDictionary alloc] initWithContentsOfFile:plistPath];
    }
}

@end

// For setting up nav bar background image for iOS < 5.0
@implementation UINavigationBar (CustomImage)
- (void)drawRect:(CGRect)rect {
    UIImage *image = [UIImage imageNamed: @"NavBar.png"];
    [image drawInRect:CGRectMake(0, 0, self.frame.size.width, self.frame.size.height)];
}
@end
