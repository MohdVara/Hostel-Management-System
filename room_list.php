<?php
require_once'core/init.php';

if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$displayerrors= '';
		    $validate = new Validate();
		  $validation = $validate->check($_POST,array(
				'id' => array(
					'required' => trueh
				),
				'password' => array(
					'required' => true
				),
			));
		if($validation->passed())
		{
			$user = new User();
			$login = $user->login(Input::get('id'), Input::get('password'));
			if($login){
				Redirect::to('index.php');
			}else{
				Session::flash('error','Either id or password do not match');
			}
			
		}
		else{
			foreach($validation->errors() as $error){
				$displayerror.= $error.'<br>';
			}
			
			Session::flash('error',$displayerror);
		}
	
	}
}

?>
<!DOCTYPE html>
<html manifest="MMC.appcache" lang="en"> 
<head>
<meta charset=utf-8">
<meta name="Description" content="Multimedia College Hostel Registration System is a system where students are able to apply for hostel room in seconds.">
<title>MMC Hostel Registration</title>
<link rel="stylesheet" type="text/css" href="css/hostelRegistration.css">
</head>

<body>
	<!-- container -->
	<div id="container">
    	
        <!-- header -->
        <div id="header">
        <img src="image/project header.jpg" min-width="100%" height="100" />
        </div>
        <!-- end of header -->
        
        <!-- button -->
        <div id="NavBar">	
			<ul>
				<li><a href="">Room List</a></li>
				<li><a href="#">Contact</a></li>
			</ul>
		</div>
        <!-- end of button -->
        
        <!-- content -->
        <div id="content">
           <?php 
            
            $blocks = new Block();
            $blocks = $blocks->list_all_block();
            
            foreach($blocks as $block){
                echo '<h1>Block '.$block->block_id.'</h1>';
                echo '<br><h3>Available</h3>';
                    $rooms = new Room();
                    $rooms = $rooms->get_room_by_block_and_status($block->block_id,1);
                
                echo '<br><h3>Full</h3>';
                 $rooms = new Room();
                 $rooms = $rooms->get_room_by_block_and_status($block->block_id,0);
                
                echo '<br><br>';
            
            }
            
            
            
           ?> 
        </div>
        <!-- end of content -->
        
        <!-- sidebar -->
        <div id="sidebar">
        <p align="center"> <font size="+4"> Sign In </font> </p>
		<?php
			if(Session::exists('error'))
			{
				echo '<hr color="#666666"/>';
				echo '<p align="center">'.Session::flash('error').'</p>';
			}
		?>
        <hr color="#666666"/>
		
		<p align="center">Please insert your id <br /> and password :</p>
      
		
        <form action method="post" name="logInForm">	
		<table>
			<tr align="center"><td align="right">      ID :</td><td align="left"> <input name="id" type="text" /> </td> </tr>
        <tr align="center"><td align="right">Password :</td><td align="left"> <input name="password" type="password" /> <td> </tr>
		</table>
        <p align="center"> <input name="submit" type="submit" value="Submit" /> <input type="hidden" name="token" value="<?php echo Token::generate();?>">
		 
		 <input type="button" value="Register" onClick="window.location.href='registrationform.php'"/> </p> 
        </form> 
        
        <p align="center"> Please make sure you follow all <br /> regulations fixed by management and <br /> make sure you pay fees before <br /> date fixed. </p>
        </div>
        <!-- end of sidebar -->
        
    </div>
    <!-- end of container --?
    
</body>
</html>
