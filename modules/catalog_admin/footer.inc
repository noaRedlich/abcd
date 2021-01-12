

<!-- FOOTER -->

     <td valign=top align=left width="20%">
      <br>
       <table width="100%" cellspacing=0 cellpadding=10 border=0>
        <tr>
         <td valign=top align=left>

<? if($enterlink <> "true") { ?>
    &nbsp;<img src=img/strelka.gif>&nbsp;<a class=normal  href=firms.php?REQ=auth><? echo "$def_admin_checkreg_button";?></a><br><br>
    &nbsp;<img src=img/strelka.gif>&nbsp;<a class=normal  href=offers.php?REQ=auth><? echo "$def_admin_offersedit_button";?></a><br><br>
    &nbsp;<img src=img/strelka.gif>&nbsp;<a class=normal  href=edlocations.php><? echo "$def_admin_locations_button";?></a><br><br>

<?
if ($def_states_allow == "YES") {
    echo "&nbsp;<img src=img/strelka.gif>&nbsp;<a class=normal  href=edstates.php>$def_admin_states_button</a><br><br>";
}?>
    &nbsp;<img src=img/strelka.gif>&nbsp;<a class=normal  href=edcat.php><? echo "$def_admin_edcat_button";?></a><br><br>
    &nbsp;<img src=img/strelka.gif>&nbsp;<a class=normal  href=mailer.php><? echo "$def_admin_mailer_button";?></a><br><br>
    &nbsp;<img src=img/strelka.gif>&nbsp;<a class=normal  href=log.php?REQ=auth><? echo "$def_admin_log_button";?></a><br><br>
    &nbsp;<img src=img/strelka.gif>&nbsp;<a class=normal  href=rfqstats.php?REQ=auth>RFQ History</a><br><br>
    &nbsp;<img src=img/strelka.gif>&nbsp;<a class=normal  href=rfqstats.php?REQ=auth&mode=1>Orders History</a><br><br><br><br>
<? } ?>

          </font>
         </td>
        </tr>
       </table>

    </td>
   </tr>
  </table>

  </td></tr>



 </table>

<br>



<? 


//require the footer file
if ($simple)
	require($GO_THEME->theme_path."simple_footer.inc");
else
	require($GO_THEME->theme_path."footer.inc");


?>

