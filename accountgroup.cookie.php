<?php
/*******************************************************************************
* accountgroup.cookie.php
* 通用类
* AccountgroupCookie class

* Public Functions List

			AccountgroupCookie
			exist
			getGroupid
			set
			getExpirationTime
                        remove

* Private Functions List


* Revision 0.01  2014/11/19  created by ema
* Desc: 

********************************************************************************/
header('Expires: Sat, 01 Jan 2000 00:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Cache-Control: post-check=0, pre-check=0',false);
header('Pragma: no-cache');

class AccountgroupCookie {
    var $cookie;
    var $cookieName = "CALLSHOP";
    
    function AccountgroupCookie() {
       if ( isset($_COOKIE[$this->cookieName]) )
           $this->cookie = json_decode( $_COOKIE[$this->cookieName] );
       else
           $this->cookie = NULL;
    }
    
    function exist() {
        return $this->cookie != NULL || $this->cookie->data->groupid == 0;
    }
    
    function getGroupid() {
        if ( $this->exist() ) {
            return $this->cookie->data->groupid;
        }
        return 0;
    }
    
    function set( $groupid, $expire_days ) {
        // 60 * 60 * 24 = 86400 seconds
        $expire = time() + (86400 * $expire_days);
        $data = (object) array("groupid" => $groupid);
        $this->cookie = (object) array("data" => $data, "expire" => $expire);
        setcookie($this->cookieName, json_encode($this->cookie), $expire);
    }
    
    function getExpirationTime() {
        if ( $this->exist() ) {
            return $this->cookie->expire;
        }
        return 0;
    }
        
    function remove() {
        setcookie($this->cookieName, '', time() - 3600);
        $this->cookie = NULL;
    }
}
?>