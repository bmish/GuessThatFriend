//
//  FriendStatsObject.h
//  GuessThatFriend
//
//  Created on 4/10/12.
//  Copyright (c) 2012 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface FriendStatsObject : NSObject {
    NSString *name;         // Full name.
    UIImage *picture;       // Link to profile picture.
    int correctCount;
    int totalCount;
    float fastestCorrectRT;
    float averageRT;
}

@property (nonatomic, retain) NSString *name;
@property (nonatomic, retain) UIImage *picture;
@property int correctCount;
@property int totalCount;
@property float fastestCorrectRT;
@property float averageRT;

- (FriendStatsObject *)initWithName:(NSString *)friendName andImagePath:(NSString *)imagePath andCorrectCount:(int)cCount andTotalCount:(int)tCount andFastestRT:(int)fRT andAverageRT:(int)aRT;

@end
