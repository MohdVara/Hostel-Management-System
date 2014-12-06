<?php
class Message{
	private $_db,
			$_id,
			$_message_data,
			$_inbox,
			$_read;
	
	public function __construct($id = null,$message = null){
		$this->_db = DB::getInstance();
		$this->_id = $id;
		$this->_message_data = $message;
	}
	
	public function send(){
		if(!$this->_db->insert('message',$this->_message_data)){
			throw new Exception('There was problem in sending a message');
		}
	}
	
	public function get_a_message($message_id){
		return $this->_db->get('message',array('message_id','=',$message_id))->result()[0];
			
	}
	
	public function delete_a_message($message_id){
		if(!$this->_db->delete('message',array('message_id','=',$message_id))){
			throw new Exception('Error deleting message id= {$message_id}');
		}
	}
	
	public function markread($message_id){
		return $this->_db->update('message',$message_id,array('read' => true),'message_id');
	}
	
	
	public function get_all_private_messages(){
		return array_reverse($this->_db->get('message',array('receiver_id','=',$this->_id))->result());
	}
	
	public function print_messages($type,$GroupNo=null){	
		
		if($type == 'private'){
			$messages = $this->get_all_private_messages();
			if($messages){
				
				foreach($messages as $message){
					echo'<div id="feed">';
					echo'<h2>'.$message->message_title.'</h2>';
					echo'<div id="date">'.$message->message_DateTime.'</div>';
					echo'<hr>';
					echo'<p>'.$message->message_body.'</p>';
					if($GroupNo == 3){
						echo '<form action method="GET">';
						echo '<input type="hidden" name="deleting" value="'.$message->message_id.'">';
						echo '<input type="submit" value="delete"/>';
						echo '</form>';
					}
					echo'</div>';
				}
				return true;
			}
		}
		
		if($type == 'feed'){
			$messages = $this->get_feed();
			if($messages){
				
				foreach($messages as $message){
					echo'<div id="feed">';
					echo'<h2>'.$message->message_title.'</h2>';
					echo'<div id="date">'.$message->message_DateTime.'</div>';
					echo'<hr>';
					echo'<p>'.$message->message_body.'</p>';
					if($GroupNo == 3){
						echo '<form action method="GET">';
						echo '<input type="hidden" name="deleting" value="'.$message->message_id.'">';
						echo '<input type="submit" value="delete"/>';
						echo '</form>';
					}
					echo'</div>';
				}
				return true;
			}
		}
		return false;
	}
	
	public function get_feed(){
		return array_reverse($this->_db->query('SELECT * FROM message WHERE receiver_id IS NULL')->result());
	}
	
	
	
}
?>