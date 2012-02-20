//
//  AboutViewController.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/2/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface AboutViewController : UIViewController {
	UIButton *backButton;
}

@property (nonatomic, retain) IBOutlet UIButton *backButton;

- (IBAction)switchViewToGoBack:(id)sender;

@end
