<?php
class Payment{
	public $_db,
			$_payinfo;
	
	public function __construct($details = array()){
		$this->_db = DB::getInstance();
		$this->_payinfo = $details;
	}
	
	public function get_student_payments($id){
		return array_reverse($this->_db->get2('payment',array(array('student_id','=',$id),array('payment_type','=','monthly','AND')))->result());
	}
	
	public function check_paid($month,$stud_id){
		
		if(count($this->_db->get2('payment',array(
							array('payment_month','=',$month),
							array('payment_type','=','monthly','AND'),
							array('payed','=',true,'AND'),
							array('student_id','=',$stud_id,'AND')))->result())!=0)
		{
			return true;
		} 
		return false;
	}
	
	public function check_deposit_paid($stud_id){
		if(count($this->_db->get2('payment',array(
							array('payment_type','=','deposit'),
							array('student_id','=',$stud_id,'AND')))->result())!=0)
		{
			return true;
		}else{
			return false;
		}
	}
	
	public function check_all_paid($stud_id){
		if(count($this->_db->get2('payment',array(
							array('student_id','=',$stud_id),
							array('payment_type','=','monthly','AND'),
							array('payed','=',false,'AND')
							))->result())!=0)
		{
			return false;
			if(!$this->_db->update('users',$stud_id,array('debt' => true),'ID')){
				echo "Couldn't update status";
			}
		}
		if(!$this->_db->update('users',$stud_id,array('debt' => false),'ID')){
				echo "Couldn't update status";
		}
		return true;
	}
	
	public function check_pend_payment($month,$stud_id){
		
		if(count($this->_db->get2('payment',array(
							array('payment_month','=',$month),
							array('payment_type','=','monthly','AND'),
							array('payed','=',false,'AND'),
							array('student_id','=',$stud_id,'AND')))->result())==0)
		{
			return false;
		}
		return true;
	}
	
	public function pay($month,$stud_id,$receipt_no){
		$payment_id = $this->_db->get2('payment',array(
							array('payment_month','=',$month),
							array('payment_type','=','monthly','AND'),
							array('payed','=',false,'AND'),
							array('student_id','=',$stud_id,'AND')))->first()->payment_id;
		if(!$this->_db->update('payment',$payment_id,array('payed' => true,'receipt_no' => $receipt_no),'payment_id')){
			echo('Error paying');
			return false;
		}
		return true;
	}
	
	public function get_room($room_id){
		return $this->_db->get('room',array('room_id','=',$room_id))->first();
		
	}
	
	public function activate_payment_tracker($stud_id,$room_id){
		$payment_amt =$this->get_room($room_id)->room_price;
		if(!$this->_db->query("
			CREATE EVENT `test` 
			ON SCHEDULE EVERY 1 MONTH 
			STARTS CURRENT_TIMESTAMP 
			ON COMPLETION NOT PRESERVE ENABLE 
			DO BEGIN 
				IF (SELECT COUNT(*) FROM payment WHERE student_id=".$stud_id." AND payment_month=MONTH(CURRENT_DATE())=0) THEN 
					INSERT INTO `payment`(`payment_type`,`payment_regisdate`,`payment_month`,`student_id`,`payment_amt`,`payed`) 
					VALUES ('monthly',CURRENT_DATE(),MONTH(CURRENT_DATE()),".$stud_id.",".$payment_id.",FALSE); 
				END IF; 
			END
		")){
			echo 'Event not entered';
			
		}
		
		
		
	}
	
	public function print_slip($id){
		$user = $this->_db->get('users',array('ID','=',$id))->first();
		$lastpay = $this->_db->get('payment',array('student_id','=',$id))->first();
		 $slip ='
		<!DOCTYPE HTML>
		<html>
		<head>
			<title>Hostel Admission Slip</title>
				<link rel="stylesheet" type="text/css" href="css/Print.css">
				<script type="text/javascript">
					function Redirect()
					{
						window.location="Payments.php";
					}
				</script>

		</head>
		<body>
			<div id="header">
				<img src="image/project header.jpg" width="1024" height="100" />
			</div>
		
			<div id="s">
			<table id="studentDetails">
				<tr><td>Student ID </td><td>:</td><td>'.$user->ID.'</td><tr>
				<tr><td>Name </td><td>:</td><td>'.$user->Name.'</td><tr>
				<tr><td>Branch </td><td>:</td><td>Multimedia College</td><tr>
				<tr><td>Faculty </td><td>:</td><td>'.$user->School.'</td><tr>
				<tr><td>Major </td><td>:</td><td>'.$user->Course.'</td><tr>
			</table>
		
			<div id="Payment">
				<h3>Deposit Payment</h3>
				<table id="PayTable">
					<tr><td>Payment ID</td><td>Date</td><td>Payment Type</td><td> Amount</td><td>Student Name</td><td>Student ID</td></tr>
					<tr><td>'.$lastpay->payment_id.'</td><td>'.$lastpay->payment_regisdate.'</td><td>'.$lastpay->payment_type.'</td><td>RM'.$lastpay->payment_amt.'</td><td>'.$user->Name.'</td><td>'.$user->ID.'</td></tr>
				</table>
			</div>
			
			<div id="terms&conditions">
			</div>
		
			<div id="footer">
				<div id="StudentSignature">
					<hr>
					Student Signature
				</div>
				<div id="StudentSignature">
					<hr>
					Hostel Management Signature
				</div>
				<div id="DateBox">
					Date : '.TheDate().'
				</div>
			</div><script type="application/javascript">window.onload=function(){setTimeout(Redirect(),1000000);window.print()}</script></body></html>
			<br><br>
			<hr>
			<br><br><br><a href="Payments.php">Click To Return</a>
			';
	return $slip;	
			
	}
	
	public function add_payment(){
		if(!$this->_db->insert('payment',$this->_payinfo)){
			echo 'Error Occurred';
		}
	}
	
	public function delete_payment($stud_id){
		if(!$this->_db->delete('payment',array('payment_id','=',$stud_id))){
			throw new Exception('Error deleting = {$stud_id}');
		}
	}
	
	public function get_all_payment(){
		return array_reverse($this->_db->get('payment')->result());
	}
	
	public function print_all_payment($GroupNo,$id=null){
		$user = new User();
		$GroupNo = $user->data()->GroupNo;
		if(!$id && $GroupNo == 3){
			$payments = $this->get_all_payment();
		}else{
			$payments = $this->get_student_payments($id);
		}
		
		if($id && $GroupNo == 0){
			$payments = $this->get_student_payments($id);
		}
		
		if($payments){
			echo '<table id="data">';
				$people = '';
				echo '<tr><td> Payment ID</td>
						  <td> Month</td>
						  <td> Student ID</td>
						  <td> Payment Amount</td>
						  <td> Paid Status</td>
						  <td> Receipt No. </td>
						  <td> Admin Aprroved ID</td>
						  </tr>';
				foreach($payments as $payment){
					$id = $payment->student_id;
					echo '<tr>'.'<td>'.$payment->payment_id.'</td>';
					echo '<td>'.$payment->payment_month.'</td>';
					echo '<td>'.$payment->student_id.'</td>';
					echo '<td>RM'.$payment->payment_amt.'</td>';
					echo '<td>';
						echo ($payment->payed==1)? 'Paid' : '<b>Not Paid</b>';
					echo '</td>';
					echo '<td>'.$payment->receipt_no.'</td>';
					
					if($GroupNo == 3 && $payment->admin_id==NULL){
						echo'<form action method="GET">';
							echo '<td bg>
								<input type="hidden" name="Approve" value="'.$payment->payment_id.'">
								<input type="submit" value="Approve">
								</td>';
						echo'</form>';
					}else if($GroupNo == 1){
						echo '<td>Not Verified</td>';
					}else{
						echo '<td>'.$payment->admin_id.'</td>';
					}
					IF($GroupNo == 3){
						echo'<form action method="GET">';
							echo '<td><input type="hidden" name="Delete" value="'.$payment->payment_id.'">';
							echo '<input type="submit" value="Delete"></td>';
						echo'</form>';
					}
				}
					echo '</tr>';
					echo '</table>';
					return true;
		}
		return false;
	}
	
	public function verify_payment($admin_id,$payment_id){
		if(!$this->_db->update('payment',$payment_id,array('admin_id' => $admin_id),'payment_id')){
			echo 'Verify Payment error occurred';
			return false;
		}
		echo 'Payment approved';
		return true;
	}
	
}
?>