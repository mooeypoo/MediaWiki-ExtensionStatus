# Mediawiki Extension: Special:ExtensionStatus

This extension creates a special page _Special:ExtensionStatus_ (an extension to Special:Version page) which shows users information about any updates in the extensions they are using. 

The extensions that are checked must have a gerrit repository to be tested-against. The extension will first check local git information to see if there is a 'last update' date, but if git is unavailable, or if the extension was manually downloaded, the code will fall back to checking the file last modification date. 

This isn't entirely accurate, but it can give users (and developers) some idea of whether or not they should upgrade their extensions. This is especailly important for MediaWiki developers, since some bugs that appear may be solved by an extension upgrade (and sometimes those extensions haven't been updated in months) 

## Note

This is my very first MediaWiki extension, and I am pretty sure what I've done can be done better without the use of regular expressions to screenscrape the git repository off of gerrit. If you have suggestions or comments, please add them to the GitHub issues. You can also contact me through the site, or on IRC (irc.freenode.net) as _mooeypoo_

## Installation

This extension is installed the usual way:

* Download the [latest version](https://github.com/mooeypoo/MediaWiki-ExtensionStatus/archive/master.zip) into your mediawiki/extensions/ folder, call the new folder "ExtensionStatus".
* Add this to your LocalSettings.php


```
require_once( "$IP/extensions/ExtensionStatus/ExtensionStatus.php" );
```
## Usage

After installation, browse to Special:ExtensionStatus page:

```yourmediawiki.com/Special:ExtensionStatus```

Please report bugs and suggestions in the issues!

## Credits
* Author: Moriel Schottlender (mooeypoo)
* GNU General Public License http://www.gnu.org/licenses/old-licenses/gpl-2.0.html