//
//  Category.h
//  GuessThatFriend
//
//  Created by Bryan Mishkin on 2/25/12.
//  Copyright (c) 2012. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface Category : NSObject {
    int categoryId;
    NSString *facebookName; // Name that Facebook gives to this category.
    NSString *prettyName;   // Pretty name that we gave to this category.
}

@property (nonatomic, assign) int categoryId;
@property (nonatomic, retain) NSString *facebookName;
@property (nonatomic, retain) NSString *prettyName;

@end
