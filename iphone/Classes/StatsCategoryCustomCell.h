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
    UILabel *fastestCorrectRT;
    UILabel *averageRT;
}

@property (nonatomic) IBOutlet UILabel *name;
@property (nonatomic) IBOutlet UIProgressView *progressBar;
@property (nonatomic) IBOutlet UILabel *percentageLabel;
@property (nonatomic) IBOutlet UILabel *fastestCorrectRT;
@property (nonatomic) IBOutlet UILabel *averageRT;

@end
