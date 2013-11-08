#
# Table structure for table 'tx_featureflag_domain_model_featureflag'
#
CREATE TABLE tx_featureflag_domain_model_featureflag (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,

	description varchar(255) DEFAULT '' NOT NULL,
	flag varchar(255) DEFAULT '' NOT NULL,
	enabled tinyint(3) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY flag (flag)
) ENGINE=InnoDB;

#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content (
  tx_featureflag_featureflag varchar(255) DEFAULT '' NOT NULL,
) ENGINE=InnoDB;

#
# Table structure for table 'pages'
#
CREATE TABLE pages (
  tx_featureflag_featureflag varchar(255) DEFAULT '' NOT NULL,
) ENGINE=InnoDB;