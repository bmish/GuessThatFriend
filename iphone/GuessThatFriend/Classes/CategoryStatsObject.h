//
//  CategoryStatsObject.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 4/30/12.
//  Copyright (c) 2012 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface CategoryStatsObject : NSObject {
    NSString *name;         // Full name.
    int correctCount;
    int totalCount;
    int fastestCorrectResponseTime;
    int averageResponseTime;
}

@property (nonatomic, retain) NSString *name;
@property int correctCount;
@property int totalCount;
@property int fastestCorrectResponseTime;
@property int averageResponseTime;

- (CategoryStatsObject *)initWithName:(NSString *)categoryName andCorrectCount:(int)cCount andTotalCount:(int)tCount andCorrectRT:(int)cRT andAverageRT:(int)aRT;

@end
