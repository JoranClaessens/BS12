<?php
/*
*
* Zipper (Uitpakken ZIP bestanden.)
*
* Geschiedenis
*
* 2013-12-28: Aanmaken Zipper Class (ZipArchive versie)
*	- functie zipDitBestand aangemaakt (var $folder, $ditBestand)
*	- functie zipMap aangemaakt (var $folder)
*	- functie zipBestand aangemaakt (var $folder)
*	- functie unZip aangemaakt (var $bestemming)
*	- functie sluiten aangemaakt
*	- __construct aangemaakt (var $bestand)
*
* @author		Rudy Mas <rudy.mas@rudymas.be>
* @copyright	Copyright (c) 2013, rudymas.be. (http://www.rudymas.be/)
* @license		https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
* @version		1.0.0
* @since		2013-12-28
*/

class Zipper extends ZipArchive
{
	private $openBestand;
	
	# Openen of aanmaken ZIP-bestand
	public function __construct($bestand)
	{
		$this->openBestand = $bestand;
		if(is_file($bestand))
		{
			$testZip = @$this->open($bestand);
			if($testZip !== TRUE) die("Fout bij openen bestand: '$bestand'.");
		}
		else
		{
			$testZip = @$this->open($bestand, Zipper::CREATE);
			if($testZip !== TRUE) die("Fout bij het aanmaken van bestand: '$bestand'.");
		}
	}
	

	# Afsluiten ZIP-bestand
	public function sluiten()
	{
		@$this->close() or die("Fout bij het sluiten van '$this->openBestand'.");
	}


	# Uitpakken ZIP-bestand
	public function unZip($bestemming)
	{
		if(substr($bestemming, -1) != '/') $bestemming = $bestemming.'/';
		@$this->extractTo($bestemming) or die("Fout bij het uitpakken van '$this->openBestand'.");
	}
	

	# Inpakken specifiek bestanden in een bepaalde map
	public function zipDitBestand($folder, $ditBestand)
	{
		$map = opendir($folder);
		while($file = readdir($map))
		{
			if($file != '.' && $file != '..' && $file == $ditBestand)
			{
				if (is_file("$folder/$file")) @$this->addFile("$folder/$file", $file) or die("Kon bestand '$file' niet toevoegen aan '$this->openBestand'.");
			}
		}
		closedir($map);
	}
	

	# Inpakken alle bestanden in een bepaalde map
	public function zipBestand($folder)
	{
		$map = opendir($folder);
		while($file = readdir($map))
		{
			if($file != '.' && $file != '..')
			{
				if (is_file("$folder/$file")) @$this->addFile("$folder/$file", $file) or die("Kon bestand '$file' niet toevoegen aan '$this->openBestand'.");
			}
		}
		closedir($map);
	}
	

	# Inpakken alle bestanden en mappen in een bepaalde map
	public function zipMap($folder)
	{
		$map = opendir($folder);
		while($file = readdir($map))
		{
			if($file != '.' && $file != '..')
			{
				if(is_file("$folder/$file"))
				{
					@$this->addFile("$folder/$file") or die("Kon bestand '$folder/$file' niet toevoegen aan '$this->openBestand'.");
				}
				if(is_dir("$folder/$file"))
				{
					@$this->addEmptyDir("$folder/$file") or die("Kon map '$folder/$file' niet toevoegen aan '$this->openBestand'.");
					$this->zipMap("$folder/$file");
				}
			}
		}
		closedir($map);
	}
}
/** End of File: class.Zipper.php **/