//
//  FBFriendCustomCell.h
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface FBFriendCustomCell : UITableViewCell {
	UIImageView *picture;
	UILabel *name;
	UISegmentedControl *answer;
}

@property (nonatomic, retain) IBOutlet UIImageView *picture;
@property (nonatomic, retain) IBOutlet UILabel *name;
@property (nonatomic, retain) IBOutlet UISegmentedControl *answer;

@end
