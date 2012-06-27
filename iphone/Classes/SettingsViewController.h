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

@property (nonatomic) IBOutlet UIButton *shareButton;
@property (nonatomic) IBOutlet UIButton *logoutButton;

- (IBAction)switchViewToFBLogin:(id)sender;
- (IBAction)shareOnFacebook:(id)sender;
- (void)facebookLogout;

@end
