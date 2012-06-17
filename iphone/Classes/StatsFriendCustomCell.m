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
    [picture release];
    [percentageLabel release];
    [progressBar release];
    [name release];
    [fastestCorrectRT release];
    [averageRT release];

    [super dealloc];
}

@end
