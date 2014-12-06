<?php
require_once'core/init.php';
$title= 'View Profile';
$user =  new User();
if($user->isLoggedIn()){

	if($_GET['user_id']){
		$profile = new User($_GET['user_id']);
		$data = $profile->data();
	}
	
	if(Input::get('Delete')){
		$payments =  new Payment();
		$payments->delete_payment(Input::get('Delete'));
	}
	
	if(Input::get('Approve')){
		$payments =  new Payment();
		$payments->verify_Payment($user->data()->ID,Input::get('Approve'));
	}
	

$options = getNavBar($user->data()->GroupNo);
}else{
	Session::flash('error','You must be logged in to see the page');
	Redirect::to('login.php');
}	
?>
<!DOCTYPE html> 
<html lang="en" manifest="MMC.appcache">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title ?></title>
<link rel="stylesheet" type="text/css" href="css/registrationForm.css">
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
        <div id="NavBar">	
			<ul>
            	<li><a href="index.php">Home</a></li>
				<li><a href="EditForm.php">Edit Profile</a></li>
				<?php echo $options?>
				<li><a href="Logout.php">Log Out</a></li>
			</ul>
		</div>
		
        <!-- form content -->
		<?php echo Session::flash('error'); ?>
        <form action method="post">
		
		<div id="content">
        	<div id="firstParticular">
            	<table width="100%" border="0">
  					<tr>
    					<td align="center" bgcolor="#0066FF"> <font size="+1"> <b> 1. Academic Particulars </b> </font> </td>
                    </tr>
                </table>
  				
                <table width="80%" align="center" border="0">	
  					<tr>
						<td>Student ID </td>
						<td align="center"> : </td>
						<td><?php echo $data->ID; ?></td>
					
					</tr>
					
                    <tr>
					<div class="field">
   					  	<td><label for="course">Course</label></td>
    					<td align="center"> : </td>
    					<td><?php echo $data->Course; ?></td>
  					</div>
					</tr>
  
  					<tr>
					<div class="field">
   					  	<td><label for="school">School</label></td>
    					<td align="center"> : </td>
    					<td><?php echo escape($data->School); ?></td>
  					</div>
					</tr>
					
					<tr>
						<td>Room ID</td>
						<td align="center"> : </td>
						<td><?php echo escape($data->RoomID); ?></td>
					</tr>
			  </table>
			</div>
            <!-- end of 1st particular -->
        
        	<!-- 2nd particular -->
			<div id="secondParticular">
            	<table width="100%" border="0">
  					<tr>
    					<td align="center" bgcolor="#0066FF"> <font size="+1"> <b> 2. Personal Particulars </b> </font> </td>
                    </tr>
                </table>
  				
  			<table width="80%" align="center" border="0">
       		  <tr>
   					  	<td> Name</td>
    					<td align="center"> : </td>
    					<td><?php echo escape($data->Name);?></td>
  					</tr>
 
  
  					<tr>
   					  	<td> Home Address</td>
						<td align="center"> : </td>
    					<td align="left"><?php echo $data->House_No.' , '.$data->Area.' , '.$data->Postcode ?></td>
    				</tr>
  
  					<tr>
   					  	<td> State </td>
    					<td align="center"> : </td>
    					<td><?php echo escape($data->State); ?></td>
  					</tr>
  
  					<tr>
   					  	<td> Gender </td>
    					<td align="center"> : </td>
    					<td><?php echo escape($data->Gender); ?></td>
  					</tr>
  
  					<tr>
   					  	<td> Race </td>
    					<td align="center"> : </td>
    					<td> <?php echo escape($data->Race);?></td>
  					</tr>
  
  					<tr>
   					  	<td> Religion </td>
    					<td align="center"> : </td>
    					<td><?php echo escape($data->Religion);?></td>
  					</tr>
  
  					<tr>
   					  	<td> E-mail Address </td>
    					<td align="center"> : </td>
    					<td><?php echo escape($data->EMail); ?></td>
  					</tr>
					
					<tr>
						<td>Telephone Number </td>
						<td align="center"> : </td>
						<td><?php echo escape($data->TelephoneNo);?></td>
				</table>
			</div>
            <!-- end of 2nd particular -->
        
        	<!-- 3rd particular -->
        	<div id="thirdParticular">
				<table width="100%" border="0">
  					<tr>
    					<td align="center" bgcolor="#0066FF"> <font size="+1"> <b> 3. Person To Be Notified In Case Of Emergency / Guarantor </b> </font> </td>
                    </tr>
                </table>
  				
                <table width="80%" align="center" border="0">
  					<tr>
   					  <td> Name </td>
    					<td align="center"> : </td>
    					<td><?php echo escape($data->GName);?></td>
				  	</tr>
  
  					<tr>
   					  	<td><div style="margin-bottom:10px"> Home Address</div> </td>
    					<td align="center"> : </td>
						<td align="left"><?php echo $data->GHouse_No.' , '.$data->GArea.' , '.$data->GPostcode ?></td>
  					</tr>
  
  					<tr>
   					  	<td> Relationship </td>
    					<td align="center"> : </td>
    					<td><?php echo escape($data->GRelationship); ?></td>
  					</tr>
  					
                    <tr>
   					  	<td> Telephone Number </td>
    					<td align="center"> : </td>
    					<td><?php echo escape($data->GTelephoneNo);?></td>
  					</tr>
					
					<tr>
   					  	<td> Guarantor Email </td>
    					<td align="center"> : </td>
    					<td><?php echo escape($data->GEMail);?></td>
  					</tr>
				 
				</table>
			
			</div>
			<br><br>
			<div id="">
				<?php
					$payments = new Payment();
					echo '<h1>Payments</h1>';
					echo ($payments->check_deposit_paid($data->ID))? 'Deposit Paid' : '<b>Deposit Not Paid</b>';
					if(!$payments->print_all_payment(1,$data->ID)){
						echo '<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>';
						echo '<h3 align="center">No payments are inside the database</h3>';
					}
				?>
			</div>
        <!-- end of form content -->
		</div>
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
									<tr><td valign="right">Body :<br><br><br><br><br><br><br><br><br><br></td><td align="left"><textarea name="msgBody" type="text" id="msgBody" size="15" cols="15" rows="10">Type your message here</textarea></td></tr>
									<tr><td valign="right">Type :</td><td align="left">
                                    										<select name="msgType">
																				<option value="message">Message</option>
																				<option value="complaint" selected>Complaint</option>
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
    <!-- end of container -->
    
</body>
</html>
