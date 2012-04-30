//
//  StatsCategoryCustomCell.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 4/30/12.
//  Copyright (c) 2012 __MyCompanyName__. All rights reserved.
//

#import "StatsCategoryCustomCell.h"

@implementation StatsCategoryCustomCell

@synthesize name;
@synthesize progressBar;
@synthesize percentageLabel;
@synthesize fastestCorrectRT;
@synthesize averageRT;

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

- (void)dealloc {
    [progressBar release];
    [percentageLabel release];
    [name release];
    [fastestCorrectRT release];
    [averageRT release];
    
    [super dealloc];
}

@end
