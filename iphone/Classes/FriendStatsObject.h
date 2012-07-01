//
//  FriendStatsObject.h
//  GuessThatFriend
//
//  Created on 4/10/12.
//

#import <Foundation/Foundation.h>
#import "Subject.h"

@interface FriendStatsObject : NSObject {
    Subject *subject;
    int correctCount;
    int totalCount;
    float fastestCorrectResponseTime;
    float averageResponseTime;
}

@property (nonatomic) Subject *subject;
@property int correctCount;
@property int totalCount;
@property float fastestCorrectResponseTime;
@property float averageResponseTime;

- (FriendStatsObject *)initWithSubject:(Subject *)mySubject andCorrectCount:(int)cCount andTotalCount:(int)tCount andFastestRT:(int)fRT andAverageRT:(int)aRT;

@end
