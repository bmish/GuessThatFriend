//
//  Subject.h
//  GuessThatFriend
//
//  Created on 2/25/12.
//  Copyright (c) 2012. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface Subject : NSObject {
    NSString *facebookId;   // FacebookID of this person or page.
    NSString *name;         // Full name.
    UIImage *picture;       // Link to profile picture.
    NSString *link;         // Link to profile.
}

@property (nonatomic, retain) NSString *facebookId;
@property (nonatomic, retain) NSString *name;
@property (nonatomic, retain) UIImage *picture;
@property (nonatomic, retain) NSString *link;

- (Subject *)initWithName:(NSString *)friendName andImagePath:(NSString *)imagePath 
            andFacebookId:(NSString *)facebookId
                  andLink:(NSString *)link;

@end
