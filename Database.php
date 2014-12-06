<?php
require_once('core/init.php');
$options ='';
$title = 'Database';
$user = new User();
$rank = $user->data()->GroupNo;
if(!$user->isLoggedIn()){
	Redirect::to('login.php');
}
Session::put('CPage','Database.php');
$options = getNavBar($rank);
if($rank == 3){
$user = new Admin();
}else if($rank == 2){
$user = new Warden();
}

if(Input::exists('get')){
	
	if($rank == 3){
		if(Input::get('deleting')){
			$user->delete_User(Input::get('deleting'));
			echo 'Done';
		}
		
		if(Input::get('verify')){
			$user->Verify_Student(Input::get('verify'));
			echo 'Done';
		}
	}
	
	if($rank == 2 || $rank == 3){
		if(Input::get('profile')){
	
		}
	}
	
	
}
require_once('functions/MessageBox.php');
?>
<!DOCTYPE HTML>
<html manifest="MMC.appcahce" lang="en">
<head>
<meta charset="utf-8">
<title><?php echo $title?></title>
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
		<?php echo $title?>
		<?php
			switch($user->data()->GroupNo){
				case 2:
					
					echo '<h1>'.'List of Student'.'</h1>';
					if(!$user->print_All(1)){
						echo 'No student are in the database';
					}
					
					echo '<h1>'.'List of Warden '.'</h1>';
					if(!$user->print_All(2)){
						echo 'No warden are in the database';
					}
					
					echo '<h1>'.'List of room by block'.'</h1>';
					
					
				break;
				case 3:
					
					
					
					echo '<h1>'.'List of Pending Student'.'</h1>';  //Assign Room for these students
					if(!$user->print_All(4)){						 //Delete students
						echo 'No students are in the database';		 
					}
					
					echo '<h1>'.'List of Student'.'</h1>';			//See profiles of student
					if(!$user->print_All(1)){						//Delete students
						echo 'No students are in the database';		
					}
					
					echo '<h1>'.'List of Warden '.'</h1>';
					if(!$user->print_All(2)){						//See profiles warden
						echo 'No wardens are in the database';		
					}
					
					echo '<h1>'.'List of Admin '.'</h1>';			//Delete admin
					if(!$user->print_All(3)){						//See profiles students
						echo 'No wardens are in the database';
					}
					
					echo '<h1>'.'List of Students In Debt'.'</h1>';
					if(!$unpaid_students=$user->get_notPaid()){
						
						echo 'All student have paid their rent this month';
					}else{
									if($unpaid_students){
										echo '<table id="data">';
										foreach($unpaid_students as $student){
											echo '<tr>'.'<td>'.$student->ID.'</td>';
											echo '<td>'.$student->Name.'</td>';
											echo '<td>'.$student->Course.'</td>';
											echo '<td>'.$student->State.'</td>';	
											echo '<td>';
											echo '<form action method="GET">';
											echo '<input type="hidden" name="deleting" value="'.$student->ID.'"/>';
											echo '<input type="submit" value="delete" onSubmit="Conform()" />';
											echo '</form>';
											echo '</td>';
											echo '<td>';
											echo '<form action="Profile.php" method="GET">';
												echo '<input type="hidden" name="user_id" value="'.$student->ID.'"/>';
												echo '<input type="submit" value="profile"/>';
											echo '</form>';
											echo '</td>';
											if($rank == 4){
												echo '<td>';
												echo '<form action method="GET">';
													echo '<input type="hidden" name="verify" value="'.$student->ID.'"/>';
													echo '<input type="submit" value="verify"/>';
												echo '</form>';
												echo '</td>';
											}
										}
										echo '</tr>';
										echo '</table>';
									}
					}
					
					//Add admin or warden button
				break;
			}
			//Database list of all user/students
			
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
							<form action method="post" enctype="multipart/form-data" name="messageform" id="messageform" onsubmit="return validate_form();">
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
