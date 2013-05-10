<?php
/**
 * Internationalisation for ExtensionSetup
 *
 * @file
 * @ingroup Extensions
 */
$messages = array();
 
/** English
 * @author (mooeypoo) Moriel Schottlender
 */
$messages[ 'en' ] = array(
        'extensionstatus' => "ExtensionStatus", 
        'extensionstatus-desc' => "Validates existing extensions and checks whether they require update.",
		
		'extensionstatus-extendversionlink' => "This page extends the {{Special:Version}} page with information about how far behind remote changes your local extensions are.",
		'extensionstatus-latestcommit' => "Last commit: $1",
		'extensionstatus-behindcommits' => "You're about $1 behind the latest commit!",
);