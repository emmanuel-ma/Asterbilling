<?php
/*******************************************************************************
* asteriskami.class.php
* ami asterisk class

* Revision 0.01  2014/09/09 10:25:00  modified by ema
* Desc: create class, add sendText and AOCMessage methods

********************************************************************************/
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'asterisk.class.php');

class AsteriskAMI extends Asterisk {
   function sendText($channel, $message='', $actionid=NULL){
      //$req = "Action: SendText\r\n";

      $parameters = array('Channel'=>$channel);
      $parameters['Message'] = $message;
      if($actionid) $parameters['ActionID'] = $actionid;

      /*foreach($parameters as $var=>$val)
         $req .= "$var: $val\r\n";
      $req .= "\r\n";
      
      //print $req; exit;
      return fwrite($this->socket, $req);
      */

      $res = $this->send_request('sendtext', $parameters);
      if($res['Response'] != 'Success') {
         //$this->disconnect();
         return false;
      }
      return true;
   }

   function AOCMessage($channel, 
			$currencyname='MXN', $currencyamount=0, $currencymultiplier='One',
			$msgtype='D', $chargetype='Currency', $totaltype='subtotal', 
                        $aocbillingid='normal', $actionid=NULL){
      //$req = "Action: SendText\r\n";

      $parameters = array('Channel'=>$channel);
      $parameters['MsgType'] = $msgtype;
      $parameters['ChargeType'] = $chargetype;
      $parameters['CurrencyName'] = $currencyname;
      $parameters['CurrencyAmount'] = $currencyamount;
      $parameters['CurrencyMultiplier'] = $currencymultiplier;
      $parameters['TotalType'] = $totaltype;
      $parameters['AOCBillingId'] = $aocbillingid;
      if($actionid) $parameters['ActionID'] = $actionid;

      /*foreach($parameters as $var=>$val)
         $req .= "$var: $val\r\n";
      $req .= "\r\n";
      
      //print $req; exit;
      return fwrite($this->socket, $req);
      */

      $res = $this->send_request('aocmessage', $parameters);
      if($res['Response'] != 'Success') {
         //$this->disconnect();
//         print( "Channel: " . $channel );
//         print( "Message: " . $res['Message'] );
         return false;
      }

      return true;
   }

   /*function isConnected() {
      return !feof($this->socket);
   }*/
}

?>
