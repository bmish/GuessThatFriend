//
//  MultipleChoiceQuestion.m
//  GuessThatFriend
//
//  Created by Tianyi Wang on 2/15/12.
//  Copyright 2012 __MyCompanyName__. All rights reserved.
//

#import "MCQuestion.h"
#import "Option.h"

@implementation MCQuestion

@synthesize options;

- (MCQuestion *)initQuestion {
	//TODO: actual implementation
	
	text = [[NSString alloc] initWithString:@"Which of the following four people likes the movie 300?"];
	options = [[NSMutableArray alloc] initWithCapacity:8];
	
	Option *friend;
	friend = [[Option alloc] initWithName:@"Arjan" andImagePath:@"arjan.jpg"];
	[options addObject:friend];
	[friend release];
	friend = [[Option alloc] initWithName:@"Bryan" andImagePath:@"bryan.jpg"];
	[options addObject:friend];
	[friend release];
	friend = [[Option alloc] initWithName:@"Colin" andImagePath:@"colin.jpg"];
	[options addObject:friend];
	[friend release];
	friend = [[Option alloc] initWithName:@"Grace" andImagePath:@"grace.jpg"];
	[options addObject:friend];
	[friend release];
	friend = [[Option alloc] initWithName:@"Jason" andImagePath:@"jason.jpg"];
	[options addObject:friend];
	[friend release];
	friend = [[Option alloc] initWithName:@"Ken" andImagePath:@"ken.jpg"];
	[options addObject:friend];
	[friend release];
	friend = [[Option alloc] initWithName:@"Mike" andImagePath:@"mike.jpg"];
	[options addObject:friend];
	[friend release];
	friend = [[Option alloc] initWithName:@"Tian" andImagePath:@"tian.jpg"];
	[options addObject:friend];
	[friend release];

	return [super init];
}

- (void)dealloc {
	[options release];
	
	[super dealloc];
}

@end
