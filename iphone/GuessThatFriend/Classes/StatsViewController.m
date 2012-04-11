//
//  StatsViewController.m
//  GuessThatFriend
//
//  Created by Arjan Singh Nirh on 4/9/12.
//  Copyright (c) 2012 University of Illinois at Urbana-Champaign. All rights reserved.
//

#import "StatsViewController.h"
#import "StatsCustomCell.h"
#import "GuessThatFriendAppDelegate.h"

#define SAMPLE_GET_STATISTICS_ADDR   "http://guessthatfriend.jasonsze.com/api/examples/json/getStatistics.json"
#define BASE_URL_ADDR               "http://guessthatfriend.jasonsze.com/api/"

@implementation StatsViewController

@synthesize friendsList;
@synthesize friendsTable;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
        friendsList = [[NSMutableArray alloc] initWithCapacity:10];
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    // Do any additional setup after loading the view from its nib.
}

- (void)viewDidUnload
{
    [super viewDidUnload];
    self.friendsTable = nil;
}

- (void)dealloc {
    [friendsTable release];
    [friendsList release];
    
    [super dealloc];
}

- (void)viewDidAppear:(BOOL)animated {
    
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    
    delegate.navController.navigationBar.topItem.title = @"Statistics";
    
    delegate.navController.navigationBar.topItem.hidesBackButton = YES;
    
    UIBarButtonItem *leftCornerButton = [[UIBarButtonItem alloc] 
                                         initWithTitle:@"Back" 
                                         style:UIBarButtonItemStylePlain target:self 
                                         action:@selector(backItemPressed:)];
    delegate.navController.navigationBar.topItem.leftBarButtonItem = leftCornerButton;
    [leftCornerButton release];
    
    [super viewDidAppear:animated];
}

- (IBAction)backItemPressed:(id)sender {
    GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate *)[[UIApplication sharedApplication] delegate];
    [delegate.navController popViewControllerAnimated:YES];
}

#pragma mark -
#pragma mark Table View Data Source Methods

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
	return [self.friendsList count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
	
	StatsCustomCell *cell = (StatsCustomCell *)[tableView dequeueReusableCellWithIdentifier:@"StatsCustomCellIdentifier"];
	
	if(cell == nil) {
		NSArray *nib = [[NSBundle mainBundle] loadNibNamed:@"StatsCustomCell" owner:self options:nil];
		cell = [nib objectAtIndex:0];
	}
	
	NSUInteger row = [indexPath row];
	
	/*Option *option = [optionsList objectAtIndex:row];
	cell.picture.image = option.subject.picture;
	cell.name.text = option.subject.name;
	*/
     //cell = option.subject.link;
	//	cell.name.text = option.subject.facebookId;
	return cell;
}



- (void)requestStatisticsFromServer:(BOOL)useSampleData{
    
    // Create GET request.
    NSMutableString *getRequest;
    
    if (useSampleData) {    // Retrieve sample data.
        getRequest = [NSMutableString stringWithString:@SAMPLE_GET_STATISTICS_ADDR];
    } else { 
        // Make a real request.
        
        getRequest = [NSMutableString stringWithString:@BASE_URL_ADDR];
        [getRequest appendString:@"?cmd=getStatistics"];
        
        GuessThatFriendAppDelegate *delegate = (GuessThatFriendAppDelegate*)
                                                        [[UIApplication sharedApplication] delegate];
        
        [getRequest appendFormat:@"&facebookAccessToken=%@", delegate.facebook.accessToken];
        [getRequest appendFormat:@"&type=listAnswerCounts"];
    }
    
    NSLog(@"STATS Request string: %@", getRequest);
    
    // Send the GET request to the server.
    NSURLRequest *request = [NSURLRequest requestWithURL:[NSURL URLWithString:getRequest]];
    
    NSData *response = [NSURLConnection sendSynchronousRequest:request returningResponse:nil error:nil];
    NSString *responseString = [[NSString alloc] initWithData:response encoding:NSUTF8StringEncoding];
    
    NSLog(@"STATS RESPONSE STRING: %@ \n",responseString);
    
    // Initialize array of questions from the server's response.
    [self createStatsFromServerResponse:responseString];
    
    [responseString release];
}

- (void)createStatsFromServerResponse:(NSString *)response {
   
    /*
    numQuestions = 0;
	numCorrect = 0;
    
    // Parse the JSON response.
    NSDictionary *responseDictionary = [response objectFromJSONString];
    
    //Check if valid JSON response
    if(responseDictionary==nil){
        [self requestQuestionsFromServer];                  //Just ask for more questions
        return;
    }
    
    NSArray *questionsArray = [responseDictionary objectForKey:@"questions"];
    
    NSEnumerator *questionEnumerator = [questionsArray objectEnumerator];
    NSDictionary *curQuestion;
    
    //Go through all QUESTIONS
    while (curQuestion = [questionEnumerator nextObject]) {
        NSString *text = [curQuestion objectForKey:@"text"];
        NSArray *options = [curQuestion objectForKey:@"options"];
        NSDictionary *correctSubject = [curQuestion objectForKey:@"correctSubject"];
        NSString *correctFbId = [correctSubject objectForKey:@"facebookId"];
        NSDictionary *topicDict = [curQuestion objectForKey:@"topicSubject"];
        NSString *topicPicture = [topicDict objectForKey:@"picture"];
        
        int questionId = [[curQuestion objectForKey:@"questionId"] intValue]; 
        
        NSEnumerator *optionEnumerator = [options objectEnumerator];
        NSDictionary *curOption;
        NSMutableArray *optionArray = [[NSMutableArray alloc] initWithCapacity:8];
        
        //Go through all OPTIONS for current Question
        while (curOption = [optionEnumerator nextObject]) {
            NSDictionary *subjectDict = [curOption objectForKey:@"topicSubject"];
            NSString *subjectName = [subjectDict objectForKey:@"name"];
            NSString *subjectImageURL = [subjectDict objectForKey:@"picture"];
            NSString *subjectFacebookId = [subjectDict objectForKey:@"facebookId"];
            NSString *subjectLink = [subjectDict objectForKey:@"link"];
            
            Option *option = [[Option alloc] initWithName:subjectName andImagePath:subjectImageURL andFacebookId:subjectFacebookId andLink:subjectLink];
            [optionArray addObject:option];
            [option release];
        }
        
        Question *question = [[MCQuestion alloc] initQuestionWithOptions:optionArray];
        question.text = text;
        question.correctFacebookId = correctFbId;
        question.questionId = questionId;
        question.topicImage = topicPicture;
        
        [optionArray release];
        
        [questionArray addObject:question];
        [question release];
        
        numQuestions++;
    }
     */
}

/* Everytime this view will appear, we ask the server for stats jason */
- (void)viewWillAppear:(BOOL)animated{
    
    [self requestStatisticsFromServer:YES];
    [super viewWillAppear:animated];
}

#pragma mark -
#pragma mark Table Delegate Methods

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	
    // don't do anything if row is selected.
    return;
}

- (CGFloat) tableView: (UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
	return 55;		// 55 is the fixed height of each cell, it is set in the nib.
}

@end
