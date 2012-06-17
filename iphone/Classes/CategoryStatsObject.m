//
//  CategoryStatsObject.m
//  GuessThatFriend
//
//  Created on 4/30/12.
//

#import "CategoryStatsObject.h"

@implementation CategoryStatsObject

@synthesize name, correctCount, totalCount, fastestCorrectResponseTime, averageResponseTime;

- (CategoryStatsObject *)initWithName:(NSString *)categoryName andCorrectCount:(int)cCount andTotalCount:(int)tCount andCorrectRT:(int)cRT andAverageRT:(int)aRT {
    
    self.name = categoryName;
    self.correctCount = cCount;
    self.totalCount = tCount;
    self.fastestCorrectResponseTime = ((float)cRT) / 1000.0;
    if (self.fastestCorrectResponseTime < 0) {
        self.fastestCorrectResponseTime *= -1;
    }
    self.averageResponseTime = ((float)aRT) / 1000.0;
    if (self.averageResponseTime < 0) {
        self.averageResponseTime *= -1;
    }
    
    return [super init];
}

- (void)dealloc {
    [name release];
    
	[super dealloc];
}

@end
