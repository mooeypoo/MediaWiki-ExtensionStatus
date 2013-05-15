<?php
/**
 * Internationalisation for ExtensionSetup
 *
 * @file
 * @ingroup Extensions
 */
$messages = array();
 
/** English
 * @author Moriel Schottlender (mooeypoo)
 */
$messages[ 'en' ] = array(
	'extensionstatus' => "ExtensionStatus",
	'extensionstatus-desc' => "Validates existing extensions and checks whether they require update.",
		
	'extensionstatus-extendversionlink' => "This page extends the [[Special:Version]] page with information about how far behind remote changes your local extensions are. \n\nCommits from [https://gerrit.wikimedia.org/r/#/q/owner:%22L10n-bot+%253Cl10n-bot%2540translatewiki.net%253E%22,n,z Translation Updater Bot] are counted separately.",

	/* Update Status */
	'extstat-status-uptodate' => "Up to Date",
	'extstat-status-translupdate' => "Translation Update",
	'extstat-status-updateavailable' => "Update Available",
	'extstat-subtitle-commits' => "About $1 Changes Behind",
	'extstat-subtitle-language' => "$1 Translation Bot Updates",
	
	'extstat-commitinfo-latest' => "The latest change (<em>$1</em>) was submitted by <em>$2</em> on <em>$3</em>",
	'extstat-commitinfo-link' => "[$1 Go to Repository]",
);

/** Message documentation (Message documentation)
 * @author Moriel Schottlender (mooeypoo)
 */

$messages[ 'qqq' ] = array(
	'extensionstatus-desc' => "{{desc|name=ExtensionStatus|url=http://www.mediawiki.org/wiki/Extension:ExtensionStatus}}",

	'extensionstatus-extendversionlink' => "The introduction section in the Special page.",

	/* Update Status */
	'extstat-status-uptodate' => "'Up to Date' extension upgrade status, appears when no new commits were found",
	'extstat-status-translupdate' => "'Translation Update' extension upgrade status, appears when there are commits done by the Translation Updater Bot",
	'extstat-status-updateavailable' => "'Update Available' extension upgrade status, appears when there are changes/commits done to the code other than by the Translation Updater Bot",
	'extstat-subtitle-commits' => "States the number of new available commits. Parameters: $1: How many new updates/commits are available in the remote repository",
	'extstat-subtitle-language' => "States the number of new available commits done by the Translation Updater Bot. Parameters: $1: How many new updates/commits are available in the remote repository specifically from the Translation Updater Bot",

	'extstat-commitinfo-latest' => "Displays specific information about the latest change (disregards ones from Translation Updater Bot).
	Parameters: $1: The head of the latest commit, $2: The author of the latest commit, $3: The date of the latest commit",
	'extstat-commitinfo-link' => "Link to the extension's repository. Parameters: $1: Repository URL",
);