<?php
include("include/common.php");
include("include/functions.php");
$sql=
			"select 1 as client, 3 as sortorder, c.id,ClientNum, SupplierName as CreditorName, Phone as HomePhone, CellPhone,Address,
			round(sum((case when paymid=7 then hakafasum 
			when paymid = 1 then CashSum
			when paymid = 2 then ChequeSum
			when paymid = 5 or paymid = 8 then creditcardsum 
			else CouponSum end)*(case when isrefund=0 and IsDebtPayment=0 then 1 else 0 end)),2) as Hov,
    			round(sum((case when paymid=7 then hakafasum else 0 end)* (case when isrefund=1 or IsDebtPayment=1 then 1 else 0 end)),2) as Hakafa,
    			round(sum((case when paymid=7 then 0
			when paymid = 1 then CashSum
			when paymid = 2 then ChequeSum
			when paymid = 5 or paymid = 8 then creditcardsum			
			else CouponSum end)*(case when isrefund=1 or IsDebtPayment=1 then 1 else 0 end)),2) as Zikui
			from listingsSuppliers c,transactionpayments p,transactions t where
                        ifnull(c.ClientNum,'')<>'' and
			((c.ClientNum = p.CouponNumber and paymid=3)  or (p.paymid=7 and p.custid = c.ClientNum) or (t.custid = c.ClientNum and t.IsDebtPayment=1) ) and
			p.trans_id = t.id and t.user_id = 570  and ifnull(tranErechDate,trandate) between '2003-05-15' and '2014-11-24' 
			and c.user_id = 570 
			and c.status=1
			
			group by c.id order by sortorder,ClientNum+0";
?>
