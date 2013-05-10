<?php
error_reporting(E_ALL);

class SpecialExtensionStatus extends SpecialVersion {
	
	protected $reader = null;
	protected $dateTimeFormat = "";
	
	public function __construct() {
		// Call the SpecialPage constructor
//		$this->reader = new XMLReader();
		$this->dateTimeFormat = "F d Y H:i:s.";
		SpecialPage::__construct( 'ExtensionStatus' );
	}

	public function execute( $par ) {
		$request = $this->getRequest();
		$output = $this->getOutput();
		$this->setHeaders();

		$output->prependHTML( wfMessage( 'extensionstatus-extendversionlink' )->parse() );
		$wikitext = $this->getExtensionCredits();
		$output->addWikiText( $wikitext );
	}

	
	

	function getChangeStatus( $extName ) {
		$extGitURL = "https://gerrit.wikimedia.org/r/gitweb?p=mediawiki/extensions/".$extName.".git;a=commit;h=refs/heads/master";
		$extLocalPath = dirname( dirname(  __FILE__ ) ) . "/" . $extName . "/" . $extName . ".php";
		
		// Check local file timestamp:
		$localChangeTime = filemtime( $extLocalPath );
		
		// Check remote last change:
		$content = file_get_contents($extGitURL);
		if ( $content !== false ) {
			// Look for the last commit date
			preg_match_all('%<span class=\"datetime\">(.*?)</span>%i', $content, $matches, PREG_PATTERN_ORDER);
			$remoteChangeTime = strtotime( $matches[1][0] );
			
			$diff = $remoteChangeTime - $localChangeTime;
		} else {
			$diff = false;
			// Erorr reading remote file
		}
		return $diff;
	}

	function calcDiff( $newDate, $oldDate ) {
		return ( $newDate - $oldDate );
	}
	
	function intervalToText( $timeInterval ) {
		$days = floor($timeInterval/(60*60*24));
		$hours = floor(($timeInterval - $days*(60*60*24)) / (60*60));
		
		$result = $days . " Days";
		if ($hours > 0) {
			$result .= ", " . $hours ." Hours";
		}
		return $result;
	}
	
	
	/**
	 * Creates and formats the credits for a single extension and returns this.
	 *
	 * @param $extension Array
	 *
	 * @return string
	 */
	function getCreditsForExtension( array $extension ) {
		global $wgLang;
	
		$name = isset( $extension['name'] ) ? $extension['name'] : '[no name]';
	
		$vcsText = false;
	
		if ( isset( $extension['path'] ) ) {
			$gitInfo = new GitInfo( dirname( $extension['path'] ) );
			$gitHeadSHA1 = $gitInfo->getHeadSHA1();
			if ( $gitHeadSHA1 !== false ) {
				$vcsText = '(' . substr( $gitHeadSHA1, 0, 7 ) . ')';
				$gitViewerUrl = $gitInfo->getHeadViewUrl();
				if ( $gitViewerUrl !== false ) {
					$vcsText = "[$gitViewerUrl $vcsText]";
				}
				$gitHeadCommitDate = $gitInfo->getHeadCommitDate();
				if ( $gitHeadCommitDate ) {
					$vcsText .= "<br/>" . $wgLang->timeanddate( $gitHeadCommitDate, true );

					/* Adding comparison to local version */
					$diff = $this->getChangeStatus( $name );
					if ($diff !== false) {
						$msg = wfMessage( 'extensionstatus-behindcommits', $this->intervalToText( $diff ) )->parse();
						$extStatText = "<p class='extstatus-warning'>".$msg."</p>";
					}
						
				}
			} else {
				$svnInfo = self::getSvnInfo( dirname( $extension['path'] ) );
				# Make subversion text/link.
				if ( $svnInfo !== false ) {
					$directoryRev = isset( $svnInfo['directory-rev'] ) ? $svnInfo['directory-rev'] : null;
					$vcsText = $this->msg( 'version-svn-revision', $directoryRev, $svnInfo['checkout-rev'] )->text();
					$vcsText = isset( $svnInfo['viewvc-url'] ) ? '[' . $svnInfo['viewvc-url'] . " $vcsText]" : $vcsText;
				}
			}
		}
	
		# Make main link (or just the name if there is no URL).
		if ( isset( $extension['url'] ) ) {
		$mainLink = "[{$extension['url']} $name]";
		} else {
		$mainLink = $name;
		}
	
		if ( isset( $extension['version'] ) ) {
				$versionText = '<span class="mw-version-ext-version">' .
				$this->msg( 'version-version', $extension['version'] )->text() .
							'</span>';
		} else {
		$versionText = '';
		}
	
		# Make description text.
		$description = isset( $extension['description'] ) ? $extension['description'] : '';
	
		if ( isset( $extension['descriptionmsg'] ) ) {
			# Look for a localized description.
			$descriptionMsg = $extension['descriptionmsg'];
	
			if ( is_array( $descriptionMsg ) ) {
				$descriptionMsgKey = $descriptionMsg[0]; // Get the message key
				array_shift( $descriptionMsg ); // Shift out the message key to get the parameters only
				array_map( "htmlspecialchars", $descriptionMsg ); // For sanity
				$description = $this->msg( $descriptionMsgKey, $descriptionMsg )->text();
			} else {
				$description = $this->msg( $descriptionMsg )->text();
			}
		}
	
		if ( $vcsText !== false ) {
			$extNameVer = "<tr>
			<td><em>$mainLink $versionText</em></td>
			<td>
				<em>$vcsText</em>
				$extStatText
			</td>";
			
		} else {
			$extNameVer = "<tr>
			<td colspan=\"2\"><em>$mainLink $versionText</em></td>";
		}
	
		$author = isset( $extension['author'] ) ? $extension['author'] : array();
		$extDescAuthor = "<td>$description</td>
		<td>" . $this->listAuthors( $author, false ) . "</td></tr>\n";
	
		return $extNameVer . $extDescAuthor;
	}
	
	
	
	
	
	
	
	
	
	/** Including 'private' functions for inheritance **/
	private function openExtType( $text, $name = null ) {
		$opt = array( 'colspan' => 4 );
		$out = '';
	
		if( $this->firstExtOpened ) {
			// Insert a spacing line
			$out .= '<tr class="sv-space">' . Html::element( 'td', $opt ) . "</tr>\n";
		}
		$this->firstExtOpened = true;
	
		if( $name ) {
			$opt['id'] = "sv-$name";
		}
	
		$out .= "<tr>" . Xml::element( 'th', $opt, $text ) . "</tr>\n";
	
		return $out;
	}
	
	
	
	
}