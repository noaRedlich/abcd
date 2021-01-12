<?
include( "psxlsgen.php" );

$myxls = new PhpSimpleXlsGen();
$myxls->totalcol = 2;
$myxls->InsertText( "Erol" );
$myxls->filename = "test";
$myxls->InsertText( "Ozcan" );
$myxls->InsertText( "This text should be at (3,0) if header was used, otherwise at (1,0)" );
$myxls->ChangePos(7,0);
$myxls->InsertText( "You must pay" );
$myxls->InsertNumber( 20.48 );
$myxls->WriteText_pos(4,2, "USD to use this class :-))" );         // hidden costs :-)) 
$myxls->SendFile();   
//$myxls->SaveFile();   
?>