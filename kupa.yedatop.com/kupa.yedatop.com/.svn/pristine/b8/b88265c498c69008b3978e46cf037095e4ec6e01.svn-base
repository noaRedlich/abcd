<?php

require_once("libs/Smarty.class.php");

function db_get_template ($tpl_name, &$tpl_source, &$smarty_obj) 
{ 
	// do database call here to fetch your template, 
	// populating $tpl_source 
	$rs = DBQuery("select template from v_templates where id='$tpl_name'"); 
	if (!$rs->EOF) 
	{ 
		$tpl_source = $rs->fields["template"]; 
		return true; 
	}
	else 
	{ 
		return false; 
	} 
} 

function db_get_timestamp($tpl_name, &$tpl_timestamp, &$smarty_obj) 
{ 
	// do database call here to populate $tpl_timestamp. 
	$rs = DBQuery("select lastupdated from v_templates where id='$tpl_name'"); 
	if (!$rs->EOF) 
	{ 
		$tpl_timestamp = $rs->fields['lastupdated']; 
		return true; 
	} 
	else 
	{ 
		return false; 
	} 
} 

function db_get_secure($tpl_name, &$smarty_obj) 
{ 
	// assume all templates are secure 
	return true; 
} 

function db_get_trusted($tpl_name, &$smarty_obj) 
{ 
	// not used for templates 
} 



class SmartyProxy extends Smarty 
{
	function SmartyProxy()
	{
		global $DOCUMENT_ROOT;
		
		$this->Smarty();
		
		$this->template_dir = $DOCUMENT_ROOT.'/smarty/templates';
		$this->config_dir = $DOCUMENT_ROOT.'/smarty/config';
		$this->cache_dir = $DOCUMENT_ROOT.'/smarty/cache';
		$this->compile_dir = $DOCUMENT_ROOT.'/smarty/templates_c';
		
		$this->register_resource("db", array("db_get_template", 
					"db_get_timestamp", 
					"db_get_secure", 
					"db_get_trusted")); 
	}
}



?>