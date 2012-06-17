//
//  SettingsViewController.h
//  GuessThatFriend
//
//  Created on 2/13/12.
//
//

#import <UIKit/UIKit.h>

@interface SettingsViewController : UIViewController {
    UIButton *shareButton;
	UIButton *logoutButton;
}

@property (nonatomic, retain) IBOutlet UIButton *shareButton;
@property (nonatomic, retain) IBOutlet UIButton *logoutButton;

- (IBAction)switchViewToFBLogin:(id)sender;
- (IBAction)shareOnFacebook:(id)sender;
- (void)facebookLogout;

@end
