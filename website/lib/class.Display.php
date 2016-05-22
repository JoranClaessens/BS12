<?php
require_once('class.DBconnect.php');
require_once('HTML5/class.HTML5.php');
require_once('HTML5/class.Menu.php');
require_once('HTML5/class.Forms.php');
require_once('class.TekstVerwerken.php');
/**
* Class Display - This is used to create the output (HTML) for the website
*
* This is used in combination with the login class. You call it like this:
* 
* $display = new Display($login)
*
* All class login functions are available through $this->login->[function you need]()
* The user data is available through $this->login->data['field']
*
* @author		Rudy Mas <rudy.mas@rudymas.be>
* @copyright	Copyright (c) 2016, rudymas.be. (http://www.rudymas.be/)
* @license		https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
* @version		0.5.4
* @since		2016-05-21
*/
class Display extends HTML5
{
	protected $login;
	
	/**
	* Class constructor
	*/
	public function __construct($login)
	{
		$this->login = $login;
	}
	
	/**
	* function displayPage($page)
	*
	* @param	string	$page	The page reference that has to be loaded. (Default: '')
	* @return	string	$output	The HTML-code to display.
	*/
	public function displayPage($page = '')
	{
		switch (strtolower($page))
		{
			case 'home':
				$output = $this->pagHome();
				break;
			case 'login':
				$output = $this->pagLogin();
				break;
			case 'registreren':
				$output = $this->pagRegistreren();
				break;
			case 'accountaangemaakt':
				$output = $this->pagAccountAangemaakt();
				break;
			case 'encryptfile':
				$output = $this->pagEncryptFile();
				break;
			case 'encryptdone':
				$output = $this->pagEncryptDone();
				break;
			case 'decryptfile':
				$output = $this->pagDecryptFile();
				break;
			case 'decryptdone':
				$output = $this->pagDecryptDone();
				break;
			case 'stegencrypt':
				$output = $this->pagStegEncrypt();
				break;
			case 'stegaencryptdone':
				$output = $this->pagStegaEncryptDone();
				break;
			case 'stegdecrypt':
				$output = $this->pagStegDecrypt();
				break;
			case 'showimage':
				$output = $this->pagShowImage($_GET['id']);
				break;
			case 'showtext':
				$output = $this->pagShowText();
				break;
			case 'probleeminloggen':
			case 'probleempaswoord':
			case 'probleembestand':
			case 'usernamebestaat':
				$output = $this->pagProbleem();
				break;
			default:
				$output = $this->pagERROR404();
		}
		$output.= $this->pagFooter();
		return $output;
	}

	/**
	* function pagERROR404()
	*
	* Show this page when someone tries to access a page that doesn't exist
	*
	* @return	string	$pageOutput	The HTML-code for the ERROR 404 page
	*/
	private function pagERROR404()
	{
		$tekst = new TekstVerwerken;
		
		$pageOutput = $this->menu();
		$pageOutput.= $this->div('open', 'id="inhoud"');
		$pageOutput.= $this->h2('full', '', 'De pagina die je zoekt kon niet worden gevonden.');
		$pageOutput.= $this->p('full', 'style="padding: 10px"', 'De pagina "'.$tekst->cleanHTML($_SESSION['page']).'" bestaat niet op de website.<br>Denk je dat dit een vergissing is, gelieve dan het contactformulier te gebruiken om dit probleem te melden. Vermeld in dit bericht zeker het gedeelte dat tussen quotes staat.');
		$pageOutput.= $this->div('close');
		return $pageOutput;
	}

	/**
	* function pagProbleem()
	*
	* Quick fix for the error page. Needs to be worked out further
	*
	* @return	string	$pageOutput	The HTML-code for the problem page
	*/
	private function pagProbleem()
	{
		$pageOutput = $this->menu();
		$pageOutput.= $this->div('open', 'id="inhoud"');
		$pageOutput.= $this->h1('full', '', 'Er is iets misgelopen!');
		$pageOutput.= $this->div('close');
		return $pageOutput;
	}

	/**
	* function pagHome()
	*
	* @return	string	$pageOutput	The HTML-code for the home page
	*/
	private function pagHome()
	{
		if (isset($_SESSION['downloadBestand']))
		{
			unlink($_SESSION['download'].$_SESSION['downloadBestand']);
			unset($_SESSION['downloadBestand']);
		}
		if (file_exists($_SESSION['download'].$this->login->data['familienaam'].$this->login->data['voornaam'].'_private.key'))
		{
			unlink($_SESSION['download'].$this->login->data['familienaam'].$this->login->data['voornaam'].'_private.key');
		}
		
		$pageOutput = $this->menu();
		$pageOutput.= $this->div('open', 'id="inhoud"');
		$pageOutput.= $this->code('full', '', $this->pre('full', '', 'Frontpage met informatie voor de gebruiker.'));
		$pageOutput.= $this->div('close');
		return $pageOutput;
	}
	
	/**
	* function pagLogin()
	*
	* @return	string	$pageOutput	The HTML-code for the login form.
	*/
	private function pagLogin()
	{
		$forms = new Forms;
		
		$forms->addInput('text', 'vUsername', (isset($_COOKIE['username'])) ? $_COOKIE['username'] : '', 'username', 'widthInput', 'required', 'veld', 'Gebruikersnaam:', '', 'alignRight');
		$forms->addInput('password', 'vPassword', '', 'password', 'widthInput', 'required', 'veld', 'Wachtwoord:', '', 'alignRight');
		$forms->addInput('checkbox', 'vRemember', 'yes', 'onthouden', '', '', 'veld', 'Onthouden', '', 'alignLeft');
		$forms->addInput('submit', 'aktie', 'Aanmelden', '', 'button', '', 'inline');
		$forms->addInput('a', 'page', 'registreren', '', 'button', '', 'inline', 'Registreren');
		
		$pageOutput = $this->div('open', 'id="login"');
		$pageOutput.= $this->h3('full', '', 'Aanmelden');
		$pageOutput.= $forms->createForm('loginForm', 'index.php', 'post', 'autocomplete="on"');
		$pageOutput.= $this->div('close');
		return $pageOutput;
	}

	/**
	* function pagRegistreren()
	*
	* @return	string	$pageOutput	The HTML-code for the register form.
	*/
	private function pagRegistreren()
	{
		$forms = new Forms;
		
		$forms->addFieldset('login', 'Login gegevens');
		$forms->addFieldset('persoon', 'Persoonlijke Gegevens');
		$forms->addFieldset('rsa', 'RSA sleutels');
		
		// Fieldset: login
		$forms->addInput('text', 'vUsername', (isset($_POST['vUsername'])) ? $_POST['vUsername'] : '', 'username', 'widthInput', 'required', 'veld', 'Gebruikersnaam:', '', 'alignRight', 'login');
		$forms->addInput('password', 'vPassword1', (isset($_POST['vPassword'])) ? $_POST['vPassword'] : '', 'password1', 'widthInput', 'required', 'veld', 'Wachtwoord:', '', 'alignRight', 'login');
		$forms->addInput('password', 'vPassword2', '', 'password2', 'widthInput', '', 'veld', 'Herhaal Wachtwoord:', '', 'alignRight', 'login', 'required');
		
		// Fieldset: persoon
		$forms->addInput('text', 'vVoornaam', '', 'voornaam', 'widthInput', 'required', 'veld', 'Voornaam:', '', 'alignRight', 'persoon');
		$forms->addInput('text', 'vFamilienaam', '', 'familienaam', 'widthInput', 'required', 'veld', 'Familienaam:', '', 'alignRight', 'persoon');
		$forms->addInput('email', 'vEmail', '', 'email', 'widthInput', 'required', 'veld', 'E-mail adres:', '', 'alignRight', 'persoon');

		// Fieldset: rsa
		$forms->addInput('radio', 'vRSAkey', 'createkey', 'createkey', '', 'checked', 'veld alignleft', 'Aanmaken nieuwe privé- en publieke sleutel.', '', '', 'rsa');
		$forms->addInput('radio', 'vRSAkey', 'uploadkey', 'uploadkey', '', '', 'veld alignleft', 'Uploaden bestaande publieke sleutel.', '', '', 'rsa');
		$forms->addInput('file', 'vPublicKey', '', 'publickey', '', '', 'veld alignCenter toggleView', 'Upload je publieke sleutel:', '', 'alignCenter', 'rsa');

		// Zonder fieldset
		$forms->addInput('submit', 'aktie', 'Account Aanmaken', '', 'button', '', 'inline');
		$forms->addInput('a', 'page', 'login', '', 'button', '', 'inline', 'Terug naar Login');
		
		$pageOutput = $this->div('open', 'id="register"');
		$pageOutput.= $this->h3('full', '', 'Registreren nieuwe gebruiker');
		$pageOutput.= $forms->createForm('registerForm', 'index.php', 'post', 'autocomplete="on" enctype="multipart/form-data"');
		$pageOutput.= $this->div('close');
		return $pageOutput;
	}
	
	/**
	* function pagAccountAangemaakt()
	*
	* @return	string	$pageOutput	The HTML-code to display when a new account has been created
	*/
	private function pagAccountAangemaakt()
	{
		$tekst = new TekstVerwerken;
		
		$file = $this->login->data['familienaam'].$this->login->data['voornaam'].'_private.key';

		$pageOutput = $this->menu();
		$pageOutput.= $this->div('open', 'id="inhoud"');
		$pageOutput.= $this->article('open');
		$pageOutput.= $this->h1('full', '', 'Welkom!');
		$pageOutput.= $this->p('full', 'class="alignJustify"', "Er is een nieuw account aangemaakt voor jou onder de naam '{$this->login->data['voornaam']} {$this->login->data['familienaam']}' met gebruikersnaam: '{$this->login->data['username']}'.");
		if (file_exists($_SESSION['download'].$file))
		{		
			$pageOutput.= $this->p('full', 'class="alignJustify"', 'Het eerste wat je nu moet doen is je private RSA key downloaden en op een veilige plaats bewaren. Deze key heb je nodig als je bestanden wilt gaan encrypteren of decrypteren op de website of met de desktop applicatie. De private key wordt namelijk niet op de server bijgehouden. Enkel bij de aanmaak van de key zal deze voor maximaal 1 uur beschikbaar blijven voor download en dan zal de sleutel definitief verwijderd worden. Moest je je private key verliezen, dan kan deze niet meer opnieuw worden aangemaakt en zal je op de website een nieuwe private en public key moeten aanmaken. Hierdoor zal je dan al je contactpersonen moeten verwittigen dat ze je nieuwe public key moeten downloaden.');
			$pageOutput.= $this->p('full', 'class="alignJustify"', 'Deze public key blijft natuurlijk altijd beschikbaar op de website daar jij en anderen die altijd nodig hebben. Echter, als je enkel gebruik maakt van de website, dan hoef je de public keys van andere niet te downloaden. Je zal op de website enkel moeten aangeven voor wie het bestand of bericht bestemd is, en dan zal de website zelf de juiste keys voor jou gebruiken. Hou wel altijd je private key bij de hand, want de website heeft deze natuurlijk nodig!');
			$pageOutput.= $this->p('full', 'class="alignJustify"', 'Het enige wat je dan nu nog moet doen, is even op de \'Download Key\'-button klikken om je private key te downloaden. Zorg ervoor dat je deze dus <b>NOOIT</b> verliest!');
			$pageOutput.= $this->div('open', 'class="alignCenter"');
			$pageOutput.= $this->a('full', 'script/download.php?file='.$file, 'id="downloadFile" class="button"', 'Download Private Key');
			$pageOutput.= $this->div('close');
		}
		else
		{
			$pageOutput.= $this->p('full', 'class="alignJustify"', 'De public key die je net hebt geupload is nu gekoppeld aan je account. Jouw contactpersonen kunnen deze key downloaden om te gebruiken met onze desktop applicatie, of eender welk ander programma dat gebruik maakt van RSA sleutels. Zolang je enkel met de website werkt, hoef je geen public keys te downloaden, op de website kun je namelijk bij encryptie of decryptie aangeven voor/van wie het bestand is en de website maakt dan automtisch gebruik van de juiste public key.');
			$pageOutput.= $this->p('full', 'class="alignJustify"', 'De private key moet je zelf goed bewaren. Om veiligheidsoverwegingen wordt deze key nooit bewaard op de website. Als je een bestand of tekst wilt encrypteren of decrypteren, dan zal de website deze key opvragen. Van zodra de bewerking is afgelopen, wordt deze key dadelijk opnieuw verwijderd van het systeem.');
		}
		$pageOutput.= $this->article('close');
		$pageOutput.= $this->div('close');
		return $pageOutput;
	}
	
	/**
	* function pagEncryptFile
	*
	* @return	string	$pageOutput	The HTML-code for the File Encryption page
	*/
	private function pagEncryptFile()
	{
		$DBcon = new DBconnect($_SESSION['mysqlHost'], $_SESSION['mysqlGebruiker'], $_SESSION['mysqlPaswoord'], $_SESSION['mysqlDatabase']);
		
		// Preparing the form
		$forms = new Forms;

		// Preparing the options part for the select
		$query = "SELECT id, username, voornaam, familienaam FROM li_user WHERE id != {$this->login->data['id']} ORDER BY username";
		$DBcon->query($query);
		$input = $this->option('full', '0');
		for ($x = 0; $x < $DBcon->rows; $x++)
		{
			$DBcon->fetch($x);
			$input.= $this->option('full', $DBcon->data['id'], $DBcon->data['username'].' ('.$DBcon->data['voornaam'].' '.$DBcon->data['familienaam'].')');
		}
		
		$forms->addInput('select', 'vTo', $input, 'vTo', '', '', 'veld', 'Bestand is voor: ');
		$forms->addInput('radio', 'vEncrypType', 'des', '', '', 'checked', 'veld inline', 'DES', '', '', '', 'Kies de encryptie methode:');
		$forms->addInput('radio', 'vEncrypType', 'aes', '', '', '', 'veld inline', 'AES');
		$forms->addInput('file', 'vBestand', '', '', '', '', 'veld', 'Te encrypteren bestand: ');
		$forms->addInput('file', 'vPrivKey', '', '', '', '', 'veld', 'Je privé sleutel: ');
		$forms->addInput('submit', 'aktie', 'Encrypteren', '', 'button', '', 'alignCenter');
		
		$pageOutput = $this->menu();
		$pageOutput.= $this->div('open', 'id="inhoud"');
		$pageOutput.= $this->article('open');
		$pageOutput.= $this->h3('full', '', 'Bestand Encryptie');
		$pageOutput.= $this->p('full', '', 'Hou er rekening mee dat de webserver een beperking heeft ivm de upload van bestanden. Indien je een bestand wilt encrypteren die groter is dan '.ini_get('upload_max_filesize').'B, dan zul je de desktop applicatie moeten gebruiken.');
		$pageOutput.= $this->article('close');

		$pageOutput.= $forms->createForm('encryptForm', 'index.php', 'post', 'autocomplete="on" enctype="multipart/form-data"');
		$pageOutput.= $this->div('close');
		return $pageOutput;
	}
	
	/**
	* function pagEncryptDone()
	*
	* @return	string	$pageOutput	The HTML-code for the Encryption Done page
	*/
	private function pagEncryptDone()
	{
		$DBcon = new DBconnect($_SESSION['mysqlHost'], $_SESSION['mysqlGebruiker'], $_SESSION['mysqlPaswoord'], $_SESSION['mysqlDatabase']);
		
		$query = "SELECT bestand FROM download WHERE id_from = {$this->login->data['id']} AND available = TRUE ORDER BY id DESC";
		$file = $DBcon->queryItem($query, 'bestand');
		
		$pageOutput = $this->menu();
		$pageOutput.= $this->div('open', 'id="inhoud"');
		$pageOutput.= $this->article('open');
		$pageOutput.= $this->h3('full', '', 'Bestand versleuteld');
		$pageOutput.= $this->p('full', '', 'Jouw bestand is versleuteld en is klaargezet om te downloaden.');
		$pageOutput.= $this->p('full', '', 'Je hebt nu 2 mogelijkheden. Ofwel download je zelf het bestand en verzend je hem via E-mail naar je contactpersoon, ofwel laat je hem gewoon op de server staan en dan kan je contactpersoon hem hier downloaden en natuurlijk ook terug decrypteren.');
		$pageOutput.= $this->p('full', '', 'Het bestand mag gerust op de server blijven staan, want jouw contactpersoon is de enige persoon die het originele bestand kan uitpakken. Hier is namelijk zijn privésleutel voor nodig en zodoende kan niemand anders het originele bestand uit het ZIP-bestand halen.');
		$pageOutput.= $this->div('open', 'id="buttons"');
		$pageOutput.= $this->a('full', 'script/download.php?file='.$file, 'id="clickDownZIP" class="button"', 'Download Encrypted Bestand');
		$pageOutput.= $this->a('full', '?page=home', 'class="button"', 'Bewaar Bestand');
		$pageOutput.= $this->div('close');
		$pageOutput.= $this->article('close');
		$pageOutput.= $this->div('close');
		return $pageOutput;
	}
	
	/**
	* function pagDecryptFile
	*
	* @return	string	$pageOutput	The HTML-code for the File Decryption page
	*/
	private function pagDecryptFile()
	{
		$DBcon = new DBconnect($_SESSION['mysqlHost'], $_SESSION['mysqlGebruiker'], $_SESSION['mysqlPaswoord'], $_SESSION['mysqlDatabase']);
		
		// Preparing the form
		$forms = new Forms;

		// Preparing the options part for the select
		$query = "SELECT id, username, voornaam, familienaam FROM li_user WHERE id != {$this->login->data['id']} ORDER BY username";
		$DBcon->query($query);
		$input = $this->option('full', '0');
		for ($x = 0; $x < $DBcon->rows; $x++)
		{
			$DBcon->fetch($x);
			$input.= $this->option('full', $DBcon->data['id'], $DBcon->data['username'].' ('.$DBcon->data['voornaam'].' '.$DBcon->data['familienaam'].')');
		}
		
		$forms->addInput('select', 'vFrom', $input, 'vFrom', '', '', 'veld', 'Bestand is van: ');
		$forms->addInput('file', 'vBestand', '', '', '', '', 'veld', 'Te decrypteren bestand: ');
		$forms->addInput('file', 'vPrivKey', '', '', '', '', 'veld', 'Je privé sleutel: ');
		$forms->addInput('submit', 'aktie', 'Decrypteren', '', 'button', '', 'alignCenter');
		
		$pageOutput = $this->menu();
		$pageOutput.= $this->div('open', 'id="inhoud"');
		$pageOutput.= $this->article('open');
		$pageOutput.= $this->h3('full', '', 'Bestand Decryptie');
		$pageOutput.= $this->p('full', '', 'Hou er rekening mee dat de webserver een beperking heeft ivm de upload van bestanden. Indien je een bestand wilt decrypteren die groter is dan '.ini_get('upload_max_filesize').'B, dan zul je de desktop applicatie moeten gebruiken.');
		$pageOutput.= $this->article('close');

		$pageOutput.= $forms->createForm('decryptForm', 'index.php', 'post', 'autocomplete="on" enctype="multipart/form-data"');
		$pageOutput.= $this->div('close');
		return $pageOutput;
	}
	
	/**
	* function pagDecryptDone()
	*
	* @return	string	$pageOutput	The HTML-code for the Decryption Done page
	*/
	private function pagDecryptDone()
	{
		$pageOutput = $this->menu();
		$pageOutput.= $this->div('open', 'id="inhoud"');
		$pageOutput.= $this->article('open');
		$pageOutput.= $this->h3('full', '', 'Bestand ontsleuteld');
		$pageOutput.= $this->p('full', '', 'Jouw bestand is ontsleuteld en is zal automatisch naar je computer worden verzonden.');
		$pageOutput.= $this->p('full', '', 'Moest de download niet automatisch starten, dan kun je op onderstaande download button klikken.');
		$pageOutput.= $this->p('full', '', 'Het bestand zal automatisch gewist worden, ook als je het bestand niet zou downloaden! Hou hier rekening mee als de download niet zou lukken, dan mag je het ZIP-bestand nog niet wissen, maar moet je de decryptie opnieuw uitvoeren.');
		$pageOutput.= $this->div('open', 'id="buttons"');
		$pageOutput.= $this->a('full', 'script/download.php?file='.$_SESSION['downloadBestand'], 'id="downloadFile" class="button"', 'Download Decrypted Bestand');
		$pageOutput.= $this->div('close');
		$pageOutput.= $this->article('close');
		$pageOutput.= $this->div('close');
		$javaScript = "$(function() {
							$.fileDownload('script/download.php?file={$_SESSION['downloadBestand']}');
						 	$('#downloadFile').prop('href', '?page=home');
	 						$('#downloadFile').text('Naar Home');
							$('#downloadFile').off();
						})";
		
		$pageOutput.= $this->runJavascript($javaScript);
		return $pageOutput;
	}
	
	/**
	* function pagStegEncrypt()
	*
	* @return	string	$pageOutput	The HTML-code for the Steganography Encryption page
	*/
	private function pagStegEncrypt()
	{
		$DBcon = new DBconnect($_SESSION['mysqlHost'], $_SESSION['mysqlGebruiker'], $_SESSION['mysqlPaswoord'], $_SESSION['mysqlDatabase']);
		
		// Preparing the form
		$forms = new Forms;

		// Preparing the options part for the select
		$query = "SELECT id, username, voornaam, familienaam FROM li_user WHERE id != {$this->login->data['id']} ORDER BY username";
		$DBcon->query($query);
		$input = $this->option('full', '0');
		for ($x = 0; $x < $DBcon->rows; $x++)
		{
			$DBcon->fetch($x);
			$input.= $this->option('full', $DBcon->data['id'], $DBcon->data['username'].' ('.$DBcon->data['voornaam'].' '.$DBcon->data['familienaam'].')');
		}
		
		$forms->addInput('select', 'vTo', $input, 'vTo', '', '', 'veld', 'Boodschap is voor: ');
		$forms->addInput('file', 'vFoto', '', '', '', '', 'veld', 'Foto om te gebruiken: ');
		$forms->addInput('textarea', 'vText', '', 'vText', '', '', 'veld', 'Boodschap:');
		$forms->addInput('submit', 'aktie', 'Verwerk Inzending', '', 'button', '', 'alignCenter');
		
		$pageOutput = $this->menu();
		$pageOutput.= $this->div('open', 'id="inhoud"');
		$pageOutput.= $this->article('open');
		$pageOutput.= $this->h3('full', '', 'Steganography Encryptie');
		$pageOutput.= $this->p('full', '', 'Hou er rekening mee dat de webserver een beperking heeft ivm de upload van bestanden. Indien je een foto wilt gebruiken die groter is dan '.ini_get('upload_max_filesize').'B, dan zul je de desktop applicatie moeten gebruiken.');
		$pageOutput.= $this->article('close');

		$pageOutput.= $forms->createForm('StenoEncryptForm', 'index.php', 'post', 'autocomplete="on" enctype="multipart/form-data"');
		$pageOutput.= $this->div('close');
		return $pageOutput;
	}
	
	/**
	* function pagStegEncryptDone()
	*
	* @return	string	$pageOutput	The HTML-code for the Steganography Encryption page
	*/
	private function pagStegaEncryptDone()
	{
		$DBcon = new DBconnect($_SESSION['mysqlHost'], $_SESSION['mysqlGebruiker'], $_SESSION['mysqlPaswoord'], $_SESSION['mysqlDatabase']);
		$query = "SELECT bestand FROM download WHERE id_from = {$this->login->data['id']} ORDER BY id DESC LIMIT 1";
		$image = $DBcon->queryItem($query, 'bestand');
		
		$pageOutput = $this->menu();
		$pageOutput.= $this->div('open', 'id="inhoud"');
		$pageOutput.= $this->article('open');
		$pageOutput.= $this->h3('full', '', 'Steganography gelukt!');
		$pageOutput.= $this->p('full', '', 'Jouw tekst is met succes toegevoegd aan de foto. Zodra de ontvanger zich aanmeld kan hij je foto bekijken, en het bericht in de foto lezen op de website.');
		$pageOutput.= $this->div('open', 'id="image"');
		$pageOutput.= $this->img('download/'.$image, 'special image');
		$pageOutput.= $this->div('close');
		$pageOutput.= $this->article('close');
		$pageOutput.= $this->div('close');
		return $pageOutput;
	}
	
	/**
	* function pagStegDecrypt()
	*
	* @return	string	$pageOutput	The HTML-code for the Steganography Encryption page
	*/
	private function pagStegDecrypt()
	{
		$DBcon = new DBconnect($_SESSION['mysqlHost'], $_SESSION['mysqlGebruiker'], $_SESSION['mysqlPaswoord'], $_SESSION['mysqlDatabase']);
		$query = "SELECT d.id, u.username, u.voornaam, u.familienaam
					FROM download d
					JOIN li_user u
					ON d.id_from = u.id
					WHERE id_to = {$this->login->data['id']}
					AND d.type = 'image'
					ORDER BY id";
		$DBcon->query($query);

		$pageOutput = $this->menu();
		$pageOutput.= $this->div('open', 'id="inhoud"');
		$pageOutput.= $this->article('open');
		$pageOutput.= $this->h3('full', '', 'Steganography Decryptie');
		$pageOutput.= $this->p('full', '', 'In onderstaande tabel staan alle Steganography foto\'s die je hebt ontvangen.');
		$pageOutput.= $this->table('open');
		$pageOutput.= $this->thead('open');
		$pageOutput.= $this->tr('open');
		$pageOutput.= $this->th('full', '', 'Afzender');
		$pageOutput.= $this->th('full', '', 'Aktie');
		$pageOutput.= $this->tr('close');
		$pageOutput.= $this->thead('close');
		$pageOutput.= $this->tbody('open');
		
		for ($x = 0; $x < $DBcon->rows; $x++)
		{
			$DBcon->fetch($x);
			$pageOutput.= $this->tr('open');
			$pageOutput.= $this->td('full', '', $DBcon->data['username'].' ('.$DBcon->data['voornaam'].' '.$DBcon->data['familienaam'].')');
			$pageOutput.= $this->td('full', '', $this->a('full', '?page=showimage&amp;id='.$DBcon->data['id'], 'class="button"', 'Toon Foto'));
			$pageOutput.= $this->tr('close');
		}
		
		$pageOutput.= $this->tbody('close');
		$pageOutput.= $this->table('close');
		$pageOutput.= $this->article('close');
		$pageOutput.= $this->div('close');
		return $pageOutput;
	}
	
	/**
	* function pagShowImage($id)
	*
	* @param	int		$id			The download id to retrieve the data
	* @return	string	$pageOutput	The HTML-code for the Steganography Encryption page
	*/
	private function pagShowImage($id)
	{
		$DBcon = new DBconnect($_SESSION['mysqlHost'], $_SESSION['mysqlGebruiker'], $_SESSION['mysqlPaswoord'], $_SESSION['mysqlDatabase']);
		$query = "SELECT d.bestand, u.username, u.voornaam, u.familienaam
					FROM download d JOIN li_user u
					ON d.id_from = u.id
					WHERE d.id_to = {$this->login->data['id']}
					AND d.id = {$id}";
		$DBcon->queryRow($query);
		
		$forms = new Forms;
		$forms->addInput('file', 'vPrivateKey', '', '', '', '', 'veld', 'Jouw RSA private key: ');
		$forms->addInput('hidden', 'vImage', $DBcon->data['bestand']);
		$forms->addInput('submit', 'aktie', 'Ontcijfer bericht', '', 'button', '', 'alignCenter');

		$pageOutput = $this->menu();
		$pageOutput.= $this->div('open', 'id="inhoud"');
		$pageOutput.= $this->article('open');
		$pageOutput.= $this->h3('full', '', 'Steganography Decryptie - Foto');
		$pageOutput.= $this->p('full', '', $DBcon->data['voornaam'].' '.$DBcon->data['familienaam'].' heeft je volgende foto gestuurd:');
		$pageOutput.= $this->div('open', 'id="image"');
		$pageOutput.= $this->img('download/'.$DBcon->data['bestand'], 'Bestand van '.$DBcon->data['username']);
		$pageOutput.= $this->div('close');
		$pageOutput.= $this->p('full', '', 'Deze foto bevat een verborgen boodschap, deze kun je ontcijferen door onderstaand formulier in te vullen.');
		$pageOutput.= $this->article('close');
		$pageOutput.= $forms->createForm('StenoDecryptForm', 'index.php', 'post', 'autocomplete="on" enctype="multipart/form-data"');
		$pageOutput.= $this->div('close');
		return $pageOutput;
	}
	
	/**
	* function pagShowText()
	*
	* @return	string	$pageOutput	The HTML-code for the Steganography Encryption page
	*/
	private function pagShowText()
	{
		$tekst = new TekstVerwerken;
		
		$pageOutput = $this->menu();
		$pageOutput.= $this->div('open', 'id="inhoud"');
		$pageOutput.= $this->article('open');
		$pageOutput.= $this->h3('full', '', 'Steganography Decryptie - Text');
		$pageOutput.= $this->p('full', '', 'Het bericht bevatte volgende tekst:');
		$pageOutput.= $this->p('full', 'id="message"', $tekst->cleanHTML($_SESSION['stegaText']));
		$pageOutput.= $this->article('close');
		$pageOutput.= $this->div('close');
		return $pageOutput;
	}
	
	/**
	* function pagFooter()
	*
	* @return	string	$pageFooter	The HTML-code for the footer of the page
	*/
	private function pagFooter()
	{
		$pageFooter = $this->footer('open');
		$pageFooter.= $this->p('full', 'id="copyright"', "The Vault {$_SESSION['version']} &copy; ".date('Y')." by Rudy Mas");
		$pageFooter.= $this->footer('close');
		return $pageFooter;
	}
	
	/**
	* function menu()
	*
	* @return	string	The HTML-code for the menu of the page.
	*/
	private function menu()
	{
		$menu = new Menu($_SESSION['numberMenuItems']);
		
		// Main Menu
		$menu->addMenu('?page=home', 'homeIcon', '', '', $this->span('full', 'id="hideMenu"', 'Home'));
		$menu->addMenu('?page=encryptfile', '', '', '', 'Encryption');
		$menu->addMenu('?page=decryptfile', '', '', '', 'Decryption');
		$menu->addMenu('', '', '', '', 'Steganography');
#		$menu->addMenu('?page=account', '', '', '', 'Account');
		$menu->addMenu('?aktie=afmelden', '', '', '', 'Afmelden');
		
		// Stenography Menu
		$menu->addMenu('?page=stegencrypt', '', '', '', 'Steganography', 'Encryption');
		$menu->addMenu('?page=stegdecrypt', '', '', '', 'Steganography', 'Decryption');
		return $menu->createMenu('menu-wrap', '', '', 'menu');
	}
}
/** End of File: class.Display.php **/