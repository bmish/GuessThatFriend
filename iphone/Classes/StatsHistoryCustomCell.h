//
//  StatsHistoryCustomCell.h
//  GuessThatFriend
//
//  Created on 4/30/12.

//

#import <UIKit/UIKit.h>

@interface StatsHistoryCustomCell : UITableViewCell {
    UITextView *text;
    UIImageView *picture;
    UILabel *correctAnswer;
    UILabel *chosenAnswer;
    UILabel *date;
    UILabel *responseTime;
}

@property (nonatomic) IBOutlet UITextView *text;
@property (nonatomic) IBOutlet UIImageView *picture;
@property (nonatomic) IBOutlet UILabel *correctAnswer;
@property (nonatomic) IBOutlet UILabel *chosenAnswer;
@property (nonatomic) IBOutlet UILabel *date;
@property (nonatomic) IBOutlet UILabel *responseTime;

@end
