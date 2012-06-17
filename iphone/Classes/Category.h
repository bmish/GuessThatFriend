//
//  Category.h
//  GuessThatFriend
//
//  Created on 2/25/12.
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
