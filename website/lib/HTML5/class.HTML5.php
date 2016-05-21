<?php
/**
*
*	HTML5 (Creating a HTML5 page with PHP code)
*
*	Use $className->HTML-tag('!help!') at the first line of your code to get
*	more information about the element which you want to use.
*	Example: $className->abbr(!help!);
*
*	History
*
*	2016-04-16:
*		- function testVar() created
*	2016-04-04 - 2016-04-09:
*		Rewritten the code and added all the HTML5 elements to the class.
*	2014-02-05: creating HTML5 Class
*		- __destruct created
*		- __construct created
*
*
*	-----
*
*	!! Make sure that you add the language parameter on the pages that need a doctype !!
*	Example: $html = new HTML5('nl-BE'); => &lt;doctype html&gt;&lt;html lang="nl-BE"&gt;
*
*	When you use "new HTML5" or "new HTML5()" no doctype is added.
*	This is done, so you can extend the class to other classes.
*
* @author		Rudy Mas <rudy.mas@rudymas.be>
* @copyright	Copyright (c) 2014 - 2016, rudymas.be. (http://www.rudymas.be/)
* @license		https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
* @version		0.5.3
* @since		2016-04-18
*/
class HTML5
{
	private $destruct;
	
	/**
	* Opening a new HTML5 page
	*/
	public function __construct($lang = NULL)
	{
		if ($lang !== NULL)
		{
			$this->destruct = TRUE;
			$output = sprintf('<!doctype html><html lang="%s">', $lang);
			echo $output;
		}
		else
		{
			$this->destruct = FALSE;
		}
	}
	
	/**
	* Closing the HTML5 page
	*/
	public function __destruct()
	{
		if ($this->destruct === TRUE)
		{
			$output = '</html>';
			echo $output;
		}
	}

	/**
	* Creating the head section of the page
	*/
	public function head($action, $title = 'Titel Website', $author = 'Rudy Mas', $email = 'rudy.mas@rudymas.be', $keywords = 'rudy, mas', $desciption = 'Korte omschrijving website', $charset = 'utf-8')
	{
		switch (strtolower($action))
		{
			case 'open':
			case 'start':
			case 'begin':
				$output = '<head>';
				$output.= $this->meta('charset', $charset);
				$output.= $this->meta('http-equiv', 'X-UA-Compatible', 'IE=Edge');
				$output.= $this->meta('name', 'robots', 'index,follow');
				$output.= $this->meta('name', 'keywords', $keywords);
				$output.= $this->meta('name', 'description', $desciption);
				$output.= $this->meta('name', 'generator', 'PHP code');
				$output.= $this->meta('name', 'author', $author);
				$output.= $this->meta('name', 'contact', $email);
				$output.= $this->meta('name', 'rating', 'General');
				$output.= $this->meta('name', 'revisit-after', '7 days');
				$output.= $this->meta('name', 'viewport', 'width=device-width, initial-scale=1');
				$output.= sprintf('<title>%s</title>', $title);
				break;
			case 'close':
			case 'stop':
			case 'end':
				$output = '</head>';
				break;
			case 'full':
			case '!help!':
			default:
				$errorMsg = 'class.HTML5->head($action, $title, $author, $email, $keywords, $description, $charset)<br>';
				$errorMsg.= '<ul><b>$action:</b>';
				$errorMsg.= '<li><i>open/start/begin</i>: Sets &lt;head&gt; and all the other &lt;meta&gt;-tags. When you use this, you need to set all the other fields as well, or the default settings will be used.</li>';
				$errorMsg.= '<li><i>close/stop/end</i>: Sets &lt;/head&gt;</li>';
				$errorMsg.= '<li><i>full</i>: <b>Is not supported for this HTML-element</b></li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$title:</b>';
				$errorMsg.= '<li>The title that will be used for the website/page.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$author:</b>';
				$errorMsg.= '<li>Your name or the name(s) of the person(s) who worked on the website.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$email:</b>';
				$errorMsg.= '<li>The email address where people can contact someone about the website.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$keywords:</b>';
				$errorMsg.= '<li>The keywords that will be used by search engines to locate your website.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$description:</b>';
				$errorMsg.= '<li>A short discription that explains what your website is all about.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$charset:</b>';
				$errorMsg.= '<li>The character set that will be used for the website. (default = utf-8)</li>';
				$errorMsg.= ('</ul>');
				die($errorMsg);
		}
		return $output;
	}
	
	/**
	* Adding a CSS-file
	*/
	public function addCSS($file, $media = 'all')
	{
		$output = $this->link($file, '', '', sprintf('media="%s"', $media));
		return $output;
	}
	
	/**
	* Adding a script-file
	*/
	public function addScript($file, $type = 'text/javascript')
	{
		$output = $this->script('full', $file, $type);
		return $output;
	}

	/**
	* Instantly run JavaScript
	*/
	public function runJavascript($action)
	{
		$output = $this->script('full', '', '', '', $action);
		return $output;
	}
	
	/**
	* Adding a check for older versions of Internet Explorer (Add this just after opening the body of the page)
	*/
	public function checkHTML5($lang = 'nl')
	{
		$output = '<!--[if lt IE 9]>';
		$output.= '<script type="text/javascript">document.createElement(\'header\');document.createElement(\'footer\');document.createElement(\'section\');document.createElement(\'aside\');document.createElement(\'nav\');document.createElement(\'article\');</script>';
		$output.= '<style>header, footer, section, aside, nav, article { display: block; }</style>';
		if(!isset($_COOKIE['warningBox']))
		{
			$output.= '<script type="text/javascript">function centerBox(boxCenter) { var box = document.getElementById(boxCenter); var availHeight = document.documentElement.clientHeight; var availWidth = document.documentElement.clientWidth; var boxHeight = box.clientHeight; var boxWidth = box.clientWidth; box.style.position = \'absolute\'; box.style.top = ((availHeight - boxHeight) / 2) + \'px\'; box.style.left = ((availWidth - boxWidth) / 2) + \'px\'; }
							function hideBox(elementHide) { var verbergen = document.getElementById(elementHide); verbergen.style.display = \'none\'; var theCookie = "warningBox=true"; document.cookie = theCookie; } </script>';
			$output.= '<style>';
			$output.= '#browser { position:absolute; width:350px; border:2px solid rgb(0,0,0); background-color:rgb(255,255,204); color:rgb(0,0,0); z-index:100; font-size:16px; }';
			$output.= '#browser div.warningTop { background-color:rgb(255,0,0); overflow:hidden; }';
			$output.= '#browser div.warningTop p.floatLeft { float:left; font-weight:bold; padding:1px 0px 1px 10px; font-size:18px; }';
			$output.= '#browser div.warningTop p.floatRight { float:right; border:1px solid rgb(153,153,153); background-color:rgb(255,102,102); }';
			$output.= '#browser div.warningTop p.floatRight a { text-decoration:none; color:rgb(255,255,255); padding:0px 20px; text-align:center; font-family:Arial, Helvetica, sans-serif; }';
			$output.= '#browser div.warningTop p.floatRight a:hover { background-color:rgb(255,204,204); color:rgb(0,0,0); }';
			$output.= '#browser div.warningMessage { clear:both; text-align:justify; padding:5px; border-top:1px solid rgb(0,0,0); }';
			$output.= '#browser div.warningMessage p.centreren { text-align:center; }';
			$output.= '</style>';
			$output.= '<div id="prompt"></div>';
			$output.= '<div id="browser">';
			if ($lang == 'en')
			{
				$output.= '<div class="warningTop"><p class="floatLeft">BROWSER WARNING</p><p class="floatRight"><a title="Close Window" href="javascript:void(0)" onClick="hideBox(\'browser\')">X</a></p></div>';
				$output.= '<div class="warningMessage"><p>This website was created with HTML5 and CSS3. To avoid problems, you can use Internet Explorer 9+ or the latest version of Firefox or Google Chrome.</p></div>';
			}
			else
			{
				$output.= '<div class="warningTop"><p class="floatLeft">BROWSER WAARSCHUWING</p><p class="floatRight"><a title="Sluit Venster" href="javascript:void(0)" onClick="hideBox(\'browser\')">X</a></p></div>';
				$output.= '<div class="warningMessage"><p>Deze website is gemaakt met HTML5 en CSS3. Om geen problemen te ondervinden kun je het beste Internet Explorer 9+ gebruiken of de laatste nieuwe versie van FireFox of Google Chroome.</p></div>';
			}
			$output.= '</div>';
			$output.= '<script type="text/javascript">centerBox(\'browser\');</script>';
		}
		$output.= '<![endif]-->';
		return $output;
	}

	/**
	* Opening and closing the body of the page
	*/
	public function body($action, $attributes = '', $input = '')
	{
		return $this->newTag('body', $action, $attributes, $input);
	}
	
	/**
	* Use this to add an a-tag
	*/
	public function a($action, $url = '', $attributes = '', $input = '')
	{
		switch (strtolower($action))
		{
			case 'open':
			case 'start':
			case 'begin':
				$output = '<a';
				if ($url != '') $output.= sprintf(' href="%s"', $url);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
				break;
			case 'close':
			case 'stop':
			case 'end':
				$output = '</a>';
				break;
			case 'full':
				$output = '<a';
				if ($url != '') $output.= sprintf(' href="%s"', $url);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= sprintf('>%s</a>', $input);
				break;
			case '!help!':
			default:
				$errorMsg = 'class.HTML5->a($action, $url, $attributes, $input)<br>';
				$errorMsg.= '<ul><b>$action:</b>';
				$errorMsg.= '<li><i>open/start/begin</i>: add &lt;a href="URL"&gt;.<br>';
				$errorMsg.= '<b>!</b> Don\'t forget to set $url and if needed $attributes <b>!</b></li>';
				$errorMsg.= '<li><i>close/stop/end</i>: add &lt;/a&gt;</li>';
				$errorMsg.= '<li><i>full</i>: add &lt;a href="URL"&gt;[All the HTML + text that you want to display]&lt;/a&gt;</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$url:</b> [Optional]';
				$errorMsg.= '<li>The url of the hyperlink.</li>';
				$errorMsg.= '</ul>';
				$errorMsg.= $this->w3schoolsAttr('a');
				$errorMsg.= '<ul><b>$input:</b> [Optional]';
				$errorMsg.= '<li>The text that you want to display. (Can contain anything you want.)<br>';
				$errorMsg.= 'If you don\'t have attributes to add, you need to use it as followed:<br>';
				$errorMsg.= "className->a('full', 'http://www.rudymas.be/', '', 'Add the stuff that you want to display.')<br>";
				$errorMsg.= '<b>Or even better:</b><br>';
				$errorMsg.= "\$string = 'Your fully prepared string, with HTML and all';<br>";
				$errorMsg.= "className->a('full', 'http://www.rudymas.be/', '', \$string)<br></li>";
				$errorMsg.= ('</ul>');
				die($errorMsg);
		}
		return $output;
	}
	
	/**
	* Use this to add an abbr-tag
	*/
	public function abbr($abbrevation, $explenation = 'Missing explenation', $attributes = '')
	{
		switch (strtolower($abbrevation))
		{
			case '!help!':
				$errorMsg = 'class.HTML5->abbr($abbrevation, $explenation, $attributes)<br>';
				$errorMsg.= '<ul><b>$abbrevation:</b>';
				$errorMsg.= '<li>The abbrevation or acronum to display.</li>';
				$errorMsg.= '</ul>';
				$errorMsg.= '<ul><b>$explenation:</b>';
				$errorMsg.= '<li>The full text of what the abbrevation stands for.</li>';
				$errorMsg.= '</ul>';
				$errorMsg.= $this->w3schoolsAttr('abbr');
				die($errorMsg);
				break;
			default:
				$output = sprintf('<abbr title="%s"', $explenation);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= sprintf('>%s</abbr>', $abbrevation);
		}
		return $output;
	}
	
	/**
	* Use this to add an address-tag
	*/
	public function address($action, $attributes = '', $input = '')
	{
		return $this->newTag('address', $action, $attributes, $input);
	}
	
	/**
	* Use this to add an area-tag
	*/
	public function area($shape, $coords = NULL, $url = NULL, $alt = NULL, $attributes = '')
	{
		if ($shape == '!help!' OR $shape == NULL OR $coords == NULL OR $url == NULL OR $alt == NULL)
		{
			$errorMsg = 'class.HTML5->area($shape, $coords, $url, $alt, $attributes)<br>';
			$errorMsg.= '<b>! This has to be used inside a &lt;map&gt;-tag which is linked to an image !</b>';
			$errorMsg.= '<ul><b>$shape:</b>';
			$errorMsg.= '<li>The shape of the area that can be clicked on. (rect|circle|poly)</li>';
			$errorMsg.= '</ul>';
			$errorMsg.= '<ul><b>$coords:</b>';
			$errorMsg.= '<li>The coordinates of the area than can be clicked on.</li>';
			$errorMsg.= '</ul>';
			$errorMsg.= '<ul><b>$url:</b>';
			$errorMsg.= '<li>The url of the page you want to link to.</li>';
			$errorMsg.= '</ul>';
			$errorMsg.= '<ul><b>$alt:</b>';
			$errorMsg.= '<li>The alternate text for the link.</li>';
			$errorMsg.= '</ul>';
			$errorMsg.= $this->w3schoolsAttr('area');
			die($errorMsg);
		}
		else
		{
			$output = sprintf('<area shape="%s" coords="%s" href="%s" alt="%s"', $shape, $coords, $url, $alt);
			if ($attributes != '') $output.= sprintf(' %s', $attributes);
			$output.= '>';
			return $output;
		}
	}
	
	/**
	* Use this to add an article-tag
	*/
	public function article($action, $attributes = '', $input = '')
	{
		return $this->newTag('article', $action, $attributes, $input);
	}
	
	/**
	* Use this to add an aside-tag
	*/
	public function aside($action, $attributes = '', $input = '')
	{
		return $this->newTag('aside', $action, $attributes, $input);
	}
	
	/**
	* Use this to add an audio-tag
	*/
	public function audio($input, $audioSource = NULL, $attributes = 'controls')
	{
		switch (strtolower($input))
		{
			case '!help!':
				$errorMsg = 'class.HTML5->audio($input, $audioSource, $attributes)<br>';
				$errorMsg.= '<ul><b>$input:</b>';
				$errorMsg.= '<li>The text to display when the browser doesn\'t support audio.</li>';
				$errorMsg.= '</ul>';
				$errorMsg.= '<ul><b>$audioSource:</b>';
				$errorMsg.= '<li>All the audio sources to be used. (Look at the example how to use this.)</li>';
				$errorMsg.= '</ul>';
				$errorMsg.= $this->w3schoolsAttr('audio');
				$errorMsg.= 'How to use this in your code?<br>';
				$errorMsg.= '<i>// First you have to create the source to be used, you do this by making an array.</i><br>';
				$errorMsg.= '$source = array();<br>';
				$errorMsg.= "\$source['audio/mpeg'] = 'your_audio_file.mp3';<br>";
				$errorMsg.= "\$source['audio/ogg'] = 'your_audio_file.ogg';<br>";
				$errorMsg.= "\$source['audio/wav'] = 'your_audio_file.wav';<br>";
				$errorMsg.= '<i>// Then you call the function.</i><br>';
				$errorMsg.= 'echo $html->audio(\'Your browser doesn\'t support the audio tag.\', $source, \'controls autoplay\');<br>';
				$errorMsg.= '<br>';
				$errorMsg.= 'At the moment, only .mp3, .ogg and .wav are supported. You can add different sources and the browser will use the one it supports.';
				die($errorMsg);
			default:
				$output = '<audio';
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
				foreach ($audioSource as $type => $source)
				{
					$output.= sprintf('<source src="%s" type="%s">', $source, $type);
				}
				$output.= $input;
				$output.= '</audio>';
		}
		return $output;
	}
	
	/**
	* Use this to add a b-tag
	*/
	public function b($action, $attributes = '', $input = '')
	{
		return $this->newTag('b', $action, $attributes, $input);
	}
	
	/**
	* Use this to add a base-tag
	*/
	public function base($url, $target = '_self')
	{
		switch (strtolower($url))
		{
			case '!help!':
				$errorMsg = 'class.HTML5->base($url, $target)<br>';
				$errorMsg.= '<ul><b>$url:</b>';
				$errorMsg.= '<li>The URL that will be used as the base URL.</li>';
				$errorMsg.= '</ul>';
				$errorMsg.= '<ul><b>$target:</b>';
				$errorMsg.= '<li>The default way how to upon a link.</li>';
				$errorMsg.= '</ul>';
				$errorMsg.= 'More information can be found at '. $this->a('full', 'http://www.w3schools.com/tags/tag_base.asp', '', 'w3schools link');
				die($errorMsg);
			default:
				$output = sprintf('<base href="%s" target="%s">', $url, $target);
		}
		return $output;
	}
	
	/**
	* Use this to add a bdi-tag
	*/
	public function bdi($action, $attributes = '', $input = '')
	{
		return $this->newTag('bdi', $action, $attributes, $input);
	}
	
	/**
	* Use this to add a bdo-tag
	*/
	public function bdo($action, $dir = 'ltr', $attributes = '', $input = '')
	{
		if ( ! ($dir == 'ltr' OR $dir == 'rtl')) $dir = 'ltr';
		switch (strtolower($action))
		{
			case 'open':
			case 'start':
			case 'begin':
				$output = sprintf('<bdo dir="%s"', $dir);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
				break;
			case 'close':
			case 'stop':
			case 'end':
				$output = '</bdo>';
				break;
			case 'full':
				$output = sprintf('<bdo dir="%s"', $dir);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= sprintf('>%s</bdo>', $input);
				break;
			case '!help!':
			default:
				$errorMsg = 'class.HTML5->bdo($action, $dir, $attributes, $input)<br>';
				$errorMsg.= '<ul><b>$action:</b>';
				$errorMsg.= '<li><i>open/start/begin</i>: add &lt;bdo dir="ltr|rtl"&gt;. (With optional attributes settings)</li>';
				$errorMsg.= '<li><i>close/stop/end</i>: add &lt;/bdo&gt;</li>';
				$errorMsg.= '<li><i>full</i>: add &lt;bdo dir="ltr|rtl"&gt;[All the HTML and text you want to display]&lt;/bdo&gt;</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$dir:</b> [Optional]';
				$errorMsg.= '<li>The direction the text has to go. (ltr: left to right / rtl: right to left)</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('bdo');
				$errorMsg.= '<ul><b>$input:</b> [Optional]';
				$errorMsg.= '<li>The text that you want to display. (Can contain HTML-tags)<br>';
				$errorMsg.= 'If you don\'t have attributes to add, you need to use it as followed:<br>';
				$errorMsg.= "className->bdo('full', 'rtl', '', 'Add the text that you want to display.')<br>";
				$errorMsg.= '<b>Or even better:</b><br>';
				$errorMsg.= "\$string = 'Your fully prepared string, with HTML and all';<br>";
				$errorMsg.= "className->bdo('full', 'rtl', '', \$string)<br></li>";
				$errorMsg.= ('</ul>');
				die($errorMsg);
		}
		return $output;
	}
	
	/**
	* Use this to add a blockquote-tag
	*/
	public function blockquote($action, $cite = 'http://www.rudymas.be/', $attributes = '', $input = '')
	{
		switch (strtolower($action))
		{
			case 'open':
			case 'start':
			case 'begin':
				$output = sprintf('<blockquote cite="%s"', $cite);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
				break;
			case 'close':
			case 'stop':
			case 'end':
				$output = '</blockquote>';
				break;
			case 'full':
				$output = sprintf('<blockquote cite="%s"', $cite);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= sprintf('>%s</blockquote>', $input);
				break;
			case '!help!':
			default:
				$errorMsg = 'class.HTML5->blockquote($action, $cite, $attributes, $input)<br>';
				$errorMsg.= '<ul><b>$action:</b>';
				$errorMsg.= '<li><i>open/start/begin</i>: add &lt;blockquote cite="[URL]"&gt;. (With optional attributes settings)</li>';
				$errorMsg.= '<li><i>close/stop/end</i>: add &lt;/blockquote&gt;</li>';
				$errorMsg.= '<li><i>full</i>: add &lt;blockquote cite="[URL]"&gt;[All the HTML and text you want to display]&lt;/blockquote&gt;</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$cite:</b>';
				$errorMsg.= '<li>The URL of the site where your quote is from.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('blockquote');
				$errorMsg.= '<ul><b>$input:</b> [Optional]';
				$errorMsg.= '<li>The text that you want to display. (Can contain HTML-tags)<br>';
				$errorMsg.= 'If you don\'t have attributes to add, you need to use it as followed:<br>';
				$errorMsg.= "className->blockquote('full', 'http://www.site.be/', '', 'Add the text that you want to display.')<br>";
				$errorMsg.= '<b>Or even better:</b><br>';
				$errorMsg.= "\$string = 'Your fully prepared string, with HTML and all';<br>";
				$errorMsg.= "className->blockquote('full', 'http://www.site.be/', '', \$string)<br></li>";
				$errorMsg.= ('</ul>');
				die($errorMsg);
		}
		return $output;
	}

	/**
	* Use this to add a br-tag
	*/
	public function br($attributes = '')
	{
		switch (strtolower($attributes))
		{
			case '!help!':
				$errorMsg = 'class.HTML5->br($attributes)<br>';
				$errorMsg.= $this->w3schoolsAttr('br');
				die($errorMsg);
			default:
				$output = '<br';
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
		}
		return $output;
	}

	/**
	* Use this to add a button-tag
	*/
	public function button($action, $attributes = 'type="button"', $input = '')
	{
		if ($attributes == '') $attributes = 'type="button"';
		return $this->newTag('button', $action, $attributes, $input);
	}

	/**
	* Use this to add a canvas-tag
	*/
	public function canvas($action, $attributes = 'id="myCanvas"', $input = '')
	{
		if ($attributes == '') $attributes = 'id="myCanvas"';
		return $this->newTag('canvas', $action, $attributes, $input);
	}

	/**
	* Use this to add a caption-tag
	*/
	public function caption($action, $attributes = '', $input = '')
	{
		return $this->newTag('caption', $action, $attributes, $input);
	}

	/**
	* Use this to add a cite-tag
	*/
	public function cite($action, $attributes = '', $input = '')
	{
		return $this->newTag('cite', $action, $attributes, $input);
	}

	/**
	* Use this to add a code-tag
	*/
	public function code($action, $attributes = '', $input = '')
	{
		return $this->newTag('code', $action, $attributes, $input);
	}

	/**
	* Use this to add a col-tag
	*/
	public function col($attributes)
	{
		switch (strtolower($attributes))
		{
			case '!help!':
				$errorMsg = 'class.HTML5->col($attributes)<br>';
				$errorMsg.= $this->w3schoolsAttr('col', FALSE);
				die($errorMsg);
			default:
				$output = '<col';
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
		}
		return $output;
	}

	/**
	* Use this to add a colgroup-tag
	*/
	public function colgroup($action, $attributes = '', $input = '')
	{
		return $this->newTag('colgroup', $action, $attributes, $input);
	}
	
	/**
	* Use this to add a datalist-tag (included options)
	*/
	public function datalist($id, $options = NULL, $attributes = '')
	{
		switch (strtolower($id))
		{
			case '!help!':
				$errorMsg = 'class.HTML5->datalist($id, $options, $attributes)<br>';
				$errorMsg.= '<ul><b>$id:</b>';
				$errorMsg.= '<li>The $id points to an input-tags list reference.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$options:</b>';
				$errorMsg.= '<li>This is an array which holds all the options to be used.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('datalist');
				$errorMsg.= 'How to use this in your code?<br>';
				$errorMsg.= '<i>// First you have to create a list of options, you do this by making an array.</i><br>';
				$errorMsg.= '$list = array();<br>';
				$errorMsg.= "\$list[] = 'Option 1';<br>";
				$errorMsg.= "\$list[] = 'Option 2';<br>";
				$errorMsg.= "\$list[] = 'Option 3';<br>";
				$errorMsg.= '<i>// Then you call the function.</i><br>';
				$errorMsg.= 'echo $html->datalist(\'testList\', $list, \'[attributes are optional]\');<br>';
				die($errorMsg);
			default:
				$output = sprintf('<datalist id="%s"', $id);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
				foreach ($options as $option)
				{
					$output.= sprintf('<option value="%s">', $option);
				}				
				$output.= '</datalist>';
		}
		return $output;
	}
	
	/**
	* Use this to add a dl-tag
	*/
	public function dl($action, $attributes = '', $input = '')
	{
		return $this->newTag('dl', $action, $attributes, $input);
	}

	/**
	* Use this to add a dt-tag
	*/
	public function dt($action, $attributes = '', $input = '')
	{
		return $this->newTag('dt', $action, $attributes, $input);
	}

	/**
	* Use this to add a dd-tag
	*/
	public function dd($action, $attributes = '', $input = '')
	{
		return $this->newTag('dd', $action, $attributes, $input);
	}

	/**
	* Use this to add a del-tag
	*/
	public function del($action, $attributes = '', $input = '')
	{
		return $this->newTag('del', $action, $attributes, $input);
	}
	
	/**
	* Use this to add a details-tag
	*/
	public function details($action, $attributes = '', $input = '')
	{
		return $this->newTag('details', $action, $attributes, $input);
	}

	/**
	* Use this to add a dfn-tag
	*/
	public function dfn($action, $attributes = '', $input = '')
	{
		return $this->newTag('dfn', $action, $attributes, $input);
	}

	/**
	* Use this to add a dialog-tag
	*/
	public function dialog($action, $attributes = 'open', $input = '')
	{
		return $this->newTag('dialog', $action, $attributes, $input);
	}

	/**
	* Use this to add a div-tag
	*/
	public function div($action, $attributes = '', $input = '')
	{
		return $this->newTag('div', $action, $attributes, $input);
	}

	/**
	* Use this to add a em-tag
	*/
	public function em($action, $attributes = '', $input = '')
	{
		return $this->newTag('em', $action, $attributes, $input);
	}

	/**
	* Use this to add a embed-tag
	*/
	public function embed($src, $type = '', $width = 0, $height = 0, $attributes = '')
	{
		switch (strtolower($src))
		{
			case '!help!':
				$errorMsg = 'class.HTML5->embed($src, $type, $width, $height, $attributes)<br>';
				$errorMsg.= '<ul><b>$src:</b>';
				$errorMsg.= '<li>Specifies the address of the external file to embed.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$type:</b>';
				$errorMsg.= '<li>Specifies the media type of the embedded content.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$width:</b>';
				$errorMsg.= '<li>Specifies the width of the embedded content.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$height:</b>';
				$errorMsg.= '<li>Specifies the height of the embedded content.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('embed');
				$errorMsg.= 'Extra information about the media types can be found '.$this->a('full', 'http://www.iana.org/assignments/media-types/media-types.xhtml', 'target="_blank"', '>here<').'.';
				die($errorMsg);
			default:
				$output = sprintf('<embed src="%s"', $src);
				if ($type != '') $output.= sprintf(' type="%s"', $type);
				if ($width != 0) $output.= sprintf(' width=%d', $width);
				if ($height != 0) $output.= sprintf(' height=%d', $height);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
		}
		return $output;
	}

	/**
	* Use this to add a fieldset-tag
	*/
	public function fieldset($action, $attributes = '', $input = '')
	{
		return $this->newTag('fieldset', $action, $attributes, $input);
	}

	/**
	* Use this to add a figcaption-tag
	*/
	public function figcaption($action, $attributes = '', $input = '')
	{
		return $this->newTag('figcaption', $action, $attributes, $input);
	}

	/**
	* Use this to add a figure-tag
	*/
	public function figure($action, $attributes = '', $input = '')
	{
		return $this->newTag('figure', $action, $attributes, $input);
	}

	/**
	* Use this to add a footer-tag
	*/
	public function footer($action, $attributes = '', $input = '')
	{
		return $this->newTag('footer', $action, $attributes, $input);
	}
	
	/**
	* Use this to add a form-tag
	*/
	public function form($action, $sendTo = '', $method = '', $attributes = '', $input = '')
	{
		switch (strtolower($action))
		{
			case 'open':
			case 'start':
			case 'begin':
				$output = sprintf('<form action="%s"', $sendTo);
				if ($method != '') $output.= sprintf(' method="%s"', $method);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
				break;
			case 'close':
			case 'stop':
			case 'end':
				$output = '</form>';
				break;
			case 'full':
				$output = sprintf('<form action="%s"', $sendTo);
				if ($method != '') $output.= sprintf(' method="%s"', $method);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= sprintf('>%s</form>', $input);
				break;
			case '!help!':
			default:
				$errorMsg = 'class.HTML5->form($action, $sendTo, $method, $attributes, $input)<br>';
				$errorMsg.= '<ul><b>$action:</b>';
				$errorMsg.= '<li><i>open/start/begin</i>: add &lt;form action="[Link to Script]" method="[get|post]"&gt;. (With optional attributes settings)</li>';
				$errorMsg.= '<li><i>close/stop/end</i>: add &lt;/form&gt;</li>';
				$errorMsg.= '<li><i>full</i>: add &lt;form action="[Link to Script]" method="[get|post]"&gt;[All the HTML and text you want to display]&lt;/form&gt;</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$sendTo:</b>';
				$errorMsg.= '<li>Specifies where to send the form-data when a form is submitted.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$method:</b>';
				$errorMsg.= '<li>Specifies the HTTP method to use when sending form-data. (get or post)</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('form');
				$errorMsg.= '<ul><b>$input:</b> [Optional]';
				$errorMsg.= '<li>All the input-tags and extra information you want to show.<br>';
				$errorMsg.= 'If you don\'t have attributes to add, you need to use it as followed:<br>';
				$errorMsg.= "\$input = [Put all the information for the form in a string]<br>";
				$errorMsg.= "className->form('full', 'verwerk.php', 'post', '', \$input)<br></li>";
				$errorMsg.= ('</ul>');
				die($errorMsg);
		}
		return $output;
	}

	/**
	* Use this to add a h1-tag
	*/
	public function h1($action, $attributes = '', $input = '')
	{
		return $this->newTag('h1', $action, $attributes, $input);
	}

	/**
	* Use this to add a h2-tag
	*/
	public function h2($action, $attributes = '', $input = '')
	{
		return $this->newTag('h2', $action, $attributes, $input);
	}

	/**
	* Use this to add a h3-tag
	*/
	public function h3($action, $attributes = '', $input = '')
	{
		return $this->newTag('h3', $action, $attributes, $input);
	}

	/**
	* Use this to add a h4-tag
	*/
	public function h4($action, $attributes = '', $input = '')
	{
		return $this->newTag('h4', $action, $attributes, $input);
	}

	/**
	* Use this to add a h5-tag
	*/
	public function h5($action, $attributes = '', $input = '')
	{
		return $this->newTag('h5', $action, $attributes, $input);
	}

	/**
	* Use this to add a h6-tag
	*/
	public function h6($action, $attributes = '', $input = '')
	{
		return $this->newTag('h6', $action, $attributes, $input);
	}

	/**
	* Use this to add a header-tag
	*/
	public function header($action, $attributes = '', $input = '')
	{
		return $this->newTag('header', $action, $attributes, $input);
	}

	/**
	* Use this to add a hr-tag
	*/
	public function hr($attributes = '')
	{
		switch (strtolower($attributes))
		{
			case '!help!':
				$errorMsg = 'class.HTML5->hr($attributes)<br>';
				$errorMsg.= $this->w3schoolsAttr('col');
				die($errorMsg);
			default:
				$output = '<hr';
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
		}
		return $output;
	}

	/**
	* Use this to add a i-tag
	*/
	public function i($action, $attributes = '', $input = '')
	{
		return $this->newTag('i', $action, $attributes, $input);
	}

	/**
	* Use this to add a iframe-tag
	*/
	public function iframe($src, $attributes = '', $input = '')
	{
		switch (strtolower($src))
		{
			case '!help!':
				$errorMsg = 'class.HTML5->iframe($src, $attributes, $input)<br>';
				$errorMsg.= '<ul><b>$src:</b>';
				$errorMsg.= '<li>Specifies the address of the document to embed in the &lt;iframe&gt;.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('iframe');
				$errorMsg.= '<ul><b>$input:</b> [Optional]';
				$errorMsg.= '<li>To deal with browsers that do not support &lt;iframe&gt;, add a text here.</li>';
				$errorMsg.= ('</ul>');
				die($errorMsg);
			default:
				$output = sprintf('<iframe src="%s"', $src);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= sprintf('>%s</iframe>', $input);
		}
		return $output;
	}

	/**
	* Use this to add a img-tag
	*/
	public function img($src, $alt = 'no alternate text', $width = 0, $height = 0, $attributes = '')
	{
		switch (strtolower($src))
		{
			case '!help!':
				$errorMsg = 'class.HTML5->img($src, $alt, $width, $height, $attributes)<br>';
				$errorMsg.= '<ul><b>$src:</b>';
				$errorMsg.= '<li>Specifies the URL of an image.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$alt:</b>';
				$errorMsg.= '<li>Specifies an alternate text for an image.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$width:</b> [Optional]';
				$errorMsg.= '<li>Specifies the width of an image.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$height:</b> [Optional]';
				$errorMsg.= '<li>Specifies the height of an image.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('img');
				die($errorMsg);
			default:
				$output = sprintf('<img src="%s" alt="%s"', $src, $alt);
				if ($width != 0) $output.= sprintf(' width=%d', $width);
				if ($height != 0) $output.= sprintf(' height=%d', $height);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
		}
		return $output;
	}

	/**
	* Use this to add an input-tag
	*/
	public function input($type, $name = '', $value = '', $attributes = '')
	{
		switch (strtolower($type))
		{
			case '!help!':
				$errorMsg = 'class.HTML5->input($type, $name, $value, $attributes)<br>';
				$errorMsg.= '<ul><b>$type:</b>';
				$errorMsg.= '<li>Specifies the type &lt;input&gt; element to display.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$name:</b>';
				$errorMsg.= '<li>Specifies the name of an &lt;input&gt; element.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$value:</b> [Optional]';
				$errorMsg.= '<li>Specifies the value of an &lt;input&gt; element.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('input');
				die($errorMsg);
			default:
				$output = sprintf('<input type="%s"', $type);
				if ($name != '') $output.= sprintf(' name="%s"', $name);
				if ($value != '') $output.= sprintf(' value="%s"', $value);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
		}
		return $output;
	}
	
	/**
	* Use this to add an input-tag for checkbox
	*/
	public function inputCheckbox($name = '', $value = '', $attributes = '', $text = '')
	{
		switch (strtolower($name))
		{
			case '!help!':
				$errorMsg = 'class.HTML5->input($name, $value, $attributes, $text)<br>';
				$errorMsg.= '<ul><b>$type:</b>';
				$errorMsg.= '<li>Specifies the type &lt;input type="checkbox"&gt; element to display.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$name:</b>';
				$errorMsg.= '<li>Specifies the name of an &lt;input type="checkbox"&gt; element.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$value:</b> [Optional]';
				$errorMsg.= '<li>Specifies the value of an &lt;input type="checkbox"&gt; element.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('input_checked');
				$errorMsg.= '<ul><b>$text:</b> [Optional]';
				$errorMsg.= '<li>Specifies the to add before &lt;input type="checkbox"&gt; element.</li>';
				$errorMsg.= ('</ul>');
				die($errorMsg);
			default:
				$output = '<input type="checkbox"';
				if ($name != '') $output.= sprintf(' name="%s"', $name);
				if ($value != '') $output.= sprintf(' value="%s"', $value);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= sprintf('> <label for="%s"%s</label>', $text, $id);
		}
		return $output;		
	}

	/**
	* Use this to add a ins-tag
	*/
	public function ins($action, $attributes = '', $input = '')
	{
		return $this->newTag('ins', $action, $attributes, $input);
	}

	/**
	* Use this to add a kbd-tag
	*/
	public function kbd($action, $attributes = '', $input = '')
	{
		return $this->newTag('kbd', $action, $attributes, $input);
	}

	/**
	* Use this to add a keygen-tag
	*/
	public function keygen($name, $attributes = '')
	{
		switch (strtolower($name))
		{
			case '!help!':
				$errorMsg = 'class.HTML5->keygen($name, $attributes)<br>';
				$errorMsg.= '<ul><b>$name:</b>';
				$errorMsg.= '<li>Defines a name for the &lt;keygen&gt; element.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('keygen');
				die($errorMsg);
			default:
				$output = sprintf('<keygen name="%s"', $name);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
		}
		return $output;
	}

	/**
	* Use this to add a label-tag
	*/
	public function label($for, $attributes = '', $input = '')
	{
		switch (strtolower($for))
		{
			case '!help!':
				$errorMsg = 'class.HTML5->label($for, $attributes, $input)<br>';
				$errorMsg.= '<ul><b>$for:</b>';
				$errorMsg.= '<li>Specifies which form element a label is bound to. (It\'s linked to the id of the input-tag.)</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('label');
				$errorMsg.= '<ul><b>$input:</b> [Optional]';
				$errorMsg.= '<li>The text that is displayed for the label.</li>';
				$errorMsg.= ('</ul>');
				die($errorMsg);
			default:
				$output = sprintf('<label for="%s"', $for);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= sprintf('>%s</label>', $input);
		}
		return $output;
	}

	/**
	* Use this to add a legend-tag
	*/
	public function legend($action, $attributes = '', $input = '')
	{
		return $this->newTag('legend', $action, $attributes, $input);
	}

	/**
	* Use this to add a li-tag
	*/
	public function li($action, $attributes = '', $input = '')
	{
		return $this->newTag('li', $action, $attributes, $input);
	}

	/**
	* Use this to add a link-tag
	*/
	public function link($href, $rel = 'stylesheet', $type = 'text/css', $attributes = '')
	{
		if ($rel == '') $rel = 'stylesheet';
		if ($type == '') $type = 'text/css';
		switch (strtolower($href))
		{
			case '!help!':
				$errorMsg = 'class.HTML5->link($href, $rel, $type, $attributes)<br>';
				$errorMsg.= '<ul><b>$href:</b>';
				$errorMsg.= '<li>Specifies the location of the linked document.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$rel:</b>';
				$errorMsg.= '<li>Required. Specifies the relationship between the current document and the linked document.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$type:</b>';
				$errorMsg.= '<li>Specifies the media type of the linked document.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('link');
				die($errorMsg);
			default:
				$output = sprintf('<link href="%s" rel="%s" type="%s"', $href, $rel, $type);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
		}
		return $output;
	}

	/**
	* Use this to add a main-tag
	*/
	public function main($action, $attributes = '', $input = '')
	{
		return $this->newTag('main', $action, $attributes, $input);
	}

	/**
	* use this to add a map-tag
	*/
	public function map($action, $name = '', $attributes = '', $input = '')
	{
		switch (strtolower($action))
		{
			case 'open':
			case 'start':
			case 'begin':
				$output = sprintf('<map name="%s"', $name);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
				break;
			case 'close':
			case 'stop':
			case 'end':
				$output = '</map>';
				break;
			case 'full':
				$output = sprintf('<map name="%s"', $name);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= sprintf('>%s</map>', $input);
				break;
			case '!help!':
			default:
				$errorMsg = 'class.HTML5->map($action, $name, $attributes, $input)<br>';
				$errorMsg.= '<ul><b>$action:</b>';
				$errorMsg.= '<li><i>open/start/begin</i>: add &lt;map name="[Link to Image]"&gt;. (With optional attributes settings)</li>';
				$errorMsg.= '<li><i>close/stop/end</i>: add &lt;/map&gt;</li>';
				$errorMsg.= '<li><i>full</i>: add &lt;map name="[Link to Image]"&gt;[The area-tags go here]&lt;/map&gt;</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$name:</b>';
				$errorMsg.= '<li>Required. Specifies the name of an image-map.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('map');
				$errorMsg.= '<ul><b>$input:</b> [Optional]';
				$errorMsg.= '<li>All the area-tags you want to add as a string.<br>';
				$errorMsg.= 'If you don\'t have attributes to add, you need to use it as followed:<br>';
				$errorMsg.= "\$string = \$htmlClass->area('rect', '0,0,20,20', 'http://www.link1.be/', 'link1');<br>";
				$errorMsg.= "\$string.= \$htmlClass->area('circle', '30,30,10', 'http://www.link2.be/', 'link2');<br>";
				$errorMsg.= "className->map('full', 'imageName', '', \$string)<br></li>";
				$errorMsg.= ('</ul>');
				die($errorMsg);
		}
		return $output;
	}

	/**
	* Use this to add a mark-tag
	*/
	public function mark($action, $attributes = '', $input = '')
	{
		return $this->newTag('mark', $action, $attributes, $input);
	}

	/**
	* Use this to add a meta-tag
	*/
	public function meta($type, $typeValue = '', $content = '', $attributes = '')
	{
		switch (strtolower($type))
		{
			case '!help!':
				$errorMsg = 'class.HTML5->meta($type, $typeValue, $content, $attributes)<br>';
				$errorMsg.= '<ul><b>$type:</b>';
				$errorMsg.= '<li>Set the type of META-tag to use:<br>';
				$errorMsg.= '<ul>';
				$errorMsg.= '<li><i>charset</i>: Specifies the character encoding for the HTML document.</li>';
				$errorMsg.= '<li><i>name</i>: Specifies a name for the metadata.</li>';
				$errorMsg.= '<li><i>http-equiv</i>: Provides an HTTP header for the information/value of the content attribute.</li>';
				$errorMsg.= '</ul></li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$typeValue:</b>';
				$errorMsg.= '<li>The value of the type you want to set.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$content:</b> [Optional]';
				$errorMsg.= '<li>Gives the value associated with the http-equiv or name attribute.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('meta');
				die($errorMsg);
			default:
				$output = sprintf('<meta %s="%s"', $type, $typeValue);
				if ($content != '') $output.= sprintf(' content="%s"', $content);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
		}
		return $output;
	}

	/**
	* Use this to add a meter-tag
	*/
	public function meter($value, $text = '', $attributes = '')
	{
		switch (strtolower($value))
		{
			case '!help!':
				$errorMsg = 'class.HTML5->meter($value, $text, $attributes)<br>';
				$errorMsg.= '<ul><b>$value:</b>';
				$errorMsg.= '<li>Required. Specifies the current value of the gauge.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$text:</b>';
				$errorMsg.= '<li>Required. The text version of the value.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('meter');
				die($errorMsg);
			default:
				$output = sprintf('<meter value="%s"', $value);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= sprintf('>%s</meter>', $text);
		}
		return $output;
	}

	/**
	* Use this to add a nav-tag
	*/
	public function nav($action, $attributes = '', $input = '')
	{
		return $this->newTag('nav', $action, $attributes, $input);
	}

	/**
	* Use this to add a noscript-tag
	*/
	public function noscript($action, $attributes = '', $input = '')
	{
		switch (strtolower($action))
		{
			case 'open':
			case 'start':
			case 'begin':
				$output = '<noscript';
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
				break;
			case 'close':
			case 'stop':
			case 'end':
				$output = '</noscript>';
				break;
			case 'waarschuwing':
				$output = '<p id="noScriptText">Om deze site optimaal te gebruiken is het noodzakelijk om Javascript aan te zetten.<br>';
				$output .= '<a href="http://www.enable-javascript.com/nl/" target="_blank">Hier vind je instructies over hoe je Javascript activeert in je web browser</a>.</p>';
				break;
			case 'warning':
				$output = '<p id="noScriptText">For full functionality of this site it is necessary to enable JavaScript.<br>';
				$output .= 'Here are the <a href="http://www.enable-javascript.com/" target="_blank"> instructions how to enable JavaScript in your web browser</a>.</p>';
				break;
			case 'alerte':
				$output = '<p id="noScriptText">Pour accéder à toutes les fonctionnalités de ce site, vous devez activer JavaScript.';
				$output .= 'Voici les <a href="http://www.enable-javascript.com/fr/" target="_blank">
 instructions pour activer JavaScript dans votre navigateur Web</a>.</p>';
				break;
			case 'full':
				$output = '<noscript';
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= sprintf('>%s</noscript>', $input);
				break;
			case '!help!':
			default:
				$errorMsg = 'class.HTML5->noscript($action, $attributes, $input)<br>';
				$errorMsg.= '<ul><b>$action:</b>';
				$errorMsg.= '<li><i>open/start/begin</i>: add &lt;noscript&gt;</li>';
				$errorMsg.= '<li><i>close/stop/end</i>: add &lt;/noscript&gt;</li>';
				$errorMsg.= '<li><i>full</i>: add &lt;noscript&gt;[Anything you want to show when the browser doesn\'t support scripting]&lt;/noscript&gt;</li>';
				$errorMsg.= '<li><i>warning</i>: English warning when JavaScript is disabled</li>';
				$errorMsg.= '<li><i>waarschuwing</i>: Dutch warning when JavaScript is disabled</li>';
				$errorMsg.= '<li><i>alerte</i>: French warning when JavaScript is disabled</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('noscript');
				$errorMsg.= '<ul><b>$input:</b> [Optional]';
				$errorMsg.= '<li>Anything you want to show when the browser doesn\'t support scripting</li>';
				$errorMsg.= ('</ul>');
				die($errorMsg);
		}
		return $output;
	}
	
	/**
	* Use this to add an object-tag
	*/
	public function object($action, $data = '', $width = 0, $height = 0, $attributes = '', $input = '')
	{
		switch (strtolower($action))
		{
			case 'open':
			case 'start':
			case 'begin':
				$output = sprintf('<object data="%s"', $data);
				if ($width != 0) $output.= sprintf(' width="%s"', $width);
				if ($height != 0) $output.= sprintf(' height="%s"', $height);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
				break;
			case 'close':
			case 'stop':
			case 'end':
				$output = '</object>';
				break;
			case 'full':
				$output = sprintf('<object data="%s"', $data);
				if ($width != 0) $output.= sprintf(' width="%s"', $width);
				if ($height != 0) $output.= sprintf(' height="%s"', $height);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= sprintf('>%s</object>', $input);
				break;
			case '!help!':
			default:
				$errorMsg = 'class.HTML5->object($action, $data, $width, $height, $attributes, $input)<br>';
				$errorMsg.= '<ul><b>$action:</b>';
				$errorMsg.= '<li><i>open/start/begin</i>: add &lt;object data="[Link to File]"&gt;. (With optional attributes settings)</li>';
				$errorMsg.= '<li><i>close/stop/end</i>: add &lt;/object&gt;</li>';
				$errorMsg.= '<li><i>full</i>: add &lt;object data="[Link to File]"&gt;[Show text when browser doesn\'t support the object]&lt;/object&gt;</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$data:</b>';
				$errorMsg.= '<li>Specifies the URL of the resource to be used by the object.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$width:</b>';
				$errorMsg.= '<li>Specifies the width of the object.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$height:</b>';
				$errorMsg.= '<li>Specifies the height of the object.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('object');
				$errorMsg.= '<ul><b>$input:</b> [Optional]';
				$errorMsg.= '<li>Show text when browser doesn\'t support the object.<br>';
				$errorMsg.= 'If you don\'t have attributes to add, you need to use it as followed:<br>';
				$errorMsg.= "className->object('full', 'file.swf', 0, 0, '', 'Your browser doesn\'t support this object.')<br>";
				$errorMsg.= '<b>Or even better:</b><br>';
				$errorMsg.= "\$input = 'Your browser doesn\'t support this object.'<br>";
				$errorMsg.= "className->object('full', 'file.swf', 0, 0, '', \$input)<br></li>";
				$errorMsg.= ('</ul>');
				die($errorMsg);
		}
		return $output;
	}

	/**
	* Use this to add an ol-tag
	*/
	public function ol($action, $attributes = '', $input = '')
	{
		return $this->newTag('ol', $action, $attributes, $input);
	}

	/**
	* Use this to add an optgroup-tag
	*/
	public function optgroup($action, $label = '', $attributes = '', $input = '')
	{
		switch (strtolower($action))
		{
			case 'open':
			case 'start':
			case 'begin':
				$output = sprintf('<optgroup label="%s"', $label);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
				break;
			case 'close':
			case 'stop':
			case 'end':
				$output = '</optgroup>';
				break;
			case 'full':
				$output = sprintf('<optgroup label="%s"', $label);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= sprintf('>%s</object>', $input);
				break;
			case '!help!':
			default:
				$errorMsg = 'class.HTML5->optgroup($action, $label, $attributes, $input)<br>';
				$errorMsg.= '<ul><b>$action:</b>';
				$errorMsg.= '<li><i>open/start/begin</i>: add &lt;optgroup label="[Label of the Group]"&gt;. (With optional attributes settings)</li>';
				$errorMsg.= '<li><i>close/stop/end</i>: add &lt;/optgroup&gt;</li>';
				$errorMsg.= '<li><i>full</i>: add &lt;optgroup label="[Label of the Group]"&gt;[Pre-prepared option list]&lt;/optgroup&gt;</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$label:</b>';
				$errorMsg.= '<li>Specifies a label for an option-group.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('optgroup');
				$errorMsg.= '<ul><b>$input:</b> [Optional]';
				$errorMsg.= '<li>You can pre-prepare a list of options and add it to the optgroup.<br>';
				$errorMsg.= "\$input = className->option('full', 'volvo', 'Volvo')<br>";
				$errorMsg.= "\$input.= className->option('full', 'honda', 'Honda', 'selected')<br>";
				$errorMsg.= "\$input.= className->option('full', 'bmw', 'BMW')<br>";
				$errorMsg.= "className->optgroup('full', 'variable', 'attributes', \$input)<br></li>";
				$errorMsg.= ('</ul>');
				die($errorMsg);
		}
		return $output;
	}

	/**
	* Use this to add an option-tag
	*/
	public function option($action, $value = '', $text = '', $attributes = '')
	{
		switch (strtolower($action))
		{
			case 'open':
			case 'start':
			case 'begin':
				$output = sprintf('<option value="%s"', $value);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
				break;
			case 'close':
			case 'stop':
			case 'end':
				$output = '</option>';
				break;
			case 'full':
				$output = sprintf('<option value="%s"', $value);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= sprintf('>%s</option>', $text);
				break;
			case '!help!':
			default:
				$errorMsg = 'class.HTML5->option($action, $value, $text, $attributes)<br>';
				$errorMsg.= '<ul><b>$action:</b>';
				$errorMsg.= '<li><i>open/start/begin</i>: add &lt;option value="[Value to Send]"&gt;. (With optional attributes settings)</li>';
				$errorMsg.= '<li><i>close/stop/end</i>: add &lt;/option&gt;</li>';
				$errorMsg.= '<li><i>full</i>: add &lt;option value="[Value to Send]"&gt;[Text to display]&lt;/option&gt;</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$value:</b>';
				$errorMsg.= '<li>Specifies the value to be send to a server.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$text:</b> [Used by action \'full\']';
				$errorMsg.= '<li>The text that will be displayed.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('option');
				die($errorMsg);
		}
		return $output;
	}

	/**
	* Use this to add an output-tag
	*/
	public function output($action, $name = '', $for = '', $text = '', $attributes = '')
	{
		switch (strtolower($action))
		{
			case 'open':
			case 'start':
			case 'begin':
				$output = sprintf('<output name="%s" for="%s"', $name, $for);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
				break;
			case 'close':
			case 'stop':
			case 'end':
				$output = '</output>';
				break;
			case 'full':
				$output = sprintf('<output name="%s" for="%s"', $name, $for);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= sprintf('>%s</output>', $text);
				break;
			case '!help!':
			default:
				$errorMsg = 'class.HTML5->output($action, $name, $for, $text, $attributes)<br>';
				$errorMsg.= '<ul><b>$action:</b>';
				$errorMsg.= '<li><i>open/start/begin</i>: add &lt;output name="[name]" for="[a b]"&gt;. (With optional attributes settings)</li>';
				$errorMsg.= '<li><i>close/stop/end</i>: add &lt;/output&gt;</li>';
				$errorMsg.= '<li><i>full</i>: add &lt;output name="[name]" for="[a b]"&gt;[Initial Output]&lt;/output&gt;</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$name:</b>';
				$errorMsg.= '<li>Specifies a name for the output element.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$for:</b>';
				$errorMsg.= '<li>Specifies the relationship between the result of the calculation, and the elements used in the calculation.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$text:</b> [Optional]';
				$errorMsg.= '<li>The initial text that will be displayed.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('output');
				die($errorMsg);
		}
		return $output;
	}

	/**
	* Use this to add a p-tag
	*/
	public function p($action, $attributes = '', $input = '')
	{
		return $this->newTag('p', $action, $attributes, $input);
	}

	/**
	* Use this to add a param-tag
	*/
	public function param($name, $value = '', $attributes = '')
	{
		switch (strtolower($name))
		{
			case '!help!':
				$errorMsg = 'class.HTML5->param($name, $value, $attributes)<br>';
				$errorMsg.= '<ul><b>$name:</b>';
				$errorMsg.= '<li>Specifies the name of a parameter.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$value:</b>';
				$errorMsg.= '<li>Specifies the value of the parameter.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('param');
				die($errorMsg);
			default:
				$output = sprintf('<param name="%s" value="%s"', $name, $value);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
		}
		return $output;
	}

	/**
	* Use this to add a pre-tag
	*/
	public function pre($action, $attributes = '', $input = '')
	{
		return $this->newTag('pre', $action, $attributes, $input);
	}

	/**
	* Use this to add a progress-tag
	*/
	public function progress($action, $value = '', $max = '', $attributes = '')
	{
		switch (strtolower($action))
		{
			case 'open':
			case 'start':
			case 'begin':
				$output = sprintf('<progress value="%s" max="%s"', $value, $max);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
				break;
			case 'close':
			case 'stop':
			case 'end':
				$output = '</progress>';
				break;
			case 'full':
				$output = sprintf('<progress value="%s" max="%s"', $value, $max);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '></progress>';
				break;
			case '!help!':
			default:
				$errorMsg = 'class.HTML5->progress($action, $value, $max, $attributes)<br>';
				$errorMsg.= '<ul><b>$action:</b>';
				$errorMsg.= '<li><i>open/start/begin</i>: add &lt;progress value="22" max="100"&gt;. (With optional attributes settings)</li>';
				$errorMsg.= '<li><i>close/stop/end</i>: add &lt;/progress&gt;</li>';
				$errorMsg.= '<li><i>full</i>: add &lt;progress value="22" max="100"&gt;&lt;/progress&gt;</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$value:</b>';
				$errorMsg.= '<li>Specifies how much of the task has been completed.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$max:</b>';
				$errorMsg.= '<li>Specifies how much work the task requires in total.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('progress');
				die($errorMsg);
		}
		return $output;
	}

	/**
	* Use this to add a q-tag
	*/
	public function q($action, $attributes = '', $input = '')
	{
		return $this->newTag('q', $action, $attributes, $input);
	}

	/**
	* Use this to add a rp-tag
	*/
	public function rp($action, $attributes = '', $input = '')
	{
		return $this->newTag('rp', $action, $attributes, $input);
	}

	/**
	* Use this to add a rt-tag
	*/
	public function rt($action, $attributes = '', $input = '')
	{
		return $this->newTag('rt', $action, $attributes, $input);
	}

	/**
	* Use this to add a ruby-tag
	*/
	public function ruby($action, $attributes = '', $input = '')
	{
		return $this->newTag('ruby', $action, $attributes, $input);
	}

	/**
	* Use this to add a s-tag
	*/
	public function s($action, $attributes = '', $input = '')
	{
		return $this->newTag('s', $action, $attributes, $input);
	}

	/**
	* Use this to add a samp-tag
	*/
	public function samp($action, $attributes = '', $input = '')
	{
		return $this->newTag('samp', $action, $attributes, $input);
	}

	/**
	* Use this to add a script-tag
	*/
	public function script($action, $src = '', $type = '', $attributes = '', $code = '')
	{
		switch (strtolower($action))
		{
			case 'open':
			case 'start':
			case 'begin':
				$output = '<script';
				if ($src != '') $output.= sprintf(' src="%s"', $src);
				if ($type != '') $output.= sprintf(' type="%s"', $type);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
				break;
			case 'close':
			case 'stop':
			case 'end':
				$output = '</script>';
				break;
			case 'full':
				$output = '<script';
				if ($src != '') $output.= sprintf(' src="%s"', $src);
				if ($type != '') $output.= sprintf(' type="%s"', $type);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= sprintf('>%s</script>', $code);
				break;
			case '!help!':
			default:
				$errorMsg = 'class.HTML5->script($action, $src, $type, $attributes, $code)<br>';
				$errorMsg.= '<ul><b>$action:</b>';
				$errorMsg.= '<li><i>open/start/begin</i>: add &lt;script&gt;. (With optional attributes settings)</li>';
				$errorMsg.= '<li><i>close/stop/end</i>: add &lt;/script&gt;</li>';
				$errorMsg.= '<li><i>full</i>: add &lt;script&gt;[Some code]&lt;/script&gt;</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$src:</b>';
				$errorMsg.= '<li>Specifies the URL of an external script file.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$type:</b>';
				$errorMsg.= '<li>Specifies the media type of the script.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('script');
				$errorMsg.= '<ul><b>$code:</b>';
				$errorMsg.= '<li>Some code to run.</li>';
				$errorMsg.= ('</ul>');
				die($errorMsg);
		}
		return $output;
	}

	/**
	* Use this to add a section-tag
	*/
	public function section($action, $attributes = '', $input = '')
	{
		return $this->newTag('section', $action, $attributes, $input);
	}

	/**
	* Use this to add a select-tag
	*/
	public function select($action, $name = '', $attributes = '', $input = '')
	{
		switch (strtolower($action))
		{
			case 'open':
			case 'start':
			case 'begin':
				$output = '<select';
				if ($name != '') $output.= sprintf(' name="%s"', $name);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
				break;
			case 'close':
			case 'stop':
			case 'end':
				$output = '</select>';
				break;
			case 'full':
				$output = '<select';
				if ($name != '') $output.= sprintf(' name="%s"', $name);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= sprintf('>%s</select>', $input);
				break;
			case '!help!':
			default:
				$errorMsg = 'class.HTML5->select($action, $name, $attributes, $input)<br>';
				$errorMsg.= '<ul><b>$action:</b>';
				$errorMsg.= '<li><i>open/start/begin</i>: add &lt;select&gt;. (With optional attributes settings)</li>';
				$errorMsg.= '<li><i>close/stop/end</i>: add &lt;/select&gt;</li>';
				$errorMsg.= '<li><i>full</i>: add &lt;select name="fieldName"&gt;[The option list]&lt;/select&gt;</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$name:</b>';
				$errorMsg.= '<li>Defines a name for the drop-down list.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('select');
				$errorMsg.= '<ul><b>$input:</b>';
				$errorMsg.= '<li>You can pre-prepare a list of options and add it to the optgroup.<br>';
				$errorMsg.= "\$input = className->option('full', 'volvo', 'Volvo')<br>";
				$errorMsg.= "\$input.= className->option('full', 'honda', 'Honda', 'selected')<br>";
				$errorMsg.= "\$input.= className->option('full', 'bmw', 'BMW')<br>";
				$errorMsg.= "className->select('full', 'variable', 'attrib', \$input)<br></li>";
				$errorMsg.= ('</ul>');
				die($errorMsg);
		}
		return $output;
	}

	/**
	* Use this to add a section-tag
	*/
	public function small($action, $attributes = '', $input = '')
	{
		return $this->newTag('small', $action, $attributes, $input);
	}

	/**
	* Use this to add a source-tag
	*/
	public function source($src, $type = '', $attributes = '')
	{
		switch (strtolower($src))
		{
			case '!help!':
				$errorMsg = 'class.HTML5->source($src, $type, $attributes)<br>';
				$errorMsg.= '<ul><b>$src</b>';
				$errorMsg.= '<li>Specifies the URL of the media file.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$type:</b>';
				$errorMsg.= '<li>Specifies the media type of the media resource.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('source');
				$errorMsg.= 'Extra information about the media types can be found '.$this->a('full', 'http://www.iana.org/assignments/media-types/media-types.xhtml', 'target="_blank"', '>here<').'.';
				die($errorMsg);
			default:
				$output = sprintf('<source src="%s" type="%s"', $src, $type);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
		}
		return $output;
	}

	/**
	* Use this to add a span-tag
	*/
	public function span($action, $attributes = '', $input = '')
	{
		return $this->newTag('span', $action, $attributes, $input);
	}

	/**
	* Use this to add a strong-tag
	*/
	public function strong($action, $attributes = '', $input = '')
	{
		return $this->newTag('strong', $action, $attributes, $input);
	}

	/**
	* Use this to add a style-tag
	*/
	public function style($action, $attributes = '', $input = '')
	{
		return $this->newTag('style', $action, $attributes, $input);
	}

	/**
	* Use this to add a sub-tag
	*/
	public function sub($action, $attributes = '', $input = '')
	{
		return $this->newTag('sub', $action, $attributes, $input);
	}

	/**
	* Use this to add a summary-tag
	*/
	public function summary($action, $attributes = '', $input = '')
	{
		return $this->newTag('summary', $action, $attributes, $input);
	}

	/**
	* Use this to add a sup-tag
	*/
	public function sup($action, $attributes = '', $input = '')
	{
		return $this->newTag('sup', $action, $attributes, $input);
	}

	/**
	* Use this to add a table-tag
	*/
	public function table($action, $attributes = '', $input = '')
	{
		return $this->newTag('table', $action, $attributes, $input);
	}

	/**
	* Use this to add a tbody-tag
	*/
	public function tbody($action, $attributes = '', $input = '')
	{
		return $this->newTag('tbody', $action, $attributes, $input);
	}

	/**
	* Use this to add a td-tag
	*/
	public function td($action, $attributes = '', $input = '')
	{
		return $this->newTag('td', $action, $attributes, $input);
	}

	/**
	* Use this to add a textarea-tag
	*/
	public function textarea($action, $name = '', $rows = 0, $cols = 0, $attributes = '', $input = '')
	{
		switch (strtolower($action))
		{
			case 'open':
			case 'start':
			case 'begin':
				$output = '<textarea';
				if ($name != '') $output.= sprintf(' name="%s"', $name);
				if ($rows != 0) $output.= sprintf(' rows="%d"', $rows);
				if ($cols != 0) $output.= sprintf(' cols="%d"', $cols);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
				break;
			case 'close':
			case 'stop':
			case 'end':
				$output = '</textarea>';
				break;
			case 'full':
				$output = '<textarea';
				if ($name != '') $output.= sprintf(' name="%s"', $name);
				if ($rows != 0) $output.= sprintf(' rows="%d"', $rows);
				if ($cols != 0) $output.= sprintf(' cols="%d"', $cols);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= sprintf('>%s</textarea>', $input);
				break;
			case '!help!':
			default:
				$errorMsg = 'class.HTML5->textarea($action, $name, $rows, $cols, $attributes, $input)<br>';
				$errorMsg.= '<ul><b>$action:</b>';
				$errorMsg.= '<li><i>open/start/begin</i>: add &lt;textarea&gt;. (With optional attributes settings)</li>';
				$errorMsg.= '<li><i>close/stop/end</i>: add &lt;/textarea&gt;</li>';
				$errorMsg.= '<li><i>full</i>: add &lt;textarea&gt;[Optional: Initial text]&lt;/textarea&gt;</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$name:</b>';
				$errorMsg.= '<li>Specifies a name for a text area.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$rows:</b>';
				$errorMsg.= '<li>Specifies the visible number of lines in a text area.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$cols:</b>';
				$errorMsg.= '<li>Specifies the visible width of a text area.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('textarea');
				$errorMsg.= '<ul><b>$input:</b>';
				$errorMsg.= '<li>Text that will be displayed inside the text area.</li>';
				$errorMsg.= ('</ul>');
				die($errorMsg);
		}
		return $output;
	}

	/**
	* Use this to add a tfoot-tag
	*/
	public function tfoot($action, $attributes = '', $input = '')
	{
		return $this->newTag('tfoot', $action, $attributes, $input);
	}

	/**
	* Use this to add a th-tag
	*/
	public function th($action, $attributes = '', $input = '')
	{
		return $this->newTag('th', $action, $attributes, $input);
	}

	/**
	* Use this to add a thead-tag
	*/
	public function thead($action, $attributes = '', $input = '')
	{
		return $this->newTag('thead', $action, $attributes, $input);
	}

	/**
	* Use this to add a time-tag
	*/
	public function time($action, $attributes = '', $input = '')
	{
		return $this->newTag('time', $action, $attributes, $input);
	}

	/**
	* Use this to add a title-tag
	*/
	public function title($action, $attributes = '', $input = '')
	{
		return $this->newTag('title', $action, $attributes, $input);
	}

	/**
	* Use this to add a tr-tag
	*/
	public function tr($action, $attributes = '', $input = '')
	{
		return $this->newTag('tr', $action, $attributes, $input);
	}

	/**
	* Use this to add a track-tag
	*/
	public function track($src, $kind = '', $srclang = '', $label = '', $attributes = '')
	{
		switch (strtolower($src))
		{
			case '!help!':
				$errorMsg = 'class.HTML5->track($src, $kind, $srclang, $label, $attributes)<br>';
				$errorMsg.= '<ul><b>$src</b>';
				$errorMsg.= '<li>Required. Specifies the URL of the track file.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$kind:</b> [Optional]';
				$errorMsg.= '<li>Specifies the kind of text tracke.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$srclang:</b> [Optional]';
				$errorMsg.= '<li>Specifies the language of the track text data (required if kind="subtitles").</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= '<ul><b>$label:</b> [Optional]';
				$errorMsg.= '<li>Specifies the title of the text track.</li>';
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr('track');
				die($errorMsg);
			default:
				$output = sprintf('<track src="%s"', $src);
				if ($kind != '') $output.= sprintf(' kind="%s"', $kind);
				if ($srclang != '') $output.= sprintf(' srclang="%s"', $srclang);
				if ($label != '') $output.= sprintf(' label="%s"', $label);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
		}
		return $output;
	}

	/**
	* Use this to add a u-tag
	*/
	public function u($action, $attributes = '', $input = '')
	{
		return $this->newTag('u', $action, $attributes, $input);
	}

	/**
	* Use this to add a ul-tag
	*/
	public function ul($action, $attributes = '', $input = '')
	{
		return $this->newTag('ul', $action, $attributes, $input);
	}

	/**
	* Use this to add a var-tag
	*/
	public function _var($action, $attributes = '', $input = '')
	{
		return $this->newTag('var', $action, $attributes, $input);
	}

	/**
	* Use this to add an video-tag
	*/
	public function video($input, $videoSource = NULL, $attributes = 'controls')
	{
		switch (strtolower($input))
		{
			case '!help!':
				$errorMsg = 'class.HTML5->video($input, $videoSource, $attributes)<br>';
				$errorMsg.= '<ul><b>$input:</b>';
				$errorMsg.= '<li>The text to display when the browser doesn\'t support video.</li>';
				$errorMsg.= '</ul>';
				$errorMsg.= '<ul><b>$videoSource:</b>';
				$errorMsg.= '<li>All the video sources to be used. (Look at the example how to use this.)</li>';
				$errorMsg.= '</ul>';
				$errorMsg.= $this->w3schoolsAttr('video');
				$errorMsg.= 'How to use this in your code?<br>';
				$errorMsg.= '<i>// First you have to create the source to be used, you do this by making an array.</i><br>';
				$errorMsg.= '$source = array();<br>';
				$errorMsg.= "\$source['video/mp4'] = 'your_video_file.mp4';<br>";
				$errorMsg.= "\$source['video/ogg'] = 'your_video_file.ogg';<br>";
				$errorMsg.= "\$source['video/webm'] = 'your_video_file.webm';<br>";
				$errorMsg.= '<i>// Then you call the function.</i><br>';
				$errorMsg.= 'echo $html->video(\'Your browser doesn\'t support the video.\', $source, \'controls autoplay\');<br>';
				$errorMsg.= '<br>';
				$errorMsg.= 'At the moment, only MP4, Ogg and WebM are supported. You can add different sources and the browser will use the one it supports.';
				die($errorMsg);
			default:
				$output = '<video';
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
				foreach ($videoSource as $type => $source)
				{
					$output.= sprintf('<source src="%s" type="%s">', $source, $type);
				}
				$output.= $input;
				$output.= '</video>';
		}
		return $output;
	}

	/**
	* Use this to add a wbr-tag
	*/
	public function wbr($attributes = '')
	{
		switch (strtolower($attributes))
		{
			case '!help!':
				$errorMsg = 'class.HTML5->br($attributes)<br>';
				$errorMsg.= $this->w3schoolsAttr('wbr');
				die($errorMsg);
			default:
				$output = '<wbr';
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
		}
		return $output;
	}

#### Private functions ####
	
	/**
	* This private function is used to add more information about $attributes with the different HTML-elements
	*/
	private function w3schoolsAttr($tag = '', $optional = TRUE)
	{
		$output = '<ul><b>$attributes:</b>';
		if ($optional == TRUE) $output.= ' [Optional]';
		$output.= '<li>To add special Tag-, Global- and Event Attributes to the HTML-element.<br>';
		$output.= 'More information can be found at:<br>';
		$urlLink = sprintf('http://www.w3schools.com/tags/tag_%s.asp', $tag);
		$output.= $this->a('full', $urlLink, 'target="_blank"', 'w3schools page.').'<br>';
		$output.= $this->a('full', 'http://www.w3schools.com/tags/ref_standardattributes.asp','target="_blank"', 'w3schools Global Attributes page.').'<br>';
		$output.= $this->a('full', 'http://www.w3schools.com/tags/ref_eventattributes.asp','target="_blank"', 'w3schools Event Attributes page.').'<br>';
		$output.= sprintf('$attributes = <b>\'</b>id="cssID" style="color:red" onload="alert(\\\'Alert box example\\\')"<b>\'</b></ul>');
		return $output;
	}
	
	/**
	* This private function is used for all the tags that have a common structure
	*/
	private function newTag($tag, $action, $attributes, $input)
	{
		switch (strtolower($action))
		{
			case 'open':
			case 'start':
			case 'begin':
				$output = sprintf('<%s', $tag);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= '>';
				break;
			case 'close':
			case 'stop':
			case 'end':
				$output = sprintf('</%s>', $tag);
				break;
			case 'full':
				$output = sprintf('<%s', $tag);
				if ($attributes != '') $output.= sprintf(' %s', $attributes);
				$output.= sprintf('>%s</%s>', $input, $tag);
				break;
			case '!help!':
			default:
				$errorMsg = sprintf('class.HTML5->%s($action, $attributes, $input)<br>', $tag);
				$errorMsg.= '<ul><b>$action:</b>';
				$errorMsg.= sprintf('<li><i>open/start/begin</i>: add &lt;%s&gt;. (With optional attributes settings)</li>', $tag);
				$errorMsg.= sprintf('<li><i>close/stop/end</i>: add &lt;/%s&gt;</li>', $tag);
				$errorMsg.= sprintf('<li><i>full</i>: add &lt;%s&gt;[All the HTML and text you want to display]&lt;/%s&gt;</li>', $tag, $tag);
				$errorMsg.= ('</ul>');
				$errorMsg.= $this->w3schoolsAttr($tag);
				$errorMsg.= '<ul><b>$input:</b> [Optional]';
				$errorMsg.= '<li>The text that you want to display. (Can contain HTML-tags)<br>';
				$errorMsg.= 'If you don\'t have attributes to add, you need to use it as followed:<br>';
				$errorMsg.= sprintf("className->%s('full', '', 'Add the text that you want to display.')<br>", $tag);
				$errorMsg.= '<b>Or even better:</b><br>';
				$errorMsg.= "\$string = 'Your fully prepared string, with HTML and all';<br>";
				$errorMsg.= sprintf("className->%s('full', '', \$string)<br></li>", $tag);
				$errorMsg.= ('</ul>');
				die($errorMsg);
		}
		return $output;
	}
	
	/**
	* You can use this to do a quick check of some importent arrays on the server
	*/
	public function testVar()
	{
		print('<pre>');
		print('GET:<br>');
		print_r($_GET);
		print('POST:<br>');
		print_r($_POST);
		print('COOKIE:<br>');
		print_r($_COOKIE);
		print('SESSION:<br>');
		print_r($_SESSION);
		print('FILES:<br>');
		print_r($_FILES);
		print('</pre>');
	}
}
/** End of File: class.HTML5.php **/