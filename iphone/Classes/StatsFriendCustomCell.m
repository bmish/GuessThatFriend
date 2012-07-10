//
//  StatsFriendCustomCell.m
//  GuessThatFriend
//
//  Created on 4/10/12.
//

#import "StatsFriendCustomCell.h"

@implementation StatsFriendCustomCell

@synthesize percentageLabel;
@synthesize progressBar;
@synthesize name;
@synthesize picture;
@synthesize fastestCorrectRT;
@synthesize averageRT;

- (id)initWithStyle:(UITableViewCellStyle)style reuseIdentifier:(NSString *)reuseIdentifier
{
    if (self = [super initWithStyle:style reuseIdentifier:reuseIdentifier]) {
        // Initialization code
    }
    return self;
}

- (void)setSelected:(BOOL)selected animated:(BOOL)animated
{
    [super setSelected:selected animated:animated];

    // Configure the view for the selected state
}


@end
