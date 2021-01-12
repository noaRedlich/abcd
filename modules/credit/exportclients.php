<?php
require "excelparser.php";
include("include/common.php");
$simple=1;
$page_subtitle = $lang['admin_menu_imports'];


include("../stock/include/functions.php");
if (!loginCheck('User'))exit;
global $action, $id, $cur_page, $lang, $conn, $config;
include("$config[template_path]/admin_top.html");

if ($action!="do")
{
    echo $lang['ok_to_start_the_process'];

    exit;
}
print"<pre dir=rtl>";

$sql = "insert into listingsSuppliers (clientnum,suppliername,businessnum,address,postadress,cellphone,phone,status,isclient, user_id,comment,allowhakafa)
    select creditornum, creditorname,teudatzehut,address,address,cellphone,homephone,status,1,$userID,description,1 from creditors c
    where not exists (select id from listingsSuppliers where  clientnum = c.creditornum)";

DBQuery($sql);
echo $conn->Affected_Rows()." לקוחות חדשים\n";

if ($update)
{
    $sql = "update listingsSuppliers l set
            businessnum = (select teudatzehut from creditors where creditornum = l.clientnum limit 1)
            ,clientnum = (select clientnum from creditors where creditornum = l.clientnum limit 1)
            ,suppliername  = (select creditorname from creditors where creditornum = l.clientnum limit 1)
            ,address = (select address from creditors where creditornum = l.clientnum limit 1)
            ,postadress = (select address from creditors where creditornum = l.clientnum limit 1)
            ,cellphone =(select cellphone from creditors where creditornum = l.clientnum limit 1)
            ,phone =(select homephone from creditors where creditornum = l.clientnum limit 1)
            ,status = (select status from creditors where creditornum = l.clientnum limit 1)
            ,comment = (select description from creditors where creditornum = l.clientnum limit 1)
            ,user_id = $userID
            ,isclient = 1
            ,allowhakafa = 1
    where clientnum in (select creditornum from creditors)";

    DBQuery($sql);
    echo $conn->Affected_Rows()." לקוחות עדכנו";
}
?>


<?
function uc2html($str)
{
    $ret = '';
    for( $i=0; $i<strlen($str)/2; $i++ )
    {
        $charcode = ord($str[$i*2])+256*ord($str[$i*2+1]);
        $ret .= '&#'.$charcode;
    }
    return $ret;
}

function get( $exc, $data )
{
    switch( $data['type'] )
    {
        // string
        case 0:
            $ind = $data['data'];
            if( $exc->sst[unicode][$ind] )
                return uc2html($exc->sst['data'][$ind]);
            else
                return $exc->sst['data'][$ind];

        // integer
        case 1:
            return (integer) $data['data'];

        // float
        case 2:
            return (float) $data['data'];

        case 3:
            return gmdate("m-d-Y",$exc->xls2tstamp($data[data]));

        default:
            return '';
    }
}

include("$config[template_path]/admin_bottom.html");
$conn->Close(); // close the db connection
?>