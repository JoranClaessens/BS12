<?php
/**
* Class Process - This is used to process all interaction from the user
*
* @author		Rudy Mas <rudy.mas@rudymas.be>
* @copyright	Copyright (c) 2016, rudymas.be. (http://www.rudymas.be/)
* @license		https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
* @version		0.5.5
* @since		2016-05-21
*/
class Process
{
	private $errorCode;
	protected $login;
	
	/**
	* Class constructor
	*
	* Link leggen met de data van de login class
	*/
	public function __construct($login)
	{
		$this->login = $login;
	}
	
	/**
	* function process()
	*/
	public function action($aktie)
	{
		switch (strtolower($aktie))
		{
			case 'afmelden':
				$this->afmelden();
				$_SESSION['page'] = 'login';
				$_SESSION['aangemeld'] = FALSE;
				break;
			case 'aanmelden':
				$status = $this->aanmelden();
				if ($status === TRUE)
				{
					$_SESSION['page'] = 'home';
					$_SESSION['aangemeld'] = TRUE;
				}
				else $_SESSION['page'] = 'login';
				break;
			case 'account aanmaken':
				$status = $this->aanmakenAccount();
				if ($status === TRUE)
				{
					$_SESSION['page'] = 'accountaangemaakt';
					$_SESSION['aangemeld'] = TRUE;
				}
				else
				{
					switch ($this->errorCode)
					{
						case 2:
							$_SESSION['page'] = 'probleeminloggen';
							break;
						case 3:
							$_SESSION['page'] = 'probleempaswoord';
							break;
						case 4:
							$_SESSION['page'] = 'probleembestand';
							break;
						case 9:
							$_SESSION['page'] = 'usernamebestaat';
							break;
					}
				}
				break;
			case 'encrypteren':
				$this->encrypt();
				$_SESSION['page'] = 'encryptdone';
				break;
			case 'wiszip':
				$this->wisZIP();
				$_SESSION['page'] = 'home';
				break;
			case 'decrypteren':
				$this->decrypt();
				$_SESSION['page'] = 'decryptdone';
				break;
			case 'verwerk inzending':
				$this->stegaEncrypt();
				$_SESSION['page'] = 'stegaencryptdone';
				break;
			case 'ontcijfer bericht':
				$this->stegaDecrypt();
				$_SESSION['page'] = 'showtext';
				break;
			default:
				$_SESSION['page'] = 'error404';
		}
	}

	/**
	* function afmelden()
	*/
	private function afmelden()
	{
		$this->login->logoutUser(TRUE);
	}
	
	/**
	* function aanmelden()
	*/
	private function aanmelden()
	{
		return $this->login->loginUser($_POST['vUsername'], $_POST['vPassword'], (isset($_POST['vRemember'])) ? (($_POST['vRemember'] == 'yes') ? TRUE : FALSE) : '');
	}
	
	/**
	* function aanmakenAccount()
	*/
	private function aanmakenAccount()
	{
		if (isset($_POST['vPassword1'])) $vPassword1 = $_POST['vPassword1']; else return FALSE;
		if (isset($_POST['vPassword2'])) $vPassword2 = $_POST['vPassword2']; else return FALSE;
		require_once('class.BestandsBeheer.php');
		$bestand = new BestandsBeheer;
		$vRSAkey = $_POST['vRSAkey'];
		
		if ($vPassword1 == $vPassword2)
		{
			if (isset($_POST['vUsername'])) $this->login->data['username'] = $_POST['vUsername']; else return FALSE;
			$this->login->data['password'] = $vPassword1;
			if (isset($_POST['vVoornaam'])) $this->login->data['voornaam'] = $_POST['vVoornaam']; else return FALSE;
			if (isset($_POST['vFamilienaam'])) $this->login->data['familienaam'] = $_POST['vFamilienaam']; else return FALSE;
			if (isset($_POST['vEmail'])) $this->login->data['email'] = $_POST['vEmail']; else return FALSE;
			
			if ($vRSAkey == 'createkey')
			{
				require_once('phpseclib/Crypt/RSA.php');
				$rsa = new Crypt_RSA();
				$rsa->setPrivateKeyFormat(CRYPT_RSA_PRIVATE_FORMAT_XML);
				$rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_XML);
				extract($rsa->createKey(2048)); // returns $privatekey & $publickey
				$this->login->data['RSA_public'] = $publickey;
				if ($this->login->insertUser())
				{
					file_put_contents($_SESSION['download'].$this->login->data['familienaam'].$this->login->data['voornaam'].'_private.key', $privatekey);
					return TRUE;
				}
				else
				{
					$this->errorCode = $this->login->errorCode;
					return FALSE;
				}
			}
			elseif ($vRSAkey == 'uploadkey')
			{
				$this->login->data['RSA_public'] = $bestand->leesBestand($_FILES['vPublicKey']['tmp_name']);
				if ($this->login->data['RSA_public'] === FALSE)
				{
					$this->errorCode = 4;
					return FALSE;
				}
				if ($this->login->insertUser()) return TRUE;
				else
				{
					$this->errorCode = $this->login->errorCode;
					return FALSE;
				}
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			$this->errorCode = 3;
			return FALSE;
		}
	}

	/**
	* function encrypt()
	*/
	private function encrypt()
	{
		require_once('class.Crypt.php');
		$crypt = new Crypt;
		
		require_once('class.BestandsBeheer.php');
		$bestand = new BestandsBeheer;
		
		require_once('class.DBconnect.php');
		$DBcon = new DBconnect($_SESSION['mysqlHost'], $_SESSION['mysqlGebruiker'], $_SESSION['mysqlPaswoord'], $_SESSION['mysqlDatabase']);
		
		require_once('class.TekstVerwerken.php');
		$tekst = new TekstVerwerken;
		
		require_once('class.Zipper.php');
		
		// Loading RSA-keys
		$query = "SELECT RSA_public FROM li_user WHERE id = '{$_POST['vTo']}'";
		$publicKeyTo = $DBcon->queryItem($query, 'RSA_public');
		$privateKeyFrom = $bestand->leesBestandKlein($_FILES['vPrivKey']['tmp_name']);
		
		// Setting Encryption Type to use
		$encrypType = $_POST['vEncrypType'];
		
		// Preparing DATA of the file to encrypt
		$fileToProc = $bestand->leesBestand($_FILES['vBestand']['tmp_name']);
		
		// Data to write to File_1 (Main data)
		$encryptedData = $crypt->encrypt($encrypType, $fileToProc);
		
		// Data to write to File_2 (key + iv + filename)
		$key = $crypt->getKey();
		$IV = $crypt->getIv();
		$fileName = $_FILES['vBestand']['name'];
		
		// Data to wrtie to File_3 (hash of Main data before encrypt)
		$hash = $crypt->getHash();
		
		// Writing File_1
		$bestand->schrijfBestand($encryptedData, $_SESSION['workspace'].'file_1.'.$encrypType);
		
		// Writing File_2
		$crypt->resetAll();
		$dataString = $key.':'.$IV.':'.$fileName;
		$crypt->setKey($publicKeyTo);
		$dataEncrypt = $crypt->encrypt('RSA', $dataString);
		$bestand->schrijfBestandKlein($dataEncrypt, $_SESSION['workspace'].'file_2.key');
		
		// Writing File_3
		$crypt->resetAll();
		$crypt->setKey($privateKeyFrom);
		$dataEncrypt = $crypt->encrypt('RSA', $hash);
		$bestand->schrijfBestandKlein($dataEncrypt, $_SESSION['workspace'].'file_3.hash');
		
		// Zipping files
		do
		{
			$zipBestand = $tekst->randomTekst(8).'.zip';
		} while (is_file($_SESSION['download'].$zipBestand));
		$zip = new Zipper($_SESSION['download'].$zipBestand);
		
		$zip->zipBestand($_SESSION['workspace']);
		$zip->sluiten();
		
		$bestand->bestandWissen($_SESSION['workspace'].'file_1.'.$encrypType);
		$bestand->bestandWissen($_SESSION['workspace'].'file_2.key');		
		$bestand->bestandWissen($_SESSION['workspace'].'file_3.hash');
		
		$query = "INSERT INTO download VALUES (0, {$this->login->data['id']}, {$_POST['vTo']}, '{$zipBestand}', 'zip', TRUE)";
		$DBcon->insert($query);
	}

	/**
	* function decrypt()
	*/
	private function decrypt()
	{
		require_once('class.Crypt.php');
		$crypt = new Crypt;
		
		require_once('class.BestandsBeheer.php');
		$bestand = new BestandsBeheer;
		
		require_once('class.DBconnect.php');
		$DBcon = new DBconnect($_SESSION['mysqlHost'], $_SESSION['mysqlGebruiker'], $_SESSION['mysqlPaswoord'], $_SESSION['mysqlDatabase']);
		
		require_once('class.TekstVerwerken.php');
		$tekst = new TekstVerwerken;
		
		require_once('class.Zipper.php');
		
		// Unzipping file
		$zip = new Zipper($_FILES['vBestand']['tmp_name']);
		$zip->unZip($_SESSION['workspace']);

		// Loading RSA keys
		$query = "SELECT RSA_public FROM li_user WHERE id = '{$_POST['vFrom']}'";
		$publicKeyFrom = $DBcon->queryItem($query, 'RSA_public');
		$privateKeyTo = $bestand->leesBestandKlein($_FILES['vPrivKey']['tmp_name']);

		// Setting Encryption Type to use
		if (file_exists($_SESSION['workspace'].'file_1.des'))
		{
			$decrypType = 'des';
		}
		else
		{
			$decrypType = 'aes';
		}
		
		// Reading data of the three files
		$encryptedData = $bestand->leesBestand($_SESSION['workspace'].'file_1.'.$decrypType);
		$encryptedKey = $bestand->leesBestandKlein($_SESSION['workspace'].'file_2.key');
		$encryptedHash = $bestand->leesBestandKlein($_SESSION['workspace'].'file_3.hash');
		unlink($_SESSION['workspace'].'file_1.'.$decrypType);
		unlink($_SESSION['workspace'].'file_2.key');
		unlink($_SESSION['workspace'].'file_3.hash');
		
		// Retrieving the encryption key used
		$crypt->resetAll();
		$crypt->setKey($privateKeyTo);
		$decrypedKey = $crypt->decrypt('RSA', $encryptedKey);
		list($decrypKey, $decrypIV, $decrypBestand) = explode(':', $decrypedKey);
		
		// Retrieving the original hash of the encrypted file
		$crypt->resetAll();
		$crypt->setKey($publicKeyFrom);
		$originalHash = $crypt->decrypt('RSA', $encryptedHash);
		
		// Retrieving the original file + the hash of it
		$crypt->resetAll();
		$crypt->setKey($decrypKey);
		$crypt->setIv($decrypIV);
		$decryptedData = $crypt->decrypt($decrypType, $encryptedData);
		$decrypHash = $crypt->getHash();
		
		if ($originalHash == $decrypHash)
		{
			$bestand->schrijfBestand($decryptedData, $_SESSION['workspace'].$decrypBestand);
			$bestand->bestandMove($decrypBestand, $_SESSION['workspace'], $_SESSION['download']);
			$_SESSION['downloadBestand'] = $decrypBestand;
		}
		else
		{
			return FALSE;
		}
	}
	
	/**
	* function stegaEncrypt()
	*/
	private function stegaEncrypt()
	{
		// Loading needed classes
		require_once('class.BestandsBeheer.php');
		$bestand = new BestandsBeheer;
		
		require_once('class.TekstVerwerken.php');
		$text = new TekstVerwerken;
		
		require_once('class.Crypt.php');
		$crypt = new Crypt;
		
		require_once('class.Stega.php');
		$stega = new Stega;
		
		require_once('class.DBconnect.php');
		$DBcon = new DBconnect($_SESSION['mysqlHost'], $_SESSION['mysqlGebruiker'], $_SESSION['mysqlPaswoord'], $_SESSION['mysqlDatabase']);
		
		// Moving uploaded image to the users workspace
		$bestand->bestandUpload($_FILES['vFoto']['tmp_name'], $_FILES['vFoto']['name'], $_SESSION['workspace']);
		$image = $_SESSION['workspace'].$_FILES['vFoto']['name'];
		
		// Encrypting the message with the public RSA key of the recipient
		$data = $_POST['vText'];
		$query = "SELECT RSA_public FROM li_user WHERE id = '{$_POST['vTo']}'";
		$publicKeyTo = $DBcon->queryItem($query, 'RSA_public');
		$crypt->setKey($publicKeyTo);
		$dataEncrypt = $crypt->encrypt('RSA', $data);
		
		// Adding the encrypted data to the image
		$newImage = $text->randomTekst(8).'.png';
		$stega->loadImage($image);
		$stega->addTextToImage($dataEncrypt);
		$stega->saveImage($_SESSION['download'].$newImage);
		
		// Adding the information to the database
		$query = "INSERT INTO download VALUES (0, {$this->login->data['id']}, {$_POST['vTo']}, '{$newImage}', 'image', TRUE)";
		$DBcon->insert($query);
		
		// Cleaning workspace
		$bestand->bestandWissen($image);
	}
	
	/**
	* functin stegaDecrypt()
	*/
	private function stegaDecrypt()
	{
		// Loading needed classes
		require_once('class.BestandsBeheer.php');
		$bestand = new BestandsBeheer;
		
		require_once('class.Stega.php');
		$stega = new Stega;
		
		require_once('class.Crypt.php');
		$crypt = new Crypt;

		// Processing Form
		$privateKey = $bestand->leesBestandKlein($_FILES['vPrivateKey']['tmp_name']);
		$image = $_SESSION['download'].$_POST['vImage'];
		
		// Stripping the encrypted data from the image
		$stega->loadImage($image);
		$stega->stripTextFromImage();
		
		// Decrypting the message with the private RSA key of the recipient
		$crypt->setKey($privateKey);
		$text = $crypt->decrypt('RSA', $stega->text);
		
		$_SESSION['stegaText'] = $text;
	}
	
	/**
	* function wisZIP()
	*/
	private function wisZIP()
	{
		require_once('class.BestandsBeheer.php');
		$bestand = new BestandsBeheer;

		require_once('class.DBconnect.php');
		$DBcon = new DBconnect($_SESSION['mysqlHost'], $_SESSION['mysqlGebruiker'], $_SESSION['mysqlPaswoord'], $_SESSION['mysqlDatabase']);

		$query = "SELECT id, bestand FROM download WHERE id_from = {$this->login->data['id']} AND available = TRUE ORDER BY id DESC";
		$DBcon->queryRow($query, 'bestand');
		$fileId = $DBcon->data['id'];
		$file = $DBcon->data['bestand'];
		
		$bestand->bestandWissen($_SESSION['download'].$file);
		
		$query = "UPDATE download SET available = FALSE WHERE id = {$fileId}";
		$DBcon->update($query);
	}
}
/** End of File: class.Process.php **/