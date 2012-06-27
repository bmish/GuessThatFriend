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

@property (nonatomic) IBOutlet UIImageView *picture;
@property (nonatomic) IBOutlet UILabel *name;

@end
