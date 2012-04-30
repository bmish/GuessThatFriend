//
//  StatsCategoriesViewController.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 4/30/12.
//  Copyright (c) 2012 __MyCompanyName__. All rights reserved.
//

#import "StatsCategoriesViewController.h"
#import "GuessThatFriendAppDelegate.h"
#import "JSONKit.h"
#import "CategoryStatsObject.h"

@implementation StatsCategoriesViewController

- (void)viewDidLoad {
    [super viewDidLoad];
}

- (void)viewDidUnload {
    [super viewDidUnload];
}

- (void)dealloc {
    [super dealloc];
}

- (BOOL)createStatsFromServerResponse:(NSString *)response {
    
    // Parse the JSON response.
    NSDictionary *responseDictionary = [response objectFromJSONString];
    
    BOOL success = [[responseDictionary objectForKey:@"success"] boolValue];
    if (success == false) {
        return NO;
    }
    
    // Empty the current list
    [list removeAllObjects];
    
    NSArray *friendsArray = [responseDictionary objectForKey:@"friends"];
    NSEnumerator *friendEnumerator = [friendsArray objectEnumerator];
    NSDictionary *curFriend;
    
    // Go through all FRIENDS
    while (curFriend = [friendEnumerator nextObject]) {
        NSDictionary *subjectDict = [curFriend objectForKey:@"subject"];
        NSString *correctCountStr = [curFriend objectForKey:@"correctAnswerCount"];
        NSString *totalCountStr = [curFriend objectForKey:@"totalAnswerCount"];
        
        int correctCount = [correctCountStr intValue];
        int totalCount = [totalCountStr intValue];
        
        NSString *name = [subjectDict objectForKey:@"name"];
        NSString *picURL = [subjectDict objectForKey:@"picture"];
        
        CategoryStatsObject *statsObj = [[CategoryStatsObject alloc] init];
        
        [list addObject:statsObj];
        
        [statsObj release];
    }
    
    return YES;
}

- (void)requestStatisticsFromServer:(BOOL)useSampleData{
    
    BOOL success = NO;
    
    while (success == NO) {
        // Create GET request.
        NSMutableString *getRequest;
        
        if (useSampleData) {    // Retrieve sample data.
            getRequest = [NSMutableString stringWithString:@SAMPLE_GET_STATISTICS_CATEGORIES_ADDR];
        } else {
            // Make a real request.
            
            getRequest = [NSMutableString stringWithString:@BASE_URL_ADDR];
            [getRequest appendString:@"?cmd=getStatistics"];
            
            GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)
            [[UIApplication sharedApplication] delegate];
            
            [getRequest appendFormat:@"&facebookAccessToken=%@", delegate.facebook.accessToken];
            [getRequest appendFormat:@"&type=categories"];
        }
        
        NSLog(@"STATS Request string: %@", getRequest);
        
        // Send the GET request to the server.
        NSURLRequest *request = [NSURLRequest requestWithURL:[NSURL URLWithString:getRequest]];
        
        NSData *response = [NSURLConnection sendSynchronousRequest:request returningResponse:nil error:nil];
        NSString *responseString = [[NSString alloc] initWithData:response encoding:NSUTF8StringEncoding];
        
        NSLog(@"STATS RESPONSE STRING: %@ \n",responseString);
        
        // Initialize array of questions from the server's response.
        success = [self createStatsFromServerResponse:responseString];
        
        [responseString release];
    }
}

@end
