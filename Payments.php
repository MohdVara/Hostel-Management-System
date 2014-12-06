<?php
require_once('core/init.php');
$Slip = "Print.php";
$Writer = fopen($Slip, 'w');
$content ='';
fwrite($Writer,$content);
fclose($Writer);
$options ='';
$title = 'Payment';
$user = new User();
$payment = new Payment();
if(!$user->isLoggedIn()){
	Redirect::to('login.php');
}

if(Input::exists()){
	if(Token::check(Input::get('token'))){
			$displayerror = '';
		    $validate = new Validate();
		  $validation = $validate->check($_POST,array(
				
				'PaidMonth' => array(
					'required' => 'true',
				),
				
				
				'Amount' => array(
					'required' => 'true'
				),
				
				'ReceiptNo' => array(
					'required' => 'true',
					'numbered' => 'true'
				),
				
				'TypeOfPay' => array(
					'required' => 'true'
				)
			));
			if($validation->passed()){
			
				$payment = new Payment();
					$room_price = $payment->get_room($user->data()->RoomID)->room_price;
					If(!$payment->check_paid(Input::get('PaidMonth'),$user->data()->ID)){
						//echo 'Not yet paid';
						if(!$payment->check_pend_payment(Input::get('PaidMonth'),$user->data()->ID)){
							//echo '<br>Paying';
							$add_payment = new Payment(array(
								'payment_type' => Input::get('TypeOfPay'),
								'payment_regisdate' => TheDate(),
								'payment_month' => Input::get('PaidMonth'),
								'student_id' => $user->data()->ID,
								'payment_amt' => $room_price,
								'receipt_no' => Input::get('ReceiptNo'),
								'payed' => true
							));
							$add_payment->add_payment();
							$month_number = (Input::get('PaidMonth')!=12)? Input::get('PaidMonth') + 1 : 1;
							$add_payment = new Payment(array(
								'payment_type' => Input::get('TypeOfPay'),
								'payment_regisdate' => TheDate(),
								'payment_month' => $month_number,
								'student_id' => $user->data()->ID,
								'payment_amt' => $room_price,
								'payed' => false
							));
							$add_payment->add_payment();
						$payment->check_all_paid($user->data()->ID);
						}else{
							//echo 'There is an entry';
							if(!$payment->pay(Input::get('PaidMonth'),$user->data()->ID,Input::get('ReceiptNo'))){
								echo 'An error occurred';
							}
							$add_payment = new Payment(array(
								'payment_type' => Input::get('TypeOfPay'),
								'payment_regisdate' => TheDate(),
								'payment_month' => Input::get('PaidMonth')+1,
								'student_id' => $user->data()->ID,
								'payment_amt' => $room_price,
								'payed' => false
							));
						}
					}
					
					if(Input::get('TypeOfPay') == 'deposit'){
						$payment = new Payment();
						$Slip = "Print.php";
						$Writer = fopen($Slip, 'w');
						$content =$payment->print_slip($user->data()->ID);
						fwrite($Writer,$content);
						fclose($Writer);
						$payment->activate_payment_tracker($user->data()->ID,$user->data()->RoomID);
						Redirect::to('Print.php');
					}
				}
				
			}else{
		
				foreach($validation->errors() as $error){
					$displayerror.= $error.'<br>';
				}
				Session::flash('error',$displayerror);	
			}
	}
if(Input::exists('get')){
		$payments =  new Payment();
		if(Input::get('Delete')){
			$payments->delete_payment(Input::get('Delete'));
		}
		Redirect::to('Payments.php');
}

require_once('functions/MessageBox.php');
$options = getNavBar($user->data()->GroupNo);
?>
<!DOCTYPE HTML>
<html manifest="MMC.appcahce" lang="en">
<head>
<meta charset="utf-8">
<title><?php echo $title ?></title>
<link rel="stylesheet" type="text/css" href="css/hostelRegistration.css">
</head>

<body>
	<!-- container -->
	<div id="container">
    	
        <!-- header -->
        <div id="header">
        <img src="image/project header.jpg" width="1024" height="100" />
        </div>
        <!-- end of header -->
        
        <!-- button -->
        <div id="NavBar">	
			<ul>
            	<li><a href="index.php">Home</a></li>
				<li><a href="EditForm.php">Edit Profile</a></li>
				<?php echo $options ?>
				<li><a href="Logout.php">Log Out</a></li>
			</ul>
		</div>
        <!-- end of button -->
        
        <!-- content -->
        <div id="content">
		<form id="AddPayment" action method="POST">
			<table>
				<tr><td>Payment Month </td><td>:</td><td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="text" name="PaidMonth" value="<?php echo TheDate('month') ?>"></td></tr>
				<tr><td>Amount  </td><td>:</td><td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbspRM <?php echo $payment->get_room($user->data()->RoomID)->room_price;?></td></tr>
				<tr><td>Receipt No</td><td>:</td><td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input name="ReceiptNo" type="text"></td></tr>
				<tr><td>Type of payment</td><td>:</td><td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<select name="TypeOfPay">
																			<option value="monthly">Monthly</option>
																			<option value="deposit">Deposit</option></select>
																					</td>
				<tr><td><input type="hidden" name="token" value="<?php echo Token::generate()?>"></td></tr>
			</table>
				<input type="submit" value="Add Payment">
		</form>
		<?php echo $title?>
		<?php 
		
		switch($user->data()->GroupNo){
			
			case 1:
				
			
			case 3:
			
			
			$payments = new Payment();
			echo '<h2>Payments</h2>';
			echo ($payments->check_deposit_paid($user->data()->ID))? 'Deposit Paid' : '<b>Deposit Not Paid</b>';
			if(!$payments->print_all_payment(1,$user->data()->ID)){
				echo '<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>';
				echo 'No payments are inside the database';
			}
			break;
			
			
			default:
			echo '<h1>Access Denied</h1>';
			break;
		}
		?>
		
        </div>
        <!-- end of content -->
        
        <!-- sidebar -->
        <div id="sidebar">
                
                <div id="Second_Box">
					<div id="Second_Head">
						<p align="center"> Messaging </p>
					</div>
                    
					<div id="Second_Body">
						<div id="MessagingForm">
							<form action="MessageBox.php" method="post" enctype="multipart/form-data" name="messageform" id="messageform" onsubmit="return validate_form();">
								<table align="center" cellpadding="1">
									<tr><td valign="right">Title :</td><td align="left"><input name="msgTitle" type="text" id="msgTitle" size="18" maxlength="24" /></td></tr>
									<tr><td valign="right">Body :<br><br><br><br><br><br><br><br></td><td align="left"><textarea name="msgBody" type="text" id="msgBody" size="15" cols="15" rows="10">Type your message here</textarea></td></tr>
									<tr><td valign="right">Type :</td><td align="left">
                                    										<select name="msgType">
																				<option value="private">Private</option>
																				<option value="feed" selected>Feed</option>
																			</select>	</td>
                                                                            </tr>
									<tr>
                                    	<td> To : </td>
                                        <td> <input name="msgReceipient" type="text" id="msgReceipient" size="18.5" maxlength="24"/> </td>
                                    </tr>
									<tr>
                                    	<td align="center" colspan="2"> <input name="Send" type="submit" value="Send" /> </td>
                                    </tr>
								</table>
							</form>
						</div>
					</div>
				</div>
        </div>
        <!-- end of sidebar -->
        
    </div>
    <!-- end of container --?
    
</body>
</html>
