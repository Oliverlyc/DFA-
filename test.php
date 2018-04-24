<?php 
require_once 'badwords.class.php';
	$badwords = ['王八蛋','兴奋剂'];
	$DFA = new DFA($badwords);
	$DFA->searchKey('王八蛋');
 ?>