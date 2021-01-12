<?php
/*////////////////////////////////////////////////////////////////////////////////
//																				//
// Author: Merijn Schering <mschering@hilckmanngroep.com>						//
// Version: 1.0 Release date: 14 March 2003										//
//																				//
////////////////////////////////////////////////////////////////////////////////*/

if ($email->get_folders($email_id) > 0)
{
	?>
	<table border="0" cellpadding="4" cellspacing="0">
	<tr>
		<td colspan="3">
		<br />
		<?php
			if (isset($feedback))
			{
				echo $feedback;
			}
		?>
		</td>
	</tr>

	<tr>
		<td>
		<?php
		$field = isset($field) ? $field : '';
		$dropbox=new dropbox();
		$dropbox->add_value('sender',$ml_email_is);
		$dropbox->add_value('subject',$ml_subject_is);
		$dropbox->add_value('to',$ml_to_is);
		$dropbox->add_value('cc',$ml_cc_is);
		$dropbox->print_dropbox('field',$field);
		?>
		</td>
		<td>
		<input type="text" name="keyword" size="30" class="textbox" />
		</td>
	</tr>
	<tr>
		<td colspan="2">

		<?php
		$folder = isset($folder) ? $folder : '';
		$dropbox=new dropbox();
		$dropbox->add_value('',$ml_move_to);
		$dropbox->add_sql_data('email','name','name');
		$dropbox->print_dropbox('folder',$folder);
		?>
		</td>

	</tr>

	<tr>
		<td colspan="2">
		<br />
		<?php
		$button = new button($cmdSave, 'javascript:document.forms[0].submit()');
		?>
		</td>
	</tr>
	</table>
	<?php
}else
{
	echo $ml_no_folders;
}
?>