<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key';


t3lib_extMgm::addPlugin(array(
	'LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
), 'list_type');

if (TYPO3_MODE == 'BE') {
    $TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_pitgooglemaps_pi1_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_pitgooglemaps_pi1_wizicon.php';
}

$tempColumns = array (
	'tx_pitgooglemaps_addresses' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_addresses',		
		'config' => array (
			'type' => 'text',
			'cols' => '30',	
			'rows' => '5',
		)
	),
	'tx_pitgooglemaps_width' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_width',		
		'config' => array (
			'type'     => 'input',
			'size'     => '4',
			'max'      => '4',
			'eval'     => 'int',
			'checkbox' => '0',
			'range'    => array (
				'upper' => '1000',
				'lower' => '10'
			),
			'default' => 0
		)
	),
	'tx_pitgooglemaps_height' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_height',		
		'config' => array (
			'type'     => 'input',
			'size'     => '4',
			'max'      => '4',
			'eval'     => 'int',
			'checkbox' => '0',
			'range'    => array (
				'upper' => '1000',
				'lower' => '10'
			),
			'default' => 0
		)
	),
	'tx_pitgooglemaps_zoom' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_zoom',		
		'config' => array (
			'type' => 'select',
			'items' => array (
				array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_zoom.I.0', '1'),
				array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_zoom.I.1', '2'),
				array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_zoom.I.2', '3'),
				array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_zoom.I.3', '4'),
				array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_zoom.I.4', '5'),
				array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_zoom.I.5', '6'),
				array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_zoom.I.6', '7'),
				array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_zoom.I.7', '8'),
				array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_zoom.I.8', '9'),
				array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_zoom.I.9', '10'),
				array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_zoom.I.10', '11'),
				array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_zoom.I.11', '12'),
				array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_zoom.I.12', '13'),
				array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_zoom.I.13', '14'),
				array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_zoom.I.14', '15'),
				array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_zoom.I.15', '16'),
				array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_zoom.I.16', '17'),
			),
			'size' => 1,	
			'maxitems' => 1,
		)
	),
	'tx_pitgooglemaps_showsidebar' => array (        
        'exclude' => 0,        
        'label' => 'LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_showsidebar',        
        'config'  => array (
				'type'    => 'check',
				'default' => '0'
		)
    ),
    'tx_pitgooglemaps_showroute' => array (        
        'exclude' => 0,        
        'label' => 'LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_showroute',        
        'config'  => array (
				'type'    => 'check',
				'default' => '1'
		)
    ),
    'tx_pitgooglemaps_showtype' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_showtype',		
		'config' => array (
			'type' => 'select',
			'items' => array (
				array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_showtype.I.0', '0'),
				array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_showtype.I.1', '1'),
				//array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_showtype.I.2', '2'),
				array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_showtype.I.3', '3'),
				//array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_showtype.I.4', '4'),
				array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_showtype.I.5', '5'),
				array('LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_showtype.I.6', '6'),
			),
			'size' => 1,	
			'maxitems' => 1,
		)
	),
	'tx_pitgooglemaps_infowindow' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_infowindow',		
		'config' => array (
			'type' => 'text',
			'cols' => '30',
			'rows' => '5',
			'wizards' => array(
				'_PADDING' => 2,
				'RTE' => array(
					'notNewRecords' => 1,
					'RTEonly'       => 1,
					'type'          => 'script',
					'title'         => 'Full screen Rich Text Editing|Formatteret redigering i hele vinduet',
					'icon'          => 'wizard_rte2.gif',
					'script'        => 'wizard_rte.php',
				),
			),
		)
	),
	'tx_pitgooglemaps_markers' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_markers',		
		'config' => array (
			'type' => 'text',
			'cols' => '30',	
			'rows' => '5',
		)
	),
	'tx_pitgooglemaps_geodata' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_geodata',		
		'config' => array (
			'type' => 'text',
			'cols' => '30',	
			'rows' => '5',
		)
	),
	 'tx_pitgooglemaps_markericons' => array (        
        'exclude' => 0,        
        'label' => 'LLL:EXT:pit_googlemaps/locallang_db.xml:tt_content.tx_pitgooglemaps_markericons',        
        'config' => array (
            'type' => 'group',
            'internal_type' => 'file',
            'allowed' => 'gif,png,jpeg,jpg',    
            'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],    
            'uploadfolder' => 'uploads/tx_pitgooglemaps',
            'show_thumbs' => 1,    
            'size' => 5,    
            'minitems' => 0,
	    'maxitems' => 100,
        )
    ),
	
);


t3lib_div::loadTCA('tt_content');
t3lib_extMgm::addTCAcolumns('tt_content', $tempColumns, 1);
//t3lib_extMgm::addToAllTCAtypes('tt_content','tx_pitgooglemaps_addresses;;;;1-1-1, tx_pitgooglemaps_width, tx_pitgooglemaps_height, tx_pitgooglemaps_zoom, tx_pitgooglemaps_infowindow;;;richtext[]:rte_transform[mode=ts], tx_pitgooglemaps_markers, tx_pitgooglemaps_geodata');
$TCA["tt_content"]["types"]["list"]["subtypes_addlist"][$_EXTKEY."_pi1"]="tx_pitgooglemaps_addresses,tx_pitgooglemaps_width, tx_pitgooglemaps_height, tx_pitgooglemaps_zoom, tx_pitgooglemaps_showsidebar, tx_pitgooglemaps_showroute, tx_pitgooglemaps_showtype, tx_pitgooglemaps_infowindow;;;richtext[]:rte_transform[mode=ts], tx_pitgooglemaps_markers, tx_pitgooglemaps_markericons";
?>