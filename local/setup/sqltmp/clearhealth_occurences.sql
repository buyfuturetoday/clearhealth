CREATE TABLE `occurences` (
  `id` int(11) NOT NULL default '0',
  `event_id` int(11) NOT NULL default '0',
  `start` datetime NOT NULL default '0000-00-00 00:00:00',
  `end` datetime NOT NULL default '0000-00-00 00:00:00',
  `notes` varchar(255) NOT NULL default '',
  `location_id` int(11) NOT NULL default '0',
  `user_id` int(11) default NULL,
  `last_change_id` int(11) default NULL,
  `external_id` int(11) default NULL,
  `reason_code` int(11) NOT NULL default '0',
  `timestamp` timestamp NOT NULL,
  `walkin` tinyint(4) NOT NULL default '0',
  `group_appointment` tinyint(4) NOT NULL default '0',
  `creator_id` int(11) default 0,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE `occurence_breakdown` (
  `occurence_breakdown_id` int(11) NOT NULL default '0',
  `occurence_id` int(11) NOT NULL default '0',
  `index` int(11) default '0',
  `offset` int(11) NOT NULL default '0',
  `length` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`occurence_breakdown_id`)
) TYPE=MyISAM;

