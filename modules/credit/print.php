<?php
$simple=1;
include("include/common.php");
$page_subtitle = $lang['customer_list'];
include("include/functions.php");
if (!loginCheck('User'))exit;
global $action, $id, $cur_page, $lang, $conn, $config;
include("$config[template_path]/admin_top.html");
?>
<style>
.tableHead2{border-bottom:solid 1 black;border-left:solid 1 black}
img{display:none}
.none{display:none}
<?if (!$money){?>
.money{display:none}
<?}?>
body{background-color:white}
</style>
<body>
<div style='border-right:solid 1 black'>
<script>
document.write(window.opener.document.all.REPORT_TAB.outerHTML.replace(/<a /gi,"<as ").replace(/<u>/gi,"<us>").replace(/onmouse/gi," ").replace(/onclick/gi," "));
print();
</script>
</div>
</body>