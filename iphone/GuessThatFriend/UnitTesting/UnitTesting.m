//
//  UnitTesting.m
//  UnitTesting
//
//  Created on 3/12/12.
//  Copyright (c) 2012. All rights reserved.
//

#import "UnitTesting.h"
#import "QuizManager.h"
#import "GuessThatFriendAppDelegate.h"
#import "QuizSettings.h"

@implementation UnitTesting

@synthesize delegate;
@synthesize facebookToken;

- (void)setUp {
    [super setUp];
    
    // Set-up code here.
    delegate = (GuessThatFriendAppDelegate *) [[UIApplication sharedApplication] delegate];
    facebookToken = delegate.facebook.accessToken;
}

- (void)tearDown {
    // Tear-down code here.
    self.facebookToken = nil;
    self.delegate = nil;
    
    [super tearDown];
}

- (void)testRetrieveSampleQuestionsFromAPI {
    QuizManager *quizManager = [[QuizManager alloc] initWithFBToken:@"" andUseSampleData:YES];
    STAssertTrue(quizManager.questionArray.count == 4, @"Number of sample questions was not four as expected.");
}

- (void)testRetrieveQuestionsFromAPIWithEmptyFBToken {
    QuizManager *quizManager = [[QuizManager alloc] initWithFBToken:@"" andUseSampleData:NO];
    STAssertTrue(quizManager.questionArray.count == 4, @"Number of questions was not four as expected.");
}

- (void)testRetrieveQuestionsFromAPIWithBadFBToken {
    QuizManager *quizManager = [[QuizManager alloc] initWithFBToken:@"badtoken" andUseSampleData:NO];
    STAssertTrue(quizManager.questionArray.count == 4, @"Number of questions was not four as expected.");
}

- (void)testRetrieveQuestionsFromAPIWithValidFBToken {
    QuizManager *quizManager = [[QuizManager alloc] initWithFBToken:facebookToken andUseSampleData:NO];
    STAssertTrue(quizManager.questionArray.count == QUESTION_COUNT, @"Number of questions was not %i as expected.", QUESTION_COUNT);
}

@end
