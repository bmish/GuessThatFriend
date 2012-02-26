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
	NSString *name;     // To be deleted (stored in subject instead).
	UIImage *image;     // To be deleted (stored in subject instead).
}

@property (nonatomic, retain) NSString *name;
@property (nonatomic, retain) UIImage *image;

- (Option *)initWithName:(NSString *)friendName andImagePath:(NSString *)imagePath;
- (id)copyWithZone:(NSZone *)zone;

@end
