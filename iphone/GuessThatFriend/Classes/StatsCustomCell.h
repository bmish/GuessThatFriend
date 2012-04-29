//
//  StatsCustomCell.h
//  GuessThatFriend
//
//  Created on 4/10/12.
//  Copyright (c) 2012 University of Illinois at Urbana-Champaign. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface StatsCustomCell : UITableViewCell {
	UIImageView *picture;
	UILabel *name;
    UIProgressView *progressBar;
    UILabel *percentageLabel;
}

@property (nonatomic, retain) IBOutlet UIImageView *picture;
@property (nonatomic, retain) IBOutlet UILabel *name;
@property (nonatomic, retain) IBOutlet UIProgressView *progressBar;
@property (nonatomic, retain) IBOutlet UILabel *percentageLabel;

@end
