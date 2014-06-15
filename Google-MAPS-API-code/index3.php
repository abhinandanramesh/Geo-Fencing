
Conversation opened. 1 read message.
Something's not right.
We're having trouble connecting to Google. We'll keep trying...
 
 
More
 
5 of 511
 
Why this ad?
Mobile App Developer? - www.dreamfactory.com - Free easy to use backend hosting REST API with JSON for iOS, Android
API
Inbox
	x
Ankit Jain
	
2:34 PM (21 hours ago)
		
to me
9 Attachments
[Text]
[PHP]
[Binary File]
[PHP]
[PHP]
[PHP]
[PHP]
[Javascript]
[Text]
	
Click here to Reply or Forward
Why this ad?Ads –
Mobile App Developer?
Free easy to use backend hosting REST API with JSON for iOS, Android
www.dreamfactory.com
1.14 GB (7%) of 15 GB used
Manage
©2014 Google - Terms & Privacy
Last account activity: 1 hour ago
Details
	
	
Ankit Jain's profile photo
	Ankit Jain
Show details
Ads
Custom Servers Solutions
More Custom Servers from a Trusted Source. 24/7 Support. Chat Now!
www.softlayer.com/dedicated-server
Wanna power management ic
11rd year Gold Supplier,Low MOQ, Top Quality,all specs available!
www.jgxdz.com/en
Facebook Group Messaging
Facebook Helps You Connect and Share with Friends. Sign Up Today!
www.facebook.com
Call For Paper 2014
Publish Your Research Article In International Journal:IOSR JOURNALS
iosrjournals.org
Mobile App Developer?
<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
* forms frameset
*
*                                              or common.inc.php
* @package PhpMyAdmin
*/

/**
* Gets core libraries and defines some variables
*/
require_once './libraries/common.inc.php';

// free the session file, for the other frames to be loaded
session_write_close();

// Gets the host name
if (empty($HTTP_HOST)) {
   if (PMA_getenv('HTTP_HOST')) {
       $HTTP_HOST = PMA_getenv('HTTP_HOST');
   } else {
       $HTTP_HOST = '';
   }
}


// purge querywindow history
$cfgRelation = PMA_getRelationsParam();
if ($GLOBALS['cfg']['QueryHistoryDB'] && $cfgRelation['historywork']) {
   PMA_purgeHistory($GLOBALS['cfg']['Server']['user']);
}
unset($cfgRelation);


/**
* pass variables to child pages
*/
$drops = array('lang', 'server', 'collation_connection',
   'db', 'table');

foreach ($drops as $each_drop) {
   if (array_key_exists($each_drop, $_GET)) {
       unset($_GET[$each_drop]);
   }
}
unset($drops, $each_drop);

if (! strlen($GLOBALS['db'])) {
   $main_target = $GLOBALS['cfg']['DefaultTabServer'];
} elseif (! strlen($GLOBALS['table'])) {
   $_GET['db'] = $GLOBALS['db'];
   $main_target = $GLOBALS['cfg']['DefaultTabDatabase'];
} else {
   $_GET['db'] = $GLOBALS['db'];
   $_GET['table'] = $GLOBALS['table'];
   $main_target = ! empty($GLOBALS['goto']) ? $GLOBALS['goto'] : $GLOBALS['cfg']['DefaultTabTable'];
}

$url_query = PMA_generate_common_url($_GET);

if (isset($GLOBALS['target']) && is_string($GLOBALS['target']) && !empty($GLOBALS['target']) && in_array($GLOBALS['target'], $goto_whitelist)) {
   $main_target = $GLOBALS['target'];
}

$main_target .= $url_query;

$lang_iso_code = $GLOBALS['available_languages'][$GLOBALS['lang']][1];


// start output
require './libraries/header_http.inc.php';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"
   xml:lang="<?php echo $lang_iso_code; ?>"
   lang="<?php echo $lang_iso_code; ?>"
   dir="<?php echo $GLOBALS['text_dir']; ?>">
<head>
<link rel="icon" href="./favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="./favicon.ico" type="image/x-icon" />
<title>phpMyAdmin <?php echo PMA_VERSION; ?> -
   <?php echo htmlspecialchars($HTTP_HOST); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex,nofollow" />
<script type="text/javascript">
// <![CDATA[
   // definitions used in common.js
   var common_query = '<?php echo PMA_escapeJsString(PMA_generate_common_url('', '', '&'));?>';
   var opendb_url = '<?php echo PMA_escapeJsString($GLOBALS['cfg']['DefaultTabDatabase']); ?>';
   var safari_browser = <?php echo PMA_USR_BROWSER_AGENT == 'SAFARI' ? 'true' : 'false' ?>;
   var querywindow_height = <?php echo PMA_escapeJsString($GLOBALS['cfg']['QueryWindowHeight']); ?>;
   var querywindow_width = <?php echo PMA_escapeJsString($GLOBALS['cfg']['QueryWindowWidth']); ?>;
   var collation_connection = '<?php echo PMA_escapeJsString($GLOBALS['collation_connection']); ?>';
   var lang = '<?php echo PMA_escapeJsString($GLOBALS['lang']); ?>';
   var server = '<?php echo PMA_escapeJsString($GLOBALS['server']); ?>';
   var table = '<?php echo PMA_escapeJsString($GLOBALS['table']); ?>';
   var db    = '<?php echo PMA_escapeJsString($GLOBALS['db']); ?>';
   var token = '<?php echo PMA_escapeJsString($_SESSION[' PMA_token ']); ?>';
   var text_dir = '<?php echo PMA_escapeJsString($GLOBALS['text_dir']); ?>';
   var pma_absolute_uri = '<?php echo PMA_escapeJsString($GLOBALS['cfg']['PmaAbsoluteUri']); ?>';
   var pma_text_default_tab = '<?php echo PMA_escapeJsString(PMA_getTitleForTarget($GLOBALS['cfg']['DefaultTabTable'])); ?>';
   var pma_text_left_default_tab = '<?php echo PMA_escapeJsString(PMA_getTitleForTarget($GLOBALS['cfg']['LeftDefaultTabTable'])); ?>';

   // for content and navigation frames

   var frame_content = 0;
   var frame_navigation = 0;
   function getFrames() {
<?php if ($GLOBALS['text_dir'] === 'ltr') { ?>
       frame_content = window.frames[1];
       frame_navigation = window.frames[0];
<?php } else { ?>
       frame_content = window.frames[0];
       frame_navigation = window.frames[1];
<?php } ?>
   }
   var onloadCnt = 0;
   var onLoadHandler = window.onload;
   window.onload = function() {
       if (onloadCnt == 0) {
           if (typeof(onLoadHandler) == "function") {
               onLoadHandler();
           }
           if (typeof(getFrames) != 'undefined' && typeof(getFrames) == 'function') {
               getFrames();
           }
           onloadCnt++;
       }
   };
// ]]>
</script>
<?php
echo PMA_includeJS('jquery/jquery-1.6.2.js');
echo PMA_includeJS('update-location.js');
echo PMA_includeJS('common.js');
?>
</head>
<frameset cols="<?php
if ($GLOBALS['text_dir'] === 'rtl') {
   echo '*,';
}
echo $GLOBALS['cfg']['NaviWidth'];
if ($GLOBALS['text_dir'] === 'ltr') {
   echo ',*';
}
?>" rows="*" id="mainFrameset">
   <?php if ($GLOBALS['text_dir'] === 'ltr') { ?>
   <frame frameborder="0" id="frame_navigation"
       src="navigation.php<?php echo $url_query; ?>"
       name="frame_navigation" />
   <?php } ?>
   <frame frameborder="0" id="frame_content"
       src="<?php echo $main_target; ?>"
       name="frame_content" />
   <?php if ($GLOBALS['text_dir'] === 'rtl') { ?>
   <frame frameborder="0" id="frame_navigation"
       src="navigation.php<?php echo $url_query; ?>"
       name="frame_navigation" />
   <?php } ?>
   <noframes>
       <body>
           <p><?php echo __('phpMyAdmin is more friendly with a <b>frames-capable</b> browser.'); ?></p>
       </body>
   </noframes>
</frameset>
</html>
index5.php

6 of 9
Displaying index5.php.
