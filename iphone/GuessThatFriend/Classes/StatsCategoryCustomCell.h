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

@property (nonatomic, retain) IBOutlet UILabel *name;
@property (nonatomic, retain) IBOutlet UIProgressView *progressBar;
@property (nonatomic, retain) IBOutlet UILabel *percentageLabel;
@property (nonatomic, retain) IBOutlet UILabel *fastestCorrectRT;
@property (nonatomic, retain) IBOutlet UILabel *averageRT;

@end
