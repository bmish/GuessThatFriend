//
//  Subject.m
//  GuessThatFriend
//
//  Created by Bryan Mishkin on 2/25/12.
//  Copyright (c) 2012. All rights reserved.
//

#import "Subject.h"

@implementation Subject

@synthesize facebookId, name, picture, link;

- (Subject *)initWithName:(NSString *)friendName andImagePath:(NSString *)imagePath {
    // Download the subject's image from the 'imagePath'
    NSURL *url = [NSURL URLWithString:imagePath];
    UIImage *image = [UIImage imageWithData: [NSData dataWithContentsOfURL:url]]; 
    
    self.name = friendName;
	self.picture = image;
    
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
