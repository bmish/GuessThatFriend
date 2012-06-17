//
//  UnitTesting.m
//  UnitTesting
//
//  Created on 3/12/12.
//
//

#import "UnitTesting.h"
#import "QuizManager.h"
#import "GuessThatFriendAppDelegate.h"
#import "QuizSettings.h"
#import "StatsFriendsViewController.h"
#import "StatsCategoriesViewController.h"
#import "StatsHistoryViewController.h"

@implementation UnitTesting

- (void)setUp {
    [super setUp];
    
    // Set-up code here.
    delegate = (GuessThatFriendAppDelegate *) [[UIApplication sharedApplication] delegate];
    facebookToken = delegate.facebook.accessToken;
}

- (void)tearDown {
    // Tear-down code here.
    
    [super tearDown];
}

#pragma mark -
#pragma mark QuizManager Tests

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

// Simply initialize the quiz manager, this should give us 'QUESTION_COUNT' number of questions.
- (void)testRetrieveQuestionsFromAPIWithValidFBToken {
    if ([delegate.facebook isSessionValid] == NO) {
        // can't test w/o valid token.
        return;
    }

    QuizManager *quizManager = [[QuizManager alloc] initWithFBToken:facebookToken andUseSampleData:NO];
    
    // wait until prefetching is done.
    while (quizManager.threadRunning) {
    }
    
    STAssertTrue(quizManager.questionArray.count == QUESTION_COUNT, @"Number of questions was %i, not %i as expected.", 
                 quizManager.questionArray.count, QUESTION_COUNT);
}

// Simply initialize the quiz manager, this should give us 'QUESTION_COUNT' number of questions.
// Then ask for more question once, this should double the number of questions.
- (void)testRetrieveQuestionsFromAPIWithValidFBToken2 {
    if ([delegate.facebook isSessionValid] == NO) {
        // can't test w/o valid token.
        return;
    }
    
    QuizManager *quizManager = [[QuizManager alloc] initWithFBToken:facebookToken andUseSampleData:NO];
    
    // wait until prefetching is done.
    while (quizManager.threadRunning) {
    }
    
    STAssertTrue(quizManager.questionArray.count == QUESTION_COUNT, @"Number of questions was %i, not %i as expected.", 
                 quizManager.questionArray.count, QUESTION_COUNT);
    
    [quizManager requestQuestionsFromServer];
    
    // wait until prefetching is done.
    while (quizManager.threadRunning) {
    }
    
    STAssertTrue(quizManager.questionArray.count == QUESTION_COUNT * 2, @"Number of questions was %i, not %i as expected.", 
                 quizManager.questionArray.count, QUESTION_COUNT * 2);
}

// IPhone app would request for more questions long before the questions run out, this test case tests that.
- (void)testPrefetchingQuestions {
    QuizManager *quizManager = [[QuizManager alloc] initWithFBToken:facebookToken andUseSampleData:NO];
    [quizManager requestQuestionsFromServer];
    
    // Now, there should be QUESTION_COUNT * 2 questions in the array, we take out (QUESTION_COUNT * 2) of them.
    for (int i = 0; i < QUESTION_COUNT * 2 - 1; i++) {
        Question *question = [quizManager getNextQuestion];
        STAssertTrue(question != nil, @"Question was not valid as expected");
    }
    
    // wait until prefetching is done.
    while (quizManager.threadRunning) {
    }
    
    // there should be more than one questions in the question array due to prefetching.
    STAssertTrue(quizManager.questionArray.count > 1, @"Number of questions was not greater than 1 as expected.");
}

#pragma mark -
#pragma mark Retrieving Statistics Tests

- (void)testRetrieveSampleFriendsStatsFromAPI {
    StatsFriendsViewController *viewController = [[StatsFriendsViewController alloc] init];
    viewController.list = [[NSMutableArray alloc] initWithCapacity:10];
    
    STAssertTrue(viewController.list.count == 0, @"Number of stats was not zero as expected.");
    [viewController requestStatisticsFromServer:YES];
    
    // wait until stats is returned.
    while (viewController.threadIsRunning) {
    }
    
    STAssertTrue(viewController.list.count == 11, @"Number of stats was %i, not 11 as expected.", viewController.list.count);
}

- (void)testRetrieveSampleCategoriesStatsFromAPI {
    StatsCategoriesViewController *viewController = [[StatsCategoriesViewController alloc] init];
    viewController.list = [[NSMutableArray alloc] initWithCapacity:10];

    STAssertTrue(viewController.list.count == 0, @"Number of stats was not zero as expected.");
    [viewController requestStatisticsFromServer:YES];
    
    // wait until stats is returned.
    while (viewController.threadIsRunning) {
    }
    
    STAssertTrue(viewController.list.count == 1, @"Number of stats was %i, not 1 as expected.", viewController.list.count);
}

- (void)testRetrieveSampleHistoryStatsFromAPI {
    StatsHistoryViewController *viewController = [[StatsHistoryViewController alloc] init];
    viewController.list = [[NSMutableArray alloc] initWithCapacity:10];

    STAssertTrue(viewController.list.count == 0, @"Number of stats was not zero as expected.");
    [viewController requestStatisticsFromServer:YES];
    
    // wait until stats is returned.
    while (viewController.threadIsRunning) {
    }
    
    STAssertTrue(viewController.list.count == 1, @"Number of stats was %i, not 1 as expected.", viewController.list.count);
}

- (void)testRetrieveFriendsStatsFromAPI {
    StatsFriendsViewController *viewController = [[StatsFriendsViewController alloc] init];
    viewController.list = [[NSMutableArray alloc] initWithCapacity:10];
    
    STAssertTrue(viewController.list.count == 0, @"Number of stats was not zero as expected.");
    [viewController requestStatisticsFromServer:NO];
    
    // wait until stats is returned.
    while (viewController.threadIsRunning) {
    }
    
    STAssertTrue(viewController.list.count >= 0, @"Number of stats was not >= 0 as expected.", viewController.list.count);
}

- (void)testRetrieveCategoriesStatsFromAPI {
    StatsCategoriesViewController *viewController = [[StatsCategoriesViewController alloc] init];
    viewController.list = [[NSMutableArray alloc] initWithCapacity:10];
    
    STAssertTrue(viewController.list.count == 0, @"Number of stats was not zero as expected.");
    [viewController requestStatisticsFromServer:NO];
    
    // wait until stats is returned.
    while (viewController.threadIsRunning) {
    }
    
    STAssertTrue(viewController.list.count >= 0, @"Number of stats was not >= 0 as expected.", viewController.list.count);
}

- (void)testRetrieveHistoryStatsFromAPI {
    StatsHistoryViewController *viewController = [[StatsHistoryViewController alloc] init];
    viewController.list = [[NSMutableArray alloc] initWithCapacity:10];
    
    STAssertTrue(viewController.list.count == 0, @"Number of stats was not zero as expected.");
    [viewController requestStatisticsFromServer:NO];
    
    // wait until stats is returned.
    while (viewController.threadIsRunning) {
    }
    
    STAssertTrue(viewController.list.count >= 0, @"Number of stats was not >= 0 as expected.", viewController.list.count);
}

#pragma mark -
#pragma mark Test Facebook logout

- (void)testFacebookLogout {
    if ([delegate.facebook isSessionValid]) {
        [delegate fbLogout];
        
        STAssertTrue([delegate.facebook isSessionValid] == NO, @"Session is not invalid as expected.");
    }
}

@end
