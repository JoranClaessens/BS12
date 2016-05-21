<?php
require_once('phpseclib/Crypt/Random.php');
/**
* Class Crypt - This is used to combine different types of encryption techniqes
*
* This class is used in combination with following class:
*	- phpseclib (Group of encryption classes)
*	- TekstVerwerken (http://forum.rudymas.be/)
* 
* @author		Rudy Mas <rudy.mas@rudymas.be>
* @copyright	Copyright (c) 2016, rudymas.be. (http://www.rudymas.be/)
* @license		https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
* @version		0.5.4
* @since		2016-04-18
*/
class Crypt
{
	private $key;
	private $iv;
	private $hash;
	
	/**
	* function encrypt()
	*
	* @param	string	$cryptType	The method of encrytion
	* @param	string	$data		The data to encrypt
	* @return	string				The encrypted data
	*
	* $key, $iv and $hash are saved
	*/
	public function encrypt($cryptType, $data)
	{
		switch (strtoupper($cryptType))
		{
			case 'DES':
				require_once('phpseclib/Crypt/DES.php');
				$cipher = new Crypt_DES;
				require_once('class.TekstVerwerken.php');
				$tekst = new TekstVerwerken;
				
				// Create and store the DES key
				$this->setKey($tekst->randomTekst(8));
				$cipher->setKey($this->getKey());
				// Create and store the IV
				$this->setIv(crypt_random_string($cipher->getBlockLength() >> 3));
				$cipher->setIV($this->getIv());
				// Store the hash of the file data
				$this->setHash(sha1($data));
				// Encrypt the data and return it as a string
				return $cipher->encrypt($data);
				break;
			case 'AES':
				require_once('phpseclib/Crypt/AES.php');
				$cipher = new Crypt_AES;
				require_once('class.TekstVerwerken.php');
				$tekst = new TekstVerwerken;
				
				// Create and store the AES key
				$this->setKey($tekst->randomTekst(32));
				$cipher->setKey($this->getKey());
				// Create and store the IV
				$this->setIv(crypt_random_string($cipher->getBlockLength() >> 3));
				$cipher->setIV($this->getIv());
				// Store the hash of the file data
				$this->setHash(sha1($data));
				// Encrypt the data and return it as a string
				return $cipher->encrypt($data);
				break;
			case 'RSA':
				require_once('phpseclib/Crypt/RSA.php');
				$rsa = new Crypt_RSA;
				
				// Load the RSA key outside this class through $crypt->setKey('...');
				$rsa->loadKey($this->getKey());
				// Encrypt the data and return it as a string
				return $rsa->encrypt($data);
				break;
		}
	}
	
	/**
	* function decrypt()
	*
	* @param	string	$cryptType	The method of encrytion
	* @param	string	$data		The data to encrypt
	* @return	string				The encrypted data
	*
	* $key, $iv and $hash are stored
	*/
	public function decrypt($cryptType, $data)
	{
		switch (strtoupper($cryptType))
		{
			case 'DES':
				require_once('phpseclib/Crypt/DES.php');
				$cipher = new Crypt_DES;
				
				// Load the DES key
				$cipher->setKey($this->getKey());
				// Load the IV
				$cipher->setIV($this->getIv());
				// Decrypt the data
				$decrypt = $cipher->decrypt($data);
				// Store the hash of the file data
				$this->setHash(sha1($decrypt));
				// Return the decrypted data
				return $decrypt;
				break;
			case 'AES':
				require_once('phpseclib/Crypt/AES.php');
				$cipher = new Crypt_AES;

				// Load the AES key
				$cipher->setKey($this->getKey());
				// Load the IV
				$cipher->setIV($this->getIv());
				// Encrypt the data and return it as a string
				$decrypt = $cipher->decrypt($data);
				// Store the hash of the file data
				$this->setHash(sha1($decrypt));
				// Return the decrypted data
				return $decrypt;
				break;
			case 'RSA':
				require_once('phpseclib/Crypt/RSA.php');
				$rsa = new Crypt_RSA;
				
				// Load the RSA key outside this class through $crypt->setKey('...');
				$rsa->loadKey($this->getKey());
				// Encrypt the data and return it as a string
				$decrypt = $rsa->decrypt($data);
				return $decrypt;
				break;
		}
	}
	
	
	// The get* en set* functions for the properties
	public function getKey(){ return $this->key; }
	public function setKey($key){ $this->key=$key; }
	public function getIv(){ return $this->iv; }
	public function setIv($iv){ $this->iv=$iv; }
	public function getHash(){ return $this->hash; }
	public function setHash($hash){ $this->hash=$hash; }
	public function resetAll() { $this->key=''; $this->iv=''; $this->hash=''; }
}
/** End of File: class.Crypt.php **/