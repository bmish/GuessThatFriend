//
//  Category.h
//  GuessThatFriend
//
//  Created by Bryan Mishkin on 2/25/12.
//  Copyright (c) 2012 University of Illinois. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface Category : NSObject {
    int categoryId;
    NSString *facebookName; // Name that Facebook gives to this category.
    NSString *prettyName;   // Pretty name that we gave to this category.
}

@end
