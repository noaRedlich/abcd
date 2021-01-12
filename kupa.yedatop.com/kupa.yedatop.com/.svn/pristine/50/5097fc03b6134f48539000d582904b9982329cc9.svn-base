
 <? echo "$def_style"; ?>



  <script language="JavaScript">
    function checkemail(email)
     {
	if( !(email.indexOf("@")>1 && email.indexOf(".")>1) && email.length!=0 )
	{
		alert("<? echo"$def_mail_error"; ?>");
		document.forms[0].mail.value="";
		document.forms[0].mail.focus();
	}
     }
  </script>

 <table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>

   <td valign="top" align="left" width="100%">


  <tr>
   <td width="100%" height="8" align="left" valign="top">

    <table width="100%" cellspacing="0" cellpadding="2" border="0">
    <tr>
	<td valign=center align=center width=25 bgcolor=<? echo"$def_status_color";?> height=17>
       <a class=normal  href="./"><img src=img/home.gif border=0></a>

      </td>	
	   <form name=LANG method=post>
	        <td valign=center align=center width=25 bgcolor=<? echo"$def_status_color";?> height=17>
	  
	  			<?php
			$languages = $GO_LANGUAGE->get_languages();
			$dropbox = new dropbox();
			$dropbox->add_arrays($languages, $languages);
			$dropbox->print_dropbox("SET_LANGUAGE", $GO_LANGUAGE->language,' onchange="document.LANG.submit();"');
			?>
		</td></FORM>
      <td valign=center width=100% align=left bgcolor=<? echo"$def_status_color";?> height=17>

<!--  $incomingline is a content generated for the status line, don't remove it! -->

       <font color=<? echo "$def_status_font_color"; ?>><? echo "$incomingline"; ?><br></font>
      </td>
     </tr>
    </table>

   </td>
  </tr>



  <tr>
   <td width="100%">



   </td>
  </tr>


     <tr>
      <td valign=center align=left width=100% bgcolor=<? echo "$def_help_background"; ?>>


<!-- HEADER END -->
