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
# Table structure for table 'tx_featureflag_table_featureflag_mm'
#
CREATE TABLE tx_featureflag_table_featureflag_mm (
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(255) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,

  PRIMARY KEY (uid_local,uid_foreign),
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);