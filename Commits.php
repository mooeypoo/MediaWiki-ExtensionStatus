<?php

class SECommits {
	
	protected $doc;
	protected $git;
	
	protected $commitCounter;
	protected $translationBotCommits;
	protected $localChangeTime;
	
	function __construct() {
		$this->doc = new DOMDocument;
		$this->commitCounter = 0;
		$this->updaterBotCommits = 0;
	}
	
	public function reset() {
		$this->doc = new DOMDocument;
		$this->commitCounter = 0;
	}
	
	public function readRemoteRepo( $url ) {
		
		try {
			$output = file_get_contents( $url );
		} catch (Exception $e) {
			//if page can't be read or fetched:
			return false;
		}
		
		$DOM = new DOMDocument;
		$DOM->loadHTML( $output);
		
		return $DOM;
	}
	
	public function getCommits( $dom, $compareTime = 0 ) {
		
		$commits 	= array();
		$authors 	= array();
		$dates 		= array();
		$headers 	= array();
		
		$xpath = new DOMXPath( $dom );

		// collect commit data:
		$authors	= $xpath->query( '//div[@class="title_text"]/span[@class="author_date"]/a' );
		$dates 		= $xpath->query( '//div[@class="title_text"]/span[@class="author_date"]/span[@class="datetime"]' );
		$headers	= $xpath->query( '//div[@class="log_body"]' );

		$this->commitCounter = 0;
		$this->translationBotCommits = 0;
		
		for ($i=0; $i < $authors->length; $i++) {
			$author = trim($authors->item($i)->nodeValue);
			$date = strtotime($dates->item($i)->nodeValue);
			$header = trim($headers->item($i)->nodeValue);

			if ( $author !== "Translation updater bot" ) { //ignore commits from translator bot
				$commits[$i]['author'] = $author;
				$commits[$i]['date'] = $date;
				$commits[$i]['header'] = $header;
				$this->commitCounter++;
			} else {
				$this->translationBotCommits++;
			}

			// reached the same local date.. stop
			if ($compareTime > $date) {
				break;
			}
		}
		

		return $commits;
	}

	public function getCommitCounter() {
		return $this->commitCounter;
	}

	public function getLOcalChangeTime() {
		return $this->localChangeTime;
	}

	public function setLocalChangeTime( $t ) {
		$this->localChangeTime = $t;
	}
	
	public function getUpdaterBotCommits() {
		return $this->translationBotCommits;
	}
}