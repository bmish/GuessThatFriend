//
//  StatsCategoryCustomCell.h
//  GuessThatFriend
//
//  Created on 4/30/12.

//

#import <UIKit/UIKit.h>

@interface StatsCategoryCustomCell : UITableViewCell {
    UILabel *name;
    UIProgressView *progressBar;
    UILabel *percentageLabel;
    UILabel *fastestCorrectResponseTime;
    UILabel *averageResponseTime;
}

@property (nonatomic) IBOutlet UILabel *name;
@property (nonatomic) IBOutlet UIProgressView *progressBar;
@property (nonatomic) IBOutlet UILabel *percentageLabel;
@property (nonatomic) IBOutlet UILabel *fastestCorrectResponseTime;
@property (nonatomic) IBOutlet UILabel *averageResponseTime;

@end
