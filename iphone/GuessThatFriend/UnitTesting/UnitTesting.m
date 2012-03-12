//
//  UnitTesting.m
//  UnitTesting
//
//  Created by Bryan Mishkin on 3/12/12.
//  Copyright (c) 2012. All rights reserved.
//

#import "UnitTesting.h"
#import "QuizManager.h"

@implementation UnitTesting

- (void)setUp
{
    [super setUp];
    
    // Set-up code here.
}

- (void)tearDown
{
    // Tear-down code here.
    
    [super tearDown];
}

- (void)testRetrieveSampleQuestionsFromAPI
{
    QuizManager *quizManager = [[QuizManager alloc] initWithQuizSettings:nil andFBToken:@""];
    STAssertTrue(quizManager.questionArray.count == 1, @"Number of sample questions was not one as expected.");
}

@end
