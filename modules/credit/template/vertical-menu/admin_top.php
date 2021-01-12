<?php
	global $config, $lang;
	include("$config[template_path]/admin_top2.php");
?>
	<link rel=stylesheet href="<?php echo $config[template_url] ?>/style.css?1" type="text/css">
	<!--script language="JavaScript" src="/modules/stock/include/calendar/Calendar1-901.js"></script>
	<LINK REL="stylesheet" TYPE="text/css" HREF="/modules/stock/include/calendar/Calendar.css"-->

	<script type="text/javascript">
		<!-- // 

		function basket(id,addaction)
		{
			s = "basket.php?id="+id+"&action="+((addaction)?"add":"del")+"&rnd="+Math.random();
			refreshBasket(s); 
		}
		function confirmDelete()
		{
		var agree=confirm("<?php echo $lang['are_you_sure_you_want_to_delete'] ?>");
		if (agree)
			return true ;
		else
			return false ;
		}
		function confirmUserDelete()
		{
		var agree=confirm("<?php echo $lang['delete_user'] ?>");
		if (agree)
			return true ;
		else
			return false ;
		}
		
		function OnLoad(){
			try{
				<?if ($action){?>
					opener.location = opener.location.href;
				<?}?>
			}
			catch(e){}
		}
		
		function refreshBasket(url){
			if (url+""=="undefined"){
				url = "basket.php";
			}
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP")
		    xmlhttp.open("GET",url,false)
			xmlhttp.send()
			s = xmlhttp.ResponseText.replace("items_in_basket","<?=$lang['items_in_basket']?>");
			s = s.replace("basket_empty","<?=$lang['basket_empty']?>");
			document.getElementById("basket").innerHTML = s;
		}
		

		function wopen(url,name,width,height,center,resizable){
			var left = 150;
			var top = 50;
                           var s;
			if (typeof(width)=="undefined")width=500;
			if (typeof(height)=="undefined")height=500;
                       if (typeof(resizable)=="undefined")resizable="yes";
			if (typeof(center)!="undefined"){
				left = Math.round((screen.availWidth-width)/2);
				top = Math.round((screen.availHeight-height)/2);
			}
		   s = window.open(url+'&simple=1',name,'top='+top+',left='+left+',height='+height+',width='+width+',resizable='+resizable+',scrollbars=yes');
                     try{
                        s.focus();
                       }
                       catch(e){};
		}
		
		function wopen1(url,name){
			s = window.open(url+'&simple=1',name,'top=50,left=100,height=500,width=600,resizable=yes,scrollbars=yes');
			s.focus();
		}
		
		function wopen2(url,name){
			s = window.open(url+'&simple=1',name,'top=50,left=100,height=300,width=700,resizable=yes,scrollbars=yes');
			s.focus();
		}

		function openReport(url){
			s = window.open(url+'&simple=1','report','top=50,left=100,height=500,width=800,resizable=yes,scrollbars=yes,status=yes');
			s.focus();
		}		
		
		function openReports(){
		    //var oPopBody = oPopup.document.body;
			//oPopBody.innerHTML = document.getElementById("reports").innerHTML;
			//oPopup.show(window.event.clientX, window.event.clientY+5, 130, 70, document.body);
			wopen("reports.php?","reports");
		}
		
		function openImports(){
		    //var oPopBody = oPopup.document.body;
			//oPopBody.innerHTML = document.getElementById("reports").innerHTML;
			//oPopup.show(window.event.clientX, window.event.clientY+5, 130, 70, document.body);
			wopen("imports.php?","reports");
		}		
		// -->
	</script>
	
			<?if (!$simple && !$rmodule){?>
			<div id="reports" style='display:none'>
				<div dir=<?=$direction?> style="padding:5px;font-family:arial;font-size:9pt;height:100%;width:100%;border:solid 1 black;background-color:#FFFFCC">
				<a href=#></a>
				<a href=# style='width:100%;color:black' onclick='parent.openReport("rep_tazrim.php?")'><?=$lang["report_tazrim"]?></a>
				</div>
			</div>
			<table bgcolor=#E0DBDC border="0" cellspacing="2" cellpadding="2" width="100%"  style="background: rgba(218, 221, 222, 0.68)!important;">
				<form name=F method=get>
				<tr>
							<?php
							global $lang;
							if ($admin_privs == "yes" || $editForms == "yes")
							{
								// if the user has either admin or edit forms privs
								echo "{$lang['admin_menu_regular_options']} | ";
							} // end if
							?>

<!--lc 01/05/2016 remove td of icon folder_gear.png-->
							<!-- <td nowrap style='border:outset 2'>
                            <table border=0><tr><td align=center>
                            <img src=<?=$imgPath?>folder_gear.png hspace=3  border=0 >
                            </td>
                            </tr><tr>
                            <td nowrap align=center>
							<a href="javascript:wopen1('cp/main.php?service=credituserdata','tools')"><?php echo "$lang[admin_menu_tools]"; ?></a>
                            <br>
							<a href="javascript:wopen1('../stock/cppro/main.php?service=suppliers','imports')"><?=$lang['clients']?></a>
                            </td></tr></table>
							</td> -->

							<? if($GO_SECURITY->has_admin_permission($GO_SECURITY->user_id)){?>

							<?}?>
						
						
						<?php
						// admin options
						global $editForms, $viewLogs, $admin_privs;
						if ($editForms == "yes")
						{
							// if the user can edit forms
							echo "<div align=\"right\" class=\"small\">$lang[admin_menu_form_editor_options] | <a href=\"user_form_editor.php\">$lang[admin_menu_edit_user_form]</a> | <a href=\"template_editor.php\">$lang[admin_menu_edit_listings_template]</a></div>";
						} // end if
						
						if ($admin_privs == "yes")
						{
							// if the user has admin privs
							echo "<div align=\"right\" class=\"small\">$lang[admin_menu_admin_options] | <a href=\"user_edit.php\">$lang[admin_menu_edit_users]</a> | <a href=\"listings_edit.php\">$lang[admin_menu_edit_listings]</a> | <a href=\"add_user.php\">$lang[user_editor_new]</a>";
						} // end if
						
						if ($admin_privs == "yes" AND $config[moderate_listings] == "yes")
						{
							// if the user has admin privs and moderation is turned on
							echo " | <a href=\"moderation_queue.php\">$lang[admin_listings_moderation_queue]</a>";
						}
						
						if ($viewLogs == "yes")
						{
							echo " | <a href=\"log_view.php\">$lang[log_view_activity_logs]</a>";
						} // end if
						
						?>
				<td>&nbsp;</td>
				<td width=99% >
				<fieldset><legend><?=$lang['search_conditions']?></legend>
				
					<?=$lang['transactions']?> <?=$lang["from"]?> <input size=6 name=sDate id=sDate value="<?=$sDate?>">
					<img align=absmiddle style="cursor:hand" src='<?=$imgPath?>calendar.gif' onclick='ShowCalendar1("F.sDate")
					'>
					&nbsp;
					 <?=$lang["to"]?> <input size=6 name=eDate id=eDate value="<?=$eDate?>">
					<img align=absmiddle style="cursor:hand" src='<?=$imgPath?>calendar.gif' onclick='ShowCalendar1("F.eDate")
					'>
					&nbsp;
					<?=$lang["client_num"]?>
					<input type="text" size=2 name=cnum  value="<?=$cnum?>">
					<?=$lang["to_several"]?>
					<input type="text" size=2 name=cnum1  value="<?=$cnum1?>">
					<?=$lang["client_name"]?>
					<input type="text" size=6 name=cname  value="<?=$cname?>">
					&nbsp;
					<input style="cursor:hand;padding:0 0 0 15;background-image:url(<?=$imgPath?>refresh.gif);;background-repeat:no-repeat;background-position:left top" type=submit value='<?=$lang["show"]?>'>
					<div>
						<?=$lang["clients"]?>
					<select name=clients>
					    <option value="0" <?=!$clients?"selected":""?>><?=$lang["actives"]?>
					    <option value="1" <?=$clients=="1"?"selected":""?>><?=$lang["deleted"]?>
					    <option value="2" <?=$clients=="2"?"selected":""?>><?=$lang["are_not_defined"]?>
					    <option value="3" <?=$clients=="3"?"selected":""?>><?=$lang["all_points1"]?>
					</select>
					&nbsp;
						<?=$lang["only_with_debt"]?><input type=checkbox value=1 name=only <?=($only)?"checked":""?>>
					&nbsp;
						<?=$lang["one_page"]?>
					<input type=checkbox name=showall value=1 <?=($showall)?"checked":""?>>
					
					</div>
					<hr>
                    <? if ($HasWritePermissions) {?>
                    <input type=button id=ZERO onclick=zeroHov() value="<?=$lang["reset_debt_to_certain_customer"]?>" style="cursor:hand;padding:0 0 0 10;background-image:url();background-repeat:no-repeat;background-position:left top" >
                    <?}?>
					<?if ($xlsfilename){?>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type=button value=" Excel " style="cursor:hand;padding:0 0 0 10;background-image:url(<?=$imgPath?>excel.gif);background-repeat:no-repeat;background-position:left top" onclick='location="../../tmp/<?=$xlsfilename?>_<?=$userID?>.xls?rnd=<?=rand()?>"'>
					<?}?>
					<input style="cursor:hand;padding:0 0 0 10;background-image:url(<?=$imgPath?>printrep.gif);background-repeat:no-repeat;background-position:left top" type=button onclick='wopen2("print.php?")' value='<?=$lang["print"]?>'>
					<input style="cursor:hand;padding:0 0 0 10;background-image:url(<?=$imgPath?>printrep.gif);background-repeat:no-repeat;background-position:left top" type=button onclick='wopen2("print.php?money=1")' value='<?=$lang["print_with_balance"]?>'>
					</fieldset>
				</td>
				
				<td id=basket width=200>
	
					<!--iframe id=basket src=basket.php style='width:200px;height:40' frameborder=0></iframe-->
				</td>
				</tr>
				</form>
			</table>
			<p>
			<?}elseif ($simple){?>
			<style>
			body {background-color:buttonface}
			</style>
			<script>
			document.body.onload = OnLoad;
			</script>
			<?}?>
			<table width="100%" border="0" cellspacing="3" cellpadding="3">
				<tr>
					<td width="100%" valign="top">
					
					
					<!-- A Separate Layer for the Calendar -->
<!-- Make sure to use the name Calendar for this layer -->
<SCRIPT Language="Javascript" TYPE="text/javascript">
Calendar.CreateCalendarLayer(10, 275, "");
</SCRIPT>