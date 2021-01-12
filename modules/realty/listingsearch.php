<?php
	include("include/common.php");
	if (!loginCheck('User'))exit;
	include("$config[template_path]/admin_top.html");
?>
<table border="<?php echo $style[admin_listing_border] ?>" cellspacing="<?php echo $style[admin_listing_cellspacing] ?>" cellpadding="<?php echo $style[admin_listing_cellpadding] ?>" width="<?php echo $style[admin_table_width] ?>" class="form_main">
	<tr>
		<td valign="top" width=1%>
			<?php renderFeaturedListingsVertical(4); ?>
		</td>
		<td valign="top">
		<table bgcolor=buttonface width=100% ><tr><td>
			<h3><?=$lang[search_listings]?></h3>
			</td><td align=right>
			<p><img src=<?=$GO_THEME->image_url?>buttons/ordlist.gif hspace=3 align=absmiddle border=0 ><?php browse_all_listings() ?> |
			<a href='./listing_browse.php?drisha=<?=urlencode($lang[demand])?>'><?=$lang[demand]?></a> |
			<a href='./listing_browse.php?drisha=<?=urlencode($lang[offer])?>'><?=$lang[offer]?></a> |
			</p>
			</td></tr></table>
				<form name="listingsearch" action="./listing_browse.php" method="get">
					<table>
						<?php
							// get the db object in scope
							global $conn;
							// Get all searchable fields and display them
							$sql = "select search_label, search_type, field_name from listingsFormElements where searchable = 1 order by rank";
							if (!$rs = $conn->execute ($sql))
							{
								log_error($sql);
							}
							while (!$rs->EOF)
							{
								$searchfunction = 'searchbox_' . $rs->fields['search_type'];
								$searchfunction($rs->fields['search_label'], $rs->fields['field_name']);
								$rs->MoveNext();
							}
						?>
						<tr>
							<td><b><?=$lang['search_mode']?></td>
							<td>
 								<select name="userid" style='width:200px'> 
									<option value=""><?=$lang[all]?>
									<option value="<?=$userID?>"><?=$lang[only_my]?>
									<option value="0"><?=$lang[only_pniot]?>
									<option value="-1"><?=$lang[only_brockers]?>
								</select>
							</td>
						</tr>						
						<tr>
							<td align="center" colspan="2">
								<input type="checkbox" name="imagesOnly" value="yes"> <b><?=$lang[with_pictures]?></b>
							</td>
						</tr>
						<tr>
							<td align="center" colspan="2">
								<input type="submit" value="<?=$lang[search_listings]?>">
							</td>
						</tr>
					</table>
				</form>

		</td>
	</tr>
</table>

<?php
	include("$config[template_path]/admin_bottom.html");
	$conn->Close(); // close the db connection
?>