<?PHP
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;


//include_once 'lib/configuration.php'; //remove this soon.
require_once( 'lib/CONFIG/class.ConfigMagik.php');
include_once 'lib/constants.class.php';

$CFG = new ConfigMagik( 'lib/config.ini', true, true);
Constants::$CFG = $CFG ;
date_default_timezone_set(Constants::$CFG->get('timezone','Site_Setting'));
define('BASE_URL',Constants::$CFG->get('base_url','Site_Setting'));
define('STARTTIME',$start);

/* get gameserver class */
require Constants::$CFG->get('base_cp','Site_Setting').'libs/gameservers/gameservers.php';
include_once Constants::$CFG->get('base_cp','Site_Setting').'libs/gameservers/adodb5/adodb.inc.php';
	define('DBHOST', Constants::$CFG->get('DBHOST','MySQL'));
	define('DBNAME', Constants::$CFG->get('DBNAME','MySQL'));
	define('DBUSER', Constants::$CFG->get('DBUSER','MySQL'));
	define('DBPASSWORD', Constants::$CFG->get('DBPASSWORD','MySQL'));
	define('DBPREFIX', Constants::$CFG->get('DBPREFIX','MySQL'));
	define('CACHETIME_DB', Constants::$CFG->get('Database','Cache'));
	
include_once 'lib/gameservers.class.php';




foreach(Constants::$SRVLIST as $sid => $g) {
		$game[$sid] = new gameSite( $sid );
		$gameExists = $game[$sid]->gameExists($g);		
}

$info = array();


		foreach(Constants::$SRVLIST as $sid => $g) {	
		//$SQLcheck = $game[$sid]->initiateDB1( $sid );			
			$info[$sid]['Statistics'] = $game[$sid]->getStatistics();
			$info[$sid]['Info'] = $game[$sid]->getInfo();	
		}	
	

foreach(Constants::$SRVLIST as $sid => $g) {		
		$info[$sid]['Basic'] = $game[$sid]->decodeThis('Basic','Settings');
		$info[$sid]['Web'] = $game[$sid]->decodeThis('Website','Settings');
		$info[$sid]['Forum'] = $game[$sid]->decodeThis('Forum','Settings');	
		$info[$sid]['CSS'] = $game[$sid]->decodeThis('CSS','Settings');			
}	

Constants::$SERVER = $game;
// set starttime 

Constants::$INFO = $info;
echo '<pre>';
foreach($info as $ii => $i) {
	print_r($i['Web']);
	print_r($i['Statistics']);
}
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - STARTTIME), 4);
echo 'Page generated in '.$total_time.' seconds.';
?>