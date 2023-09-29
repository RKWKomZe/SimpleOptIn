#
# Table structure for table 'tx_simpleconsent_domain_model_address'
#
CREATE TABLE tx_simpleconsent_domain_model_address
(

	uid           int(11) NOT NULL auto_increment,
	pid           int(11) DEFAULT '0' NOT NULL,

	gender        tinyint(4) DEFAULT '0' NOT NULL,
	title         varchar(255) DEFAULT '' NOT NULL,
	first_name    varchar(255) DEFAULT '' NOT NULL,
	last_name     varchar(255) DEFAULT '' NOT NULL,
	company       varchar(255) DEFAULT '' NOT NULL,
	address       varchar(255) DEFAULT '' NOT NULL,
	zip           varchar(255) DEFAULT '' NOT NULL,
	city          varchar(255) DEFAULT '' NOT NULL,
  phone         varchar(255) DEFAULT '' NOT NULL,
  email         varchar(255) DEFAULT '' NOT NULL,

  hash          varchar(255) DEFAULT '' NOT NULL,
  status        tinyint(4) unsigned DEFAULT '0' NOT NULL,

	tstamp        int(11) unsigned DEFAULT '0' NOT NULL,
	crdate        int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id     int(11) unsigned DEFAULT '0' NOT NULL,
	deleted       tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden        tinyint(4) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY           parent (pid),
	KEY hash (hash),
	KEY status (status),

);

#
# Table structure for table 'tx_simpleconsent_domain_model_mail'
#
CREATE TABLE tx_simpleconsent_domain_model_mail
(

	uid           int(11) NOT NULL auto_increment,
	pid           int(11) DEFAULT '0' NOT NULL,

	subject       varchar(255) DEFAULT '' NOT NULL,
	text_plain    text NOT NULL,
	text_html     text NOT NULL,
	status        tinyint(4) unsigned DEFAULT '0' NOT NULL,
	addresses     text NOT NULL,
	queue_mail 		int(11) unsigned DEFAULT '0' NOT NULL,

	tstamp        int(11) unsigned DEFAULT '0' NOT NULL,
	crdate        int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id     int(11) unsigned DEFAULT '0' NOT NULL,
	deleted       tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden        tinyint(4) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY           parent (pid),
	KEY status (status)

);
