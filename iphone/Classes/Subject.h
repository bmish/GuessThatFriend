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
}

@property (nonatomic) NSString *facebookId;
@property (nonatomic) NSString *name;

- (Subject *)initWithName:(NSString *)friendName 
            andFacebookId:(NSString *)facebookId;

+ (NSString *) getPictureURLStringFromFacebookID:(NSString *)facebookId;
- (NSURL *) getPictureURL;

@end
