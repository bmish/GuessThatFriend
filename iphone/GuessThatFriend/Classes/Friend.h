//
//  Friend.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface Friend : NSObject <NSCopying> {
	NSString *name;
	UIImage *image;
}

@property (nonatomic, retain) NSString *name;
@property (nonatomic, retain) UIImage *image;

- (Friend *)initWithName:(NSString *)friendName andImagePath:(NSString *)imagePath;
- (id)copyWithZone:(NSZone *)zone;

@end
