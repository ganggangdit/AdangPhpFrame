-- --------------------------------------------------
-- file: lampcms.sql
-- LAMP_CMS 系统安装使用的SQL脚本文件
-- Author： adang
-- --------------------------------------------------



# --------------------------------------------------------
# 表的结构 cms_album ,该表用于
# --------------------------------------------------------

DROP TABLE IF EXISTS cms_album;
CREATE TABLE cms_album (
  catId SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  catPid SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0,
  catPath VARCHAR(100) NOT NULL DEFAULT '',
  catTitle VARCHAR(100) NOT NULL DEFAULT '',
  description VARCHAR(200) NOT NULL DEFAULT '',
  PRIMARY KEY  (catId),
  KEY catPid (catPid)
);

# --------------------------------------------------------
# 表的结构 cms_article,该表用于
# --------------------------------------------------------

DROP TABLE IF EXISTS cms_article;
CREATE TABLE cms_article (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(50) NOT NULL DEFAULT '',
  summary VARCHAR(200) NOT NULL DEFAULT '',
  postTime INT(10) UNSIGNED NOT NULL DEFAULT '0',
  author VARCHAR(30) NOT NULL DEFAULT '',
  comeFrom VARCHAR(50) NOT NULL DEFAULT '',
  content text NOT NULL,
  keyword VARCHAR(20) NOT NULL DEFAULT '',
  catId SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0,
  audit SMALLINT(1) UNSIGNED NOT NULL DEFAULT '0',
  recommend SMALLINT(1) UNSIGNED NOT NULL DEFAULT '0',
  views SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY  (id),
  KEY catId (catId),
  KEY keyword (keyword),
  KEY recommend (recommend)
);

# --------------------------------------------------------
# 表的结构 cms_article,该表用于
# --------------------------------------------------------

DROP TABLE IF EXISTS cms_comments;
CREATE TABLE cms_comments (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  uid INT(11) UNSIGNED NOT NULL DEFAULT '0',
  aid INT(11) UNSIGNED NOT NULL DEFAULT '0',
  postTime INT(10) UNSIGNED NOT NULL DEFAULT '0',
  content text NOT NULL,
  cmt SMALLINT(5) NOT NULL DEFAULT 0,
  support SMALLINT(1) UNSIGNED NOT NULL DEFAULT '0',
  oppose SMALLINT(1) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY  (id),
  KEY postTime (postTime)
);


# --------------------------------------------------------
# 表的结构 cms_cat,该表用于栏目
# --------------------------------------------------------

DROP TABLE IF EXISTS cms_columns;
CREATE TABLE cms_columns (
  colId SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  colPid SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0,
  picId SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0,
  colPath VARCHAR(100) NOT NULL DEFAULT '',
  colTitle VARCHAR(100) NOT NULL default '',
  description VARCHAR(200) NOT NULL default '',
  ord SMALLINT(3) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY  (colId),
  KEY colPath (colPath, picId)
);

# --------------------------------------------------------
# 表的结构 cms_picture,该表用于
# --------------------------------------------------------

DROP TABLE IF EXISTS cms_picture;
CREATE TABLE cms_picture (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  picTitle VARCHAR(30) NOT NULL DEFAULT '',
  description VARCHAR(200) NOT NULL DEFAULT '',
  picName VARCHAR(50) NOT NULL DEFAULT '',
  catId SMALLINT(4)  UNSIGNED NOT NULL,
  hasThumb SMALLINT(1) NOT NULL DEFAULT '0',
  hasMark SMALLINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (id),
  KEY catId (catId)
);

# --------------------------------------------------------
# 表的结构 cms_user,该表用于
# --------------------------------------------------------

DROP TABLE IF EXISTS cms_user;
CREATE TABLE cms_user (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  userName VARCHAR(20) NOT NULL DEFAULT '',
  userPwd VARCHAR(40) NOT NULL DEFAULT '',
  email VARCHAR(60) NOT NULL DEFAULT '',
  safequestion SMALLINT(3) NOT NULL DEFAULT '0',
  safeanswer VARCHAR(60) NOT NULL DEFAULT '',
  regTime INT(10) UNSIGNED NOT NULL DEFAULT '0',
  sex CHAR(2) NOT NULL DEFAULT '',
  PRIMARY KEY  (id),
  KEY id (userName, userPwd)
);

# --------------------------------------------------------
# 表的结构 cms_flink,该表用于
# --------------------------------------------------------

DROP TABLE IF EXISTS cms_flink;
CREATE TABLE cms_flink (
  id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  webName VARCHAR(30) NOT NULL DEFAULT '',
  url VARCHAR(60) NOT NULL DEFAULT '',
  logo VARCHAR(60) NOT NULL DEFAULT '',
  email VARCHAR(50) NOT NULL DEFAULT '',
  dtime INT(10) UNSIGNED NOT NULL DEFAULT '0',
  msg varchar(200) NOT NULL DEFAULT '',
  list SMALLINT(1) unsigned NOT NULL DEFAULT '0',
  ord SMALLINT(3) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY  (id),
  KEY webName (webName, url, logo)
);

INSERT INTO cms_album VALUES(null, 0, '0','相册集', '相册根目录');
INSERT INTO cms_columns VALUES(null, '0','0','0', '首页', '分类根目录','0');
