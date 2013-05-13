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
		
		'extstat-msg-lastchange-notice' =>  "There are [$2 about $1 new changes] since you updated this extension.",
		'extstat-msg-lastchange-toomanynotice' =>  "There were [$2 over $1 changes] since you updated this extension.",
		'extstat-msg-lastchange-details' => "The latest change (<em>$1</em>) was submitted by <em>$2</em> on <em>$3</em>",
		
		'extstat-msg-updatebotcommits' => "(Additionally: Behind on $1 updates from Translation Updater Bot)",

		'extensionstatus-latestcommit' => "Last commit: $1",
		'extensionstatus-behindcommits' => "You're about $1 behind the latest commit!",
);