SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE DATABASE `GuessThatFriend` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `GuessThatFriend`;

CREATE TABLE IF NOT EXISTS `categories` (
  `categoryId` int(11) NOT NULL AUTO_INCREMENT,
  `facebookName` varchar(50) NOT NULL,
  `prettyName` varchar(50) NOT NULL,
  PRIMARY KEY (`categoryId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `errors` (
  `errorId` int(11) NOT NULL AUTO_INCREMENT,
  `msg` varchar(250) NOT NULL,
  `occurredAt` datetime NOT NULL,
  `facebookId` varchar(250) NOT NULL,
  PRIMARY KEY (`errorId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `facebookAPICache` (
  `request` varchar(50) NOT NULL,
  `response` text NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`request`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `options` (
  `optionId` int(11) NOT NULL AUTO_INCREMENT,
  `questionId` int(11) NOT NULL,
  `topicFacebookId` varchar(250) NOT NULL,
  PRIMARY KEY (`optionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `randomPages` (
  `facebookId` varchar(250) NOT NULL,
  `name` varchar(250) NOT NULL,
  `categoryFacebookName` varchar(250) NOT NULL,
  PRIMARY KEY (`facebookId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `questions` (
  `questionId` int(11) NOT NULL AUTO_INCREMENT,
  `categoryId` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `ownerFacebookId` varchar(250) NOT NULL,
  `topicFacebookId` varchar(250) NOT NULL,
  `correctFacebookId` varchar(250) NOT NULL,
  `chosenFacebookId` varchar(250) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `answeredAt` datetime NOT NULL,
  `responseTime` int(11) NOT NULL,
  `skipped` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`questionId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `users` (
  `facebookId` varchar(250) NOT NULL,
  `joinedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastVisitedAt` datetime NOT NULL,
  PRIMARY KEY (`facebookId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
