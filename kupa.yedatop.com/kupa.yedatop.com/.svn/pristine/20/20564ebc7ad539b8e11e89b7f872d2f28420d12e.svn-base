# phpMyAdmin MySQL-Dump
# version 2.5.0
# http://www.phpmyadmin.net/ (download page)
#
# Host: localhost
# Generation Time: Jun 03, 2003 at 09:26 
# Server version: 3.23.54
# PHP Version: 4.2.2
# Database : `GroupOffice`
# --------------------------------------------------------

#
# Table structure for table `acl`
#
# Creation: Feb 11, 2003 at 04:11 
# Last update: Jun 03, 2003 at 09:15 
#

CREATE TABLE `acl` (
  `acl_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `group_id` int(11) NOT NULL default '0',
  KEY `acl_id` (`acl_id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `acl_items`
#
# Creation: Feb 11, 2003 at 04:11 
# Last update: Jun 03, 2003 at 09:15 
# Last check: Mar 27, 2003 at 03:53 
#

CREATE TABLE `acl_items` (
  `id` int(11) NOT NULL default '0',
  `description` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `bookmarks`
#
# Creation: Feb 11, 2003 at 04:11 
# Last update: May 21, 2003 at 09:13 
#

CREATE TABLE `bookmarks` (
  `id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `URL` varchar(200) NOT NULL default '',
  `name` varchar(50) NOT NULL default '',
  `new_window` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `cms_files`
#
# Creation: May 20, 2003 at 10:10 
# Last update: May 20, 2003 at 10:10 
#

CREATE TABLE `cms_files` (
  `id` int(11) NOT NULL default '0',
  `folder_id` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `content` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `cms_folders`
#
# Creation: May 20, 2003 at 10:18 
# Last update: May 25, 2003 at 10:12 
# Last check: May 22, 2003 at 12:19 
#

CREATE TABLE `cms_folders` (
  `id` int(11) NOT NULL default '0',
  `parent_id` int(11) NOT NULL default '0',
  `name` char(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `cms_sites`
#
# Creation: May 22, 2003 at 12:11 
# Last update: May 23, 2003 at 09:39 
# Last check: May 22, 2003 at 12:11 
#

CREATE TABLE `cms_sites` (
  `id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `root_folder_id` int(11) NOT NULL default '0',
  `acl_id` int(11) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `contact_groups`
#
# Creation: Feb 11, 2003 at 04:11 
# Last update: May 22, 2003 at 03:12 
# Last check: May 22, 2003 at 03:12 
#

CREATE TABLE `contact_groups` (
  `id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `contacts`
#
# Creation: Feb 11, 2003 at 04:11 
# Last update: Jun 02, 2003 at 10:51 
#

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `source_id` int(11) NOT NULL default '0',
  `group_id` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `company` varchar(50) NOT NULL default '',
  `department` varchar(50) NOT NULL default '',
  `function` varchar(50) NOT NULL default '',
  `home_phone` varchar(20) NOT NULL default '',
  `work_phone` varchar(20) NOT NULL default '',
  `fax` varchar(20) NOT NULL default '',
  `cellular` varchar(20) NOT NULL default '',
  `country` varchar(50) NOT NULL default '',
  `state` varchar(50) NOT NULL default '',
  `city` varchar(50) NOT NULL default '',
  `zip` varchar(10) NOT NULL default '',
  `address` varchar(100) NOT NULL default '',
  `homepage` varchar(100) NOT NULL default '',
  `comments` text NOT NULL,
  `work_address` varchar(100) NOT NULL default '',
  `work_zip` varchar(20) NOT NULL default '',
  `work_city` varchar(50) NOT NULL default '',
  `work_state` varchar(50) NOT NULL default '',
  `work_country` varchar(50) NOT NULL default '',
  `work_fax` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `db_sequence`
#
# Creation: Feb 11, 2003 at 04:11 
# Last update: Jun 03, 2003 at 09:14 
#

CREATE TABLE `db_sequence` (
  `seq_name` char(20) NOT NULL default '',
  `nextid` int(11) NOT NULL default '0'
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `emAccounts`
#
# Creation: Jun 03, 2003 at 09:25 
# Last update: Jun 03, 2003 at 09:25 
#

CREATE TABLE `emAccounts` (
  `id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `type` varchar(4) NOT NULL default '',
  `host` varchar(100) NOT NULL default '',
  `port` int(11) NOT NULL default '0',
  `username` varchar(20) NOT NULL default '',
  `password` varchar(20) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `signature` text NOT NULL,
  `standard` tinyint(4) NOT NULL default '0',
  `mbroot` varchar(30) NOT NULL default ''
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `emFilters`
#
# Creation: Feb 11, 2003 at 04:11 
# Last update: Mar 04, 2003 at 02:12 
#

CREATE TABLE `emFilters` (
  `id` int(11) NOT NULL default '0',
  `account_id` int(11) NOT NULL default '0',
  `field` varchar(20) NOT NULL default '0',
  `keyword` varchar(100) NOT NULL default '0',
  `folder` varchar(100) NOT NULL default '0',
  `priority` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `emFolders`
#
# Creation: Feb 11, 2003 at 04:11 
# Last update: Jun 03, 2003 at 09:15 
#

CREATE TABLE `emFolders` (
  `id` int(11) NOT NULL default '0',
  `account_id` int(11) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `type` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `filetypes`
#
# Creation: Feb 17, 2003 at 05:44 
# Last update: Jun 02, 2003 at 09:40 
# Last check: Apr 10, 2003 at 07:50 
#

CREATE TABLE `filetypes` (
  `extension` varchar(10) NOT NULL default '',
  `mime` varchar(50) NOT NULL default '',
  `friendly` varchar(50) NOT NULL default '',
  `image` blob NOT NULL,
  PRIMARY KEY  (`extension`),
  KEY `extension` (`extension`)
) TYPE=MyISAM COMMENT='filetypes';
# --------------------------------------------------------

#
# Table structure for table `fsShares`
#
# Creation: Mar 21, 2003 at 09:14 
# Last update: May 30, 2003 at 03:11 
# Last check: Mar 21, 2003 at 09:14 
#

CREATE TABLE `fsShares` (
  `user_id` int(11) NOT NULL default '0',
  `path` varchar(200) NOT NULL default '',
  `acl_read` int(11) NOT NULL default '0',
  `acl_write` int(11) NOT NULL default '0',
  KEY `path` (`path`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `groups`
#
# Creation: Feb 11, 2003 at 04:11 
# Last update: May 17, 2003 at 04:45 
#

CREATE TABLE `groups` (
  `id` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `modules`
#
# Creation: Feb 12, 2003 at 03:50 
# Last update: May 29, 2003 at 09:58 
# Last check: Feb 12, 2003 at 03:50 
#

CREATE TABLE `modules` (
  `id` varchar(20) NOT NULL default '',
  `path` varchar(50) NOT NULL default '',
  `acl_read` int(11) NOT NULL default '0',
  `acl_write` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `name` (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `pmFees`
#
# Creation: Feb 10, 2003 at 09:38 
# Last update: Feb 10, 2003 at 09:38 
#

CREATE TABLE `pmFees` (
  `id` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `value` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `pmHours`
#
# Creation: Feb 10, 2003 at 09:38 
# Last update: May 24, 2003 at 05:11 
#

CREATE TABLE `pmHours` (
  `id` int(11) NOT NULL default '0',
  `project_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `start_time` int(11) NOT NULL default '0',
  `end_time` int(11) NOT NULL default '0',
  `break_time` int(11) NOT NULL default '0',
  `comments` text NOT NULL,
  `requested` enum('0','1') NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `pmMaterials`
#
# Creation: Feb 10, 2003 at 09:38 
# Last update: Feb 10, 2003 at 09:38 
#

CREATE TABLE `pmMaterials` (
  `id` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `value` double NOT NULL default '0',
  `description` text NOT NULL
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `pmProjects`
#
# Creation: May 17, 2003 at 11:21 
# Last update: May 24, 2003 at 05:10 
#

CREATE TABLE `pmProjects` (
  `id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `acl_read` int(11) NOT NULL default '0',
  `acl_write` int(11) NOT NULL default '0',
  `comments` text NOT NULL,
  `creation_time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `scEvents`
#
# Creation: Apr 20, 2003 at 11:26 
# Last update: May 23, 2003 at 05:16 
#

CREATE TABLE `scEvents` (
  `id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `start_date` date NOT NULL default '0000-00-00',
  `start_hour` tinyint(4) NOT NULL default '0',
  `start_min` tinyint(4) NOT NULL default '0',
  `end_date` date NOT NULL default '0000-00-00',
  `end_hour` tinyint(4) NOT NULL default '0',
  `end_min` tinyint(4) NOT NULL default '0',
  `title` varchar(50) NOT NULL default '',
  `description` text NOT NULL,
  `delete_record` enum('0','1') NOT NULL default '0',
  `monday` enum('0','1') NOT NULL default '0',
  `tuesday` enum('0','1') NOT NULL default '0',
  `wednesday` enum('0','1') NOT NULL default '0',
  `thursday` enum('0','1') NOT NULL default '0',
  `friday` enum('0','1') NOT NULL default '0',
  `saturday` enum('0','1') NOT NULL default '0',
  `sunday` enum('0','1') NOT NULL default '0',
  `month_time` enum('0','1','2','3','4') NOT NULL default '0',
  `notime` enum('0','1') NOT NULL default '0',
  `noend` enum('0','1') NOT NULL default '0',
  `type` varchar(10) NOT NULL default '',
  `starting_date` date NOT NULL default '0000-00-00',
  `ending_date` date NOT NULL default '0000-00-00',
  `location_text` varchar(100) NOT NULL default '',
  `acl_read` int(11) NOT NULL default '0',
  `acl_write` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `scEventsSchedulers`
#
# Creation: Feb 11, 2003 at 04:11 
# Last update: May 23, 2003 at 05:16 
#

CREATE TABLE `scEventsSchedulers` (
  `event_id` int(11) NOT NULL default '0',
  `scheduler_id` int(11) NOT NULL default '0'
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `scParticipants`
#
# Creation: Feb 11, 2003 at 04:11 
# Last update: May 21, 2003 at 08:10 
#

CREATE TABLE `scParticipants` (
  `id` int(11) NOT NULL default '0',
  `event_id` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `status` enum('0','1','2') NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `scSchedulers`
#
# Creation: Apr 26, 2003 at 10:19 
# Last update: May 18, 2003 at 12:13 
#

CREATE TABLE `scSchedulers` (
  `id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '0',
  `acl_read` int(11) NOT NULL default '0',
  `acl_write` int(11) NOT NULL default '0',
  `standard` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `scSubscribed`
#
# Creation: Feb 11, 2003 at 04:11 
# Last update: May 18, 2003 at 12:13 
#

CREATE TABLE `scSubscribed` (
  `user_id` int(11) NOT NULL default '0',
  `scheduler_id` int(11) NOT NULL default '0',
  KEY `user_id` (`user_id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `users`
#
# Creation: Feb 18, 2003 at 02:44 
# Last update: Jun 03, 2003 at 09:15 
# Last check: Feb 18, 2003 at 02:44 
#

CREATE TABLE `users` (
  `id` int(11) NOT NULL default '0',
  `username` varchar(50) NOT NULL default '',
  `password` varchar(20) NOT NULL default '',
  `authcode` varchar(20) NOT NULL default '',
  `question` varchar(100) NOT NULL default '',
  `answer` varchar(100) NOT NULL default '',
  `name` varchar(50) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `company` varchar(50) NOT NULL default '',
  `department` varchar(50) NOT NULL default '',
  `function` varchar(50) NOT NULL default '',
  `home_phone` varchar(20) NOT NULL default '',
  `work_phone` varchar(20) NOT NULL default '',
  `fax` varchar(20) NOT NULL default '',
  `cellular` varchar(20) NOT NULL default '',
  `country` varchar(50) NOT NULL default '',
  `state` varchar(50) NOT NULL default '',
  `city` varchar(50) NOT NULL default '',
  `zip` varchar(10) NOT NULL default '',
  `address` varchar(100) NOT NULL default '',
  `homepage` varchar(100) NOT NULL default '',
  `work_address` varchar(100) NOT NULL default '',
  `work_zip` varchar(10) NOT NULL default '',
  `work_country` varchar(50) NOT NULL default '',
  `work_state` varchar(50) NOT NULL default '',
  `work_city` varchar(50) NOT NULL default '',
  `work_fax` varchar(20) NOT NULL default '',
  `acl_id` int(11) NOT NULL default '0',
  `date_format` varchar(20) NOT NULL default 'd-m-Y H:i',
  `thousands_seperator` char(1) NOT NULL default '.',
  `decimal_seperator` char(1) NOT NULL default ',',
  `mail_client` tinyint(4) NOT NULL default '1',
  `logins` int(11) NOT NULL default '0',
  `lastlogin` int(11) NOT NULL default '0',
  `registration_time` int(11) NOT NULL default '0',
  `samba_user` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `users_groups`
#
# Creation: Feb 11, 2003 at 04:11 
# Last update: Jun 03, 2003 at 09:15 
#

CREATE TABLE `users_groups` (
  `group_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0'
) TYPE=MyISAM;

