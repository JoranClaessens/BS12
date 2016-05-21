<?php
/**
* TekstVerwerken - Handige functions voor teksten
*
* @author		Rudy Mas <rudy.mas@rudymas.be>
* @copyright	Copyright (c) 2015 - 2016, rudymas.be. (http://www.rudymas.be/)
* @license		https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
* @version		1.1.1
* @since		2016-05-21
*/
class TekstVerwerken
{
	/**
	* function randomTekst($aantalRekens)
	*
	* @param	int		$aantalTekens		Het aantal tekens dat je wilt hebben.
	* @return	string	$randomString		String van x aantal tekens.
	*/
	public function randomTekst($aantalTekens)
	{
		$karakters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($x=0; $x < $aantalTekens; $x++)
		{
			$randomString .= $karakters[rand(0, strlen($karakters)-1)];
		}
		return $randomString;
	}

	/**
	* function cleanHTML($input)
	*
	* @param	string	$input	Tekst die moet worden bewerkt.
	* @return	string			Tekst klaargemaakt om te gebruiken als HTML.
	*/
	public function cleanHTML($input)
	{
		return nl2br(htmlentities($input, ENT_QUOTES));
	}

	/**
	* function cleanURL($input)
	*
	* @param	string	$input	Tekst die moet worden bewerkt.
	* @return	string			Tekst klaargemaakt om te gebruiken als een URL.
	*/
	public function cleanURL($input)
	{
		return urlencode($input, ENT_QUOTES);
	}
}
/** End of File: cless.TekstVerwerken.php **/