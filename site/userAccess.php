<?

class userAccess {
	
	// database information
	var $dbUser 	= 'hoffpauir';
	var $dbPass 	= 'generallee';
	var $dbHost 	= 'mysql.bamgruz.com';
	var $dbName 	= 'bamgruz';
	var $dbTable	= 'users';
	var $dbConn		= '';
	var $dbFields	= array('id' => 'id', 'name' => 'bgname', 'uname' => 'bguname', 'pword' => 'bgpword');
	
	// cookie information
	var $cDomain 	= '';
	var $cTime 		= 2592000;
	var $cName 		= "BamGruzCookie";
	
	// encryption information
	var $eMethod 	= "sha1";
	
	// session information
	var $sVariable 	= "userSessionVariable";
	
	// user information
	var $uData 		= array();
	var $uId;	
	
	function userAccess()
	{
		
		// set cookie domain
		$this->cDomain = $_SERVER['HTTP_HOST'];
		
		// connect to the host
		$this->dbConn = mysql_connect($this->dbHost, $this->dbUser, $this->dbPass);
		if(!$this->dbConn) die(mysql_error($this->dbConn));
		
		// select the database
		mysql_select_db($this->dbName, $this->dbConn) or die(mysql_error($this->dbConn));
		
		/*
		// check for session
		if(!isset($_SESSION)) session_start();
		
		// session is not empty, load user data
		if(!empty($_SESSION[$this->sVariable]))
		{
			// $this->loadUserData($_SESSION[$this->sVariable]);
		}
		*/
				
/*
		if(isset($_COOKIE[$this->cName]) && !$this->isLoaded())
		{
			print_r("dude yeah");
			$u = unserialize(base64_decode($_COOKIE[$this->cName]));
			$this->login($u['uname'], $p['pword']);
		}
		*/
	}
	
	function login($uname, $pword, $remember = false, $loadUser = true)
	{
		// logs in the user and sets a cookie if $remember is true
		$uname = $this->escape($uname);
		$pword = $this->escape($pword);
		// $pword = "SHA1('$pword')";
		
		$result = $this->query("SELECT * from `{$this->dbTable}` WHERE `{$this->dbFields['uname']}`= '$uname' AND `{$this->dbFields['pword']}` = '$pword' LIMIT 1", __LINE__);
			if(mysql_num_rows($result) == 0) $this->error("username or password not valid", __LINE__);
		if($loadUser)
		{
			$this->uData = mysql_fetch_array($result);
			$this->uId = $this->uData[$this->dbFields['id']];
			$_SESSION[$this->sVariable] = $this->uId;
			if($remember)
			{
				$cookie = base64_encode(serialize(array('uname'=>$uname, 'pword'=>$pword)));
				$a = setcookie($this->cName, $cookie, time()+$this->cTime, '/', $this->cDomain);
			}
		}
		return true;
	}
	
	function logout()
	{
		
		// logs the user out, but doesn't redirect
		
		setcookie($this->cName, '', time()-3600);
		$_SESSION[$this->sVariable] = '';
		$this->uData = '';
	}
	
	
	function is($prop)
	{
		return $this->get_property($prop) == 1 ? true : false;
	}
	
	
	function get_property($prop)
	{
		if(empty($this->uId)) $this->error('No user loaded', __LINE__);
		if(!isset($this->uData[$prop])) $this->error('Unknown property: ' . $prop, __LINE__);
		return $this->userData[$prop];
	}
	
	
	function is_active()
	{
		return $this->uData[$this->dbFields['active']];
	}
	
	
	function is_loaded()
	{
		return empty($this->uId) ? false : true;
	}
	
	
	function activate()
	{
		if(empty($this->uId)) $this->error('No user loaded', __LINE__);
		if($this->is_active()) $this->error('User already active'. __LINE__);
		$result = $this->query("UPDATE `{$this->dbTable}` SET `{$this->dbFields['active']}` = 1 WHERE `{$this->dbFields['id']}` = '" . $this->escape($this->uId) . "' LIMIT 1");
		if(@mysql_affected_rows() == 1)
		{
			$this->uData[$this->dbFields['active']] = true;
			return true;
		}
		return false;
	}
	
	
	function insertUser($data)
	{
		if(!is_array($data)) $this->error('User data is not an array', __LINE__);
		$pword = "SHA1('" . $data[$this->dbFields['pword']] . "')";
		foreach($data as $k => $v) $data[$k] = "'" . $this->escape($v) . "'";
		$data[$this->dbFields['pword']] = $pword;
		$this->query("INSERT INTO `{$this->dbTable}` (`" . implode('`, `', array_keys($data)) . "`) VALUES (" . implode(", ", $data) . ")");
		return (int)mysql_insert_id($this->dbConn);
	}
	
	
	function query($sql, $line= 'Unknown')
	{
		print_r("query: " . $sql);
		$result = mysql_query($sql, $this->dbConn);
		if(!$result) $this->error(mysql_error($this->dbConn), $line);
		return $result;
	}
	
	
	function loadUserData($uId)
	{		
		// retrieve user data from database
		$result = $this->query("SELECT * from `{$this->dbTable}` WHERE `{$this->dbFields['id']}` = '" . $this->escape($uId) . "' LIMIT 1");		
		if(mysql_num_rows($result) == 0) return false;
		// if there is a result, set the session to their id
		$this->uData = mysql_fetch_array($result);
		$this->uId = $uId;	
		$_SESSION[$this->sVariable] = $this->uId;
		return true;
	}
	
	
	function escape($str)
	{
		// kind of like addslashes()
		$str = get_magic_quotes_gpc() ? stripslashes($str) : $str;
		$str = mysql_real_escape_string($str, $this->dbConn);
		return $str;
	}
	
	
	function error($error, $line = 'Unknown', $die = false)
	{
		echo "<br><br>Error: " . $error . " <br>on line: " . $line . "<br><br>";
		return false;
	}
	
	
	function checkData($data)
	{
		$data['name'];
	}
}

?>