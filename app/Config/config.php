<?php

$config['Site'] = array(
	'name' => 'EU-GENIE',
	'slogan' => 'EU-GENIE - for your health',
	'description' => 'EU-GENIE - For your health',
	'url' => '',
	'email_from' => array('no-reply@eu-genie.org' => 'EU-GENIE'),
	'email_to' => array('info@eu-genie.org' => 'EU-GENIE'),
	'languages' => array(
		'eng' => 'English',
		'bul' => 'Bulgarian',
		'gre' => 'Greek',
		'spa' => 'Spanish',
	),

	'kcfinder_upload_url' => '/uploads/',
	'kcfinder_upload_dir' => WWW_ROOT.'/uploads/',

	'twitter' => 'euwise',
	'embed_width' => 580,
	'embed_height' => 500,
	'remote_timeout' => 20,
	'oEmbedEndpoints' => array(
		array(
		   "url"=> "http://(www\.)?vimeo\.com/* and http://vimeo.com/groups/*/videos/*",
		   "url_re"=> "vimeo\\.com/.*",
		   "example_url"=> "http://vimeo.com/1211060",
		   "endpoint_url"=> "http://vimeo.com/api/oembed.json",
		   "title"=> "Vimeo"
		  ),
		  array(
		   "url"=> "http://*.youtube.com/watch*",
		   "url_re"=> "youtube\\.com/watch.+v=[\\w-]+&?",
		   "example_url"=> "http://www.youtube.com/watch?v=vk1HvP7NO5w",
		   "endpoint_url"=> "http://www.youtube.com/oembed",
		   "title"=> "YouTube"
		  ),

	  ),
	/*'facebook'=>'x',

	'twitter_consumer_key'=>'x',
	'twitter_consumer_secret'=>'x',

	'facebook_app_id'=>'x',
	'facebook_secret'=>'x',*/
);
