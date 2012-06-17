//
//  UnitTesting.h
//  UnitTesting
//
//  Created on 3/12/12.
//
//

#import <SenTestingKit/SenTestingKit.h>

@class GuessThatFriendAppDelegate;

@interface UnitTesting : SenTestCase {
    GuessThatFriendAppDelegate *delegate;
    NSString *facebookToken;
}

@end
