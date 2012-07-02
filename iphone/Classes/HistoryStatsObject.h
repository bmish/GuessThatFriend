//
//  HistoryStatsObject.h
//  GuessThatFriend
//
//  Created on 4/30/12.
//

#import <Foundation/Foundation.h>
#import "Subject.h"
#import "HJManagedImageV.h"

@interface HistoryStatsObject : NSObject {
    NSString *question;
    Subject *subject;
    NSString *correctAnswer;
    NSString *yourAnswer;
    NSString *date;
    float responseTime;
}

@property (nonatomic) NSString *question;
@property (nonatomic) Subject *subject;
@property (nonatomic) NSString *correctAnswer;
@property (nonatomic) NSString *yourAnswer;
@property (nonatomic) NSString *date;
@property float responseTime;

- (HistoryStatsObject *)initWithQuestion:(NSString *)text andSubject:(Subject *)theSubject andCorrectAnswer:(NSString *)cAnswer andYourAnswer:(NSString *)yAnswer andDate:(NSString *)theDate andResponseTime:(int)rt;

@end
