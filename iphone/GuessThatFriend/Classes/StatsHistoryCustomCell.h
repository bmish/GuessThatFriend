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

@property (nonatomic, retain) IBOutlet UITextView *text;
@property (nonatomic, retain) IBOutlet UIImageView *picture;
@property (nonatomic, retain) IBOutlet UILabel *correctAnswer;
@property (nonatomic, retain) IBOutlet UILabel *chosenAnswer;
@property (nonatomic, retain) IBOutlet UILabel *date;
@property (nonatomic, retain) IBOutlet UILabel *responseTime;

@end
