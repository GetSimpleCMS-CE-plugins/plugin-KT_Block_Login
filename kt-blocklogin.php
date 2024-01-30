<?php
	if(!defined('IN_GS'))
		die('you cannot load this page directly.');

	define('KTFAILEDPATH', GSDATAOTHERPATH.'logs/ktfailedlogins.log'); // path to the failed log
	define('KTFAILEDPATHBU', GSDATAOTHERPATH.'logs/ktfailedloginsbu.log'); //path to the backup failed log
	define('KTLOCKFILE', GSDATAOTHERPATH.'logs/ktlockfile'); //path to lockfile

	define('KTMAXSLEEP', 1); // max sleep time before rejecting the client request
	define('KTSLEEPINTERVAL', 9000); // max average time to process log record
	define('KTMAXFILESIZE',10240); // keep around 100 entries before log rotation
	define('KTBANTIME',3600); // time in seconds to block ip

	@date_default_timezone_set(date_default_timezone_get());

	$ktpluginid = basename(__FILE__, ".php");

	register_plugin(
			$ktpluginid,
			'KT Block Login',
			'2.1',
			'MWK, islander',
			'https://github.com/GetSimpleCMS-CE-plugins/plugin-KT_Block_Login',
			'Block an IP address after 3 failed login attempts from login for 1 hour.',
			'support',
			'kt_blocklogin_display'
	);

	function ktblockip() {
		$ktip = getenv('REMOTE_ADDR');
		$ktiptime = time();
		include_once (GSPLUGINPATH . 'kt-blocklogin/xmlLogClass.php');
		$handle = xmlLogClass::kt_create_class($ktip , $ktiptime);
		if(! $handle || $handle->kt_check_deny())
			redirect($_SERVER['REQUEST_URI']);
		
	}

	function kt_blocklogin_display(){
		include(GSPLUGINPATH . 'kt-blocklogin/kt-init-display.php');
	}

	add_action('successful-login-start', 'ktblockip');
	add_action('support-sidebar','createSideMenu',array($ktpluginid,'IP Blocker'));

?>
