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
    float fastestCorrectRT;
    float averageRT;
}

@property (nonatomic, retain) Subject *subject;
@property int correctCount;
@property int totalCount;
@property float fastestCorrectRT;
@property float averageRT;

- (FriendStatsObject *)initWithSubject:(Subject *)mySubject andCorrectCount:(int)cCount andTotalCount:(int)tCount andFastestRT:(int)fRT andAverageRT:(int)aRT;

@end
