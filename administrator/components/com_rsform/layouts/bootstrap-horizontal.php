<?php
/**
* @version 1.4.0
* @package RSform!Pro 1.4.0
* @copyright (C) 2007-2011 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$out = '';

if ($this->_form->ShowFormTitle) {
	$out.='<h2>{global:formtitle}</h2>'."\n";
}

$out.='{error}'."\n";
$out.='<fieldset>'."\n";

$page_num = 0;
if (!empty($pagefields))
{
	$out .= "\t".'<!-- Do not remove this ID, it is used to identify the page so that the pagination script can work correctly -->'."\n";
	$out .= "\t".'<div id="rsform_'.$formId.'_page_'.$page_num.'">'."\n";
}
	
foreach ($quickfields as $quickfield)
{
	if (in_array($quickfield, $pagefields))
	{
		$page_num++;
		$last_page  = $quickfield == end($pagefields);
		$last_field = $quickfield == end($quickfields);
		
		$out.= "\t".'<div class="control-group rsform-block rsform-block-'.JFilterOutput::stringURLSafe($quickfield).'">'."\n";
		$out.= "\t\t{".$quickfield.":body}\n";
		$out.= "\t</div>\n";
		
		$out .= "\t".'</div>'."\n";
		if (!$last_page || !$last_field)
		{
			$out .= '<!-- Do not remove this ID, it is used to identify the page so that the pagination script can work correctly -->'."\n";
			$out .= "\t".'<div id="rsform_'.$formId.'_page_'.$page_num.'">'."\n";
		}
			
		continue;
	}
	
	$required = in_array($quickfield, $requiredfields) ? ' '.(isset($this->_form->Required) ? $this->_form->Required : '(*)') : "";
	if (strtolower(substr($quickfield,-7)) != "_hidden") {
		$out.= "\t".'<div class="control-group rsform-block rsform-block-'.JFilterOutput::stringURLSafe($quickfield).'">'."\n";
		$out.= "\t\t<label class=\"control-label\" for=\"".$quickfield."\">{".$quickfield.":caption}".$required."</label>\n";
		$out.= "\t\t<div class=\"controls\">\n";
		$out.= "\t\t\t{".$quickfield.":body} {".$quickfield.":description} {".$quickfield.":validation}\n";
		$out.= "\t\t</div>\n";
		$out.= "\t</div>\n";
	} else {
		$out.= "{".$quickfield.":body}\n";
	}
}
if (!empty($pagefields))
	$out .= "\t".'</div>'."\n";
$out.= "</fieldset>\n";
$out.="<script type=\"text/javascript\">jQuery(\"[rel=clickover]\").clickover({html: true});</script>";

if ($out != $this->_form->FormLayout && $this->_form->FormId)
{
	// Clean it
	// Update the layout
	$db = JFactory::getDBO();
	$db->setQuery("UPDATE #__rsform_forms SET FormLayout='".$db->escape($out)."' WHERE FormId=".$this->_form->FormId);
	$db->query();
}

return $out;