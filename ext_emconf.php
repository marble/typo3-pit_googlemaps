<?php

/* ######################################################################
 * Extension Manager/Repository config file for ext "pit_googlemaps".
 *
 * Auto generated 06-12-2013 20:02
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 * ######################################################################
 */

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Google Maps',
	'description' => 'Show Markers on Google Maps. You have the possibility to put more than one marker into the map and all points can display an info-window, different text can shown (Template based in Backend)',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '1.0.14',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'excludeFromUpdates', //'stable',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 1,
	'lockType' => '',
	'author' => 'Paulsen-IT',
	'author_email' => 'service@paulsen-it.de',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'typo3' => '4.0.0-6.1.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:18:{s:9:"ChangeLog";s:4:"a80c";s:10:"README.txt";s:4:"5c10";s:12:"ext_icon.gif";s:4:"fdea";s:12:"ext_icon.xcf";s:4:"82f7";s:17:"ext_localconf.php";s:4:"1d05";s:14:"ext_tables.php";s:4:"a053";s:14:"ext_tables.sql";s:4:"c4db";s:28:"ext_typoscript_constants.txt";s:4:"d623";s:24:"ext_typoscript_setup.txt";s:4:"8a6d";s:13:"locallang.xml";s:4:"839a";s:16:"locallang_db.xml";s:4:"9aba";s:14:"doc/manual.sxw";s:4:"6dea";s:19:"doc/wizard_form.dat";s:4:"ef55";s:20:"doc/wizard_form.html";s:4:"c7a7";s:14:"pi1/ce_wiz.gif";s:4:"b092";s:34:"pi1/class.tx_pitgooglemaps_pi1.php";s:4:"1f39";s:42:"pi1/class.tx_pitgooglemaps_pi1_wizicon.php";s:4:"8939";s:17:"pi1/locallang.xml";s:4:"ab53";}',
);

?>