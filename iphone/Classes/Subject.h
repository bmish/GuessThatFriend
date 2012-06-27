//
//  Subject.h
//  GuessThatFriend
//
//  Created on 2/25/12.
//
//

#import <Foundation/Foundation.h>

@interface Subject : NSObject {
    NSString *facebookId;   // FacebookID of this person or page.
    NSString *name;         // Full name.
    UIImage *picture;       // Profile picture.
    NSString *link;         // Link to profile.
}

@property (nonatomic) NSString *facebookId;
@property (nonatomic) NSString *name;
@property (nonatomic) UIImage *picture;
@property (nonatomic) NSString *link;

- (Subject *)initWithName:(NSString *)friendName 
            andFacebookId:(NSString *)facebookId;

+ (NSString *) getPictureURLFromFacebookID:(NSString *)facebookId;

@end
