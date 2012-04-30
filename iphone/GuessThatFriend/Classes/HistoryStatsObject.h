//
//  HistoryStatsObject.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 4/30/12.
//  Copyright (c) 2012 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface HistoryStatsObject : NSObject {
    NSString *question;
    UIImage *picture;
    NSString *correctAnswer;
    NSString *yourAnswer;
    NSString *date;
    float responseTime;
}

@property (nonatomic, retain) NSString *question;
@property (nonatomic, retain) UIImage *picture;
@property (nonatomic, retain) NSString *correctAnswer;
@property (nonatomic, retain) NSString *yourAnswer;
@property (nonatomic, retain) NSString *date;
@property float responseTime;

- (HistoryStatsObject *)initWithQuestion:(NSString *)text andImagePath:(NSString *)imagePath andCorrectAnswer:(NSString *)cAnswer andYourAnswer:(NSString *)yAnswer andDate:(NSString *)theDate andResponseTime:(int)rt;

@end
