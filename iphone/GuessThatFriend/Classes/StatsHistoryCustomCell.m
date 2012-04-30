//
//  StatsHistoryCustomCell.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 4/30/12.
//  Copyright (c) 2012 __MyCompanyName__. All rights reserved.
//

#import "StatsHistoryCustomCell.h"

@implementation StatsHistoryCustomCell

@synthesize text, picture, correctAnswer, chosenAnswer;
@synthesize date, responseTime;

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
    [text release];
    [picture release];
    [correctAnswer release];
    [chosenAnswer release];
    [date release];
    
    [super dealloc];
}

@end
