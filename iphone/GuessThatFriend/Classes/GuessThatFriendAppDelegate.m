//
//  GuessThatFriendAppDelegate.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/2/12.
//  Copyright 2012. All rights reserved.
//

#import "GuessThatFriendAppDelegate.h"
#import "QuizBaseViewController.h"
#import "MultipleChoiceQuizViewController.h"
#import "SettingsViewController.h"

@implementation GuessThatFriendAppDelegate

@synthesize window;
@synthesize navController;
@synthesize settingsItem;
@synthesize doneItem;
@synthesize viewController;
@synthesize facebook;
@synthesize nextButton;

- (void)nextButtonPressed:(id)sender {
    NSLog(@"TODO: implement next question");
}

#pragma mark -
#pragma mark Application lifecycle

- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions {    
    
    // Set up the 'Next' button.
    nextButton = [UIButton buttonWithType:UIButtonTypeRoundedRect];
    CGRect buttonRect = CGRectMake(124, 359, 72, 37);
    nextButton.frame = buttonRect;
    [nextButton setTitle:@"Next" forState:UIControlStateNormal];
    [nextButton addTarget:self action:@selector(nextButtonPressed:) forControlEvents:UIControlEventTouchUpInside];
    
    // Set button images.
    UIImage *buttonImageNormal = [UIImage imageNamed:@"whiteButton.png"];
	UIImage *stretchableButtonImageNormal = [buttonImageNormal stretchableImageWithLeftCapWidth:12 topCapHeight:0];
	[nextButton setBackgroundImage:stretchableButtonImageNormal forState:UIControlStateNormal];
	
	UIImage *buttonImagePressed = [UIImage imageNamed:@"blueButton.png"];
	UIImage *stretchableButtonImagePressed = [buttonImagePressed stretchableImageWithLeftCapWidth:12 topCapHeight:0];
	[nextButton setBackgroundImage:stretchableButtonImagePressed forState:UIControlStateHighlighted];
    
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
    
    // Override point for customization after application launch.
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
        [facebook authorize:nil];
    }

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
    
    // TODO: remove this later.
    NSLog(@"access token is ");
    NSLog(@"%@", facebook.accessToken);
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
    [settingsItem release];
    [doneItem release];
    [window release];
    [nextButton release];
    
    [super dealloc];
}

@end
