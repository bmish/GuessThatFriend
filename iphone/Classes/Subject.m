//
//  Subject.m
//  GuessThatFriend
//
//  Created on 2/25/12.
//
//

#import "Subject.h"

@implementation Subject

@synthesize facebookId, name;

- (Subject *)initWithName:(NSString *)friendName andFacebookId:(NSString *)myfacebookId {
    if (self = [super init]) {
        self.name = friendName;
        self.facebookId = myfacebookId;
    }
    
	return self;
}

- (NSURL *) getPictureURL {
    NSString *urlString = [Subject getPictureURLStringFromFacebookID:self.facebookId];
    return [NSURL URLWithString:urlString];
}

+ (NSString *) getPictureURLStringFromFacebookID:(NSString *)facebookId {
    return [NSString stringWithFormat:@"%@%@%@", @"https://graph.facebook.com/", facebookId, @"/picture"];
}

+ (NSString *) getProfileURLStringFromFacebookID:(NSString *)facebookId {
    return [NSString stringWithFormat:@"%@%@", @"https://www.facebook.com/", facebookId];
}


@end
