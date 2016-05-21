<?php
require_once('class.HTML5.php');
/**
*	class Menu (Version PHP7)
*
*	This class is an extention of the HTML5 class.
*	You can use this class to create a menu for you. (Max. 5 sub-menus deep)
*
*	-----
*
*	History
*
*	2016-04-09:
*		- class Menu created + standard functions for the class
*
*	-----
*
*	$menu = new Menu($maxMenuItems);
*
*	$maxMenuItems = The number of menu items allowed. (Default = 20)
*
*/
class Menu extends HTML5
{
	private $output;
	private $dataMenu = array();
	private $aantalMenuItems;
	
	/**
	* Creating of the Menu
	*/
	public function __construct($maxMenuItems = 15)
	{
		$this->aantalMenuItems = $maxMenuItems;
	}
	
	/**
	* Opening the menu with a nav-tag
	*/
	private function openNav($id = '', $class = '', $attributes = '')
	{
		$attrib = '';
		if ($id != '') $attrib.= sprintf(' id="%s"', $id);
		if ($class != '') $attrib.= sprintf(' class="%s"', $class);
		if ($attributes != '') $attrib.= sprintf(' %s', $attributes);
		$this->output = $this->nav('open', trim($attrib));
	}
	
	private function closeNav()
	{
		$this->output.= $this->nav('close');
	}
	
	/**
	* To create the menu
	*/
	private function createList($arrayMenu = '', $id = '', $class = '', $attributes = '')
	{
		if ($arrayMenu == '') $arrayMenu = $this->dataMenu;
		$remove = array ('geen', 'Geen');
		
		$arrayKey = array_keys($arrayMenu);
		$arrayKey = array_diff($arrayKey, $remove);
		$arrayKey = array_values($arrayKey);
		$arrayCount = count($arrayKey);
		
		$attrib = '';
		if ($id != '') $attrib.= sprintf(' id="%s"', $id);
		if ($class != '') $attrib.= sprintf(' class="%s"', $class);
		if ($attributes != '') $attrib.= sprintf(' %s', $atrributes);
		$this->output.= $this->ul('open', trim($attrib));
		
		if ($arrayCount > $this->aantalMenuItems)
		{
			$aantalExtraMenu = ceil($arrayCount / $this->aantalMenuItems);
			for ($x = 0; $x < $aantalExtraMenu; $x++)
			{
				$this->output.= $this->li('open');
				$this->output.= $this->a('full', '', 'class="extraMenu"', '&lt; '.($x+1).' &gt;');
				$this->output.= $this->ul('open', 'class="extraMenu"');
				for ($y = $x * $this->aantalMenuItems; $y < $this->aantalMenuItems + ($this->aantalMenuItems * $x) && $y < $arrayCount; $y++)
				{
					list ($_url, $_id, $_class, $_attrib) = $this->getURL($arrayMenu[$arrayKey[$y]], $arrayKey[$y]);
					$attribTemp = '';
					if ($_id != '') $attribTemp.= sprintf(' id="%s"', $_id);
					if ($_class != '') $attribTemp.= sprintf(' class="%s"', $_class);
					if ($_attrib != '') $attribTemp.= sprintf(' %s', $_attrib);
					$this->output.= $this->li('open');
					$this->output.= $this->a('full', $_url, trim($attribTemp), $arrayKey[$y]);
					$testArray = is_array($arrayMenu[$arrayKey[$y]]);
					if ($testArray === TRUE)
					{
						$arrayKeyChild = array_keys($arrayMenu[$arrayKey[$y]]);
						$arrayKeyChild = array_diff($arrayKeyChild, $remove);
						$arrayCountChild = count($arrayKeyChild);
						if ($arrayCountChild > 0)
						{
							$this->output.= $this->createList($arrayMenu[$arrayKey[$y]]);
						}
					}
					$this->output.= $this->li('close');
				}
				$this->output.= $this->ul('close');
				$this->output.= $this->li('close');
			}
		}
		else
		{
			foreach ($arrayKey as $menuItem)
			{
				list ($_url, $_id, $_class, $_attrib) = $this->getURL($arrayMenu[$menuItem], $menuItem);
				$attribTemp = '';
				if ($_id != '') $attribTemp.= sprintf(' id="%s"', $_id);
				if ($_class != '') $attribTemp.= sprintf(' class="%s"', $_class);
				if ($_attrib != '') $attribTemp.= sprintf(' %s', $_attrib);
				$this->output.= $this->li('open');
				$this->output.= $this->a('full', $_url, trim($attribTemp), $menuItem);
				$testArray = is_array($arrayMenu[$menuItem]);
				if ($testArray === TRUE)
				{
					$arrayKeyChild = array_keys($arrayMenu[$menuItem]);
					$arrayKeyChild = array_diff($arrayKeyChild, $remove);
					$arrayCountChild = count($arrayKeyChild);
					if ($arrayCountChild > 0)
					{
						$this->output.= $this->createList($arrayMenu[$menuItem]);
					}
				}
				$this->output.= $this->li('close');
			}
		}
		$this->output.= $this->ul('close');
	}
	
	/**
	* Get the URL linked with the menu item
	*/
	private function getURL($array, $key)
	{
		$countArray = $this->array_depth($array)-1;
		switch ($countArray)
		{
			case 1:
				$url = $array['geen']['url'];
				$id = $array['geen']['id'];
				$class = $array['geen']['class'];
				$attrib = $array['geen']['attrib'];
				return array ($url, $id, $class, $attrib);
			case 2:
				$url = $array['geen']['geen']['url'];
				$id = $array['geen']['geen']['id'];
				$class = $array['geen']['geen']['class'];
				$attrib = $array['geen']['geen']['attrib'];
				return array ($url, $id, $class, $attrib);
			case 3:
				$url = $array['geen']['geen']['geen']['url'];
				$id = $array['geen']['geen']['geen']['id'];
				$class = $array['geen']['geen']['geen']['class'];
				$attrib = $array['geen']['geen']['geen']['attrib'];
				return array ($url, $id, $class, $attrib);
			case 4:
				$url = $array['geen']['geen']['geen']['geen']['url'];
				$id = $array['geen']['geen']['geen']['geen']['id'];
				$class = $array['geen']['geen']['geen']['geen']['class'];
				$attrib = $array['geen']['geen']['geen']['geen']['attrib'];
				return array ($url, $id, $class, $attrib);
			default:
				$url = $array['geen']['geen']['geen']['geen']['geen']['url'];
				$id = $array['geen']['geen']['geen']['geen']['geen']['id'];
				$class = $array['geen']['geen']['geen']['geen']['geen']['class'];
				$attrib = $array['geen']['geen']['geen']['geen']['geen']['attrib'];
				return array ($url, $id, $class, $attrib);
		}
	}
	
	/**
	* To return the created menu
	*/
	public function createMenu($idNav = '', $classNav = '', $attributesNav = '', $idList = '', $classList = '', $attributesList = '')
	{
		$this->openNav($idNav, $classNav, $attributesNav);
		$this->createList('', $idList, $classList, $attributesList);
		$this->closeNav();
		return $this->output;
	}
	
	/**
	* Adding a menu item
	*/
	public function addMenu($url, $id, $class, $attributes, $menu1, $menu2 = 'geen', $menu3 = 'geen', $menu4 = 'geen', $menu5 = 'geen', $menu6 = 'geen')
	{
		$this->dataMenu[$menu1][$menu2][$menu3][$menu4][$menu5][$menu6]['url'] = $url;
		$this->dataMenu[$menu1][$menu2][$menu3][$menu4][$menu5][$menu6]['id'] = $id;
		$this->dataMenu[$menu1][$menu2][$menu3][$menu4][$menu5][$menu6]['class'] = $class;
		$this->dataMenu[$menu1][$menu2][$menu3][$menu4][$menu5][$menu6]['attrib'] = $attributes;
	}

	/**
	* This returns the depth of an array
	*/
	public function array_depth($array)
	{
		$max_depth = 1;
		foreach ($array as $value)
		{
			if (is_array($value))
			{
				$depth = $this->array_depth($value) + 1;
				if ($depth > $max_depth)
				{
					$max_depth = $depth;
				}
			}
		}
		return $max_depth;
	}
}
### End of File: class.Menu.php