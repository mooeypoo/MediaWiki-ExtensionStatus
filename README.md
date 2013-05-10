# Mediawiki Extension: ExtensionStatus

This extension extends the Special:Version page and adds notices about the latest differences between the local version of an installed extension and the latest commits. This should help developers (and general users) get a sense of when an extension should be upgraded.

## Note

This is my very first MediaWiki extension, and I am pretty sure what I've done can be done better without the use of regular expressions to screenscrape the git repository off of gerrit. If you have suggestions or comments, please add them to the GitHub issues. You can also contact me through the site, or on IRC (irc.freenode.net) as _mooeypoo_

## Installation

This extension is installed the usual way:

* Download the [latest version](https://github.com/mooeypoo/MediaWiki-ExtensionStatus/archive/master.zip) into your mediawiki/extensions/ folder, call the new folder "ExtensionStatus".
* Add this to your LocalSettings.php
```
require_once( "$IP/extensions/ExtensionStatus/ExtensionStatus.php" );
```

Please report bugs and suggestions in the issues!

## Credits
* Author: Moriel Schottlender (mooeypoo)
* GNU General Public License http://www.gnu.org/licenses/old-licenses/gpl-2.0.html