//
//  StatsFriendCustomCell.h
//  GuessThatFriend
//
//  Created on 4/10/12.
//

#import <UIKit/UIKit.h>
#import "HJManagedImageV.h"

@interface StatsFriendCustomCell : UITableViewCell {
	HJManagedImageV *picture;
	UILabel *name;
    UIProgressView *progressBar;
    UILabel *percentageLabel;
    UILabel *fastestCorrectRT;
    UILabel *averageRT;
}

@property (nonatomic, strong) IBOutlet HJManagedImageV *picture;
@property (nonatomic) IBOutlet UILabel *name;
@property (nonatomic) IBOutlet UIProgressView *progressBar;
@property (nonatomic) IBOutlet UILabel *percentageLabel;
@property (nonatomic) IBOutlet UILabel *fastestCorrectRT;
@property (nonatomic) IBOutlet UILabel *averageRT;

@end
