<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

if ($editor_html == '')
{
	$editor_html = '<html><head><meta http-equiv=Content-Type content="text/html; charset='.$charset.'"><meta content="'.$GO_CONFIG->title.' '.$GO_CONFIG->version.'" name="GENERATOR"><style>P {margin: 0px;}</style></head><body>'.$editor_html.'</body></html>';
}else
{
	$editor_html = str_replace("\r", "",$editor_html);
	$editor_html = str_replace("\n", "",$editor_html);
}
?>
<input type="hidden" name="editor_html" />
<script language="JavaScript">


  var viewMode = 1; // WYSIWYG

  function Init()
  {
    iView.document.write('<?php echo $editor_html; ?>');
    iView.document.designMode = 'On';
    iView.focus();
  }

  function selOn(ctrl)
  {
	ctrl.style.borderColor = '#000000';
	ctrl.style.backgroundColor = '#B5BED6';
	ctrl.style.cursor = 'hand';
  }

  function selOff(ctrl)
  {
	ctrl.style.borderColor = '';
	ctrl.style.backgroundColor = '';
  }

  function selDown(ctrl)
  {
	ctrl.style.backgroundColor = '#8492B5';
  }

  function selUp(ctrl)
  {
    ctrl.style.backgroundColor = '#B5BED6';
  }

  function callCommand(command)
  {
    	iView.focus();
    	iView.document.execCommand(command, false, null);
  }

  function doForeCol()
  {
  	iView.focus();
  	var popup = window.open('<?php echo $GO_CONFIG->control_url; ?>color_picker.php?set=forecolor', '_blank', 'width=305,height=75,scrollbars=no,resizable=no,status=no');
	if (!popup.opener) popup.opener = self;
	popup.focus();
  }

  function doBackCol()
  {
  	iView.focus();
  	var popup = window.open('<?php echo $GO_CONFIG->control_url; ?>color_picker.php?set=backcolor', '_blank', 'width=305,height=75,scrollbars=no,resizable=no,status=no');
	if (!popup.opener) popup.opener = self;
	popup.focus();
  }

  function doImage()
  {
    	iView.focus();
      	<?php
      	if ($GO_MODULES->id = 'email')
      	{
			if ($module = $GO_MODULES->get_module('filesystem'))
			{
				if ($GO_SECURITY->has_permission($GO_SECURITY->user_id, $module['acl_read']) || $GO_SECURITY->has_permission($GO_SECURITY->user_id, $module['acl_write']))
				{
      				echo 'popup(\'select_image.php\',\'600\',\'400\')';
				}
			}
		}
      	?>
  }

  function doFont(fName)
  {
  	iView.focus();
    if(fName != '')
      iView.document.execCommand('fontname', false, fName);
  }

  function doSize(fSize)
  {
  	iView.focus();
    if(fSize != '')
      iView.document.execCommand('fontsize', false, fSize);
  }

  function doHead(hType)
  {
  	iView.focus();
    if(hType != '')
    {
      iView.document.execCommand('formatblock', false, hType);
      doFont(document.forms[0].selFont.options[document.forms[0].selFont.selectedIndex].value);
    }
  }

  function doToggleView()
  {
    if(viewMode == 1)
    {
      iHTML = iView.document.body.innerHTML;
      iView.document.body.innerText = iHTML;

      // Hide all controls
      trControls.style.display = 'none';
      tdControls.style.display = 'none';
      document.forms[0].selFont.style.display = 'none';
      document.forms[0].selSize.style.display = 'none';
      document.forms[0].selHeading.style.display = 'none';
      iView.focus();

      viewMode = 2; // Code
    }
    else
    {
      iText = iView.document.body.innerText;
      iView.document.body.innerHTML = iText;

      // Show all controls
      trControls.style.display = 'inline';
      tdControls.style.display = 'inline';
      document.forms[0].selFont.style.display = 'inline';
      document.forms[0].selSize.style.display = 'inline';
      document.forms[0].selHeading.style.display = 'inline';

      iView.focus();

      viewMode = 1; // WYSIWYG
    }
  }
	function update_editor_html()
	{
		//document.forms[0].editor_html.value = iView.document.getElementsByTagName("html")[0].outerHTML;
	}


</script>
<style>

  .butClass
  {
    border: 1px solid;
    border-color: #D6D3CE;
  }

  .tdClass
  {
    padding-left: 3px;
    padding-top:3px;
  }

</style>
	<?php
		if (!isset($editor_height)) $editor_height = '205';
		if (!isset($editor_width)) $editor_width = '415';
	?>

	<table width="<?php echo $editor_width; ?>" height="<?php echo $editor_height; ?>" border="0" cellspacing="0" cellpadding="0">
	    <tr>
			<td class="tdClass" colspan="1" valign="top" id="tdControls" height="30px" nowrap>
			<?php
				$selFont = isset($selFont) ? $selFont : '';
				$dropbox = new dropbox();
				$dropbox->add_value("","-- ".$editor_font." --");
				$dropbox->add_value("Arial","Arial");
			    $dropbox->add_value("Times New Roman","Times New Roman");
			    $dropbox->add_value("Verdana","Verdana");
			    $dropbox->add_value("Courier","Courier");
			    $dropbox->add_value("MS Sans Serif","MS Sans Serif");
			    $dropbox->add_value("Comic Sans MS","Comic Sans MS");
			    $dropbox->add_value("Tahoma","Tahoma");
			    $dropbox->add_value("Wingdings","Wingdings");
			    $dropbox->print_dropbox("selFont", $selFont, 'onchange="doFont(this.options[this.selectedIndex].value)"');

				$selSize = isset($selSize) ? $selSize : '';
			  	$dropbox = new dropbox();
			    $dropbox->add_value("","-- ".$editor_size." --");
			    $dropbox->add_value("1",$editor_very_small);
			    $dropbox->add_value("2",$editor_small);
			    $dropbox->add_value("3",$editor_medium);
			    $dropbox->add_value("4",$editor_large);
			    $dropbox->add_value("5",$editor_larger);
			    $dropbox->add_value("6",$editor_very_large);
			    $dropbox->print_dropbox("selSize",$selSize,'onchange="doSize(this.options[this.selectedIndex].value)"');

				$selHeading = isset($selHeading) ? $selHeading : '';
			  	$dropbox = new dropbox();
			    $dropbox->add_value("","-- ".$editor_heading." --");
			    $dropbox->add_value("Heading 1","H1");
			    $dropbox->add_value("Heading 2","H2");
			    $dropbox->add_value("Heading 3","H3");
			    $dropbox->add_value("Heading 4","H4");
			    $dropbox->add_value("Heading 5","H5");
			    $dropbox->add_value("Heading 6","H6");
			    $dropbox->print_dropbox("selHeading", $selHeading,'onchange="doHead(this.options[this.selectedIndex].value)"');
			?>
			  	<img alt="<?php echo $editor_bold; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/bold.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="callCommand('bold')">
			  	<img alt="<?php echo $editor_italic; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/italic.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="callCommand('italic')">
			  	<img alt="<?php echo $editor_underline; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/underline.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="callCommand('underline')">
				<img alt="<?php echo $editor_strikethrough; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/strikethrough.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="callCommand('StrikeThrough')">
				&nbsp;
				<img alt="<?php echo $editor_superscript; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/superscript.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="callCommand('superscript')">
				<img alt="<?php echo $editor_subscript; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/subscript.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="callCommand('subscript')">

			</td>
			<td class="tdClass" align="right">
			  <img alt="<?php echo $editor_mode; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/mode.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doToggleView()">
			</td>
	    </tr>
	   	<tr id="trControls">
				<td class="tdClass" colspan="3" nowrap>

					<img alt="<?php echo $editor_left; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/left.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="callCommand('justifyleft')">
					<img alt="<?php echo $editor_center; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/center.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="callCommand('justifycenter')">
					<img alt="<?php echo $editor_right; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/right.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="callCommand('justifyright')">
					<img alt="<?php echo $editor_outdent; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/outdent.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="callCommand('outdent')">
					<img alt="<?php echo $editor_indent; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/indent.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="callCommand('indent')">
					&nbsp;
					<img alt="<?php echo $editor_ord_list; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/ordlist.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="callCommand('insertorderedlist')">
					<img alt="<?php echo $editor_bul_list; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/bullist.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="callCommand('insertunorderedlist')">
					&nbsp;
					<img alt="<?php echo $editor_undo; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/undo.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="callCommand('undo')">
					<img alt="<?php echo $editor_redo; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/redo.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="callCommand('redo')">
					&nbsp;
					<img alt="<?php echo $editor_txt_color; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/textcolor.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doForeCol()">
					<img alt="<?php echo $editor_back_color; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/bg_color.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doBackCol()">
					&nbsp;
					<img alt="<?php echo $editor_hyperlink; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/link.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="callCommand('createlink')">
					<img alt="<?php echo $editor_image; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/image.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doImage()">
					<img alt="<?php echo $editor_rule; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/rule.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="callCommand('inserthorizontalrule')">
					&nbsp;
					<img alt="<?php echo $editor_copy; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/copy_small.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="callCommand('copy')">
					<img alt="<?php echo $editor_cut; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/cut_small.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="callCommand('cut')">
					<img alt="<?php echo $editor_paste; ?>" class="butClass" src="<?php echo $GO_THEME->image_url; ?>buttons/paste_small.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="callCommand('paste')">

				</td>
			</tr>
		<tr>
		<td width="100%" height="100%" colspan="3">
		<iframe id="iView" name="editor" style="width: 100%; height: 100%;" onBlur="javascript:update_editor_html()"></iframe>
		</td>
	</tr>
	</table>



