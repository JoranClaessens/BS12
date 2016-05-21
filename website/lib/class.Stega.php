<?php
require_once('class.BestandsBeheer.php');
require_once('class.TekstVerwerken.php');
/**
*
* Stega -> This class can be used to add data to an image.
*
* You can use JPG, GIF, PNG and BMP images but the output will always be a PNG image
* !!! The image file has to be 8 times bigger than the data you want to add
*
* History
*
* v0.0.2:
*		- Fixed a bug in addTextToImage function
*		- Fixed a bug in stripTextFromImage function
* v0.0.1: Creation Stega Class
*		- function rgbToBin(var $RGB)
*		- function checkEven(var $number)
*		- function binaryToAscii(var $bin)
*		- function asciiToBinary(var $char)
*		+ function stripTextFromImage(var $image , $isFile)
*		+ function addFileToImage(var $file)
*		+ function addTextToImage(var $text, $filename)
*		+ function saveImage (var $image)
*		+ function loadImage (var $image)
*
* @author		Rudy Mas <rudy.mas@rudymas.be>
* @copyright	Copyright (c) 2016, rudymas.be. (http://www.rudymas.be/)
* @license		https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
* @version		0.0.2
* @since		2016-05-21
*/
class Stega
{
	private $imageSource;
	private $imageSourceSize;
	public $text;
	public $filename;
	
	/**
	* funtion loadImage($image)
	*
	* @param	string	$image	Link to the image on the server
	* @return	boolean			TRUE/FALSE depending on the image being read or not
	*/	
	public function loadImage($image)
	{
		$bestand = new BestandsBeheer;
		$typeFile = $bestand->bestandsextensie($image);
		switch (strtolower($typeFile))
		{
			case 'jpg':
				if (($this->imageSource = @imagecreatefromjpeg($image)) === FALSE)
				{
					return FALSE;
				}
				else
				{
					$this->imageSourceSize = @getimagesize($image);
					return TRUE;
				}
			case 'gif':
				if (($this->imageSource = @imagecreatefromgif($image)) === FALSE)
				{
					return FALSE;
				}
				else
				{
					$this->imageSourceSize = @getimagesize($image);
					return TRUE;
				}
			case 'png':
				if (($this->imageSource = @imagecreatefrompng($image)) === FALSE)
				{
					return FALSE;
				}
				else
				{
					$this->imageSourceSize = @getimagesize($image);
					return TRUE;
				}
			case 'bmp':
				if (($this->imageSource = @imagecreatefromwbmp($image)) === FALSE)
				{
					return FALSE;
				}
				else
				{
					$this->imageSourceSize = @getimagesize($image);
					return TRUE;
				}
			default:
				return FALSE;
		}
	}

	/**
	* funtion saveImage($image)
	*
	* @param	string	$image	Link and name of the image to save
	* @return	boolean			TRUE/FALSE depending on the image being read or not
	*/	
	public function saveImage($image)
	{
		return @imagepng($this->imageSource, $image);
	}
	
	/**
	* funtion addTextToImage($text)
	*
	* @param	string	$text		String with the text to add in the image
	* @param	string	$filename	Filename if text is from a file (Default = '')
	* @return	boolean				TRUE/FALSE depending on the text being added to image
	*/	
	public function addTextToImage($text, $filename = '')
	{
		// Init for $make_odd array
		$make_odd = array();

		// Init TekstVerwerken Class
		$tekst = new TekstVerwerken;
		
		// Set 6 random characters to be used as the border of the data
		do {
			$border = $tekst->randomTekst(6);
		} while (strpos($text, $border) !== FALSE && strpos($filename, $border) !== FALSE);

		// Add $border around $text (If filename is given, add it also)
		if ($filename == '')
		{
			$data = $border.$text.$border;
		}
		else
		{
			$data = $border.$filename.$border.$text.$border;
		}
		
		// Check if the data will fit into the image file
		if (strlen($data)*8 > ($this->imageSourceSize[0]*$this->imageSourceSize[1])*3)
		{
			return FALSE;
		}
		
		for ($x = 0; $x < strlen($data); $x++)
		{
			$char = $data[$x];
			$bin = $this->asciiToBinary($char);

			// Creating an array of true and false for each bit
			for ($y = 0; $y < strlen($bin); $y++)
			{
				$binpart = $bin[$y];
				if ($binpart == '0')
				{
					$make_odd[] = TRUE;
				}
				else
				{
					$make_odd[] = FALSE;
				}
			}
		}
		
		// Looping through each pixel and changing it according $make_odd array
		$y = 0;
		for ($i = 0, $x = 0; $i < sizeof($make_odd); $i += 3, $x++)
		{
			// Reading pixel's RGB
			$RGB = imagecolorat($this->imageSource, $x, $y);
			$color = array();
			$color[] = ($RGB >> 16) & 0xFF;
			$color[] = ($RGB >> 8) & 0xFF;
			$color[] = $RGB & 0xFF;
			
			// Setting the pixels according $make_odd array
			for ($j = 0; $j < sizeof($color) && ($i+$j) < sizeof($make_odd); $j++)
			{
				if ($make_odd[$i + $j] === TRUE && !$this->checkEven($color[$j]))
				{
					$color[$j]--;
				}
				else if ($make_odd[$i + $j] === FALSE && $this->checkEven($color[$j]))
				{
					$color[$j]++;
				}
			}
			
			// change the pixel in the image
			$temp_col = imagecolorallocate($this->imageSource, $color[0], $color[1], $color[2]);
			imagesetpixel($this->imageSource, $x, $y, $temp_col);
			
			if ($x == ($this->imageSourceSize[0] - 1))
			{
				$y++;
				$x=-1;
			}
		}
		return TRUE;
	}
	
	/**
	* funtion addFileToImage($file)
	*
	* @param	string	$file	Textfile to be used
	* @return	boolean			TRUE when data is added to image, FALSE if not
	*/	
	public function addFileToImage($file)
	{
		$bestand = new BestandsBeheer;
		$text = $bestand->leesBestandKlein($file);
		$filename = $bestand->bestandsnaam($file);
		
		return $this->addTextToImage($text, $filename);
	}

	/**
	* function stripTextFromImage($image, $isFile = FALSE)
	*
	* @param	string	$image	Image which contains some text
	* @param	boolean	$isFile	If source was a textfile or not (Default = FALSE)
	* @return	boolean			TRUE/FALSE depending on errors or not
	*/
	public function stripTextFromImage($isFile = FALSE)
	{
		$border = '';
		$binstream = '';
		$returnIs = FALSE;
		
		// Get the border binary from the image
		$binBorder = '';
		for ($x = 0; $x < 16;  $x++)
		{
			$binBorder .= $this->rgbToBin(imagecolorat($this->imageSource, $x, 0));
		}

		// Convert border binary to border ascii
		for ($x = 0; $x < strlen($binBorder); $x += 8)
		{
			$binChunk = substr($binBorder, $x, 8);
			$border .= $this->binaryToAscii($binChunk);
		}

		// Get the original data out of the image
		$startX = 16;
		for ($y = 0; $y < $this->imageSourceSize[1]; $y++)
		{
			for ($x = $startX; $x < $this->imageSourceSize[0]; $x++)
			{
				$binstream .= $this->rgbToBin(imagecolorat($this->imageSource, $x, $y));
				
				if (strlen($binstream) >= 8)
				{
					$binchar = substr($binstream, 0, 8);
					$this->text .= $this->binaryToAscii($binchar);
					$binstream = substr($binstream, 8);
				}

				if ($isFile === TRUE)
				{
					if (strpos($this->text, $border) !== FALSE)
					{
						$this->text = substr($this->text, 0, strlen($this->text) - 6);
						
						if ($this->filename == '')
						{
							$this->filename = $this->text;
							$this->text = '';
						}
						else
						{
							$returnIs = TRUE;
							break 2;
						}
					}
				}
				else
				{
					if (strpos($this->text, $border) !== FALSE)
					{
						$returnIs = TRUE;
						$this->text = substr($this->text, 0, strlen($this->text) - 6);
						break 2;
					}
				}
			}
			$startX = 0;
		}
		return $returnIs;
	}
	
	/**
	* function asciiToBinary($char)
	*
	* @param	char	$char	The character to convert
	* @return	string			Binary representation of the character
	*/
	private function asciiToBinary($char)
	{
		return str_pad(decbin(ord($char)), 8, "0", STR_PAD_LEFT);
	}
	
	/**
	* function binaryToAscii($bin)
	*
	* @param	string	$bin	binary representation of a character
	* @return	char			The character 
	*/
	private function binaryToAscii($bin)
	{
		return chr(bindec($bin));
	}

	/**
	* function checkEven($number)
	*
	* @param	int		$number	A number
	* @return	boolean			Returns TRUE if $number is even, FALSE if not
	*/
	private function checkEven($number)
	{
		return ($number % 2 == 0);
	}
	
	/**
	* function rgbToBin($rgb)
	*
	* @param	binary	$RGB	The RGB information of a pixel in the image
	* @return	binary			3 bit binary stream
	*/
	private function rgbToBin($RGB)
	{
		$binstream = '';
		$red = ($RGB >> 16) & 0xFF;
		$green = ($RGB >> 8) & 0xFF;
		$blue = $RGB & 0xFF;
		
		if ($this->checkEven($red))
		{
			$binstream .= '0';
		}
		else
		{
			$binstream .= '1';
		}
		if ($this->checkEven($green))
		{
			$binstream .= '0';
		}
		else
		{
			$binstream .= '1';
		}
		if ($this->checkEven($blue))
		{
			$binstream .= '0';
		}
		else
		{
			$binstream .= '1';
		}
		
		return $binstream;
	}
}
/** End of FIle: class.Stega.php **/