<?php
require_once('core/init.php');
if(!$user->isLoggedIn()){
	Redirect::to('login.php');
}

if(Input::exists()){
		if(Token::check(Input::get('token'))){
			$displayerror = '';
			$validate = new Validate();
			$validation = $validate->check($_POST,array(
				'msgTitle' => array(
					'required' => true,
					'max' =>20
				),
				
				'msgBody' => array(
					'required' => true,
					'max' => 100
				),
				
				'msgType' => array(
					'required' => true
				),
				
			));
			
			if($validation->passed()){
				$message= new Message($user->data()->ID,array(
					'message_title' => Input::get('msgTitle'),
					'message_body' => Input::get('msgBody'),
					'message_type' => Input::get('msgType'),
					'message_DateTime' => datetime(),
					'sender_id' => $user->data()->ID,
					'receiver_id' => (!Input::get('msgReceipient'))? null : Input::get('msgReceipient')	
				));
				$message->send();
				
			}else{
				
				foreach($validation->errors() as $error){
					$displayerror.= $error.'<br>';
				}
			
				Session::flash('error',$displayerror);
			}
	
		}
}
?>