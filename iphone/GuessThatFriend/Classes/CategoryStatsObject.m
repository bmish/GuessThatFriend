//
//  CategoryStatsObject.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 4/30/12.
//  Copyright (c) 2012 __MyCompanyName__. All rights reserved.
//

#import "CategoryStatsObject.h"

@implementation CategoryStatsObject

@synthesize name, correctCount, totalCount, fastestCorrectResponseTime, averageResponseTime;

- (CategoryStatsObject *)initWithName:(NSString *)categoryName andCorrectCount:(int)cCount andTotalCount:(int)tCount andCorrectRT:(int)cRT andAverageRT:(int)aRT {
    
    self.name = categoryName;
    self.correctCount = cCount;
    self.totalCount = tCount;
    self.fastestCorrectResponseTime = cRT;
    self.averageResponseTime = aRT;
    
    return [super init];
}

- (void)dealloc {
    [name release];
    
	[super dealloc];
}

@end
