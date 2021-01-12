<?php global $config, $lang; ?>

<?
include("$config[template_path]/admin_top2.php");
if (!$simple && !$rmodule){
?>

<div id="reports" style='display:none'>
	<div dir=<?=$direction?> style="padding:5px;font-family:arial;font-size:9pt;height:100%;width:100%;border:solid 1 black;background-color:#FFFFCC">
		<a href=#></a>
		<a href=# style='width:100%;color:black' onclick='parent.openReport("rep_tazrim.php?")'><?=$lang["report_tazrim"]?></a>
	</div>
</div>
<table bgcolor=#E0DBDC border="0" cellspacing="2" cellpadding="2" width="100%">
	<form name=F method=get>
		<tr>
		<?php
			global $lang;
			if ($admin_privs == "yes" || $editForms == "yes")
			{
			// if the user has either admin or edit forms privs
				echo "$lang[admin_menu_regular_options] | ";
			} // end if
		?>
			<td class="topHeader111" nowrap style='border:outset 2' align=center>
				<img src=<?=$imgPath?>folder_gear.png hspace=3 align=absmiddle border=0 ><br>
				<a href="javascript:wopen('cp/main.php?service=shaonuserdata','tools')">
					<?php echo "$lang[admin_menu_tools]"; ?></a>
				<br>
				<a href="javascript:wopen('imports.php?simple=1','imports')">
					<?=$lang['admin_menu_imports']?></a>

			</td>

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
			<td width=99% align=center class="topHeader">
				<fieldset><legend><?=$lang['search_conditions']?></legend>

					<?=$lang["from"]?> <input size=7 name=sDate id=sDate value="<?=$sDate?>">
					<img align=absmiddle style="cursor:hand" src='<?=$imgPath?>calendar.gif' onclick='ShowCalendar("F.sDate")'>
					<?=$lang["to"]?> <input size=7 name=eDate id=eDate value="<?=$eDate?>">
					<img align=absmiddle style="cursor:hand" src='<?=$imgPath?>calendar.gif' onclick='ShowCalendar("F.eDate")'>
					&nbsp;
					<?=$lang['in_point']?>
					<select name=stock>
						<option value=""><?=$lang["all_points"]?>
							<?while(!$stocks->EOF){?>
						<option value="<?=$stocks->fields["ID"]?>" <?=($stock==$stocks->fields["ID"])?"selected":""?>><?=$stocks->fields["StockName"]?>
							<?$stocks->MoveNext();
							}?>
					</select>

					<input style="cursor:hand;padding: 0px 5px;" type=submit value='<?=$lang['submit']?>'>

					<?if ($xlsfilename){?>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=button value=" Excel " style="cursor:hand;padding: 0px 5px;" onclick='location="../../tmp/<?=$xlsfilename?>_<?=$userID?>.xls?rnd=<?=rand()?>"'>
						<input type="button" value="הדפס" style="cursor:hand;padding: 0px 5px;" onclick="printAllWorkerReport();"/>
					<?}?>

				</fieldset>
			</td>

			<td id=basket width=200 class="topHeader">

				<!--iframe id=basket src=basket.php style='width:200px;height:40' frameborder=0></iframe-->
			</td>
		</tr>
	</form>
</table>
<p>

<?} elseif ($simple){?>
		<style>
			body {background-color:buttonface}
		</style>
		<script>
			try{
				document.body.onload = OnLoad;
			}
			catch(e){}
		</script>
	<?}?>
	
	<script>
	/*sk 20/01/16 this function show or hide column depending on the user's selection*/
		function printAllWorkerReport(){
	
			 
			  showHideColumn("topHeader111",'none');
			  document.getElementById("divHeader").style.display = "none";
			  document.getElementById("titlenochechut").style.display = "";
		      showHideColumn("topHeader",'none');
		      if(document.getElementById("printTotalDays").value==0){
               showHideColumn('td_total_days','none');
            }
		    
		    if(document.getElementById("printWorkerNum").value==0){
               showHideColumn('td_worker_num','none');
            }
			if(document.getElementById("printWorkerName").value==0){
               showHideColumn('td_worker_name','none');
            }
            if(document.getElementById("printRegularHours").value==0){
               showHideColumn('td_normal_hours','none');
            }
             if(document.getElementById("printOvertime").value==0){
               showHideColumn('td_overtime_hours','none');
            }
            if(document.getElementById("printTotalDays").value==0){
               showHideColumn('td_total_days','none');
            }
            if(document.getElementById("printPayForHours").value==0){
               showHideColumn('td_pay_for_hour','none');
            }
            if(document.getElementById("printTotalHours").value==0){
               showHideColumn('td_total_hours','none');
            }
			window.print();
			
		showHideColumn("topHeader",'');
		document.getElementById("titlenochechut").style.display = "none";
			if(document.getElementById("printWorkerNum").value==0){
               showHideColumn('td_worker_num','');
            }
            if(document.getElementById("printWorkerName").value==0){
               showHideColumn('td_worker_name','');
            }
            if(document.getElementById("printRegularHours").value==0){
               showHideColumn('td_normal_hours','');
            }
            if(document.getElementById("printOvertime").value==0){
               showHideColumn('td_overtime_hours','');
            }
            if(document.getElementById("printTotalDays").value==0){
               showHideColumn('td_total_days','');
            }
            if(document.getElementById("printPayForHours").value==0){
               showHideColumn('td_pay_for_hour','');
            }
            if(document.getElementById("printTotalHours").value==0){
               showHideColumn('td_total_hours','');
            }
           if(document.getElementById("printTotalDays").value==0){
               showHideColumn('td_total_days','');
            }
			document.getElementById("divHeader").style.display = "";
			showHideColumn("topHeader111",'');
		}
		 
       function showHideColumn(className,display){
     $("."+className).css('display',display);
      /* 	var elements = document.getElementsByClassName(className);
       	
		    for (var i = 0; i < elements.length; i++){
		    	
		        elements[i].style.display = display ;
		    }*/
        	
        }
	</script>
<table width="100%" border="0" cellspacing="3" cellpadding="3">
	<tr>
		<td width="100%" valign="top">