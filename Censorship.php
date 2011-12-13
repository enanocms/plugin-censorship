<?php
/**!info**
{
  "Plugin Name"  : "Censorship",
  "Plugin URI"   : "http://enanocms.org/plugin/censorship",
  "Description"  : "Protest SOPA",
  "Author"       : "Dan Fuhry",
  "Version"      : "1.0",
  "Author URI"   : "http://enanocms.org/"
}
**!*/

$plugins->attachHook('render_wikiformat_post', 'anti_sopa_censor($text);');

function anti_sopa_censor(&$text)
{
	// don't censor the stop-sopa page
	global $db, $session, $paths, $template, $plugins; // Common objects
	if ( $paths->page_id == 'activism/stop-sopa' && $paths->namespace == 'Article' )
		return;
	
	// save HTML tags
	$text = preg_split('/(<a .*?<\/a>|<script[^>]*>.*?<\/script>|<(?:[a-z\/].+?|!--.+?--)>)/s', $text, NULL, PREG_SPLIT_DELIM_CAPTURE);
	foreach ( $text as &$block )
	{
		if ( strlen($block) < 1 )
			continue;
		if ( $block{0} == '<' )
			continue;
		
		// split by words
		$block = preg_split('/(\s+)/', $block, NULL, PREG_SPLIT_DELIM_CAPTURE);
		foreach ( $block as &$word )
		{
			if ( preg_match('/^\s+$/', $word) || strlen($word) < 1 || !preg_match('/\w/', $word) )
				continue;
			
			if ( mt_rand(0, 10) == 0 )
			{
				$word = '<a href="http://enanocms.org/activism/stop-sopa" onclick="window.open(this.href); return false;" title="This word redacted by the US Government" style="color: black; background-color: black; background-image: none; padding-right: 0; text-decoration: none;">' .
						preg_replace('/[A-z0-9]/', '█', $word)
						. '</a>'
						;
			}
		}
		$block = implode('', $block);
	}
	$text = implode('', $text);
}

