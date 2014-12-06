<?php
require_once'core/init.php';


if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$displayerror = '';
		    $validate = new Validate();
		  $validation = $validate->check($_POST,array(
			//1st Particulars
			'ID' => array(
				'required' => true,
				'numbered' => true,
				'unique' => 'users',
				'max' => 10,
				'min' => 10
			),
			
			'Course' => array(),
			'school' => array(),
			'Room_ID' => array(),
			
			
			//2nd Particulars
			'Name' => array(
				'required' => true,
			),
			
			'Password' => array(
				'required'	=> true,
				'min' 		=>6
			),
			
			'reenterPassword' => array(
				'required' => true,
				'matches' => 'Password'
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
				'required' => true
			),
			
			'IC' => array(
				'required' => true,
				'numbered' => true
			),
			
			'gender' => array(
				'required' => true
			),
			
			'race' => array(
				'required' => true
			),
			
			'religion' => array(
				'required' => true
			),
			
			'EMail' => array(
				'required' => true,
				'email form' => true
			),
			
			'telephone' => array(
				'required' => true,
				'numbered' => true
			),
			
			//3rd Particulars
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
			$salt = Hash::salt(32);
			if(Input::get('Room_ID')){
				$room = new Room();
				$room_info = $room->get_room(Input::get('Room_ID'));
				if($room_info->room_cap < $room_info->room_occupied){
					$room->add_tenant($room_info->room_id);
				}
				if($room_info->room_cap == $room_info->room_occupied){
					$room->mark_full($room_info->room_id);
				}
			}
			try{
				$user->create(array(
					'Name' => Input::get('Name'),
					  'ID' => Input::get('ID'),
				'Password' => Hash::make(Input::get('Password'),$salt),
					'salt' => $salt,
					  'IC' => Input::get('IC'),
				  'Course' => Input::get('Course'),
				  'School' => Input::get('school'),
			 'TelephoneNo' => Input::get('telephone'),
				   'Email' => Input::get('EMail'),
				  'Gender' => Input::get('gender'),
				    'Race' => Input::get('race'),
				'Religion' => Input::get('religion'),
				'House_No' => Input::get('house_no'),
				    'Area' => Input::get('area'),
				'Postcode' => Input::get('postcode'),
				   'State' => Input::get('State'),
			 'RegDateTime' => datetime(),
				  'RoomID' => Input::get('Room_ID'),
			     'GroupNo' => 4,
				   'GName' => Input::get('GName'),
		   'GRelationship' => Input::get('GRelationship'),
				  'GEMail' => Input::get('GEMail'),
			   'GHouse_No' => Input::get('GHouse_No'),
				   'GArea' => Input::get('GArea'),
			   'GPostcode' => Input::get('GPostcode'),
		    'GTelephoneNo' => Input::get('GTelephoneNo')
				));
				
				
				
				Session::flash('home','Registration successful, Please login');
				Redirect::to('login.php');
			
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
}	
			
?>
<!DOCTYPE html> 
<html lang="en" manifest="MMC.appcache">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Hostel Registration Form</title>
<link rel="stylesheet" type="text/css" href="css/registrationForm.css">
</head>

<body>
	<!-- container -->
	<div id="container">
    	
        <!-- header -->
        <div id="header">
        <img src="image/project header.jpg" width="1024" height="100" />
        </div>
		
        <!-- end of header -->
	
        <div id="errorBox">
			<?php
				echo '<p>' .Session::flash('error').'</p>';
			?>
		</div>
		
		</div>
        <!-- form content -->
        <div id="formContent">
        <form action method="post" enctype="multipart/form-data">
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
   						<td><label for="ID">Student ID Number</label></td>
					
    					<td align="center"> : </td>
    					<td> <input name="ID" type="text" size="55" maxlength="20" value="<?php echo escape(Input::get('ID')); ?>" /> </td>
				  	</div>
					</tr>
  					
                    <tr>
					<div class="field">
   					  	<td><label for="course">Course</label></td>
    					<td align="center"> : </td>
    					<td> <select name="Course">
                        					<option> Please select your course </option>
											<option value="DTE"> Diploma In Technology ( Telecommunication Engineering ) </option>
											<option value="DNM"> Diploma In Creative New Media </option>
											<option value="DSE"> Diploma In Software Engineering </option>
											<option value="DMT"> Diploma In Information Technology </option>
                                            <option value="DMG"> Diploma In Management with Multimedia </option>
											<option value="DIA"> Diploma In Accounting </option>
											</select> </td>
  					</div>
					</tr>
  
  					<tr>
					<div class="field">
   					  	<td><label for="school">School</label></td>
    					<td align="center"> : </td>
    					<td> <select name="school">
                        					<option> Please select your school </option>
											<option value="Engineering"> School of Engineering </option>
											<option value="IT & Multimedia"> School of IT and Multimedia </option>
											<option value="Business & Accounting"> School of Business and Accounting </option>
											</select> 
						</td>
  					</div>
					</tr>
					<tr>
						<td>Room ID</td>
						<td> : </td>
						<td><select name="Room_ID">
								<!-- Add php script to list empty rooms -->
								<?php
									$block_list = new Block();
									
									$block_list->get_vacant_room_registration_form()
								?>
							</select>
						</td>
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
				<div class="field">
   					  <td><label for="Name">Name</label></td>
					  <td align="center"> : </td>
    				  <td> <input name="Name" type="text" size="55" maxlength="100" value="<?php echo escape(Input::get('Name')); ?>" /> </td>
				</div>
  			  </tr>
  
  			  <tr>
				<div class="field">
   					  <td><label for="Password">Password</label></td>
					  <td align="center"> : </td>
    				  <td> <input name="Password" type="password" size="55" maxlength="15" /> </td>
				</div>
			  </tr>
  
  			  <tr>
				<div class="field">
   					  <td><label for="reenterPassword">Re-enter Password</label></td>
    				  <td align="center"> : </td>
    				  <td><input name="reenterPassword" type="password" size="55" maxlength="15" /> </td>
				</div>
  			  </tr>
  
  			  <tr>
				<div class="field">
   					  <td><label for="address">Home Address</label><br><br><br><br></td>
					  <td align="center">:<br><br><br><br></td>
    				  <td align="left">
    				    <input type="text" name="house_no" size="55"><br>
    				    <input type="text" name="area" size="55"><br>
    				    <input type="text" name="postcode" size="55"><br>
    				  </td>
				</div>

    		  </tr>
				
  			  <tr>
				<div class="field">
   					  <td><label for="State">State</label></td>
    				  <td align="center"> : </td>
    				  <td> <select name="State">
								<option> Please select your state </option>
								<option value="Johor"> Johor Darul Takzim </option>
								<option value="Kedah"> Kedah Darul Aman </option>
								<option value="Kelantan"> Kelantan Darul Naim </option>
								<option value="Melaka"> Melaka Bandaraya Bersejarah </option>
                                <option value="Negeri Sembilan"> Negeri Sembilan Darul Khusus </option>
								<option value="Pahang"> Pahang Darul Makmur </option>
								<option value="Perak"> Perak Darul Ridzuan </option>
								<option value="Perlis"> Perlis Indera Kayangan </option>
                                <option value="Pulau Pinang"> Pulau Pinang Pulau Mutiara </option>
								<option value="Sabah"> Sabah Negeri Di Bawah Bayu </option>
								<option value="Sarawak"> Sarawak Bumi Kenyalang </option>
								<option value="Selangor"> Selangor Darul Ehsan </option>
                                <option value="Terengganu"> Terengganu Darul Iman </option>
								<option value="Kuala Lumpur"> Wilayah Persekutuan Kuala Lumpur </option>
								<option value="Labuan"> Wilayah Persekutuan Labuan </option>
								<option value="Putrajaya"> Wilayah Persekutuan Putrajaya </option>
							</select> </td>
				</div>
			  </tr>
  
  			  <tr>
   				<div class="field">
					<td><label for="IC">Mykad Number</label></td>
					<td align="center"> : </td>
					<td> <input name="IC" type="text" size="55" maxlength="14" value="<?php echo escape(Input::get('IC')); ?>" /> </td>
				</div>
			  </tr>
  
  			  <tr>
				<div class="field">
   					<td><label for="gender">Gender</label></td>
    				<td align="center"> : </td>
    				<td> <input type="radio" name="gender" value="M"> Male
                         <input type="radio" name="gender" value="F"> Female </td>
  				</div>
			  </tr>
  
  			  <tr>
				<div class="field">
   					<td><label for="race">Race</label></td>
					<td align="center"> : </td>
    				<td> <input type="radio" name="race" value="malay"> Malay
						 <input type="radio" name="race" value="chinese"> Chinese
                         <input type="radio" name="race" value="indian"> Indian
                         <input type="radio" name="race" value="others"> Others </td>
  				</div>	
			  </tr>
  
  			  <tr>
				<div class="field">
					<td><label for="religion">Religion</label></td>
    					<td align="center"> : </td>
    					<td> <input type="radio" name="religion" value="islam"> Islam
                        				 <input type="radio" name="religion" value="christian"> Christian
                                         <input type="radio" name="religion" value="buddha"> Buddha
                                         <input type="radio" name="religion" value="others"> Others </td>
				</div>
			 </tr>
  
  			 <tr>
				<div class="field">
					<td><label for="EMail">E-mail Address</label></td>
					<td align="center"> : </td>
    				<td> <input name="EMail" type="text" size="55" maxlength="40" value="<?php echo escape(Input::get('EMail')); ?>" /> </td>
				</div>
  			 </tr>
					
			 <tr>
				<div class="field">
					<td><label for="telephone">Telephone Number</label></td>
					<td align="center"> : </td>
					<td> <input name="telephone" type="text" size="55" maxlength="20" value="<?php echo escape(Input::get('telephone')); ?>" /></td>
				</div>
			 </tr>	
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
					<div class="field">
						<td><label for="GName">Name</label></td>
    					<td align="center"> : </td>
    					<td> <input name="GName" type="text" size="55" maxlength="50" /> </td>
				  	</tr>
  
  					<tr>
					<div class="field">
   					  	<td><div style="margin-bottom:10px"><label for="GAdress">Home Address</label><br><br><br><br></div> </td>
    					<td align="center"> :<br><br><br><br></td>
    					<td align="left">   
	    					<input type="text" name="GHouse_No" size="55"><br>
    				        <input type="text" name="GArea" size="55"><br>
    				        <input type="text" name="GPostcode" size="55"><br> 
    				    </td>
  					</div>
					</tr>
  
  					<tr>
					<div class="field">
   					  	<td><label for="GRelationship">Relationship</label></td>
    					<td align="center"> : </td>
    					<td> <input name="GRelationship" type="text" size="55" maxlength="15" /> </td>
  					</div>
					</tr>
  					
                    <tr>
					<div class="field">
   					  	<td><label for="GTelephoneNo">Telephone Number</label></td>
    					<td align="center"> : </td>
    					<td> <input name="GTelephoneNo" type="text" size="55" maxlength="15" /> </td>
  					</div>
					</tr>
					
					<tr>
					<div class="field">
   					  	<td><label for="GEMail">Guarantor Email</label></td>
    					<td align="center"> : </td>
    					<td> <input name="GEMail" type="text" size="55" maxlength="50" /> </td>
  					</div>
					</tr>
				 
				</table>
				<input type="hidden" name='token' value="<?php echo Token::generate();?>">
				<p align="right"><input name="submit" type="submit" value="Submit" /></p>
				
			</div>
            <!-- end of 3rd particular -->
           </form>
			
        </div>
        <!-- end of form content -->
        
    </div>
    <!-- end of container -->
    
</body>
</html>