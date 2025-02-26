<?php

ze\dbAdm::revision( 2
, <<<_sql
	CREATE TABLE [[DB_PREFIX]][[ZENARIO_ERROR_LOG_PREFIX]]error_log (
		id int(10) unsigned NOT NULL AUTO_INCREMENT,
		logged datetime NOT NULL,
		referrer_url varchar(255) NOT NULL DEFAULT '',
		page_alias varchar(255) NOT NULL,
		PRIMARY KEY (id)
	) ENGINE=[[ZENARIO_TABLE_ENGINE]] CHARSET=[[ZENARIO_TABLE_CHARSET]] COLLATE=[[ZENARIO_TABLE_COLLATION]]
_sql

//Attempt to convert some columns with a utf8-3-byte character set to a 4-byte character set
);	ze\dbAdm::revision( 20
, <<<_sql
	ALTER TABLE `[[DB_PREFIX]][[ZENARIO_ERROR_LOG_PREFIX]]error_log`
	MODIFY COLUMN `referrer_url` text CHARACTER SET [[ZENARIO_TABLE_CHARSET]] COLLATE [[ZENARIO_TABLE_COLLATION]] NULL
_sql

);	ze\dbAdm::revision( 22
, <<<_sql
	ALTER TABLE `[[DB_PREFIX]][[ZENARIO_ERROR_LOG_PREFIX]]error_log`
	MODIFY COLUMN `page_alias` varchar(255) CHARACTER SET [[ZENARIO_TABLE_CHARSET]] COLLATE [[ZENARIO_TABLE_COLLATION]] NOT NULL
_sql
);