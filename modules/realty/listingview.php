<?php
	include("include/common.php");
	if (!loginCheck('User'))exit;
	include("$config[template_path]/admin_top.html");	
		
	if ($listingID == "")
	{
		echo "<a href=\"index.php\">$lang[perhaps_you_were_looking_something_else]</a>";
	}	
		
		
	elseif ($listingID != "")
	{
		// first, check to see whether the listing is currently active
		$show_listing = checkActive($listingID);
		if ($show_listing == "yes")
		{
			?>
				<!-- This Script opens a new window it is used by the mortgage calc. -->
				<script type="text/javascript">
				<!--
				function open_window(url)
				{
					cwin = window.open(url,"attach","width=350,height=400,toolbar=no,resizable=yes");
				}
				-->
				</script>
		<?if ($backurl){?>		
			<b><a  href="<?=$backurl?>"><img src=images/undo.gif align=absmiddle border=0 width=23 height=22><?=$lang['back_button_text']?></a></b>
		<?}?>
		<table border="0<?php echo $style[form_border] ?>" cellspacing="<?php echo $style[form_cellspacing] ?>" cellpadding="<?php echo $style[form_cellpadding] ?>" width="<?php echo $style[admin_table_width] ?>" class="form_main" align="center" >
			<tr>
				<td colspan="2" class="row_main" bgcolor=buttonface>
					<?php getMainListingData($listingID); ?>
				
					<?php renderTemplateAreaNoCaption(headline,$listingID); ?>
				</td>
			</tr>
			<tr>
				
					<?php
					renderListingsImages($listingID)
					?>

				<td class="row_main" valign=top>
				<?php renderTemplateArea(center,$listingID); ?>

					<table width="100%" cellpadding="<?php echo $style[left_right_table_cellpadding] ?>" cellspacing="<?php echo $style[left_right_table_cellspacing] ?>" border="<?php echo $style[left_right_table_border] ?>">
						<tr>
							<td  class="row_main" width="50%" valign="top">
								<?php renderTemplateArea(top_left,$listingID); ?>
								
								
							</td>
							<td  class="row_main" width="50%" valign="top">
								<?php renderTemplateArea(top_right,$listingID); ?>
								
								
							</td>
						</tr>
					</table>

					<table width="98%">
						<tr>
							<td valign="top">
							<?php renderSingleListingItemNoCaption($listingID, "full_desc") ?>
							</td>
						</tr>
					</table>
					
					
	
					<table width="100%" cellpadding="<?php echo $style[left_right_table_cellpadding] ?>" cellspacing="<?php echo $style[left_right_table_cellspacing] ?>" border="<?php echo $style[left_right_table_border] ?>">
						<tr>
							<td class="row_main" width="50%" valign="top">
								<?php renderTemplateArea(feature1,$listingID); ?>
							</td>
							<td  class="row_main" width="50%" valign="top">
								<?php renderTemplateArea(feature2,$listingID); ?>
							</td>
						</tr>
					</table>
				<?if (false){?>
				<br><a href="listingview.php?listingID=<?php echo $listingID ?>&amp;printer_friendly=yes"><b>Printer Friendly Version of This Page</b></a>
				<?}?>
				
				
					<table width="100%" cellpadding="<?php echo $style[left_right_table_cellpadding] ?>" cellspacing="<?php echo $style[left_right_table_cellspacing] ?>" border="<?php echo $style[left_right_table_border] ?>">
						<tr>
							<td  class="row_main" width="50%" valign="top">
								<?php renderTemplateArea(bottom_left,$listingID); ?>
								
								
							</td>
							<td  class="row_main" width="50%" valign="top">
								<?php renderTemplateArea(bottom_right,$listingID); ?>
								
								
							</td>
						</tr>
					</table>				
				<br><br>
				<hr size="1" width="100%">
				<?php renderUserInfoOnListingsPage($listingID) ?>
				<br>
				<?php getListingEmail($listingID) ?>
				<br>
				<?php hitcount($listingID) ?>
				
				</td>
			</tr>
	 	</table>
		
		<?php
		} // end if ($show_listing == "yes")
	} // end elseif ($listingID != "")
	
	include("$config[template_path]/admin_bottom.html");
?>