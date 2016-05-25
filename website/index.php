<?php
/**
* The Vault - WebApp to encrypt and decrypt files
*
* This app will give you the options to encrypt a file with DES or AES.
* For extra protection, we use RSA to encrypt the DES/AES key and the HASH of the file.
* The file can only safely being decrypted when both parties are known.
*
* This app also does Steganography. The WebApp will RSA encrypt a file or text and add
* it to a photo which you upload. The result will be a PNG image which includes the hidden
* data. (The Stega class can be used to add plain text to an image, the Crypt class adds
* the RSA encryption to it.)
*
* @author		Rudy Mas <rudy.mas@rudymas.be>
* @copyright	Copyright (c) 2016, rudymas.be. (http://www.rudymas.be/)
* @license		https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
* @version		0.80 (Beta version)
* @since		2016-05-21
*/
session_start();

// Check if the configuration file has been loaded, if not, load it
if ( ! isset($_SESSION['version'])) require_once('config/config.php');

// Loading the Login Class and checking if there is a user loged in. If so, the user data is loaded
require_once('lib/class.Login.php');
$login = new Login;
$login->setupDB($_SESSION['mysqlDatabase'], 'li_user', $_SESSION['mysqlGebruiker'], $_SESSION['mysqlPaswoord'], $_SESSION['mysqlHost']);
if ($login->checkUser()) $_SESSION['aangemeld'] = TRUE;
else $_SESSION['aangemeld'] = FALSE;

// Loading the HTML5 Class
require_once('lib/HTML5/class.HTML5.php');
$html = new HTML5('nl-BE');

// Loading the Display Class (This class contains all the views and is linked with the Login Class)
require_once('lib/class.Display.php');
$display = new Display($login);

// Loading the Process Claas (This class contains all the extra code and is linked with the Login Class)
require_once('lib/class.Process.php');
$process = new Process($login);

// Loading the BestandsBeheer Class (This class containt functions to work with files)
require_once('lib/class.BestandsBeheer.php');
$bestand = new BestandsBeheer;

// Mapping a few important maps and adding them to the Server's session
$_SESSION['download'] = dirname(__FILE__).'/download/';
if ($_SESSION['aangemeld'] === TRUE)
{
	$_SESSION['workspace'] = dirname(__FILE__).'/workspace/'.$login->data['salt'].'/';
	$bestand->aanmakenMap($_SESSION['workspace']);
}

// Some conditions to determine which page to load
if ($_SESSION['aangemeld'] === TRUE)
{
	if (isset($_GET['page'])) $_SESSION['page'] = $_GET['page'];
	elseif ( ! isset($_SESSION['page'])) $_SESSION['page'] = 'home';
}
else
{
	if (isset($_GET['page']))
	{
		if ($_GET['page'] == 'registreren')
		{
			$_SESSION['page'] = $_GET['page'];
		}
		else
		{
			$_SESSION['page'] = 'login';
		}
	}
	else
	{
		$_SESSION['page'] = 'login';
	}
}

// Checking if some action has to be taken (Either by a Get or a Post)
if (isset($_GET['aktie'])) $process->action($_GET['aktie']);
elseif (isset($_POST['aktie'])) $process->action($_POST['aktie']);

// Starting the HTML output
echo $html->head('open', 'Basic Security: Enryption/Decryption', 'Rudy Mas', 'rudy.mas@rudymas.be', 'des, aes, rsa, hash, encryption, decryption', 'Beheer van RSA sleutels en online encryptie en decryptie via DES of AES.');
echo $html->addCSS('css/global.css', 'screen');
if (isset($_SESSION['page']))
{
	echo $html->addCSS('lib/HTML5/css/menu1.css', 'screen');
	echo $html->addCSS('css/menuIcon.css', 'screen');
	if (file_exists('css/'.strtolower($_SESSION['page']).'.css')) echo $html->addCSS('css/'.strtolower($_SESSION['page']).'.css', 'screen');
	echo $html->addCSS('css/footer.css', 'screen');
}
echo $html->addScript('jquery/jquery-2.1.4.min.js');
echo $html->addScript('jquery/doubletaptogo.js');
echo $html->addScript('jquery/functions.js');
echo $html->addScript('jquery/jquery.filedownload.js');
echo $html->head('close');
echo $html->body('open');
echo $display->displayPage($_SESSION['page']);
//$html->testVar();
echo $html->body('close');
/** End of File: index.php **/