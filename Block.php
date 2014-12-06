<?php
require_once('core/init.php');
$options ='';
$title = 'Block';
$user = new User();
$GroupNo = $user->data()->GroupNo;
if($GroupNo == 3){
	$user = new Admin();
}
if($GroupNo == 2){
	$user = new Warden();
}
if(!$user->isLoggedIn()){
	Redirect::to('login.php');
}
if(@$_POST['block_id']){
	$validate = new Validate();
	$validation = $validate->check($_POST,array(
		'block_id' => array(
			'required' => true,
			  'unique' => 'block'
		),
		'num_of_rooms' => array(
			  'required' => true,
			  'min' => 1
		),
		'gender_code' => array(
			  'required' => true,
		)
	));
	if($validation->passed()){
		$block = new Block();
		try{
			$block->add_block(array(
				'block_id' => Input::get('block_id'),
				'block_cap' => Input::get('num_of_rooms'),
				'block_gen' => Input::get('gender_code')
			));
		echo 'Block '.Input::get('block_id').' added';
		}catch(Exception $e){
			die($e->getMessage());
		}
	}else{
		echo 'Validation failed';
		foreach($validation->errors() as $error){
				$displayerror.= $error.'<br>';
			}
			
			Session::flash('error',$displayerror);
	}
}

if(@$_POST['room_id']){
	$validate = new Validate();
	$validation = $validate->check($_POST,array(
		'room_id' => array(
			'required' => true
		),
		'block_id' => array(
			'required' => true
		),
		'room_cap' => array(
			'required' => true,
			'min' => 1
		),
		'room_price' => array(
			'required' => true,
			'numbered' => true
		),
		
	));
	echo 'Done';
	if($validation->passed()){
		$room = new Room();
		try{
			$room->add_room(array(
					 'room_id' => Input::get('room_id'),
					'room_cap' => Input::get('room_cap'),
				  'room_block' => Input::get('block_code'),
				  'room_price' => Input::get('room_price'),
				'availability' => true
			));
			echo 'Query sucess';
		}catch(Exception $e){
			echo 'Query fail';
			$e->getMessage();
			echo $e;
		}
	}else{
		echo 'Validation failed';
		foreach($validation->errors() as $error){
				$displayerror.= $error.'<br>';
			}
			
			Session::flash('error',$displayerror);
	}

}
if(Input::exists('get')){
	
	if($GroupNo == 3){
		if(Input::get('deleting')){
			$user->delete_User(Input::get('deleting'));
			echo 'Done';
		}
		
		if(Input::get('verify')){
			$user->Verify_Student(Input::get('verify'));
			echo 'Done';
		}
	}
	
	if($GroupNo == 2 || $GroupNo == 3){
		if(Input::get('profile')){
	
		}
	}
}



require_once('functions/MessageBox.php');
$options = getNavBar($user->data()->GroupNo);

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
		//Check num of blocks
		//List the rooms of each block
		  //Show status vacant or not and number available
		  //List out students in it
		  
		Session::flash('error');

		  
		?>
		
			<div id="Add_Box">
			<div id="Add_Block">
			<form action method="POST">
				<table>
				<tr><th colspan="3">Add Block</th></tr>
				<tr>
					<td>Code </td>
					<td> : </td>
					<td><input type="text" name="block_id"></td>
				</tr>
				<tr>
					<td>Number of rooms</td>
					<td> : </td>
					<td><input type="text"  name="num_of_rooms"></td>
				</tr>
				<tr>
					<td>Block for gender </td>
					<td> : </td>
					<td><select name="gender_code">
							<option value="M">Male</option>
							<option value="F">Female</option>
						</select>
					</td>
				</tr>
				<tr><td colspan="3" align="right"><input type="submit" value="Submit"></td></tr>
				</table>
			</form>
			</div>
				<div id="Add_Room">
				<form action method="POST">
					<table>
					<tr><th colspan="3">Add Room</th></tr>
					<tr>
						<td>Room ID</td>
						<td> : </td>
						<td><input type="text" name="room_id"></td>
					</tr>
					<tr>
						<td>Block</td>
						<td> : </td>
						<td><select name="block_code">
							<?php
								$block =  new Block();
								$blocks = $block->list_all_block();
								foreach($blocks as $block){
									echo '<option value="'.$block->block_id.'">'.$block->block_id.'</option>';
								}
							?>
							</select></td>
					</tr>
					<tr>
						<td>Room Capacity</td>
						<td> : </td>
						<td><input type="text" name="room_cap"></td>
					</tr>
					<tr>
					<td>Room Price</td>
					<td> : </td>
					<td><input type="text"  name="room_price"></td>
				</tr>
					<tr><td colspan="3" align="right"><input type="submit" value="Submit"></td></tr>
				</form>
			</table>
			</div>
			</div>
			<br><br>
			
			<form id="SearchRoom" action method="GET">
				<table>
					<tr><td align="left"><h3>Search Room ID</h3></td></tr>
					<tr>
						<td><input type="text" name="search_room"></td>
					</tr>
					<tr>
						<td><input type="submit" value="Search"></td>
					<?php if(@$_GET['search_room']){ echo '<td><input type="button" value="View all" onClick="ViewAll()"></td>';}?>
					</tr>
				</table>
			</form>
			<div id="Room List">
			<?php
			$rooms = new Room();
			if(@!$_GET['search_room']){
			  $blocks = new Block();
				$blocks = $blocks->list_all_block();
				foreach($blocks as $block){
					$blocks = new Block();
					echo '<h2>Block : '.$block->block_id,'</h2>';
						$rooms = new Room();
						$rooms = $rooms->list_all_rooms($block->block_id);
						foreach($rooms as $room){
							echo '<div id="room_box">';
								echo '<div id="room_id_box" align="center"><h3>'.$room->room_id.'</h3></div>';
								$tenants = new Room();
								$tenants = $tenants->get_room_tenants($room->room_id);
								if(count($tenants)){
									echo '<table>';
										echo '<tr>';
											echo '<th>ID</th>';
											echo '<th>Name</th>';
											echo '<th>Register Date & Time</th>';
										echo '</tr>';
									foreach($tenants as $tenant){
										echo '<tr>';
											echo '<td>'.$tenant->ID.'</td>';
											echo '<td>'.$tenant->Name.'</td>';
											echo '<td>'.$tenant->RegDateTime.'</td>';
											if($user->data()->ID != $tenant->ID){
												echo '<td>';
												echo '<form action method="GET">';
												echo '<input type="hidden" name="deleting" value="'.$tenant->ID.'"/>';
												echo '<input type="submit" value="delete" onSubmit="Conform()" />';
												echo '</form>';
												echo '</td>';
											}
											echo '<td>';
											echo '<form action="Profile.php" method="GET">';
											echo '<input type="hidden" name="user_id" value="'.$tenant->ID.'"/>';
											echo '<input type="submit" value="profile"/>';
											echo '</form>';
											echo '</td>';
											if($GroupNo == 4){
												echo '<td>';
												echo '<form action method="GET">';
												echo '<input type="hidden" name="verify" value="'.$tenant->ID.'"/>';
												echo '<input type="submit" value="verify"/>';
												echo '</form>';
												echo '</td>';
											}
									}
									echo '</tr>';
									echo '</table>';
									
								}else{
									echo "<h2 align='center'>Empty</h2>";
								}
								echo '</div>';
						
						
						}
				}
			}else{
				$room_id = $_GET['search_room'];
				$room = $rooms->get_room($room_id);
				if(count($rooms) != 0){
					echo '<div id="room_box">';
								echo '<div id="room_id_box" align="center"><h3>'.$room->room_id.'</h3></div>';
								$tenants = new Room();
								$tenants = $tenants->get_room_tenants($room->room_id);
								if(count($tenants)){
									echo '<table>';
										echo '<tr>';
											echo '<th>ID</th>';
											echo '<th>Name</th>';
											echo '<th>Register Date & Time</th>';
										echo '</tr>';
									foreach($tenants as $tenant){
										echo '<tr>';
											echo '<td>'.$tenant->ID.'</td>';
											echo '<td>'.$tenant->Name.'</td>';
											echo '<td>'.$tenant->RegDateTime.'</td>';
											if($user->data()->ID != $tenant->ID){
												echo '<td>';
												echo '<form action method="GET">';
												echo '<input type="hidden" name="deleting" value="'.$tenant->ID.'"/>';
												echo '<input type="submit" value="delete" onSubmit="Conform()" />';
												echo '</form>';
												echo '</td>';
											}
											echo '<td>';
											echo '<form action="Profile.php" method="GET">';
											echo '<input type="hidden" name="user_id" value="'.$tenant->ID.'"/>';
											echo '<input type="submit" value="profile"/>';
											echo '</form>';
											echo '</td>';
											if($GroupNo == 4){
												echo '<td>';
												echo '<form action method="GET">';
												echo '<input type="hidden" name="verify" value="'.$tenant->ID.'"/>';
												echo '<input type="submit" value="verify"/>';
												echo '</form>';
												echo '</td>';
											}
									}
									echo '</tr>';
									echo '</table>';
									
								}else{
									echo "<h2 align='center'>Empty</h2>";
								}
								echo '</div>';
					
				}else{
				
					Session::flash('error','Room Not Found (Use Exact Case)');
				
				}
				
			}
			?>
			</div>
			
		
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
        <!-- end of sidebar -->
        
    </div>
    <!-- end of container -->
    <script>
		function ViewAll(){
			window.open("Block.php", "_self");
		}
	</script>
</body>
</html>
