<?php
class DecryptedData{
	public $cardholderName;
	public $track1;
	public $track2;
	public $track3;
	private function __init(){
		$this->cardholderName="";
		$this->track1="";
		$this->track2="";
		$this->track3="";
	}
	public static function __staticinit(){		
	}
	public static function constructor__29f78fbf($cardholderName,$track1,$track2,$track3){
		$me=new self();
		$me->__init();
		$me->cardholderName=$cardholderName;
		$me->track1=$track1;
		$me->track2=$track2;
		$me->track3=$track3;
		return $me;
	}
}
DecryptedData::__staticinit();
?>