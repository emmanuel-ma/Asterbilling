#!/usr/bin/php -q
<?
/**************************************************************************
* asterb-notifications-daemon.php
* Asteriskbilling AOC notifications script

* Revision 0.01  2014/09/09 22:25:00  modified by ema
* Desc: 

***************************************************************************/
require_once ('include/DB.php');
//require_once ('include/PEAR.php');
//require_once ('include/localization.class.php');
require_once ('include/asterevent.class.php');
require_once ('include/asteriskami.class.php');


$config['asterisk']['server'] = "127.0.0.1";
$config['asterisk']['port'] = "5038";
$config['asterisk']['username'] = "astercc";
$config['asterisk']['secret'] = "astercc";

$config['database']['dbtype'] = "mysql";
$config['database']['dbhost'] = "127.0.0.1";
$config['database']['dbport'] = "3306";
$config['database']['dbname'] = "astercc";
$config['database']['username'] = "root";
$config['database']['password'] = "mysqlroot";

// Number of tries for AMI reconnection, -1 for infinitive loop
define('MAX_CONNECTION_TRIES', -1);

// Constants to log messages of Asterbilling
// LOG_ENABLED not use for asterb-notifications-daemon
define('LOG_ENABLED', FALSE);
// Share File Log to asterb-notifications-daemon
define('FILE_LOG','/var/log/asterb-notifications-daemon.log');

// Constants to not provoque errors
//define('_SESSION',new Array());

// define database connection string
define('SQLC', 
$config['database']['dbtype']."://".$config['database']['username'].":".$config['database']['password']."@".$config['database']['dbhost'].":".$config['database']['dbport']."/".$config['database']['dbname']."");

// set a global variable to save database connection
$db = DB::connect(SQLC);

// need to check if db connected
if (DB::iserror($db)){
   $msg = $db->getmessage();
   logger($msg);
   die($msg);
}

// change database fetch mode
$db->setFetchMode(DB_FETCHMODE_ASSOC);

$myAsterisk = new AsteriskAMI();
$myAsterisk->config['asmanager'] = $config['asterisk'];
$res = $myAsterisk->connect();

if (!$res){
   $msg = "Can't connect to Asterisk Manager Interface!";
   logger($msg);
   die($msg);
}

//$myAsterisk->add_event_handler('bridge','dump_to_db'); //insert all answered calls
//$manager->add_event_handler('hangup','dump_to_db'); // delete the corresponding anwered calls
//$manager->add_event_handler('newstate','dump_to_db'); // 
//$manager->add_event_handler('extensionstatus','dump_to_db'); // 
//$manager->add_event_handler('peerstatus','dump_to_db'); //
//$manager->add_event_handler('varset','dump_to_db'); // 

$cstatus = array();

while ( $res ) {
   sleep("1");
   if ( $myAsterisk->Ping() ) {
//      print("OK!");
      showStatus();
      sleep("1");
   } else {
      $myAsterisk->disconnect();
      $tries = 0;
      $msg = "Reconnecting to AMI until ".MAX_CONNECTION_TRIES." tries!";
//      printf($msg);
      logger($msg);

      while ( $tries < MAX_CONNECTION_TRIES && $res == FALSE ) {
         sleep("3");
         $res = $myAsterisk->connect();
         $tries++;
      }
   }
}

$msg = "There was a problem on the reconnection to Asterisk Manager Interface!";
//printf($msg);
logger($msg);

/*$response = $manager->wait_response(TRUE);
while (!$response) {
   if ($reconnect) {
      sleep("1");
      $con = $manager->connect($manager_ip,$username,$secret);
      while (!$con) {
         sleep("1");
         $con = $manager->connect($manager_ip,$username,$secret);
      }
      $response = $manager->wait_response(TRUE);
   } else {
      exit();
   }
}*/

function getGroupidWithPeers(){
   global $db;
   $gpeers = array();

   $query = "SELECT * FROM clid ORDER BY groupid ASC";
   $clid_list = $db->query($query);

   while ($clid_list->fetchInto($clid)) {
      $gpeers[$clid['groupid']][] = $clid['clid'];
   }

   return $gpeers;
}

function showStatus() {
   global $cstatus, $myAsterisk;

   $gpeers = getGroupidWithPeers();

   foreach ($gpeers as $groupid => $peers) {
      $peerstatus = astercc::checkPeerStatus($groupid,$peers);
      #print_r($peerstatus);exit;

      //if ( empty($peerstatus) ) continue;

      foreach ($peers as $peer) {
         // update peer status

         //credit changed
         if ( (array_key_exists($peer,$peerstatus) &&
               (empty($cstatus) || $cstatus[$peer]['credit'] != $peerstatus[$peer]['credit'])) && 
               ($peerstatus[$peer]['pushcall'] == 'LINK' || 
               $peerstatus[$peer]['pushcall'] == 'no')) 
         {
            //$myAsterisk->sendText( 
            // Solo debe mostrar un decimal del costo en el display
            $cost_call = $peerstatus[$peer]['credit'];

            if ( $cost_call != 0.0 )
               $myAsterisk->AOCMessage( 
                  ($peerstatus[$peer]['direction'] == 'outbound' ? $peerstatus[$peer]['srcchan'] :$peerstatus[$peer]['dstchan']),
                  "pesos", ($cost_call * 10), "OneTenth" );
               //"Costo: $".astercc::creditDigits($peerstatus[$peer]['credit']);
//		)
//               print( "pesos - " . astercc::creditDigits($peerstatus[$peer]['credit'] * 10) .  " - OneTenth" );
         }
      }
   }

   $cstatus = $peerstatus;
   return;
}

function logger($message) {
   if ( FILE_LOG != "" ) {
      $handle = fopen(FILE_LOG,"a");
      $data = date("[Y-m-d H:i:s] ");
      fwrite($handle,"$data$message");
      fwrite($handle,"\n");
      fclose($handle);
   }
}
?>
