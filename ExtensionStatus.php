<?php
# Alert the user that this is not a valid access point to MediaWiki if they try to access the special pages file directly.
if ( !defined( 'MEDIAWIKI' ) ) {
        echo <<<EOT
To install my extension, put the following line in LocalSettings.php:
require_once( "\$IP/extensions/ExtensionStatus/ExtensionStatus.php" );
EOT;
        exit( 1 );
}
 
$wgHooks['BeforePageDisplay'][] = 'wfExtensionStatusBeforePageDisplay';

$wgExtensionCredits[ 'specialpage' ][] = array(
        'path' => __FILE__,
        'name' => 'ExtensionStatus',
        'author' => 'Moriel Schottlender',
        'descriptionmsg' => 'extensionstatus-desc',
        'version' => '0.1.0',
);
 
$wgResourceModules['ext.ExtensionStatus'] = array(
        'styles' => array('ExtensionStatus.css'),
        'localBasePath' => __DIR__,
);


$wgAutoloadClasses[ 'seCommits' ] = __DIR__ . '/Commits.php'; 
$wgAutoloadClasses[ 'SpecialExtensionStatus' ] = __DIR__ . '/SpecialExtensionStatus.php'; 

$wgExtensionMessagesFiles[ 'ExtensionStatus' ] = __DIR__ . '/ExtensionStatus.i18n.php'; 
$wgExtensionMessagesFiles[ 'ExtensionStatusAlias' ] = __DIR__ . '/ExtensionStatus.alias.php'; 
$wgSpecialPages[ 'ExtensionStatus' ] = 'SpecialExtensionStatus'; 

/**
 * @param $out OutputPage
 * @param $sk Skin
 * @return bool
 */
function wfExtensionStatusBeforePageDisplay( $out, &$sk ) {
	$out->addModuleStyles( 'ext.ExtensionStatus' );
	return true;
}

