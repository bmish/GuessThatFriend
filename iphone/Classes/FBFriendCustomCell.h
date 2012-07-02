//
//  FBFriendCustomCell.h
//  GuessThatFriend
//
//  Created on 2/15/12.
//
//

#import <UIKit/UIKit.h>
#import "HJManagedImageV.h"

@interface FBFriendCustomCell : UITableViewCell {
    HJManagedImageV *picture;
	UILabel *name;
}

@property (nonatomic, strong) IBOutlet HJManagedImageV *picture;
@property (nonatomic) IBOutlet UILabel *name;

@end
