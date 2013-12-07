#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content (
	tx_pitgooglemaps_addresses text,
	tx_pitgooglemaps_width int(11) DEFAULT '0' NOT NULL,
	tx_pitgooglemaps_height int(11) DEFAULT '0' NOT NULL,
	tx_pitgooglemaps_zoom int(11) DEFAULT '0' NOT NULL,
	tx_pitgooglemaps_infowindow text,
	tx_pitgooglemaps_markers text,
	tx_pitgooglemaps_geodata text,
	tx_pitgooglemaps_markericons text,
	tx_pitgooglemaps_showsidebar tinyint(4) DEFAULT '0' NOT NULL,
	tx_pitgooglemaps_showtype tinyint(4) DEFAULT '0' NOT NULL,
	tx_pitgooglemaps_showroute tinyint(4) DEFAULT '0' NOT NULL
);