CREATE TABLE IF NOT EXISTS PREFIXmodule_seohelping_rules (
	id_rule int(11) unsigned NOT NULL auto_increment,
	id_lang int(11) unsigned NOT NULL,
	id_shop int(11) unsigned NOT NULL,
	name varchar(50) NOT NULL,
	type VARCHAR(50) NOT NULL,
	role varchar(4) NOT NULL,
	active tinyint(1) unsigned,
	date_add datetime,
	date_upd datetime,
	PRIMARY KEY (id_rule),
	KEY `id_lang` (`id_lang`),
	KEY `id_shop` (`id_shop`)
) ENGINE=ENGINE_DEFAULT DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS PREFIXmodule_seohelping_objects (
	id_rule int(11) unsigned NOT NULL,
	id_obj int(11) unsigned NOT NULL,
	PRIMARY KEY (id_rule, id_obj)
) ENGINE=ENGINE_DEFAULT DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS PREFIXmodule_seohelping_patterns (
	id_rule int(11) unsigned NOT NULL,
	field VARCHAR(100),
	pattern VARCHAR(250),
	PRIMARY KEY (id_rule,field)
) ENGINE=ENGINE_DEFAULT DEFAULT CHARSET=utf8;