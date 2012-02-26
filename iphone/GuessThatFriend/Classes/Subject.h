//
//  Subject.h
//  GuessThatFriend
//
//  Created by Bryan Mishkin on 2/25/12.
//  Copyright (c) 2012 University of Illinois. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface Subject : NSObject {
    NSString *facebookId;   // FacebookID of this person or page.
    NSString *name;         // Full name.
    NSString *picture;      // Link to profile picture.
    NSString *link;         // Link to profile.
}

@end
