//
//  HistoryStatsObject.h
//  GuessThatFriend
//
//  Created on 4/30/12.
//

#import <Foundation/Foundation.h>
#import "Subject.h"

@interface HistoryStatsObject : NSObject {
    NSString *question;
    UIImage *picture;
    NSString *correctAnswer;
    NSString *yourAnswer;
    NSString *date;
    float responseTime;
}

@property (nonatomic) NSString *question;
@property (nonatomic) UIImage *picture;
@property (nonatomic) NSString *correctAnswer;
@property (nonatomic) NSString *yourAnswer;
@property (nonatomic) NSString *date;
@property float responseTime;

- (HistoryStatsObject *)initWithQuestion:(NSString *)text andSubject:(Subject *)subject andCorrectAnswer:(NSString *)cAnswer andYourAnswer:(NSString *)yAnswer andDate:(NSString *)theDate andResponseTime:(int)rt;

@end
