<?php
/*
*
*	DBconnect -> PDO (Standaard utf8 charset)
*
*	Geschiedenis
*
*	2016-04-05: Bugfix
*		- functie exec hernoemd naar execQuery
*	2016-03-13: Kleine aanpassingen
*		- functie cleanInvoer aangepast voor PDO
*		- Foutmelding bij functie query en execute opgelost
*	2016-03-08: Toevoegen Prepared statements
*		- functie prepare (var $statement, $options = NULL) aangemaakt
*		- functie bindParam (var $paramno, $param, $type, $maxlen, $driverdata) aangemaakt
*		- functie bindValue (var $paramno, $param, $type) aangemaakt
*		- functie execute ($bound_input_params) aangemaakt
*	2016-03-07: Kleine aanpassingen
*		- functie exec (var $query) aangemaakt
*		- functie fetch aangepast (Toegevoegd var $internalData hiervoor)
*	2016-03-06: Aanmaken DBconnect Class (PDO versie voor MySQLi)
*		- functie cleanInvoer (var $inhoud)
*		- functie queryItem (var $query, $veld)
*		- functie queryRom (var $query)
*		- functie insert (var $query)
*		- functie update (var $query)
*		- functie delete (var $query)
*		- functie fetch (var $row)
*		- functie query (var $query)
*		- __construct (var $host, $gebruiker, $paswoord, $dbname, $charset, $dbtype)
*
* @author		Rudy Mas <rudy.mas@rudymas.be>
* @copyright	Copyright (c) 2014 - 2016, rudymas.be. (http://www.rudymas.be/)
* @license		https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
* @version		3.1.3
* @since		2016-04-05
*/
class DBconnect extends PDO
{
	public $rows, $data;
	private $result, $internalData;
	
# Openen van de database
	public function __construct($host = 'localhost', $gebruiker = 'gebruiker', $paswoord = 'paswoord', $dbname = 'dbname', $charset = 'utf8', $dbtype = 'mysql')
	{
		try
		{
			switch (strtolower($dbtype)) {
				case 'mysql':
					parent::__construct('mysql:host='.$host.';charset='.$charset.';dbname='.$dbname, $gebruiker, $paswoord);
					// parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
					// parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					break;
				default:
					die ($dbtype.' wordt nog niet ondersteund!');
					exit;
				}
		}
		catch (PDOException $e)
		{
			die('Exception!: ' . $e->getMessage());
			exit;
		}
	}
	
# Gebruik deze functie als de query (via SELECT) meerdere rijen moet teruggeven
	public function query($query)
	{
		$query = html_entity_decode($query);
		$this->result = parent::query($query) or die('Volgende query kon niet worden uitgevoerd: '.$query.'<br>('.$this->error.')');
		$this->rows = $this->result->rowCount();
		if ($this->rows > 0)
		{
			$this->internalData = $this->result->fetchAll(PDO::FETCH_ASSOC) or die ('Er was een fout bij het inhalen van de data.');
		}
	}
	
# Gebruik deze functie voor alle queries dat geen SELECT zijn (Interne funcie)
	public function execQuery($query)
	{
		$query = html_entity_decode($query);
		$this->rows = parent::exec($query);
	}

# Gebruik deze functie om een bepaalde rij uit de query te halen
	public function fetch($row)
	{
		$this->data = $this->internalData[$row];
	}
	
# Gebruik deze functie om een data veld in te lezen
	public function queryItem($query, $veld)
	{
		$query = html_entity_decode($query);
		$this->query($query);
		$this->fetch(0);
		return($this->data[$veld]);
	}
	
# Gebruik deze functie om een bepaalde rij op te vragen
	public function queryRow($query)
	{
		$query = html_entity_decode($query);
		$this->query($query);
		if($this->rows == 0) return FALSE;
		$this->fetch(0);
		return($this->data);
	}
	
# Gebruik deze functie om data toe te voegen aan de database
	public function insert($query)
	{
		$query = html_entity_decode($query);
		$this->execQuery($query);
	}

# Gebruik deze functie om data te updaten in de database
	public function update($query)
	{
		$query = html_entity_decode($query);
		$this->execQuery($query);
	}

# Gebruik deze functie om data te verwijderen van de database
	public function delete($query)
	{
		$query = html_entity_decode($query);
		$this->execQuery($query);
	}

# Gebruik deze functie om een query voor te bereiden (Prepared Statement)
	public function prepare($statement, $options = NULL)
	{
		$this->result = parent::prepare($statement, $options) or die('Er was een fout in de prepared statement: '.$statement);
	}

# Gebruik deze functie om de prepared query in te vullen (Prepared Statement)
# $type:
#	* PDO::PARAM_BOOL = Staat voor een boolean
#	* PDO::PARAM_NULL = Staat voor een SQL NULL data type
#	* PDO::PARAM_INT = Staat voor een SQL Integer data type
#	* PDO::PARAM_STR = Staat voor een SQL CHAR, VARCHAR, of ander String data type
#	* PDO::PARAM_LOB = Staat voor een SQL Large object data type
#	* PDO::PARAM_STMT = Staat voor een recordset type (Op dit moment nog niet ondersteund door de drivers)
#
	public function bindParam($paramno, $param, $type, $maxlen, $driverdata)
	{
		$this->result->bindParam($paramno, $param, $type, $maxlen, $driverdata) or die('Er was een fout bij het binden van de parameter: '.$paramno);
	}

# Gebruik deze functie om de prepared query in te vullen (Prepared Statement)
# $type:
#	* PDO::PARAM_BOOL = Staat voor een boolean
#	* PDO::PARAM_NULL = Staat voor een SQL NULL data type
#	* PDO::PARAM_INT = Staat voor een SQL Integer data type
#	* PDO::PARAM_STR = Staat voor een SQL CHAR, VARCHAR, of ander String data type
#	* PDO::PARAM_LOB = Staat voor een SQL Large object data type
#	* PDO::PARAM_STMT = Staat voor een recordset type (Op dit moment nog niet ondersteund door de drivers)
#
	public function bindValue($paramno, $param, $type)
	{
		$this->result->bindValue($paramno, $param, $type) or die('Er was een fout bij het binden van de parameter: '.$paramno);
	}

# Gebruik deze functie om de prepared query uit te voeren (Prepared Statement)
	public function execute($bound_input_params)
	{
		$this->rows = $this->result->execute($bound_input_params) or die('Er is een fout gebeurd bij het uitvoeren van de prepared query.');
		if ($this->rows > 0)
		{
			$this->internalData = $this->result->fetchAll(PDO::FETCH_ASSOC) or die ('Er was een fout bij het inhalen van de data.');
		}
	}

# Speciale tekens klaarmaken voor MySQL Query
	public function cleanInvoer($inhoud)
	{
		if($inhoud == NULL)
		{
			$uitvoer = parent::quote(NULL);
		} else {
			$uitvoer = parent::quote($inhoud) or die("Er is een fout gebeurd bij het uitzuiveren van de tekst '$inhoud'.");
		}
		return $uitvoer;
	}

# Aangemaakt als alternatief voor cleanInvoer($inhoud)
	public function cleanSQL($inhoud = NULL)
	{
		$this->cleanInvoer($inhoud);
	}
}
/** End of File: class.DBconnect.php **/