<?php
/**
*
*	BestandsBeheer (Werken met bestanden op de server.)
*
*	Geschiedenis
*
*	2016-04-30
*		- functie bestanfsextensie aangemaakt (var $bestand)
*	2016-04-16
*		- functie leesBestandKlein aangemaakt (var $bestand)
*		- functie schrijfBestandKlein aangemaakt (var $string, $bestand)
*		- functie leesBestand aangemaakt (var $bestand)
*		- functie schrijfBestand aangemaakt (var $string, $bestand)
*	2014-01-25
*		- functie aanmakenMap aangemaakt (var $map, $allMaps)
*		- functie bestandRename aangemaakt (var $origBestand, $nieuwBestand)
*		- functie bestandMove aangemaakt (var $origBestand, $origMap, $nieuwMap)
*		- functie bestandCopy aangemaakt (var $origBestand, $nieuwBestand, $origMap, $nieuwMap)
*	2014-01-24
*		- functie inlezenMap aangemaakt (var $map, $sort)
*	2014-01-04: Aanmaken BestandsBeheer Class
*		- functie bestandUpload aangemaakt (var $origineelBestand, $nieuwBestand, $map)
*		- functie bestandWissen aangemaakt (var $bestand)
*
* @author		Rudy Mas <rudy.mas@rudymas.be>
* @copyright	Copyright (c) 2014 - 2016, rudymas.be. (http://www.rudymas.be/)
* @license		https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
* @version		1.0.2
* @since		2016-04-30
*/
class BestandsBeheer
{
	public $data, $aantalBestanden;
	
	/**
	* Aanmaken van een map
	*/
	public function aanmakenMap($map, $allMaps = FALSE)
	{
		if(substr($map, -1) != '/') $map = $map.'/';
		if(!is_dir($map))
		{
			@mkdir($map, 0777, $allMaps) or die ("Kon de map '{$map}' niet aanmaken.");
		}
	}

	/**
	* Bestand van naam veranderen
	*/
	public function bestandRename($origBestand, $nieuwBestand)
	{
		@rename($origBestand, $nieuwBestand) or die("De bestandsnaam kon niet veranderd worden van '{$origBestand}' naar '{$nieuwBestand}'.");
	}
	
	/**
	* Verplaatsen van een bestand
	*/
	public function bestandMove($origBestand, $origMap, $nieuwMap)
	{
		if(substr($origMap, -1) != '/') $origMap = $origMap.'/';
		if(substr($nieuwMap, -1) != '/') $nieuwMap = $nieuwMap.'/';
		$this->bestandCopy($origMap.$origBestand, $nieuwMap.$origBestand);
		$this->bestandWissen($origMap.$origBestand);
	}

	/**
	* Kopieren van een bestand
	*/
	public function bestandCopy($origBestand, $nieuwBestand)
	{
		if(is_file($origBestand))
		{
			@copy($origBestand, $nieuwBestand) or die("Bestand '{$origBestand}' kon niet gekopieerd worden naar '{$nieuwBestand}'.");
		} else {
			die("Bestand '{$origBestand}' bestaat niet.");
		}
	}

	/**
	* Inlezen van alle bestanden in een map (resultaat $data + $aantalBestanden)
	*/
	public function inlezenMap($map, $sort = 0)
	{
		if(substr($map, -1) != '/') $map = $map.'/';
		$this->data = @scandir($map, $sort) or die("Problemen bij het lezen van de map: '{$map}'.");
		$this->aantalBestanden = count($this->data);
	}
	
	/**
	* De extensie van het bestand achterhalen
	*/
	public function bestandsextensie($bestand)
	{
		$file = new SplFileInfo($bestand) or die("Probleem bij het openen van het bestand: '{$bestand}'.");
		return $file->getExtension();
	}
	
	/**
	* De naam van het bestand achterhalen
	*/
	public function bestandsnaam($bestand)
	{
		$file = new SplFileInfo($bestand) or die("Probleem bij het openen van het bestand: '{$bestand}'.");
		return $file->getFilename();
	}
	
	/**
	* Verwerken van een ingezonden bestand
	*/
	public function bestandUpload($origineelBestand, $nieuwBestand, $map)
	{
		if(substr($map, -1) != '/') $map = $map.'/';
		@move_uploaded_file($origineelBestand, $map.$nieuwBestand) or die("Bestand: '{$origineelBestand}' kon niet verplaatst worden naar '{$map}'.");
		@chmod($map.$nieuwBestand, 0777) or die("Kon chmod() functie niet uitvoeren op bestand: '{$nieuwBestand}' in map '{$map}'.");
	}
	
	/**
	* Verwijderen van een bestand op de server
	*/
	public function bestandWissen($bestand)
	{
		@unlink($bestand) or die("Bestand '{$bestand}' kon niet worden verwijderd van de server.");
	}
	
	/**
	* Wegschrijven String (data) naar een groot bestand
	*/
	public function schrijfBestand($data, $bestand)
	{
		$file = @fopen($bestand, "wb") or die("schrijfBestand: Er was een fout bij het openen van '{$bestand}'.");
		@fwrite($file, $data) or die("schrijfBestand: Er was een fout bij het wegschrijvan van de data naar '{$bestand}'.");
		@fclose($file);
	}
	
	/**
	* Inlezen van een bestand en uitvoeren als een string
	*/
	public function leesBestand($bestand)
	{
		$file = @fopen($bestand, "rb") or die("leesBestand: Er was een fout bij het openen van '{$bestand}'.");
		$contents = @fread($file, filesize($bestand)) or die("leesBestand: Er was een fout bij het inlezen van de data van '{$bestand}'.");
		@fclose($file);
		return $contents;
	}
	
	/**
	* Snel wegschrijven String (data) naar een klein bestand
	*/
	public function schrijfBestandKlein($string, $bestand)
	{
		@file_put_contents($bestand, $string) or die("schrijfBestandKlein: Er was een fout bij het schrijven van het bestand '{$bestand}'.");
	}
	
	/**
	* Inlezen van een klein bestand en uitvoeren als een string
	*/
	public function leesBestandKlein($bestand)
	{
		$bestand = @file_get_contents($bestand) or die("leesBestandKlein: Het bestand '{$bestand}' kon niet worden geopend.");
		return $bestand;
	}
}
/** End of File: class.BestandBeheer.php **/