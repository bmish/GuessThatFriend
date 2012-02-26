//
//  Option.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "Subject.h"

@interface Option : NSObject <NSCopying> {
    int optionId;
    Subject *subject;   // The subject of this option (a person or page).
}

@property (nonatomic, assign) int optionId;
@property (nonatomic, retain) Subject *subject;

- (Option *)initWithName:(NSString *)friendName andImagePath:(NSString *)imagePath;
- (id)copyWithZone:(NSZone *)zone;

@end
