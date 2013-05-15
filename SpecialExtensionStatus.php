<?php

class SpecialExtensionStatus extends SpecialVersion {

	protected $dateTimeFormat = "";
	protected $lastRemoteChange = 0;
	protected $comm;
	protected $html;

	public function __construct() {
		$this->dateTimeFormat = "F d Y H:i:s.";
		$this->comm = new SECommits();
		$this->html = new Html();

		// Call the SpecialPage constructor
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

	/**
	 * Checks the details of the external repository for the extension, looks for
	 * commits/changes that were done before the update date of the local version.
	 * 
	 * @return {String} Contents of the update cell with update/commit information
	 */
	function getChangeStatus( $extName , $getFromLocalGit = false, $localGitTime = 0 ) {
		global $wgLang;

		// This is taking information from gerrit, but will be changed soon to the GitHub mirrors
		$extGitURL = "https://gerrit.wikimedia.org/r/gitweb?p=mediawiki/extensions/".$extName.".git;a=log;h=refs/heads/master";
		$extLocalPath = dirname( dirname(  __FILE__ ) ) . "/" . $extName . "/" . $extName . ".php";

		if ( $getFromLocalGit && $localGitTime > 0 ) {
			// Local git exists, take local time from there:
			$localChangeTime = $localGitTime;
		} else {
			// Check local file timestamp:
			$localChangeTime = filemtime( $extLocalPath );
		}
		$this->comm->setLocalChangeTime( $localChangeTime );

		// Check remote last change:
		$cDom = $this->comm->readRemoteRepo( $extGitURL );

		$extStatText = "";
		// If there's a document/reply existent, read it:
		if ($cDom) {
			// Get the commit list:
			$commits = $this->comm->getCommits( $cDom, $localChangeTime );
			// Prepare the message
			if ( count( $commits ) > 0 ) {
				if (  $this->comm->getCommitCounter() === 0 ) {
					// No Commits, check if there are translation commits:
					if ( $this->comm->getUpdaterBotCommits() === 0 ) {
						// No translation commits either. Up to date
						$extStatText .= $this->html->rawElement('p', array( 'class' => 'extstat-update-upToDate' ),
							wfMessage( 'extstat-status-uptodate' )
						);
					} else {
						// Only translation commits are available:
						$extStatText .= $this->html->rawElement('p', array( 'class' => 'extstat-update-translupdate' ),
							wfMessage( 'extstat-status-translupdate', $this->comm->getUpdaterBotCommits() )
						);
					}
				} else {
					// There are general commits:
					$extStatText .= $this->html->rawElement('p', array( 'class' => 'extstat-update-updateavailable' ),
							wfMessage( 'extstat-status-updateavailable' )
						);
					$extStatText .= $this->html->rawElement('p', array( 'class' => 'extstat-subtitle-commits' ),
							wfMessage( 'extstat-subtitle-commits', $this->comm->getCommitCounter() )
						);
						
					// See if there are also language updates:
					if ( $this->comm->getUpdaterBotCommits() > 0 ) {
						$extStatText .= $this->html->rawElement('p', array( 'class' => 'extstat-subtitle-language' ), 
								wfMessage( 'extstat-subtitle-language', $this->comm->getUpdaterBotCommits() )
							);
					}
					
					// Add information about the latest commit:
					
					// details of latest commit:
					$context = new RequestContext();
					$commitinfo = wfMessage( 'extstat-commitinfo-latest', $commits[0]['header'], $commits[0]['author'],
							$context->getLanguage()->timeanddate( $commits[0]['date'], true ) )->text();

					//display nicely:
					$extStatText .= $this->html->rawElement('p', array('class' => 'extstat-commitinfo-latest'), $commitinfo );

					$extStatText .= $this->html->rawElement('p', array('class' => 'extstat-commitinfo-link'),
							wfMessage( 'extstat-commitinfo-link', $extGitURL )->text()
						);
				}
			}
		}
		return $extStatText;
	}


	/**
	 * This is an original function from SpecialVersion page. 
	 * For the purpose of ExtensionStatus view, this was heavily edited.
	 *
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
	
		// Extension name / URL:
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

		/* Adding date/change comparison */
		if (isset($gitHeadCommitDate)) {
			$extStatText = $this->getChangeStatus( $name, true, $gitHeadCommitDate );
		} else {
			$extStatText = $this->getChangeStatus( $name, false );
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
	
		$author = isset( $extension['author'] ) ? $extension['author'] : array();

		// Set row:
		$output = "";
		$output .= $this->html->openElement('tr');

		$output .= $this->html->rawElement( 'td',  null,
				$this->html->rawElement('span', array( "class" => "extstat-mainlink" ), $mainLink ) . " <br />" .
					$this->html->rawElement('span', array( "class" => "extstat-versionText" ), $versionText )
			);
		$output .= $this->html->rawElement( 'td', array("class" => "extstat-desc" ), $description );
		$output .= $this->html->rawElement( 'td', array("class" => "extstat-author" ), $this->listAuthors( $author, false ) );

		if ( $vcsText !== false ) {
			$output .= $this->html->rawElement( 'td', array("class"=>"extstat-updateinfo" ),
				$this->html->rawElement( 'span', array("class"=>"extstat-vcstext"), $vcsText ) . " " . $extStatText
			);
		} else {
			$output .= $this->html->rawElement( 'td', array("class"=>"extstat-stats"), $extStatText );
		}

		$output .= $this->html->closeElement( 'tr' );

		return $output;
	}
	
}