#
# Table structure for table 'tx_featureflag_domain_model_featureflag'
#
CREATE TABLE tx_featureflag_domain_model_featureflag
(
    uid             int(11) NOT NULL auto_increment,
    pid             int(11) DEFAULT '0' NOT NULL,
    deleted         tinyint(4) DEFAULT '0' NOT NULL,
    hidden          tinyint(4) DEFAULT '0' NOT NULL,
    tstamp          int(11) DEFAULT '0' NOT NULL,
    sorting         int(10) DEFAULT '0' NOT NULL,
    crdate          int(11) DEFAULT '0' NOT NULL,
    cruser_id       int(11) DEFAULT '0' NOT NULL,

    description     varchar(255) DEFAULT '' NOT NULL,
    flag            varchar(255) DEFAULT '' NOT NULL,
    enabled         tinyint(3) DEFAULT '0' NOT NULL,

    is_dummy_record tinyint(3) DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    UNIQUE flag (flag),
    KEY             parent (pid)
) ENGINE=InnoDB;

#
# Table structure for table 'tx_featureflag_domain_model_mapping'
#
CREATE TABLE tx_featureflag_domain_model_mapping
(
    uid                int(11) NOT NULL auto_increment,
    pid                int(11) DEFAULT '0' NOT NULL,
    deleted            tinyint(4) DEFAULT '0' NOT NULL,
    tstamp             int(11) DEFAULT '0' NOT NULL,
    crdate             int(11) DEFAULT '0' NOT NULL,
    cruser_id          int(11) DEFAULT '0' NOT NULL,

    feature_flag       int(11) DEFAULT '0' NOT NULL,
    foreign_table_uid  int(11) DEFAULT '0' NOT NULL,
    foreign_table_name varchar(255) DEFAULT '' NOT NULL,
    behavior           int(11) DEFAULT '0' NOT NULL,

    is_dummy_record    tinyint(3) DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY                parent (pid),
    KEY                foreign_uid_name (foreign_table_uid, foreign_table_name)
) ENGINE=InnoDB;
