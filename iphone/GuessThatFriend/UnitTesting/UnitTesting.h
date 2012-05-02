//
//  UnitTesting.h
//  UnitTesting
//
//  Created on 3/12/12.
//  Copyright (c) 2012. All rights reserved.
//

#import <SenTestingKit/SenTestingKit.h>

@class GuessThatFriendAppDelegate;

@interface UnitTesting : SenTestCase {
    GuessThatFriendAppDelegate *delegate;
    NSString *facebookToken;
}

@property (nonatomic, retain) GuessThatFriendAppDelegate *delegate;
@property (nonatomic, retain) NSString *facebookToken;

@end
