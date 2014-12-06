<?php
require_once'core/init.php';
$title= 'Edit Profile';
$user = new User();
$data = $user->data();
if($user->isLoggedIn()){
	$options = getNavBar($user->data()->GroupNo);
	
	if(Input::exists()){
		if(Token::check(Input::get('token'))){
			
			$validate = new Validate();
			$validation = $validate->check($_POST,array(
			
				//Personal Particulars
				'Name' => array(
					'required' => 'true',
				),
				'house_no' => array(
					'required' => true,
				),	
				'area' => array(
					'required' => true,
				),
				'postcode' => array(
					'required' => true,
					'max' => 5,
				),
				'State' => array(
					'required' => 'true',
				),
				'IC' => array(
					'required' => 'true',
				),
				'gender' => array(
					'required' => 'true',
				),
				'race' => array(
					'required' => 'true',
				),
				'religion' => array(
					'required' => 'true',
				),
				'EMail' => array(
					'required' => 'true',
				),
				'telephone' => array(
					'required' => 'true',
				),
			
				//Referrer Particulars
				'GName' => array(
					'required' => true,
				),
				'GRelationship' => array(
					'required' => true,
				),
				'GTelephoneNo' => array(
					'required' => true,
					'numbered' => true,
				),
				'GEMail' => array(
					'required' => true,
				  'email form' => true,
				),
				'GHouse_No' => array(
					'required' => true,
				),
				'GArea' => array(
					'required' => true,
				),		
				'GPostcode' => array(
					'required' => true,
					'max' => 5,
				),
			));
			
			if($validation->passed()){
				$user = new User();
				
				try{
					$user->update(array(
						'Name' => Input::get('Name'),
						  'IC' => Input::get('IC'),
					  'Course' => Input::get('Course'),
					  'School' => Input::get('school'),
				 'TelephoneNo' => Input::get('telephone'),
				       'EMail' => Input::get('EMail'),
					  'Gender' => Input::get('gender'),
					    'Race' => Input::get('race'),
					'Religion' => Input::get('religion'),
					'House_No' => Input::get('house_no'),
				        'Area' => Input::get('area'),
				    'Postcode' => Input::get('postcode'),
					   'State' => Input::get('State'),
					   'GName' => Input::get('GName'),
					  'GEMail' => Input::get('GEMail'),
				   'GHouse_No' => Input::get('GHouse_no'),
				       'GArea' => Input::get('GArea'),
				   'GPostcode' => Input::get('GPostcode'),
			   'GRelationship' => Input::get('GRelationship'),
			    'GTelephoneNo' => Input::get('GTelephoneNo')
				 
					));
					Session::flash('ok','Update successful');
					Redirect::to('EditForm.php');
			
				}catch(Exception $e){
					die($e->getMessage());
				}
			
		}else{
		
			foreach($validation->errors() as $error){
				$displayerror.= $error.'<br>';
			}
			
			Session::flash('error',$displayerror);
			
		}
		
		}
		if(Token::check(Input::get('ChangePassword'))){
			 $validate = new Validate();
			$validation = $validate->check($_POST,array(
						 'c_password' => array(
							 'required' => true
						),
						'c_vpassword' => array(
							'required' => true,
							'matches' => 'c_password'
						)
					));
			if($validation){
				try{
					$user->Change_Password(Input::get('c_password'));
					Session::flash('error','Password successfully changed');
				}catch(Exception $e){
					Session::flash('error',$e->getMessage());
				}
				
			}else{
			
				foreach($validation->errors() as $error){
					$displayerror.= $error.'<br>';
				}
			
				Session::flash('error',$displayerror);
			}
		}
	}
}else{
	Session::flash('error','You must be logged in to see the page');
	Redirect::to('login.php');
}	
?>
<!DOCTYPE html> 
<html lang="en" manifest="MMC.appcache">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Profile Form</title>
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
				<?php echo $options ?>
				<li><a href="Logout.php">Log Out</a></li>
			</ul>
		</div>
		
        <!-- form content -->
		<?php echo Session::flash('error'); ?>
		<?php echo Session::flash('ok'); ?>
        <div id="content">
		<div id="change_password">
				<form action method="POST">
					<table>
					<tr><th colspan="3">Change Password</th></tr>
					<tr>
						<td>Password</td>
						<td> : </td>
						<td><input type="password" name="c_password"></td>
					</tr>
					<tr>
						<td>Re Enter Password</td>
						<td> : </td>
						<td><input type="password" name="c_vpassword"></td>
					</tr>
					<tr>
						<td colspan="3" align="right">
							<input type="submit" value="Change password">
							<input type="hidden" name="ChangePassword" value="<?php echo Token::generate();?>">
						</td>
					</tr>
				</table>
				</form>
			</div>
			<br><br>
        <form action method="post">
		
		<!-- 1st particular -->
        	<div id="firstParticular">
            	<table width="100%" border="0">
  					<tr>
    					<td align="center" bgcolor="#0066FF"> <font size="+1"> <b> 1. Academic Particulars </b> </font> </td>
                    </tr>
                </table>
  				
                <table width="80%" align="center" border="0">	
  					
                    <tr>
					<div class="field">
   					  	<td><label for="course">Course</label></td>
    					<td align="center"> : </td>
    					<td> <select name="Course">
                        					<option> Please select your course </option>
											<option  value="DTE" <?php if($data->Course == "DTE") echo "selected"?>> Diploma In Technology ( Telecommunication Engineering ) </option>
											<option  value="DNM" <?php if($data->Course == "DNM") echo "selected"?>> Diploma In Creative New Media </option>
											<option  value="DSE" <?php if($data->Course == "DSE") echo "selected"?>> Diploma In Software Engineering </option>
											<option  value="DMT" <?php if($data->Course == "DMT") echo "selected"?>> Diploma In Information Technology </option>
                                            <option  value="DMG" <?php if($data->Course == "DMG") echo "selected"?>> Diploma In Management with Multimedia </option>
											<option  value="DIA" <?php if($data->Course == "DIA") echo "selected"?>> Diploma In Accounting </option>
											</select> </td>
  					</div>
					</tr>
  
  					<tr>
					<div class="field">
   					  	<td><label for="school">School</label></td>
    					<td align="center"> : </td>
    					<td> <select name="school">
                        					<option > Please select your school </option>
											<option <?php if($data->School == "Engineering") echo "selected"?> value="Engineering"> School of Engineering </option>
											<option value="IT & Multimedia" <?php if($data->School == "IT & Multimedia" ) echo "selected"?>> School of IT and Multimedia </option>
											<option value="Business & Accounting" <?php if($data->School == "Business & Accounting") echo "selected" ?>> School of Business and Accounting </option>
											</select> </td>
  					</div>
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
    					<td> <input name="Name" type="text" size="55" maxlength="100" value="<?php echo $data->Name;?>" /> </td>
  					</tr>
 
  
  					<tr>
   					  	<td> Home Address<br><br><br><br></td>
						<td align="center"> : <br><br><br><br></td>
    					<td align="left">
			    			 <input type="text" name="house_no" size="55" value="<?php echo $data->House_No; ?>"><br>
    				         <input type="text" name="area" size="55" value="<?php echo $data->Area; ?>"><br>
    				         <input type="text" name="postcode" size="55" value="<?php echo $data->Postcode; ?>"><br>
    					</td>
    				</tr>
  
  					<tr>
   					  	<td> State </td>
    					<td align="center"> : </td>
    					<td> <select name="State">
                        					<option value=""> Please select your state </option>
											<option value="Johor" <?php if($data->State == "Johor") echo "selected" ?>> Johor Darul Takzim </option>
											<option value="Kedah"<?php if($data->State == "Kedah") echo "selected" ?>> Kedah Darul Aman </option>
											<option value="Kelantan"<?php if($data->State == "Kelantan") echo "selected" ?>> Kelantan Darul Naim </option>
											<option value="Melaka"<?php if($data->State == "Melaka") echo "selected" ?>> Melaka Bandaraya Bersejarah </option>
                                            <option value="Negeri Sembilan"<?php if($data->State == "Negeri Sembilan") echo "selected" ?>> Negeri Sembilan Darul Khusus </option>
											<option value="Pahang"<?php if($data->State == "Pahang") echo "selected" ?>> Pahang Darul Makmur </option>
											<option value="Perak"<?php if($data->State == "Perak") echo "selected" ?>> Perak Darul Ridzuan </option>
											<option value="Perlis"<?php if($data->State == "Perlis") echo "selected" ?>> Perlis Indera Kayangan </option>
                                            <option value="Pulau Pinang"<?php if($data->State == "Pulau Pinang") echo "selected" ?>> Pulau Pinang Pulau Mutiara </option>
											<option value="Sabah"<?php if($data->State == "Sabah") echo "selected" ?>> Sabah Negeri Di Bawah Bayu </option>
											<option value="Sarawak"<?php if($data->State == "Sarawak") echo "selected" ?>> Sarawak Bumi Kenyalang </option>
											<option value="Selangor"<?php if($data->State == "Selangor") echo "selected" ?>> Selangor Darul Ehsan </option>
                                            <option value="Terengganu"<?php if($data->State == "Terengganu") echo "selected" ?>> Terengganu Darul Iman </option>
											<option value="Kuala Lumpur"<?php if($data->State == "Kuala Lumpur") echo "selected" ?>> Wilayah Persekutuan Kuala Lumpur </option>
											<option value="Labuan"<?php if($data->State == "Labuan") echo "selected" ?>> Wilayah Persekutuan Labuan </option>
											<option value="Putrajaya"<?php if($data->State == "Putrajaya") echo "selected" ?>> Wilayah Persekutuan Putrajaya </option>
											</select> </td>
  					</tr>
  
  					<tr>
   					  	<td> Mykad Number </td>
    					<td align="center"> : </td>
    					<td> <input name="IC" type="text" size="55" maxlength="14" value="<?php echo escape($data->IC);?>" /> </td>
  					</tr>
  
  					<tr>
   					  	<td> Gender </td>
    					<td align="center"> : </td>
    					<td> <input type="radio" name="gender" value="M" <?php echo($data->Gender =="M")? 'checked' : '' ?>> Male
                        	 <input type="radio" name="gender" value="F" <?php echo($data->Gender =="F")? 'checked' : '' ?>> Female </td>
  					</tr>
  
  					<tr>
   					  	<td> Race </td>
    					<td align="center"> : </td>
    					<td> <input type="radio" name="race" value="malay" <?php echo($data->Race =="malay")? 'checked' : '' ?>> Malay
                        				 <input type="radio" name="race" value="chinese" <?php echo($data->Race =="chinese")? 'checked' : '' ?>> Chinese
                                         <input type="radio" name="race" value="indian"  <?php echo($data->Race =="indian")? 'checked' : '' ?>> Indian
                                         <input type="radio" name="race" value="others" <?php echo($data->Race =="others")? 'checked' : '' ?>> Others </td>
  					</tr>
  
  					<tr>
   					  	<td> Religion </td>
    					<td align="center"> : </td>
    					<td> <input type="radio" name="religion" value="islam" <?php echo($data->Religion =="islam")? 'checked' : '' ?>> Islam
                        				 <input type="radio" name="religion" value="christian" <?php echo($data->Religion =="christian")? 'checked' : '' ?>> Christian
                                         <input type="radio" name="religion" value="buddha" <?php echo($data->Religion =="buddha")? 'checked' : '' ?>> Buddha
                                         <input type="radio" name="religion" value="others" <?php echo($data->Religion =="others")? 'checked' : '' ?>> Others </td>
  					</tr>
  
  					<tr>
   					  	<td> E-mail Address </td>
    					<td align="center"> : </td>
    					<td> <input name="EMail" type="text" size="55" maxlength="40" value="<?php echo escape($data->EMail);?>" /> </td>
  					</tr>
					
					<tr>
						<td>Telephone Number </td>
						<td align="center"> : </td>
						<td> <input name="telephone" type="text" size="55" maxlength="20" value="<?php echo escape($data->TelephoneNo);?>" /></td>
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
    					<td> <input name="GName" type="text" size="55" maxlength="50" value="<?php echo $data->GName;?>"/> </td>
				  	</tr>
  
  					<tr>
   					  	<div class="field">
	   					  	<td><div style="margin-bottom:10px"><label for="GAdress">Home Address</label><br><br><br><br></div> </td>
	    					<td align="center"> :<br><br><br><br></td>
	    					<td align="left">   
		    					<input type="text" name="GHouse_no" size="55" value="<?php echo $data->GHouse_No; ?>"><br>
    				            <input type="text" name="GArea" size="55" value="<?php echo $data->GArea; ?>"><br>
    				            <input type="text" name="GPostcode" size="55" value="<?php echo $data->GPostcode; ?>"><br> 
    				        </td>
	  					</div>
  					</tr>
  
  					<tr>
   					  	<td> Relationship </td>
    					<td align="center"> : </td>
    					<td> <input name="GRelationship" type="text" size="55" maxlength="15" value="<?php echo escape($data->GRelationship) ?>" /> </td>
  					</tr>
  					
                    <tr>
   					  	<td> Telephone Number </td>
    					<td align="center"> : </td>
    					<td> <input name="GTelephoneNo" type="text" size="55" maxlength="15" value="<?php echo $data->GTelephoneNo?>"/> </td>
  					</tr>
					
					<tr>
   					  	<td> Guarantor Email </td>
    					<td align="center"> : </td>
    					<td> <input name="GEMail" type="text" size="55" maxlength="50" value="<?php echo $data->GEMail?>" /> </td>
  					</tr>
				 
				</table>
				<input type="hidden" name="token" value="<?php echo Token::generate();?>">
				<p align="right"><input name="submit" type="submit" value="Submit" /></p>
				
			</div>
            <!-- end of 3rd particular -->
           </form>
		   
			
        </div>
        <!-- end of form content -->
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
    </div>
    <!-- end of container -->
    
</body>
</html>