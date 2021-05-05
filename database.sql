SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

DROP TABLE IF EXISTS class;
DROP TABLE IF EXISTS type;
DROP TABLE IF EXISTS color;
DROP TABLE IF EXISTS events;
DROP PROCEDURE IF EXISTS Classes;
DROP PROCEDURE IF EXISTS Types;
DROP PROCEDURE IF EXISTS courseColor;
DROP PROCEDURE IF EXISTS delEvent;
DROP PROCEDURE IF EXISTS insEvent;
DROP PROCEDURE IF EXISTS upClassColor;
DROP PROCEDURE IF EXISTS upEvent;
DROP PROCEDURE IF EXISTS upEventTime;
DROP PROCEDURE IF EXISTS upEventColor;


-- --------------------------------------------------------
--                     Establish Table                   --
-- --------------------------------------------------------

-- Create table `class`

CREATE TABLE IF NOT EXISTS `class` (
  `class_no` VARCHAR(10) NOT NULL,
  `color_id` int(11) NOT NULL,
  PRIMARY KEY (`class_no`),
  INDEX (`color_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- Insert value to table `class`

INSERT INTO `class` (`class_no`, `color_id`) VALUES
('CS348', 0),
('COM217', 4),
('MA341', 2),
('EAPS375', 3),
('STAT514', 6),
('STAT417', 5);

-- --------------------------------------------------------

-- Create table `type`
-- This also indicates the importance of the event

CREATE TABLE IF NOT EXISTS `type` (
  `importance` int(11) NOT NULL,
  `gradePercent` VARCHAR(20) NOT NULL,
  `adjustPercent` decimal(3,1) NOT NULL,
  PRIMARY KEY (`importance`),
  INDEX (`gradePercent`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- Insert value to table `type`

INSERT INTO `type` (`importance`, `gradePercent`, `adjustPercent`) VALUES
(0, '0-10%', 60.0),
(1, '11-20%', 0.0),
(2, '21-30%', -60.0);

-- --------------------------------------------------------

-- Create table `color`

CREATE TABLE IF NOT EXISTS `color` (
  `color_id` int(11) NOT NULL,
  `colorname` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`color_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- Insert value to table `color`

INSERT INTO `color` (`color_id`, `colorname`) VALUES
(0, '#8899cc'),
(1, '#c8a2c8'),
(2, '#89cff0'),
(3, '#92ba75'),
(4, '#ffa351'),
(5, '#ffd1dc'),
(6, '#fdfd96');

-- --------------------------------------------------------

-- Create table `events`

CREATE TABLE IF NOT EXISTS `events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_no` VARCHAR(10) NOT NULL,
  `title` text NOT NULL,
  `importance` int(11) NOT NULL,
  `color` VARCHAR(10) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- Insert value to table `events`

INSERT INTO `events` (`class_no`, `title`, `importance`, `color`, `start`, `end`, `note`) VALUES
('CS348', 'Mid2', 1, '#8899cc', '2021-04-30 12:00', '2021-05-01 15:00', '');
INSERT INTO `events` (`class_no`, `title`, `importance`, `color`, `start`, `end`, `note`) VALUES
('CS348', 'Project', 1, '#8899cc', '2021-05-05 09:30', '2021-05-05 23:59', 'Group Project');


-- --------------------------------------------------------
--                    Stored Procedure                   --
-- --------------------------------------------------------

-- not used start
DELIMITER $$
CREATE PROCEDURE `Classes`()
BEGIN
  SELECT * from class ORDER BY class_no ASC;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `Types`()
BEGIN
  select * from type ORDER BY importance ASC;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `courseColor`(IN course CHAR(10))
BEGIN
  SELECT colorname FROM class NATURAL JOIN color WHERE class_no = course;
END$$
DELIMITER ;
-- not used end

DELIMITER $$
CREATE PROCEDURE `delEvent`(IN `id` INT(11))
BEGIN
  DELETE FROM events WHERE event_id = id LIMIT 1;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `insEvent`(IN cla VARCHAR(10), IN ti text, IN imp int(11), IN col VARCHAR(10), IN st datetime, IN en datetime, IN no text)
BEGIN
	INSERT INTO events (class_no, title, importance, color, start, end, note) VALUES  (cla, ti, imp, col, st, en, no);
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `upClassColor`(IN `id` VARCHAR(11), IN `col` int(11))
BEGIN
  UPDATE class SET color_id = col WHERE class_no = id;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `upEvent`(IN `id` INT(11), IN `cla` VARCHAR(11), IN `ti` TEXT, IN `imp` INT(11), IN `co` VARCHAR(10), IN `st` DATETIME, IN `en` DATETIME, IN `no` TEXT)
BEGIN
  UPDATE events SET title=ti, class_no = cla, importance = imp, color = co, start=st, end=en, note = no WHERE event_id=id;
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE `upEventTime`(IN `id` INT(11),IN `st` DATETIME, IN `en` DATETIME)
BEGIN
  UPDATE events SET start=st, end=en WHERE event_id=id;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `upEventColor`(IN `id` VARCHAR(11), IN `col` VARCHAR(10), IN imp int(11))
BEGIN
  UPDATE events SET color = col WHERE class_no = id AND importance = imp;
END$$
DELIMITER ;
