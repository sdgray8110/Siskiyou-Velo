-- phpMyAdmin SQL Dump
-- version 2.11.9.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 11, 2009 at 11:06 PM
-- Server version: 5.0.81
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `gray8110_etna`
--

-- --------------------------------------------------------

--
-- Table structure for table `xml_rss`
--

CREATE TABLE IF NOT EXISTS `xml_rss` (
  `ID` tinyint(4) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `creator` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `meta` varchar(255) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

--
-- Dumping data for table `xml_rss`
--

INSERT INTO `xml_rss` (`ID`, `title`, `creator`, `image`, `location`, `identifier`, `meta`) VALUES
(42, 'Marshall Amps with Joe Bonamassa', 'Winter NAMM 2008', 'http://www.harmony-central.com/theater/video/wnamm08/wnamm08_marshalljoe_thumb1.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/TradeShows/wnamm08/wnamm08_Marshall_JoeBonamassa', 'rtmp'),
(41, 'Heil Sound - with Slash', 'Winter NAMM 2008', 'http://www.harmony-central.com/theater/video/wnamm08/wnamm08_heilslash_thumb1.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/TradeShows/wnamm08/wnamm08_HeilSound_Slash', 'rtmp'),
(39, 'Zildjian', 'Winter NAMM 2009', 'http://www.harmony-central.com/theater/video/wnamm09/namm2009.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/WNAMM_2009/WNAMM09_Zildjian', 'rtmp'),
(40, 'Artists at Winter NAMM 2008', 'Winter NAMM 2008', 'http://www.harmony-central.com/theater/video/preview/Artist_at_Namm08.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/Preview/Artist_at_Namm08', 'rtmp'),
(38, 'Taylor Guitars - Doyle Dykes', 'Winter NAMM 2009', 'http://www.harmony-central.com/theater/video/wnamm09/namm2009.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/WNAMM_2009/WNAMM09_TaylorGuitars_DoyleDykes', 'rtmp'),
(37, 'Paul Reed Smith Amps', 'Winter NAMM 2009', 'http://www.harmony-central.com/theater/video/wnamm09/namm2009.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/WNAMM_2009/WNAMM09_PRS_Amps', 'rtmp'),
(36, 'Paul Reed Smith Acoustic Guitars', 'Winter NAMM 2009', 'http://www.harmony-central.com/theater/video/wnamm09/namm2009.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/WNAMM_2009/WNAMM09_PRS_Acoustics', 'rtmp'),
(35, 'Parker Fly - Adrian Belew', 'Winter NAMM 2009', 'http://www.harmony-central.com/theater/video/wnamm09/namm2009.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/WNAMM_2009/WNAMM09_ParkerFly_AdrianBelew', 'rtmp'),
(34, 'Paiste', 'Winter NAMM 2009', 'http://www.harmony-central.com/theater/video/wnamm09/namm2009.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/WNAMM_2009/WNAMM09_Paiste', 'rtmp'),
(33, 'Martin Guitars - New for 2009', 'Winter NAMM 2009', 'http://www.harmony-central.com/theater/video/wnamm09/namm2009.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/WNAMM_2009/WNAMM09_MartinGuitars', 'rtmp'),
(32, 'Marshall Amps - John5', 'Winter NAMM 2009', 'http://www.harmony-central.com/theater/video/wnamm09/namm2009.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/WNAMM_2009/WNAMM09_Marshall_John5', 'rtmp'),
(31, 'Ludwig', 'Winter NAMM 2009', 'http://www.harmony-central.com/theater/video/wnamm09/namm2009.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/WNAMM_2009/WNAMM09_LUDWIG', 'rtmp'),
(30, 'Eric Johnson Rosewood Neck Signature Startocaster', 'Winter NAMM 2009', 'http://www.harmony-central.com/theater/video/wnamm09/namm2009.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/WNAMM_2009/WNAMM09_EricJohnson_RSWDStrat', 'rtmp'),
(29, 'Ibanez - Steve Vai Hard Tail Jem', 'Winter NAMM 2009', 'http://www.harmony-central.com/theater/video/wnamm09/namm2009.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/WNAMM_2009/WNAMM09_Ibanez_SteveVai', 'rtmp'),
(27, 'Day 1 Highlight', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_day_1_highlight', 'rtmp'),
(28, 'BC Rich - Kerry King', 'Winter NAMM 2009', 'http://www.harmony-central.com/theater/video/wnamm09/namm2009.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/WNAMM_2009/WNAMM09_BCRich_KerryKing', 'rtmp'),
(26, 'Day 2 Highlights', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_day_2_highlight', 'rtmp'),
(25, 'Pigtronix: Philosopher''s Tone', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_Pigtronix_PhilosophersTone', 'rtmp'),
(24, 'Zildjian', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_ZILDJIAN', 'rtmp'),
(23, 'Yamaha', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_YAMAHA', 'rtmp'),
(22, 'Tama', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_TAMA', 'rtmp'),
(21, 'Paiste', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_PAISTE', 'rtmp'),
(20, 'Meinl', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_MEINL', 'rtmp'),
(19, 'Mapex', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_MAPEX', 'rtmp'),
(18, 'Ludwig', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_LUDWIG', 'rtmp'),
(17, 'US Music Corp', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_US_MUSIC_CORP', 'rtmp'),
(16, 'Randall', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_RANDALL', 'rtmp'),
(15, 'Heil', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_HEIL', 'rtmp'),
(14, 'Dan Tyminski', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_DAN_TYMINSKI', 'rtmp'),
(13, 'Daisy Rock', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_DAISY_ROCK', 'rtmp'),
(12, 'American DJ', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_AMERICAN_DJ', 'rtmp'),
(11, 'American Audio', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_AMERICAN_AUDIO', 'rtmp'),
(10, 'Electro Harmonix Pedals', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_Electro-Harmonix_Pedals', 'rtmp'),
(9, 'BreezSong JamHub', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_BreezSong_JamHub', 'rtmp'),
(8, 'Yamaha Guitars', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_YAMAHA_GUITARS', 'rtmp'),
(7, 'Ty Tabor', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_TY_TABOR', 'rtmp'),
(6, 'Ibanez', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_IBANEZ', 'rtmp'),
(5, 'GnL Guitars', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_GnL_GUITARS', 'rtmp'),
(4, 'Gallien Krueger', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_GALLIEN_KRUEGER', 'rtmp'),
(3, 'DAddario', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_DADDARIO', 'rtmp'),
(2, 'Breedlove', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_BREEDLOVE', 'rtmp'),
(1, 'Alfred Publishing', 'SNAMM 09', 'http://www.harmony-central.com/theater/video/gear/snamm09.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/SNAMM_2009/SNAMM09_ALFRED_PUBLISHING', 'rtmp'),
(43, 'Marshall Amps with Paul Gilbert', 'Winter NAMM 2008', 'http://www.harmony-central.com/theater/video/wnamm08/wnamm08_marshall_thumb1.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/TradeShows/wnamm08/wnamm08_marshall_paulgibert', 'rtmp'),
(44, 'Vox - Introduction Joe Satriani Pedals', 'Winter NAMM 2008', 'http://www.harmony-central.com/theater/video/wnamm08/wnamm08_voxpedals_thumb1.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/TradeShows/wnamm08/wnamm08_VOX_SatrianiPedals', 'rtmp'),
(45, 'Washburn Guitars with Paul Stanley', 'Winter NAMM 2008', 'http://www.harmony-central.com/theater/video/wnamm08/wnamm08_washburn_thumb1.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/TradeShows/wnamm08/wnamm08_washburn_paulstanley', 'rtmp'),
(46, 'Zildjian with Jason Bonham', 'Winter NAMM 2008', 'http://www.harmony-central.com/theater/video/wnamm08/wnamm08_zildjian_thumb1.jpg', 'rtmpt://cp29398.edgefcs.net/ondemand', '/hc/TradeShows/wnamm08/wnamm08_Zildjian_JasonBonham', 'rtmp');
