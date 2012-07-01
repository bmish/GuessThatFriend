//
//  StatsCategoryCustomCell.m
//  GuessThatFriend
//
//  Created on 4/30/12.

//

#import "StatsCategoryCustomCell.h"

@implementation StatsCategoryCustomCell

@synthesize name;
@synthesize progressBar;
@synthesize percentageLabel;
@synthesize fastestCorrectResponseTime;
@synthesize averageResponseTime;

- (id)initWithStyle:(UITableViewCellStyle)style reuseIdentifier:(NSString *)reuseIdentifier
{
    self = [super initWithStyle:style reuseIdentifier:reuseIdentifier];
    if (self) {
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
