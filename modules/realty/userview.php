<?php
	include("include/common.php");
	if (!loginCheck('User'))exit;
	include("$config[template_path]/admin_top.html");	
	?>
	
	
	<table border="<?php echo $style[form_border] ?>" cellspacing="<?php echo $style[form_cellspacing] ?>" cellpadding="<?php echo $style[form_cellpadding] ?>" width="<?php echo $style[admin_table_width] ?>" class="form_main" align="center">
		<tr>
			<td colspan="2" class="row_main">
				<?php getMainUserData($user) ?>
				
			</td>
		</tr>
		<tr>
			
				
				<?php
				renderUserImages($user)
				?>
			<td class="row_main" valign="top">
			<?php getUserEmail($user) ?>
			<br>
			<?php renderUserInfo($user) ?>
			<br><br>
			<?php userListings($user) ?>
			</td>
		</tr>
			<td colspan="2" class="row_main" align="center">
				<?php userHitcount($user) ?>
			</td>
		</tr>
 	</table>
	
<?php
	include("$config[template_path]/admin_bottom.html");
	$conn->Close(); // close the db connection
?>