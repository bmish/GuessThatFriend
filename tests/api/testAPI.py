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
    jsonObject = urllib2.urlopen(APIurl+"api/?cmd=getQuiz&questionCount="+str(questionCount)+"&optionCount="+str(optionCount)+"&categoryId="+str(catID));
    parsedData = json.load(jsonObject);
    return parsedData;

def loadSubmitQuizJson(questionNum):
    jsonObject = urllib2.urlopen(APIurl+"api/?cmd=submitQuiz&optionIdOfQuestion"+str(questionNum)+"=");
    parsedData = json.load(jsonObject);
    return parsedData;
    
def testGetQuiz(self, questionCount, optionCount, catID):
    parsedData=loadGetQuizJson(questionCount, optionCount, catID)
    date = parsedData["date"]
    qCount = parsedData["questionCount"]
    optionC = parsedData["optionCount"]
    self.assertEquals(qCount, questionCount)
    self.assertEquals(optionC, optionCount)
    questions = parsedData["questions"];
    for question in questions:
        categoryID=question["categoryId"];
        self.assertEqual(categoryID, str(catID))

def testSubmitQuiz(self, questionNum):
    parsedData = loadSubmitQuizJson(questionNum)
    qNum = parsedData["questions"][0]
    self.assertEquals(questionNum, qNum)
            
class Test(unittest.TestCase):
      
    def testGetQuiz1(self):
        testGetQuiz(self,1,2,2)
        
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
    #import sys;sys.argv = ['', 'Test.testName']
    log_file = "/project/httpd-www/tests/log_file.txt"
    f = open(log_file, 'w')
    runner = unittest.TxtTestRunner(f)
    unittest.main(testRunner=runner)