//
//  MultipleChoiceQuestion.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import "MultipleChoiceQuestion.h"
#import "Friend.h"

@implementation MultipleChoiceQuestion

@synthesize question;
@synthesize friends;

- (MultipleChoiceQuestion *)initQuestion {
	//TODO: actual implementation
	
	question = [[NSString alloc] initWithString:@"Which of the following four people likes the movie 300?"];
	friends = [[NSMutableArray alloc] initWithCapacity:8];
	
	Friend *friend;
	friend = [[Friend alloc] initWithName:@"Arjan" andImagePath:@"arjan.jpg"];
	[friends addObject:friend];
	[friend release];
	friend = [[Friend alloc] initWithName:@"Bryan" andImagePath:@"bryan.jpg"];
	[friends addObject:friend];
	[friend release];
	friend = [[Friend alloc] initWithName:@"Colin" andImagePath:@"colin.jpg"];
	[friends addObject:friend];
	[friend release];
	friend = [[Friend alloc] initWithName:@"Grace" andImagePath:@"grace.jpg"];
	[friends addObject:friend];
	[friend release];
	friend = [[Friend alloc] initWithName:@"Jason" andImagePath:@"jason.jpg"];
	[friends addObject:friend];
	[friend release];
	friend = [[Friend alloc] initWithName:@"Ken" andImagePath:@"ken.jpg"];
	[friends addObject:friend];
	[friend release];
	friend = [[Friend alloc] initWithName:@"Mike" andImagePath:@"mike.jpg"];
	[friends addObject:friend];
	[friend release];
	friend = [[Friend alloc] initWithName:@"Tian" andImagePath:@"tian.jpg"];
	[friends addObject:friend];
	[friend release];
	
	return [super init];
}

- (void)dealloc {
	[question release];
	[friends release];
	
	[super dealloc];
}

@end
