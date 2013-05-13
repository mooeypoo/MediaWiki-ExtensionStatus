# Mediawiki Extension: Special:ExtensionStatus

This extension creates a special page _Special:ExtensionStatus_ (an extension to Special:Version page) which shows users information about any updates in the extensions they are using. 

The extensions that are checked must have a gerrit repository to be tested-against. The extension will first check local git information to see if there is a 'last update' date, but if git is unavailable, or if the extension was manually downloaded, the code will fall back to checking the file last modification date. 

This isn't entirely accurate, but it can give users (and developers) some idea of whether or not they should upgrade their extensions. This is especailly important for MediaWiki developers, since some bugs that appear may be solved by an extension upgrade (and sometimes those extensions haven't been updated in months) 

## Note

*This is my very first MediaWiki extension!* (woohoo), and I am pretty sure what I've done can be done better. I took great care to try and follow the conventions, but please bear with me on errors and fixes, and if you find any, please add them to the GitHub issues -- I'd love to have good criticism that can make this extension useful to people! 

You can also contact me through moo[at]smarterthanthat[dot]com, or on IRC (irc.freenode.net) as _mooeypoo_

## Screenshots
Here are two screenshots of the extension in action. There are two options: if git is installed, the system will use it to verify local repositories. Otherwise, it will fallback to check local file modification time. Notice, also, that since the "ExtensionStatus" entry doesn't have a gerrit account, its commit status is simply empty. 

**Note:** The two screenshots below were taken from two different systems that have different updates, which is why the dates and number of lagging commits is different.

### With Git
Notice you can see the SHA1 and latest update date as you do in the Special:Version page:
![Special:ExtensionStatus with git installed](http://moriel.smarterthanthat.com/wp-content/uploads/2013/05/extstatus2.png "Special:ExtensionStatus with git installed")

### Without Git
Here the SHA1 wasn't available, the system fell-back to reading file modification time:
![Special:ExtensionStatus without git installed](http://moriel.smarterthanthat.com/wp-content/uploads/2013/05/extstatus1.png "Special:ExtensionStatus without git installed")

## Installation

This extension is installed the usual way:

* Download the [latest version](https://github.com/mooeypoo/MediaWiki-ExtensionStatus/archive/master.zip) into your mediawiki/extensions/ folder, call the new folder "ExtensionStatus".
* Add this to your LocalSettings.php


```
require_once( "$IP/extensions/ExtensionStatus/ExtensionStatus.php" );
```
## Usage

After installation, browse to Special:ExtensionStatus page:

```
http://yourmediawiki.com/Special:ExtensionStatus
```

Please report bugs and suggestions in the issues!

## Future / "To Do" list

There are a couple of improvements I'm consiering adding for futrue versions:

* Possibly adding an option to notify the admin if certain amount of extensions are behind on updates
* Adding an option to check other repositories than Gerrit
* Checking into using glip (https://github.com/patrikf/glip) for git functionality for users without git installed

Ideas are welcome!


## Credits
* Author: Moriel Schottlender (mooeypoo)
* GNU General Public License http://www.gnu.org/licenses/old-licenses/gpl-2.0.html