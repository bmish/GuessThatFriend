//
//  FBFriendCustomCell.h
//  GuessThatFriend
//
//  Created on 2/15/12.
//
//

#import <UIKit/UIKit.h>

@interface FBFriendCustomCell : UITableViewCell {
	UIImageView *picture;
	UILabel *name;
}

@property (nonatomic, retain) IBOutlet UIImageView *picture;
@property (nonatomic, retain) IBOutlet UILabel *name;

@end
