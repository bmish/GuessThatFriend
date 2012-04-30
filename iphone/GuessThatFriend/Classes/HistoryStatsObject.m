//
//  HistoryStatsObject.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 4/30/12.
//  Copyright (c) 2012 __MyCompanyName__. All rights reserved.
//

#import "HistoryStatsObject.h"
#import "GuessThatFriendAppDelegate.h"

@implementation HistoryStatsObject

@synthesize question, picture, correctAnswer, yourAnswer, date, responseTime;

- (HistoryStatsObject *)initWithQuestion:(NSString *)text andImagePath:(NSString *)imagePath andCorrectAnswer:(NSString *)cAnswer andYourAnswer:(NSString *)yAnswer andDate:(NSString *)theDate andResponseTime:(int)rt {
    
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *) [[UIApplication sharedApplication] delegate];
    UIImage *image = [delegate getPicture:imagePath];
    
    self.question = text;
    self.picture = image;
    self.correctAnswer = cAnswer;
    self.yourAnswer = yAnswer;
    self.date = theDate;
    self.responseTime = ((float)rt) / 1000.0;
    
    return [super init];
}

- (void)dealloc {
    [question release];
    [picture release];
    [correctAnswer release];
    [yourAnswer release];
    [date release];
    
	[super dealloc];
}

@end
