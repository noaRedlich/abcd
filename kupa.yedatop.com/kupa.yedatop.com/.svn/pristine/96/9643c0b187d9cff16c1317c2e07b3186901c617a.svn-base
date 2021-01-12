<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $GO_CONFIG->max_file_size; ?>" />
<input type="hidden" name="action" value="import" />
<table border="0" cellpadding="10" cellspacing="0">
<tr>
	<td>
	<table border="0" cellpadding="2" cellspacing="0">
	<tr>
		<td valign="top">
		<table border="0" cellpadding="0" cellspacing="0" class="TableBorder" width="600">
		<tr>
			<td valign="top">
			<table border="0" cellpadding="1" cellspacing="1" width="100%">
			<tr>
				<td colspan="99" class="TableHead"><?php echo $contacts_import_title; ?></td>
			</tr>
			<tr>
				<td class="TableInside" height="300" valign="top">
				<table border="0" cellpadding="10">
				<tr>
					<td>
					<?php
					if ($REQUEST_METHOD == "POST")
					{
						if ($action=='import')
						{
							$csv_file = $GO_CONFIG->tmpdir.'contacts_import_csv_'.$GO_SECURITY->user_id.'.csv';
							if (!is_uploaded_file($uploaded_file))
							{
								unset($csv_file);
								echo '<p class="Error">'.$fbNoFile.'</p>';
							}elseif(!copy($uploaded_file, $csv_file))
							{
								unset($csv_file);
								echo '<p class="Error">'.$fbNoFile.'</p>';
							}
						}

						if (isset($csv_file))
						{
							echo '<input type="hidden" value="'.$csv_file.'" name="csv_file" />';
							$fp = fopen($csv_file, 'r');

							if ($fp)
							{
								$line = str_replace('"','',fgets($fp, 4096));
								fclose($fp);
								$seperator=';';

								$record = explode($seperator, $line);

								//when nothing gets exploded then try again with different seperator
								if (strlen($record[0]) == strlen($line))
								{
									$seperator = ',';
									$record = explode($seperator, $line);
								}
								//when it's still not exploded then the file is not compatible.
								if (strlen($record[0]) == strlen($line))
								{
									echo '<p class="Error">'.$contacts_import_incompatible.'</p>';
								}else
								{
									echo '<input type="hidden" name="seperator" value="'.$seperator.'">';
									if (isset($feedback))
									{
										echo $feedback;
									}
									echo $contacts_import_feedback.'<br /><br />';

									echo '<table border="0" cellpadding="2" cellspacing="0">';
									$group_mode = isset($group_mode) ? $group_mode : 'group_name';

									if ($contacts->get_groups($GO_SECURITY->user_id) > 0)
									{
										$check = $group_mode == 'group_name' ? 'checked' : '';
										echo '<tr><td><input type="radio" name="group_mode" value="group_name" '.$check.' />'.$contacts_import_to_group.': </td><td>';
										$group_id = isset($group_id) ? $group_id : 0;
										$dropbox = new dropbox();
										$dropbox->add_value('0',$contacts_other);
										$dropbox->add_sql_data('contacts','id','name');
										$dropbox->print_dropbox('group_id',$group_id);
										echo '</td></tr>';
										$check = $group_mode == 'file' ? 'checked' : '';
										echo '<tr><td><input type="radio" name="group_mode" value="file" '.$check.'  />'.$contacts_auto_group.': </td><td>';
									}else
									{
										$check = $group_mode == 'file' ? 'checked' : '';
										echo '<tr><td><input type="hidden" name="group_id" value="0" /><input type="checkbox" name="group_mode" value="file" '.$check.' />Groepeer op basis van bestand:</td><td>';
									}
									$dropbox = new dropbox();
									for ($n=0;$n<sizeof($record);$n++)
									{
										$dropbox->add_value($n,$record[$n]);
									}
									$group_record = isset($group_record) ? $group_record : 0;
									$dropbox->print_dropbox('group_record', $group_record);
									echo '</td></tr></table><br />';



									echo '<table border="0" cellpadding="4" cellspacing="0">';
									$columns = isset($columns) ? $columns : array();
									for ($n=0;$n<sizeof($record);$n++)
									{
										$columns[$n] = isset($columns[$n]) ? $columns[$n] : '';
										echo '<tr><td>'.$record[$n].':</td><td>';
										$dropbox = new dropbox();
										$dropbox->add_value('',$strNotIncluded);
										$dropbox->add_value('name',$strName);
										$dropbox->add_value('email',$strEmail);
										$dropbox->add_value('home_phone',$strPhone);
										$dropbox->add_value('fax',$strFax);
										$dropbox->add_value('cellular',$strCellular);
										$dropbox->add_value('country',$strCountry);
										$dropbox->add_value('state',$strState);
										$dropbox->add_value('city',$strCity);
										$dropbox->add_value('zip',$strZip);
										$dropbox->add_value('address',$strAddress);
										$dropbox->add_value('company',$strCompany);
										$dropbox->add_value('home_phone',$strPhone);
										$dropbox->add_value('work_country',$strWorkCountry);
										$dropbox->add_value('work_state',$strWorkState);
										$dropbox->add_value('work_city',$strWorkCity);
										$dropbox->add_value('work_zip',$strWorkZip);
										$dropbox->add_value('work_address',$strWorkAddress);
										$dropbox->add_value('work_phone',$strWorkphone);
										$dropbox->add_value('work_fax',$strWorkFax);
										$dropbox->add_value('homepage',$strHomepage);
										$dropbox->add_value('department',$strDepartment);
										$dropbox->add_value('function',$strFunction);
										$dropbox->print_dropbox('columns[]',$columns[$n]);
									}
									echo "<tr><td colspan=\"2\"><br />";
									$button = new button($cmdOk, 'javascript:import_data()');
									echo "</td></tr>";
									echo "</table>";
								}
							}
						}
					}else
					{
					?>
					<table border="0">
					<tr>
						<td colspan="2">
						<table border="0">
						<tr>
							<td colspan="2">
							<?php echo $contacts_import_text; ?>
							<br /><br />
							</td>
						</tr>

						<tr>
							<td><?php echo $fbFilename; ?>:</td>
							<td><input type="file" name="uploaded_file" maxlength="100" class="textbox" /></td>
						</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td colspan="2"><?php $button = new button($cmdOk, 'javascript:upload();');	?></td>
					</tr>
					<tr>
						<td id="status" class="Success" colspan="2">&nbsp;</td>
					</tr>
					</table>
					<?php
					}
					?>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
<script type="text/javascript" langugae="javascript">
<!--
function upload()
{
	document.forms[0].enctype.value = "multipart/form-data";
	var status = null;
	if (status = get_object("status"))
	{
		status.innerHTML = "<?php echo $fbPleaseWait; ?>";
	}
	document.forms[0].submit();
}

function import_data()
{
	document.forms[0].enctype = '';
	document.forms[0].action.value='import_data';
	document.forms[0].submit();
}

-->
</script>