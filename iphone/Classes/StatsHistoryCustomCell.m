//
//  StatsHistoryCustomCell.m
//  GuessThatFriend
//
//  Created on 4/30/12.

//

#import "StatsHistoryCustomCell.h"

@implementation StatsHistoryCustomCell

@synthesize text;
@synthesize picture;
@synthesize correctAnswer;
@synthesize chosenAnswer;
@synthesize date;
@synthesize responseTime;

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
