<?php

/**
 *
 * Twitter class to display your tweets in a CakePHP application
 *
 *
 * @author    	Pierre Baron <prbaron22@gmail.com>
 *
 * @copyright	2012-2012 Pierre Baron
 * @link		http://www.pierrebaron.fr
 * @package		app.Lib
 * @since		October 2012
 * @version		1.0
 */
class PBTwitter {

	/**
	 * Twitter API base URL
	 *
	 * @var integer
	 * @access private
	 */
	private $baseURL = 'https://api.twitter.com/1/statuses/user_timeline.json';

	/**
	 * Default options for twitterapi array
	 *
	 * @var integer
	 * @access private
	 */
	private $TWITTERAPI = array(
 		'count'            => 10,
 		'exclude_replies'  => true,
		'include_entities' => true,
		'include_rts'      => true,
		'trim_user'        => true
	);

	/**
	 * Default options for pbtwitter array
	 *
	 * @var integer
	 * @access private
	 */
	private $PBTWITTER = array(
		'cache_duration' => '+30mins',
		'hashtag_class'  => 'pbtwitter-hashtag',
		'url_class'      => 'pbtwitter-url',
		'user_class'     => 'pbtwitter-user'
	);

	/**
	 * twitterapi array
	 *
	 * @var string
	 * @access private
	 */
	private $twitterapi;

	/**
	 * pbtwitter array
	 *
	 * @var integer
	 * @access private
	 */
	private $pbtwitter;


	 /**
	  * Find tweets
	  *
	  * @param	string	$name		The name of your Twitter account
	  * @param	array	$twitterapi	the options for the Twitter api, see $TWITTERAPI for options
	  * @param	array	$pbtwitter	the options for the PBTwitter class, see $PBTWITTER for options
	  * @access public
	  * @return array
	  */
	public function find($name, $twitterapi = array(), $pbtwitter = array()) {
		// Merge the arrays with the default ones
		$this->twitterapi = array_merge($this->TWITTERAPI, $twitterapi);
		$this->pbtwitter	= array_merge($this->PBTWITTER, $pbtwitter);
		
		if(($twits = Cache::read('Twitter.lines')) == false) {
			$twits = $this->_getTwits($name);
			
			Cache::set(array('duration' => $this->pbtwitter['cache_duration'])); 
			Cache::write('Twitter.lines',$twits); 
		}
		
		return $twits;
	}


	/**
	 * Get all tweets
	 *
	 * @param	string $name	The name of your Twitter account
	 * @access private
	 * @return array
	 */
	private function _getTwits($name){
		// Create the full url with the options
		$twitterURL  = $this->baseURL.'?screen_name='.$name;
		$twitterURL .= '&include_entities='.$this->twitterapi['include_entities'];
		$twitterURL .= '&include_rts='.$this->twitterapi['include_rts'];
		$twitterURL .= '&count='.$this->twitterapi['count'];
		$twitterURL .= '&trim_user='.$this->twitterapi['trim_user'];
		$twitterURL .= '&exclude_replies='.$this->twitterapi['exclude_replies'];
		// send the Get request
		App::uses('HttpSocket', 'Network/Http');
		$httpSocket = new HttpSocket();
		$tweets = $httpSocket->get($twitterURL);
		// parse the JSON string
		$tweets = json_decode($tweets);
		$out = array();
		// Add the tweet to a new array
		foreach($tweets as $k => $tweet) {
			$out[$k] = $this->_createTweetArray($tweet);
			
			// Add links for Hashtags, urls, Users
			if(!isset($out['errors']) && $this->twitterapi['include_entities'])
				$out[$k] = $this->_linkEntities($out[$k]);
		}
		return $out;
	}


	/**
	 * Create an array from an object
	 *
	 * @param	string $name	The name of your Twitter account
	 * @access private
	 * @return array
	 */
	private function _createTweetArray($tweetObject) {
		// create new empty array
		$tweetArray = array();
		// Transform Object to Array
		foreach($tweetObject as $key => $value) {
			$tweetArray[$key] = $value;
		}
		
		return $tweetArray;
	}


	/**
	 * Add links for hastags, urls, users
	 *
	 * @param	array $tweetArray	array of one tweet
	 * @access private
	 * @return array
	 */
	private function _linkEntities($tweetArray) {
		// Add link for each hashtag
		foreach($tweetArray['entities']->hashtags as $k => $v) {
			$tweetArray['text'] = preg_replace('~#'.$v->text.'~', '<a href="http://www.twitter.com/search/%23'.$v->text.'" class="'.$this->pbtwitter['hashtag_class'].'"'.'>#'.$v->text.'</a>', $tweetArray['text']);
		}

		// Add link for each url
		foreach($tweetArray['entities']->urls as $k => $v) {
			$tweetArray['text'] = preg_replace('~'.$v->url.'~', '<a href="'.$v->url.'" class="'.$this->pbtwitter['url_class'].'"'.'>'.$v->url.'</a>', $tweetArray['text']);
		}
		
		// Add link for each user
		foreach($tweetArray['entities']->user_mentions as $k => $v) {
			$tweetArray['text'] = preg_replace('~@'.$v->screen_name.'~', '<a href="http://www.twitter.com/'.$v->screen_name.'" class="'.$this->pbtwitter['user_class'].'"'.'>@'.$v->screen_name.'</a>', $tweetArray['text']);
		}

		return $tweetArray;
	}
}