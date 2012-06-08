//
//  FBFriendCustomCell.m
//  GuessThatFriend
//
//  Created on 2/15/12.
//
//

#import "FBFriendCustomCell.h"

@implementation FBFriendCustomCell

@synthesize picture;
@synthesize name;

- (id)initWithStyle:(UITableViewCellStyle)style reuseIdentifier:(NSString *)reuseIdentifier {
    
    self = [super initWithStyle:style reuseIdentifier:reuseIdentifier];
    if (self) {
        // Initialization code.
    }
    return self;
}

- (void)setSelected:(BOOL)selected animated:(BOOL)animated {
    
    [super setSelected:selected animated:animated];
    
    // Configure the view for the selected state.
}

- (void)dealloc {
	[picture release];
	[name release];
	
    [super dealloc];
}

@end
