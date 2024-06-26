SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE avps (
  arg varchar(20) NOT NULL,
  value_s text NOT NULL,
  value_i int(11) NOT NULL DEFAULT '0',
  value_u int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (arg)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE bans (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  added int(11) NOT NULL,
  addedby int(10) unsigned NOT NULL DEFAULT '0',
  `comment` varchar(255) NOT NULL,
  `first` int(11) DEFAULT NULL,
  `last` int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY first_last (`first`,`last`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE blocks (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  userid int(10) unsigned NOT NULL DEFAULT '0',
  blockid int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY userfriend (userid,blockid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE categories (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  image varchar(255) NOT NULL,
  cat_desc varchar(255) NOT NULL DEFAULT 'No Description',
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cleanup (
  clean_id int(10) NOT NULL AUTO_INCREMENT,
  clean_title char(100) NOT NULL DEFAULT '',
  clean_file char(100) NOT NULL DEFAULT '',
  clean_time int(11) NOT NULL DEFAULT '0',
  clean_increment int(11) NOT NULL DEFAULT '0',
  clean_cron_key char(32) NOT NULL DEFAULT '',
  clean_log tinyint(1) NOT NULL DEFAULT '0',
  clean_desc text NOT NULL,
  clean_on tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (clean_id),
  KEY clean_time (clean_time)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cleanup_log (
  clog_id int(10) NOT NULL AUTO_INCREMENT,
  clog_event char(150) NOT NULL DEFAULT '',
  clog_time int(11) NOT NULL DEFAULT '0',
  clog_ip char(16) NOT NULL DEFAULT '0',
  clog_desc text NOT NULL,
  PRIMARY KEY (clog_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE comments (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned NOT NULL DEFAULT '0',
  torrent int(10) unsigned NOT NULL DEFAULT '0',
  added int(11) NOT NULL,
  `text` text NOT NULL,
  ori_text text NOT NULL,
  editedby int(10) unsigned NOT NULL DEFAULT '0',
  editedat int(11) NOT NULL,
  PRIMARY KEY (id),
  KEY `user` (`user`),
  KEY torrent (torrent)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE countries (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  flagpic varchar(50) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE files (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  torrent int(10) unsigned NOT NULL DEFAULT '0',
  filename varchar(255) NOT NULL,
  size bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY torrent (torrent),
  FULLTEXT KEY filename (filename)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE forums (
  sort tinyint(3) unsigned NOT NULL DEFAULT '0',
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  description varchar(200) DEFAULT NULL,
  minclassread tinyint(3) unsigned NOT NULL DEFAULT '0',
  minclasswrite tinyint(3) unsigned NOT NULL DEFAULT '0',
  postcount int(10) unsigned NOT NULL DEFAULT '0',
  topiccount int(10) unsigned NOT NULL DEFAULT '0',
  minclasscreate tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE friends (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  userid int(10) unsigned NOT NULL DEFAULT '0',
  friendid int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY userfriend (userid,friendid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE messages (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  sender int(10) unsigned NOT NULL DEFAULT '0',
  receiver int(10) unsigned NOT NULL DEFAULT '0',
  added int(11) NOT NULL,
  `subject` varchar(30) NOT NULL DEFAULT 'No Subject',
  msg text,
  unread enum('yes','no') NOT NULL DEFAULT 'yes',
  poster bigint(20) unsigned NOT NULL DEFAULT '0',
  location smallint(6) NOT NULL DEFAULT '1',
  saved enum('no','yes') NOT NULL DEFAULT 'no',
  PRIMARY KEY (id),
  KEY receiver (receiver)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE news (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  userid int(11) NOT NULL DEFAULT '0',
  added int(11) NOT NULL,
  body text NOT NULL,
  headline varchar(150) NOT NULL DEFAULT 'TBDEV.NET News',
  PRIMARY KEY (id),
  KEY added (added)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE peers (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  torrent int(10) unsigned NOT NULL DEFAULT '0',
  passkey varchar(32) NOT NULL,
  peer_id varchar(20) COLLATE utf8_bin NOT NULL,
  ip varchar(64) NOT NULL,
  `port` smallint(5) unsigned NOT NULL DEFAULT '0',
  uploaded bigint(20) unsigned NOT NULL DEFAULT '0',
  downloaded bigint(20) unsigned NOT NULL DEFAULT '0',
  to_go bigint(20) unsigned NOT NULL DEFAULT '0',
  seeder enum('yes','no') NOT NULL DEFAULT 'no',
  started int(11) NOT NULL,
  last_action int(11) NOT NULL,
  connectable enum('yes','no') NOT NULL DEFAULT 'yes',
  userid int(10) unsigned NOT NULL DEFAULT '0',
  agent varchar(60) NOT NULL,
  finishedat int(10) unsigned NOT NULL DEFAULT '0',
  downloadoffset bigint(20) unsigned NOT NULL DEFAULT '0',
  uploadoffset bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY torrent_peer_id (torrent,peer_id),
  KEY torrent (torrent),
  KEY torrent_seeder (torrent,seeder),
  KEY last_action (last_action),
  KEY connectable (connectable),
  KEY userid (userid),
  KEY passkey (passkey),
  KEY torrent_connect (torrent,connectable)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE pmboxes (
  id int(11) NOT NULL AUTO_INCREMENT,
  userid int(11) NOT NULL,
  boxnumber tinyint(4) NOT NULL DEFAULT '2',
  `name` varchar(15) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE posts (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  topicid int(10) unsigned NOT NULL DEFAULT '0',
  userid int(10) unsigned NOT NULL DEFAULT '0',
  added int(11) NOT NULL,
  body text,
  editedby int(10) unsigned NOT NULL DEFAULT '0',
  editedat int(11) NOT NULL,
  PRIMARY KEY (id),
  KEY topicid (topicid),
  KEY userid (userid),
  FULLTEXT KEY body (body)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE readposts (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  userid int(10) unsigned NOT NULL DEFAULT '0',
  topicid int(10) unsigned NOT NULL DEFAULT '0',
  lastpostread int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY topicid (topicid)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reputation (
  reputationid int(11) unsigned NOT NULL AUTO_INCREMENT,
  reputation int(10) NOT NULL DEFAULT '0',
  whoadded int(10) NOT NULL DEFAULT '0',
  reason varchar(250) DEFAULT NULL,
  dateadd int(10) NOT NULL DEFAULT '0',
  postid int(10) NOT NULL DEFAULT '0',
  userid mediumint(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (reputationid),
  KEY userid (userid),
  KEY whoadded (whoadded),
  KEY multi (postid,userid),
  KEY dateadd (dateadd)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reputationlevel (
  reputationlevelid int(11) unsigned NOT NULL AUTO_INCREMENT,
  minimumreputation int(10) NOT NULL DEFAULT '0',
  `level` varchar(250) DEFAULT NULL,
  PRIMARY KEY (reputationlevelid),
  KEY reputationlevel (minimumreputation)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE rules (
  id int(11) NOT NULL AUTO_INCREMENT,
  cid int(3) unsigned NOT NULL DEFAULT '0',
  heading varchar(255) NOT NULL DEFAULT '',
  body text NOT NULL,
  ctime int(11) unsigned NOT NULL DEFAULT '0',
  mtime int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY cat_id (cid)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE rules_categories (
  cid int(3) unsigned NOT NULL AUTO_INCREMENT,
  rcat_name varchar(100) NOT NULL DEFAULT '',
  min_class_read int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (cid)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE searchcloud (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  searchedfor varchar(50) NOT NULL,
  howmuch int(10) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY searchedfor (searchedfor)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sitelog (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  added int(11) NOT NULL,
  txt text,
  PRIMARY KEY (id),
  KEY added (added)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE stylesheets (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  uri varchar(255) NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE topics (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  userid int(10) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(40) DEFAULT NULL,
  locked enum('yes','no') NOT NULL DEFAULT 'no',
  forumid int(10) unsigned NOT NULL DEFAULT '0',
  lastpost int(10) unsigned NOT NULL DEFAULT '0',
  sticky enum('yes','no') NOT NULL DEFAULT 'no',
  views int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY userid (userid),
  KEY `subject` (`subject`),
  KEY lastpost (lastpost),
  KEY locked_sticky (locked,sticky),
  KEY forumid (forumid)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE torrents (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  info_hash varchar(40) NOT NULL,
  `name` varchar(255) NOT NULL,
  filename varchar(255) NOT NULL,
  save_as varchar(255) NOT NULL,
  search_text text NOT NULL,
  descr text NOT NULL,
  ori_descr text NOT NULL,
  category int(10) unsigned NOT NULL DEFAULT '0',
  size bigint(20) unsigned NOT NULL DEFAULT '0',
  added int(11) NOT NULL,
  `type` enum('single','multi') NOT NULL DEFAULT 'single',
  numfiles int(10) unsigned NOT NULL DEFAULT '0',
  comments int(10) unsigned NOT NULL DEFAULT '0',
  views int(10) unsigned NOT NULL DEFAULT '0',
  hits int(10) unsigned NOT NULL DEFAULT '0',
  times_completed int(10) unsigned NOT NULL DEFAULT '0',
  leechers int(10) unsigned NOT NULL DEFAULT '0',
  seeders int(10) unsigned NOT NULL DEFAULT '0',
  last_action int(11) NOT NULL,
  visible enum('yes','no') NOT NULL DEFAULT 'yes',
  banned enum('yes','no') NOT NULL DEFAULT 'no',
  `owner` int(10) unsigned NOT NULL DEFAULT '0',
  numratings int(10) unsigned NOT NULL DEFAULT '0',
  ratingsum int(10) unsigned NOT NULL DEFAULT '0',
  nfo text COLLATE utf8_unicode_ci NOT NULL,
  client_created_by char(50) NOT NULL DEFAULT 'unknown',
  PRIMARY KEY (id),
  UNIQUE KEY info_hash (info_hash),
  KEY `owner` (`owner`),
  KEY visible (visible),
  KEY category_visible (category,visible),
  FULLTEXT KEY ft_search (search_text,ori_descr)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE users (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  username varchar(40) NOT NULL,
  passhash varchar(32) NOT NULL,
  secret varchar(20) NOT NULL,
  passkey varchar(32) NOT NULL DEFAULT '',
  email varchar(80) NOT NULL,
  `status` enum('pending','confirmed') NOT NULL DEFAULT 'pending',
  added int(11) NOT NULL,
  last_login int(11) NOT NULL,
  last_access int(11) NOT NULL,
  editsecret varchar(32) NOT NULL,
  privacy enum('strong','normal','low') NOT NULL DEFAULT 'normal',
  stylesheet int(10) DEFAULT '1',
  info text,
  acceptpms enum('yes','friends','no') NOT NULL DEFAULT 'yes',
  ip varchar(15) NOT NULL,
  class tinyint(3) unsigned NOT NULL DEFAULT '0',
  `language` varchar(32) NOT NULL DEFAULT 'en',
  avatar varchar(100) NOT NULL,
  av_w smallint(3) unsigned NOT NULL DEFAULT '0',
  av_h smallint(3) unsigned NOT NULL DEFAULT '0',
  uploaded bigint(20) unsigned NOT NULL DEFAULT '0',
  downloaded bigint(20) unsigned NOT NULL DEFAULT '0',
  title varchar(30) NOT NULL,
  country int(10) unsigned NOT NULL DEFAULT '0',
  notifs varchar(100) NOT NULL,
  modcomment text NOT NULL,
  enabled enum('yes','no') NOT NULL DEFAULT 'yes',
  avatars enum('yes','no') NOT NULL DEFAULT 'yes',
  donor enum('yes','no') NOT NULL DEFAULT 'no',
  donoruntil int(11) NOT NULL DEFAULT '0',
  warned enum('yes','no') NOT NULL DEFAULT 'no',
  warneduntil int(11) NOT NULL DEFAULT '0',
  torrentsperpage int(3) unsigned NOT NULL DEFAULT '0',
  topicsperpage int(3) unsigned NOT NULL DEFAULT '0',
  postsperpage int(3) unsigned NOT NULL DEFAULT '0',
  deletepms enum('yes','no') NOT NULL DEFAULT 'yes',
  savepms enum('yes','no') NOT NULL DEFAULT 'no',
  reputation int(10) NOT NULL DEFAULT '10',
  time_offset varchar(5) NOT NULL DEFAULT '0',
  dst_in_use tinyint(1) NOT NULL DEFAULT '0',
  auto_correct_dst tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (id),
  UNIQUE KEY username (username),
  KEY ip (ip),
  KEY uploaded (uploaded),
  KEY downloaded (downloaded),
  KEY country (country),
  KEY last_access (last_access),
  KEY enabled (enabled),
  KEY warned (warned),
  KEY pkey (passkey)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
