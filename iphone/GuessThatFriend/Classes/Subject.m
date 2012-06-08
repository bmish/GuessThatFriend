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

- (Subject *)initWithName:(NSString *)friendName andImagePath:(NSString *)imagePath andFacebookId:(NSString *)myfacebookId andLink:(NSString *)mylink {
    // Download the subject's image from the 'imagePath'
    NSURL *url = [NSURL URLWithString:imagePath];
    UIImage *image = [UIImage imageWithData: [NSData dataWithContentsOfURL:url]]; 
    
    self.name = friendName;
	self.picture = image;
    self.facebookId = myfacebookId;
    self.link = mylink;
    
	return [super init];
}

- (void)dealloc {
    [facebookId release];
    [name release];
    [picture release];
    [link release];
    
	[super dealloc];
}

@end
