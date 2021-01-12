close_enable=true;
function FrontFrame(content,show_header,height)
{
    header='';
    if(height==null) height='600px';
    if(show_header==1) {
        header+='<div style="text-align:right;padding-right:8px;background:url(images/line_gray.png) repeat-x #939393;">';
        header+='<a style="color:#FFFFFF;font-weight:bold; font-family:Latha; font-size:14pt; text-decoration:none; :-10px;" href="javascript:FrontFrameClose();">X</a></div>';
        header+='<div style="overflow-y: scroll; height:'+height+'; padding: 10px;">';
    }
    $('#popupFrame').css('visibility','visible');
    $('#popupContent').html(header+content);
}

function FrontFrameWait()
{
    close_enable=false;
    $('#popupFrame').css('visibility','visible');
    $('#popupContent').html('<h2>...Please wait...</h2>');
}

function FrontFrameClose()
{
    if(close_enable==true) $('#popupFrame').css('visibility','hidden');
}

function CardAdd()
{		
	var user_name11=$('#popupFrame').find('#user_name').val();
    $.post('functions.php',{
    	UseFunc:'CardAdd',
        ID:$('#popupFrame').find('#CardAddID').val(),
        BalanceMax:$('#CardAddBalanceMax').val(),
        Paid:$('#Paid').val(),
         user_name:$('#user_name').val(),
    },function(content){
            CardSearch(true);
            close_enable=true;
            FrontFrameClose();
            return;
        });
    FrontFrameWait();
}

function CardAddFrame()
{
    FrontFrameWait();
    $.post('functions.php',{UseFunc:'CardAddFrame'},function(content){
        close_enable=true;
            FrontFrame(content,true,'250px');
            return;
    });
}

function CardSearch(clearSearch)
{
    $('#divMain').html('<img src="images/loader.gif"/>');
    if(clearSearch==true){
        $.post('functions.php',{UseFunc:'CardGet'},function(content){
            $('#divMain').html(content);
            return;
        });
    }
    else{
        $.post('functions.php',{UseFunc:'CardGet',
            ID:$('#divMenu').find('#ID').val(),
            BalanceMax:$('#divMenu').find('#BalanceMax').val(),
            BalanceCurrent:$('#divMenu').find('#BalanceCurrent').val(),
            FromYear:$('#divMenu').find('#FromYear').val(),
            FromMonth:$('#divMenu').find('#FromMonth').val(),
            ToYear:$('#divMenu').find('#ToYear').val(),
            UserNameP:$('#divMenu').find('#UserName_p').val(),/*sk 18/02/2016*/
            ToMonth:$('#divMenu').find('#ToMonth').val()},function(content){
                $('#divMain').html(content);
                return;
            });
    }
}

function SearchReset(){
    $('#divMenu').find('#ID').val('');
    $('#divMenu').find('#BalanceCurrent').val('');
    $('#divMenu').find('#UserName_p').val('');
    CardSearch();
}

function CardInfo(id){
	
    FrontFrameWait();
    $.post('functions.php',{UseFunc:'CardInfo', ID:id},function(content){
            close_enable=true;
            FrontFrame(content,true,'250px');
            return;
        });
}

function CardChargeButton(){
    $('#popupFrame').find('#ChargeRowInfo').css('display','block');
    $('#popupFrame').find('#ChargeRow').html($('#popupFrame').find('#ChargeRow2').html());
}

/*sk 21/02/2016*/
function CardUpdateUser(){
		var id=$('#popupFrame').find('#ID').html();
		//$("#card_user").style.display="";
		  $('#popupFrame').find('#card_user').css('display','');
		  //update_button
		  $('#popupFrame').find('#update_button').css('display','');

}

function UpdateUserNameCard(){
	
	var id=$('#popupFrame').find('#ID').html();
	var new_name=$('#popupFrame').find('#card_user').val();
	 $.post('functions.php',{UseFunc:'CardUpdateUser', ID:id, user:new_name},function(content){
            CardSearch();
            CardInfo(id);
            return;
        });
}

function CardCharge(){
    var id=$('#popupFrame').find('#ID').html();
    $.post('functions.php',{UseFunc:'CardCharge', ID:id, Charge:$('#popupFrame').find('#Charge').val(), Paid:$('#popupFrame').find('#Paid').val()},function(content){
            CardSearch();
            CardInfo(id);
            return;
        });
}
function CardZeroButton(){
	var id=$('#popupFrame').find('#ID').html();
	 $.post('functions.php',{UseFunc:'CardZero', ID:id,yitra:$('#popupFrame').find('#BalanceCurrent').html()},function(content){
            CardSearch();
            CardInfo(id);
            return;
        });
}
function CardRemove(id,txt)
{
    window.event.cancelBubble = true;
    event.stopPropagation();
    if(confirm(txt)){
        FrontFrameWait();
        $.post('functions.php',{UseFunc:'CardRemove',ID:id},function(content){
                CardSearch(true);
                close_enable=true;
                FrontFrameClose();
                return;
            });
    }
}

function CardChargeCheck(){
    
}

function InputReset(text){
    if(text==$(event.target).val()) $(event.target).val('');
}


