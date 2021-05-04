SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Estrutura da tabela `class`
--

CREATE TABLE IF NOT EXISTS `class` (
  `class_no` VARCHAR(10) NOT NULL,
  `color_id` int(11) NOT NULL,
  PRIMARY KEY (`class_no`),
  INDEX (`color_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

INSERT INTO `class` (`class_no`, `color_id`) VALUES
('CS348', 0),
('COM217', 4),
('MA341', 2),
('EAPS375', 3),
('STAT514', 6),
('STAT417', 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `type`
-- This also indicates the importance of the event
--

CREATE TABLE IF NOT EXISTS `type` (
  `importance` int(11) NOT NULL,
  `gradePercent` text NOT NULL,
  `adjustPercent` decimal(3,1) NOT NULL,
  PRIMARY KEY (`importance`),
  INDEX (`gradePercent`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

INSERT INTO `type` (`importance`, `gradePercent`, `adjustPercent`) VALUES
(0, '0-10%', 60.0),
(1, '11-20%', 0.0),
(2, '21-30%', -60.0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `color`
--

CREATE TABLE IF NOT EXISTS `color` (
  `color_id` int(11) NOT NULL,
  `colorname` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`color_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

INSERT INTO `color` (`color_id`, `colorname`) VALUES
(0, '#8899cc'),
(1, '#c8a2c8'),
(2, '#89cff0'),
(3, '#92ba75'),
(4, '#ffa351'),
(5, '#ffd1dc'),
(6, '#fdfd96');



-- --------------------------------------------------------
--
-- Estrutura da tabela `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_no` VARCHAR(10) NOT NULL,
  `title` text NOT NULL,
  `importance` int(11) NOT NULL,
  `color` VARCHAR(10) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY (`event_id`),
  INDEX (`start`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


INSERT INTO `events` (`class_no`, `title`, `importance`, `color`, `start`, `end`, `note`) VALUES
('CS348', 'Mid2', 1, '#8899cc', '2021-04-30 12:00', '2021-05-01 15:00', '');
INSERT INTO `events` (`class_no`, `title`, `importance`, `color`, `start`, `end`, `note`) VALUES
('CS348', 'Project', 1, '#8899cc', '2021-05-05 09:30', '2021-05-05 23:59', 'Group Project');







