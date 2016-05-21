<?php
require_once('class.DBconnect.php');
require_once('class.TekstVerwerken.php');
/**
* Class Login (Version PHP7)
*
* With this class you can make a login system.
*
* In the MySQL table, containing your user account information, you only need to add
* 6 fixed fields:
* - id				= int : Is the index for the table (auto_increment)
* - username		= varchar(30) : The login username
* - password		= varchar(40) : The login password
* - salt			= varchar(20) : Used for extra security
* - remember_me		= varchar(40) : Special hashed password to automaticaly login
* - remember_me_ip	= varchar(45) : The IP from where the user can login automaticaly (IPv4 or IPv6)
*
* For protection, the user will only be able to automaticaly login as long as he is working
* with the same IP-address. If the IP changes, the user needs to manual login again.
*
* All the fields added to the user table can be accessed by using $className->data['....']
*
* -----
*
* The login system uses server sessions and cookies to register the login. The session is
* protected against hijacking by using the IP-address of the client.
*
* This class needs following extra classes which can be found at http://forum.rudymas.be/
* 	- class.DBconnect.php (PDO or MySQLi version)
* 	- class.TekstVerwerken.php
*
* !!! Don't forget to add session_start() at the top of all pages that uses this class !!!
*
* History
*
* 2.1.0 - Added the Remember Me part of the login
* 2.0.0 - Rewrote the class to use PHP7 and my own DBconnect class
* 1.2.0 - Added function 'insertUser' (Public)
* 1.1.1 - Added return value TRUE/FALSE to function 'checkUser'
* 1.1.0 - Added function 'updateUser' (Public)
* 1.0.0 - Changed to PHP5 Class
* 0.1.2 - Fixed logoutUser() function
* 0.1.1 - Made a class to use as a login system
*
* @author		Rudy Mas <rudy.mas@rudymas.be>
* @copyright	Copyright (c) 2007 - 2016, rudymas.be. (http://www.rudymas.be/)
* @license		https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
* @version		2.1.0
* @since		2016-04-13
*/
class Login
{
	public $data, $errorCode;
	private $DBname, $DBtable, $DBusername, $DBpassword, $DBhostname, $DBcharSet, $DBtype, $DBcon;

	/** Use this function to setup the MySQL database connection
	* - $classname->setupDB($db, $table, $username, $password, $hostname, $charSet)
	* 
	* $db		: The name of your database
	* $table	: The name of the table
	* $username	: The username to connect to your database
	* $password	: The password to connect to your database
	* $hostname	: The name of your MySQL server
	* $charSet	: Character set to use for your PHP - MySQL connection
	* 			: Set here to 'utf8' as the default character set.
	* 			: You can change this to 'latin1' or any other character set you wish to use.
	*
	* By filling in the standard connection variables in the next line, you can initiate the
	* connection by just using $classname->setupDB() in your code!
	*/
	public function setupDB($db = 'DBNAME', $table = 'TABLE', $username = 'USERNAME',
							$password = 'PASSWORD', $hostname = 'localhost', $charSet = 'utf8', $dbtype = 'mysql')
	{
		$this->DBname = $db;
		$this->DBtable = $table;
		$this->DBusername = $username;
		$this->DBpassword = $password;
		$this->DBhostname = $hostname;
		$this->DBcharSet = $charSet;
		$this->DBtype = $dbtype;
	}
	
	/** Use this function to logout the user
	*
	* $cookie	: TRUE/FALSE (Default: FALSE)
	*			: Set this to TRUE is you won't to delete the cookies as well
	*/
	public function logoutUser($cookie = FALSE)
	{
		unset($_SESSION['password']);
		unset($_SESSION['IP']);
		unset($this->data);
		if ($cookie == TRUE)
		{
			setcookie('username', '', -1, '/');
			setcookie('rememberMe', '', -1, '/');
		}
	}	

	/** Use this function to login the user
	*
	* $user			: The username to login with
	* $pass			: The password to login with
	* $rememeber	: If set to TRUE, the user is logged on until his IP-address changes
	*
	* This function returns 'true' when the login succeeded, and 'false' when it failed
	* All userdate will be stored in $classname->data (array)
	*/
	public function loginUser($user, $pass, $remember = FALSE)
	{
		$DBcon = new DBconnect($this->DBhostname, $this->DBusername, $this->DBpassword, $this->DBname, $this->DBcharSet, $this->DBtype);
		$query = "SELECT * FROM {$this->DBtable} WHERE username = {$DBcon->cleanInvoer($user)}";
		$DBcon->query($query);
		if ($DBcon->rows != 0)
		{
			$DBcon->fetch(0);
			if (sha1($pass.$DBcon->data['salt']) == $DBcon->data['password'])
			{
				$sha1paswoord = sha1($pass.$DBcon->data['salt']);
				setcookie('username', $user, time() + (30 * 24 * 3600), '/');
				if ($remember === TRUE)
				{
					$tekst = new TekstVerwerken;
					$this->data['remember_me'] = $tekst->randomTekst(25);
					$this->data['remember_me_ip'] = $this->getIP();
					$this->updateUser($user);
					setcookie('rememberMe', $this->data['remember_me'], time() + (30 * 24 * 3600), '/');
				}
				else
				{
					$_SESSION['password'] = $sha1paswoord;
					$_SESSION['IP'] = $this->getIP();
				}
				$this->data = $DBcon->data;
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}

	/** Use this function to check the status of the user and to get all the user information
	*
	* All userdate will be stored in $classname->data (array)
	*/
	public function checkUser()
	{
		if (isset($_COOKIE['username'])) $username = $_COOKIE['username']; else $username = '';
		if (isset($_COOKIE['rememberMe']))
		{
			$password = $_COOKIE['rememberMe'];
			$remember = TRUE;
		}
		elseif (isset($_SESSION['password']) && isset($_SESSION['IP']))
		{
			$password = $_SESSION['password'];
			$IP = $_SESSION['IP'];
			$remember = FALSE;
		}
		else
		{
			$password = '';
			$IP = '';
		}
		if ($username != "" && $password != "")
		{
			$DBcon = new DBconnect($this->DBhostname, $this->DBusername, $this->DBpassword, $this->DBname, $this->DBcharSet, $this->DBtype);
			$query = "SELECT * FROM {$this->DBtable} WHERE username = {$DBcon->cleanInvoer($username)}";
			$DBcon->query($query);
			if ($DBcon->rows != 0)
			{
				$DBcon->fetch(0);
				if ($password == ($remember) ? $DBcon->data['remember_me'] : $DBcon->data['password'])
				{
					if ($remember) $IP = $DBcon->data['remember_me_ip'];
					if ($IP == $this->getIP())
					{
						$this->data = $DBcon->data;
						return TRUE;
					}
					else
					{
						unset($this->data);
						$this->logoutUser(TRUE);
						?>
						<script type="text/javascript">
							alert('Your login isn\'t valid anymore!\nYou have been logged out,\nand need to login again!');
						</script>
						<?php
						return FALSE;
					}
				}
				else
				{
					unset($this->data);
					return FALSE;
				}
			}
			else
			{
				unset($this->data);
				return FALSE;
			}
		}
		else
		{
			unset($this->data);
			return FALSE;
		}
	}
	
	/**
	* Use this function to add a new user to your database
	*
	* Returns FALSE with errorCode 2 when there is a login problem
	* Returns False with errorCode 9 when the username already exists
	*/
	public function insertUser()
	{
		$tekst = new TekstVerwerken;
		$this->data['salt'] = $tekst->randomTekst(20);
		$this->data['remember_me'] = NULL;
		$this->data['remember_me_ip'] = NULL;
		
		$DBcon = new DBconnect($this->DBhostname, $this->DBusername, $this->DBpassword, $this->DBname, $this->DBcharSet, $this->DBtype);
		$query = "SELECT id FROM {$this->DBtable} WHERE username = {$DBcon->cleanInvoer($this->data['username'])}";
		$DBcon->query($query);
		if ($DBcon->rows != 0)
		{
			$this->errorCode = 9;
			return FALSE;
		}

		$query = "SHOW COLUMNS FROM {$this->DBtable}";
		$DBcon->query($query);
		$aantalVelden = $DBcon->rows;
		for ($x=0; $x<$aantalVelden; $x++)
		{
			$DBcon->fetch($x);
			$naamVeld[$x] = $DBcon->data['Field'];
		}
		
		$query = "INSERT INTO {$this->DBtable} ";
		$query .= "VALUES (0";
		for ($x=1; $x<$aantalVelden; $x++)
		{
			$query .= ", ";
			if ($naamVeld[$x] == 'password')
			{
				$query .= '\''.sha1($this->data['password'].$this->data['salt']).'\'';
			}
			else
			{
				$query .= $DBcon->cleanInvoer($this->data[$naamVeld[$x]]);
			}
		}
		$query .= ")";
		$DBcon->insert($query);
		if ($this->loginUser($this->data['username'], $this->data['password']))
		{
			return TRUE;
		}
		else
		{
			$this->errorCode = 2;
			return FALSE;
		}
	}
	
	/**
	* Use this function to update the account data in the database
	*/
	public function updateUser($user = '')
	{
		if (isset($_COOKIE['username'])) $username = $_COOKIE['username']; elseif ($user != '') $username = $user; else return false;
		$DBcon = new DBconnect($this->DBhostname, $this->DBusername, $this->DBpassword, $this->DBname, $this->DBcharSet, $this->DBtype);
		$query = "UPDATE {$this->DBtable} SET ";
		foreach ($this->data as $key => $value)
		{
			$query .= "{$key} = '{$value}', ";
		}
		$query = substr($query, 0, -2);
		$query .= " WHERE username = {$DBcon->cleanInvoer($username)}";
		$DBcon->update($query);
	}
	
	/**
	* Internal function to get the IP of the client
	*/
	private function getIP()
	{
		if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER))
		{
			$adressen = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']);			
			$IP = $adressen[count($adressen)-1];  
		}
		elseif (array_key_exists('REMOTE_ADDR', $_SERVER))
		{ 
			$IP = $_SERVER['REMOTE_ADDR']; 
		}
		elseif (array_key_exists('HTTP_CLIENT_IP', $_SERVER))
		{
			$IP = $_SERVER['HTTP_CLIENT_IP']; 
		}
		else
		{
			$IP = 'Unknown';
		}
		return $IP;
	}
}
/** End of File: class.Login.php **/