//
//  StatsFriendCustomCell.m
//  GuessThatFriend
//
//  Created on 4/10/12.
//  Copyright (c) 2012 University of Illinois at Urbana-Champaign. All rights reserved.
//

#import "StatsFriendCustomCell.h"

@implementation StatsFriendCustomCell

@synthesize percentageLabel;
@synthesize progressBar;
@synthesize name;
@synthesize picture;

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
    [picture release];
    [percentageLabel release];
    [progressBar release];
    [name release];

    [super dealloc];
}

@end
