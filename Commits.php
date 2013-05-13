<?php

class seCommits {
	
	protected $doc;
	
	protected $commitCounter;
	protected $localChangeTime;
	protected $updaterBotCommits;
	
	function __construct() {
		$this->doc = new DOMDocument;
		$this->commitCounter = 0;
		$this->updaterBotCommits = 0;
	}
	
	public function reset() {
		$this->doc = new DOMDocument;
		$this->commitCounter = 0;
	}
	
	public function readRemoteURL( $url ) {
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
		
		$xpath = new DOMXPath( $dom );

		// get the commits links:
		$links = $xpath->query( '//table[@class="shortlog"]/tr/td[@class="link"]/a' );
		
		$commits = array();
		$counter=0;
		$stop=false;
		
		$this->commitCounter = 0;
		$this->updaterBotCommits = 0;

		foreach($links as $link) {

			$stop = false;
			if ( $link->nodeValue == 'commit') {
				$commits[$counter]['link'] = "https://gerrit.wikimedia.org" . $link->getAttribute('href');
				// get info from that commit:
				$newDom = new DOMDocument;
				$newDom = $this->readRemoteURL( $commits[$counter]['link'] );
				
				$author = trim($this->domGetPiece($newDom, "author")->nodeValue);
				if ($author !== "Translation updater bot") { //ignore commits from translator bot
						
					$commits[$counter]['author'] = $author;
					
					$commits[$counter]['date'] = strtotime( $this->domGetPiece($newDom, "date")->nodeValue );
					
					// check if the date of the commit is before the local time:
					if ($compareTime > $commits[$counter]['date'] ) {
						//no need to continue checking commits after this point
						$this->commitCounter = $counter + 1;
						$stop = true;
					}
					
					$commits[$counter]['header'] = trim($this->domGetPiece($newDom, "header")->childNodes->item(0)->textContent);
					$counter++;
				} else {
					$this->updaterBotCommits++;
				}
				if ($counter > 10) { //take only the last 10 commits
					$this->commitCounter = 999; // set it as above-limit
					$stop = true;
				}
					
				if ($stop) {
					break;
				}
			}
		}
		return $commits;
	}
	
	public function domGetPiece( $dom, $piece ) {
		$newXPath = new DOMXPath( $dom );
		$query = "";
		switch( $piece ) {
			case "date":
				$query = '//span[@class="datetime"]';
				break;
			case "author":
				$query = '//a[@class="list"]';
				break;
			case "header":
				$query = '//div[@class="header"]/a';
				break;
		}
		$element = $newXPath->query( $query );

		return $element->item(0);
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
		return $this->updaterBotCommits;
	}
}