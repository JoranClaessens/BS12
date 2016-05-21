<?php
require_once('class.HTML5.php');
/**
*	class Forms (Version PHP7)
*
*	This class is an extention of the HTML5 class.
*	You can use this class to create forms.
*
*	-----
*
*	History
*
*	2016-04-17:
*		- function addInpuy changed (Select added.)
*	2016-04-14:
*		- function createForm changed (Fieldset added.)
*		- function addFieldset added
*	2016-04-12:
*		- class Forms created + standard functions for the class
*
* @author		Rudy Mas <rudy.mas@rudymas.be>
* @copyright	Copyright (c) 2016, rudymas.be. (http://www.rudymas.be/)
* @license		https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
* @version		0.5.9
* @since		2016-04-30
*/
class Forms extends HTML5
{
	private $formData = array();
	private $fieldsetData = array();
	private $nrInput = 0;
	private $nrFieldset = 0;
	
	/**
	* function addInput($type, $var, $value, $id, $class, $attributes, $div, $label, $labelId, $labelClass, $fieldsetName, $checkRadioText)
	*
	* @param $type				Specifies the type input element to display
	* @param $var				Specifies the name of an input element (variable)
	* @param $value				Specifies the value of an input element
	* @param $id				Specifies the id of an input element
	* @param $class				Specifies the class of an input element
	* @param $attributes		Any other attributes that belongs to this tag you want to use
	* @param $div				Specifies the class of the div element around the label- and input element
	* @param $label				Specifies the text that has to be shown for the label
	* @param $labelId			Specifies the id of the label element
	* @param $labelClass		Specifies the class of the label element
	* @param $fieldsetName		The name of the fieldset this element belongs to
	* @param $checkRadioText	Text to show before the checkbox or radio element
	*
	* The information is added to the $formData array
	*/
	public function addInput($type, $var= '', $value = '', $id = '', $class = '', $attributes = '', $div = '', $label = '', $labelId = '', $labelClass = '', $fieldsetName = '', $checkRadioText = '')
	{
		$this->formData[$this->nrInput]['type'] = $type;
		$this->formData[$this->nrInput]['name'] = $var;
		$this->formData[$this->nrInput]['value'] = $value;
		$this->formData[$this->nrInput]['id'] = $id;
		$this->formData[$this->nrInput]['class'] = $class;
		$this->formData[$this->nrInput]['attributes'] = $attributes;
		$this->formData[$this->nrInput]['div'] = $div;
		$this->formData[$this->nrInput]['label'] = $label;
		$this->formData[$this->nrInput]['labelId'] = $labelId;
		$this->formData[$this->nrInput]['labelClass'] = $labelClass;
		$this->formData[$this->nrInput]['fieldsetName'] = $fieldsetName;
		$this->formData[$this->nrInput]['checkRadioText'] = $checkRadioText;
		$this->nrInput++;
	}
	
	public function addFieldset($name, $legend)
	{
		$this->fieldsetData[$this->nrFieldset]['name'] = $name;
		$this->fieldsetData[$this->nrFieldset]['legend'] = $legend;
		$this->nrFieldset++;
	}
	
	/**
	* Create the form
	*/
	public function createForm($name, $action = '', $method = '', $attributes = '')
	{
		$attrib = '';
		if (isset($name)) $attrib.= sprintf(' name="%s"', $name);
		if (isset($attributes)) $attrib.= sprintf(' %s', $attributes);
		$output = $this->form('open', $action, $method, trim($attrib));
		// For the input elements that belong to a fieldset
		foreach ($this->fieldsetData as $fieldData)
		{
			$output.= $this->fieldset('open', sprintf('id="%s"', $fieldData['name']));
			$output.= $this->legend('full', '', $fieldData['legend']);
			foreach ($this->formData as $field)
			{
				if ($fieldData['name'] == $field['fieldsetName'])
				{
					$output.= $this->div('open', ($field['div'] != '') ? sprintf('class="%s"', $field['div']) : '');
					if ($field['type'] == 'checkbox' || $field['type'] == 'radio')
					{
						if ($field['checkRadioText'] != '') $output.= sprintf('<span class="label">%s</span> ', $field['checkRadioText']);
						$attrib = '';
						if ($field['id'] != '') $attrib.= sprintf('id="%s"', $field['id']);
						if ($field['class'] != '') $attrib.= sprintf(' class="%s"', $field['class']);
						if ($field['attributes'] != '') $attrib.= sprintf(' %s', $field['attributes']);
						$output.= $this->input($field['type'], $field['name'], $field['value'], trim($attrib), $field['label']);
						$output.= sprintf('%s', $field['label']);
					}
					elseif ($field['type'] == 'a')
					{
						$url = '?';
						if ($field['name'] != '') $url.= sprintf('%s=%s', $field['name'], $field['value']);
						$attrib = '';
						if ($field['id'] != '') $attrib.= sprintf('id="%s"', $field['id']);
						if ($field['class'] != '') $attrib.= sprintf(' class="%s"', $field['class']);
						if ($field['attributes'] != '') $attrib.= sprintf(' %s', $field['attributes']);
						$output.= $this->a('full', $url, $attrib, $field['label']);
					}
					elseif ($field['type'] == 'select')
					{
						$attrib = '';
						if ($field['labelId'] != '') $attrib.= sprintf('id="%s"', $field['labelId']);
						if ($field['labelClass'] != '') $attrib.= sprintf(' class="%s"', $field['labelClass']);
						if ($field['label'] != '') $output.= $this->label($field['id'], trim($attrib), $field['label']);
						$attrib = '';
						if ($field['id'] != '') $attrib.= sprintf('id="%s"', $field['id']);
						if ($field['class'] != '') $attrib.= sprintf(' class="%s"', $field['class']);
						if ($field['attributes'] != '') $attrib.= sprintf(' %s', $field['attributes']);
						$output.= $this->select('full', $field['name'], trim($attrib), $field['value']);
					}
					elseif ($field['type'] == 'textarea')
					{
						$attrib = '';
						if ($field['labelId'] != '') $attrib.= sprintf('id="%s"', $field['labelId']);
						if ($field['labelClass'] != '') $attrib.= sprintf(' class="%s"', $field['labelClass']);
						if ($field['label'] != '')
						{
							$output.= $this->label($field['id'], trim($attrib), $field['label']);
							$output.= $this->br();
						}
						$attrib = '';
						if ($field['id'] != '') $attrib.= sprintf('id="%s"', $field['id']);
						if ($field['class'] != '') $attrib.= sprintf(' class="%s"', $field['class']);
						if ($field['attributes'] != '') $attrib.= sprintf(' %s', $field['attributes']);
						$output.= $this->textarea('full', $field['name'], '', '', $attrib, $field['value']);
					}
					else
					{
						$attrib = '';
						if ($field['labelId'] != '') $attrib.= sprintf('id="%s"', $field['labelId']);
						if ($field['labelClass'] != '') $attrib.= sprintf(' class="%s"', $field['labelClass']);
						if ($field['label'] != '') $output.= $this->label($field['id'], trim($attrib), $field['label']);
						$attrib = '';
						if ($field['id'] != '') $attrib.= sprintf('id="%s"', $field['id']);
						if ($field['class'] != '') $attrib.= sprintf(' class="%s"', $field['class']);
						if ($field['attributes'] != '') $attrib.= sprintf(' %s', $field['attributes']);
						$output.= $this->input($field['type'], $field['name'], $field['value'], trim($attrib));
					}
					$output.= $this->div('close');
				}
			}
			$output.= $this->fieldset('close');
		}
		// For the input elements that aren't linked with a fieldset
		foreach ($this->formData as $field)
		{
			if ($field['fieldsetName'] == '')
			{
				$output.= $this->div('open', ($field['div'] != '') ? sprintf('class="%s"', $field['div']) : '');
				if ($field['type'] == 'checkbox' || $field['type'] == 'radio')
				{
					if ($field['checkRadioText'] != '') $output.= sprintf('<span class="label">%s</span> ', $field['checkRadioText']);
					$attrib = '';
					if ($field['id'] != '') $attrib.= sprintf('id="%s"', $field['id']);
					if ($field['class'] != '') $attrib.= sprintf(' class="%s"', $field['class']);
					if ($field['attributes'] != '') $attrib.= sprintf(' %s', $field['attributes']);
					$output.= $this->input($field['type'], $field['name'], $field['value'], trim($attrib), $field['label']);
					$output.= sprintf('%s', $field['label']);
				}
				elseif ($field['type'] == 'a')
				{
					$url = '?';
					if ($field['name'] != '') $url.= sprintf('%s=%s', $field['name'], $field['value']);
					$attrib = '';
					if ($field['id'] != '') $attrib.= sprintf('id="%s"', $field['id']);
					if ($field['class'] != '') $attrib.= sprintf(' class="%s"', $field['class']);
					if ($field['attributes'] != '') $attrib.= sprintf(' %s', $field['attributes']);
					$output.= $this->a('full', $url, $attrib, $field['label']);
				}
				elseif ($field['type'] == 'select')
				{
					$attrib = '';
					if ($field['labelId'] != '') $attrib.= sprintf('id="%s"', $field['labelId']);
					if ($field['labelClass'] != '') $attrib.= sprintf(' class="%s"', $field['labelClass']);
					if ($field['label'] != '') $output.= $this->label($field['id'], trim($attrib), $field['label']);
					$attrib = '';
					if ($field['id'] != '') $attrib.= sprintf('id="%s"', $field['id']);
					if ($field['class'] != '') $attrib.= sprintf(' class="%s"', $field['class']);
					if ($field['attributes'] != '') $attrib.= sprintf(' %s', $field['attributes']);
					$output.= $this->select('full', $field['name'], trim($attrib), $field['value']);
				}
				elseif ($field['type'] == 'textarea')
				{
					$attrib = '';
					if ($field['labelId'] != '') $attrib.= sprintf('id="%s"', $field['labelId']);
					if ($field['labelClass'] != '') $attrib.= sprintf(' class="%s"', $field['labelClass']);
					if ($field['label'] != '')
					{
						$output.= $this->label($field['id'], trim($attrib), $field['label']);
						$output.= $this->br();
					}
					$attrib = '';
					if ($field['id'] != '') $attrib.= sprintf('id="%s"', $field['id']);
					if ($field['class'] != '') $attrib.= sprintf(' class="%s"', $field['class']);
					if ($field['attributes'] != '') $attrib.= sprintf(' %s', $field['attributes']);
					$output.= $this->textarea('full', $field['name'], '', '', $attrib, $field['value']);
				}
				else
				{
					$attrib = '';
					if ($field['labelId'] != '') $attrib.= sprintf('id="%s"', $field['labelId']);
					if ($field['labelClass'] != '') $attrib.= sprintf(' class="%s"', $field['labelClass']);
					if ($field['label'] != '') $output.= $this->label($field['id'], trim($attrib), $field['label']);
					$attrib = '';
					if ($field['id'] != '') $attrib.= sprintf('id="%s"', $field['id']);
					if ($field['class'] != '') $attrib.= sprintf(' class="%s"', $field['class']);
					if ($field['attributes'] != '') $attrib.= sprintf(' %s', $field['attributes']);
					$output.= $this->input($field['type'], $field['name'], $field['value'], trim($attrib));
				}
				$output.= $this->div('close');
			}
		}
		$output.= $this->form('close');;
		return $output;
	}

	/**
	/ Quick test of the input array. Will be deleted at the end!!!
	*/
	public function testInput()
	{
		echo '<pre>';
		print_r($this->formData);
		echo '</pre>';
	}
}
/** End of File: class.Forms.php **/