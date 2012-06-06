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

- (oneway void)release {
    if (![NSThread isMainThread]) {
        [self performSelectorOnMainThread:@selector(release) withObject:nil waitUntilDone:NO];
    } else {
        [super release];
    }
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
