//
//  UIBarButtonItem+Image.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 4/21/12.
//  Copyright (c) 2012 __MyCompanyName__. All rights reserved.
//

#import "UIBarButtonItem+Image.h"

@implementation UIBarButtonItem (Image)

-(id)initWithImage:(UIImage *)image title:(NSString*)title target:(id)target action:(SEL)action {
    UIButton *button = [UIButton buttonWithType:UIButtonTypeCustom];
    button.frame= CGRectMake(0.0, 0.0, image.size.width, image.size.height);
    button.titleLabel.font = [UIFont boldSystemFontOfSize:12];
    button.titleLabel.shadowOffset = CGSizeMake(0, -1);
    button.titleLabel.shadowColor = [UIColor colorWithWhite:0 alpha:0.5];
    
    [button setTitle:title forState:UIControlStateNormal];
    [button setBackgroundImage:image forState:UIControlStateNormal];
    [button addTarget:target action:action forControlEvents:UIControlEventTouchUpInside];
    
    UIView *view =[[UIView alloc] initWithFrame:CGRectMake(0.0, 0.0, image.size.width, image.size.height) ];
    [view addSubview:button];
    
    self = [[UIBarButtonItem alloc] initWithCustomView:view];
    
    [view release];
    [image release];
    
    return self;
}

-(void)setEnabled:(BOOL)enabled {
    if (self.customView) {
        if ([[self.customView.subviews objectAtIndex:0] isKindOfClass:[UIButton class]]) {
            ((UIButton*)[self.customView.subviews objectAtIndex:0]).enabled = enabled;
        }
    }
}

@end
