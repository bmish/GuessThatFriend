//
//  Subject.m
//  GuessThatFriend
//
//  Created on 2/25/12.
//
//

#import "Subject.h"

@implementation Subject

@synthesize facebookId, name, picture, link;

- (Subject *)initWithName:(NSString *)friendName andFacebookId:(NSString *)myfacebookId {
    // Create the image path and download the subject's image.
    NSString *imagePath = [Subject getPictureURLFromFacebookID:myfacebookId];
    NSURL *url = [NSURL URLWithString:imagePath];
    UIImage *image = [UIImage imageWithData: [NSData dataWithContentsOfURL:url]]; 
    
    self.name = friendName;
	self.picture = image;
    self.facebookId = myfacebookId;
    self.link = [Subject getProfileURLFromFacebookID:myfacebookId];
    
	return [super init];
}

+ (NSString *) getPictureURLFromFacebookID:(NSString *)facebookId {
    return [NSString stringWithFormat:@"%@%@%@", @"https://graph.facebook.com/", facebookId, @"/picture"];
}

+ (NSString *) getProfileURLFromFacebookID:(NSString *)facebookId {
    return [NSString stringWithFormat:@"%@%@", @"https://www.facebook.com/", facebookId];
}

- (void)dealloc {
    [facebookId release];
    [name release];
    [picture release];
    [link release];
    
	[super dealloc];
}

@end
