'''
Created on Feb 8, 2012

@author: driscol2 and robert17
'''
import unittest
import json
import urllib2
import filecmp
import os

APIurl = "http://guessthatfriend.jasonsze.com/"

def loadGetQuizJson(questionCount, optionCount, catID):
    jsonObject = urllib2.urlopen(APIurl+"api/?cmd=getQuestions&facebookAccessToken=xxx&questionCount="+str(questionCount)+"&optionCount="+str(optionCount)+"&categoryId="+str(catID));
    testFile = "tmp.json"
    tmpJson = open(testFile, "w");
    tmpJson.write(jsonObject.read());
    tmpJson.close()
    tmpJson = open(testFile, "r");
    parsedData = json.load(tmpJson);
    tmpJson.close()
    os.remove('tmp.json')
    return parsedData;

def loadSubmitQuizJson(questionNum):
    jsonObject = urllib2.urlopen(APIurl+"api/?cmd=submitQuestions&facebookAccessToken=xxx&optionIdOfQuestion"+str(questionNum)+"=12");
    parsedData = json.load(jsonObject);
    return parsedData;
    
def testGetQuiz(self, questionCount, optionCount, catID):
    parsedData=loadGetQuizJson(questionCount, optionCount, catID)
    date = parsedData["date"]
    questions = parsedData["questions"];
    qCount = 0;
    for question in questions:
	category=question["category"];
        categoryID=category["categoryId"];
        self.assertEqual(categoryID, catID);
        qCount = qCount+1;
        oCount = 0;
        options = question["options"];
        for option in options:
            oCount = oCount + 1;
        self.assertEqual(oCount, optionCount);
    self.assertEqual(qCount, questionCount);

def testSubmitQuiz(self, questionNum):
    parsedData = loadSubmitQuizJson(questionNum)
    qNum = parsedData["questions"][0]
    self.assertEquals(questionNum, qNum)
            
class Test(unittest.TestCase):
      
    def testGetQuiz1(self):
        testGetQuiz(self,1,2,2)
        
    def testGetQuiz2(self):
        testGetQuiz(self,5,4,3)

    def testSubmitQuiz1(self):
        testSubmitQuiz(self, 11)
        
    def testCategories(self):
        jsonObject1 = urllib2.urlopen(APIurl+"api/?cmd=getCategories");
        newFile = open('tmp.json','w')
        newFile.write(jsonObject1.read());
        newFile.close()
        
        diff = filecmp.cmp('tmp.json', 'categories.json')
        os.remove('tmp.json')
        
        self.assertTrue(diff)


if __name__ == "__main__":
    logFile = 'log_file.txt'
    f = open(logFile, "w")
    runner = unittest.TextTestRunner(f)
    unittest.main(testRunner=runner)
