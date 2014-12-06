<?php
function getNavBar($group_id){
	
	$options='';
	
	switch($group_id){
	case 1:
		 $options = '<li><a href="Message.php">Message</a></li>';
		 $options .= '<li><a href="Payments.php">Payments</a></li>';
		 break;
		 
	case 2:
		 $options = '<li><a href="Message.php">Message</a></li>';
		 $options .= '<li><a href="Database.php">Database</a></li>';
		 $options .= '<li><a href="Block.php">Block</a></li>';
		break;
		
	case 3:
		 $options = '<li><a href="Message.php">Message</a></li>';
		$options .= '<li><a href="Database.php">Database</a></li>';
		$options .= '<li><a href="Block.php">Block</a></li>';
		$options .= '<li><a href="AddStaff.php">Add Staff</a></li>';
		break;
	}
	
	return $options;
}

?>