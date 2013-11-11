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
	UNIQUE flag (flag),
	KEY parent (pid)
) ENGINE=InnoDB;

#
# Table structure for table 'tx_featureflag_domain_model_featureflag_mapping'
#
CREATE TABLE tx_featureflag_mapping (
  uid int(11) NOT NULL auto_increment,
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  sorting_foreign int(11) DEFAULT '0' NOT NULL,
  local_table varchar(30) DEFAULT '' NOT NULL,
  local_column varchar(30) DEFAULT '' NOT NULL,

  PRIMARY KEY (uid),
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
) ENGINE=InnoDB;

#
# Table structure for table 'pages'
#
CREATE TABLE pages (
  tx_featureflag_hide int(11) DEFAULT '0' NOT NULL,
  tx_featureflag_show int(11) DEFAULT '0' NOT NULL
) ENGINE=InnoDB;

#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content (
  tx_featureflag_hide int(11) DEFAULT '0' NOT NULL,
  tx_featureflag_show int(11) DEFAULT '0' NOT NULL
) ENGINE=InnoDB;