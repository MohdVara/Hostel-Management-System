<?php
class Warden extends User{
	//Report student listrecord
	//Report available room

	
		public function list_All($GroupNo){
			return $this->_db->get('users',array('GroupNo','=',$GroupNo))->result();
		}
		
		public function print_All($GroupNo){
			$users = $this->list_All($GroupNo);
			if($users){
				echo '<table>';
				foreach($users as $user){
					echo '<tr>'.'<td>'.$user->ID.'</td>';
					echo '<td>'.$user->Name.'</td>';
					echo '</tr>';
				}
				echo '</table>';
				return true;
			}
			return false;
		}		
	
	public function get_rooms_with_student($block_id){
		$rooms = $this->_db->get('room',array('room_block','=',$block_id));
		if($rooms){
			foreach($rooms as $room){
				$rdata = $room->data() 
				$user = $this->_db->get('users',array('RoomID','=',$rdata->roomid));
				
				echo '<div id=Room_Box';
					echo '<div id="Room_ID_header">';
						echo escape($rdata->roomid);
					echo '</div>';
					foreach($users as $user){
					echo '<tr>'.'<td>'.$user->ID.'</td>';
					echo '<td>'.$user->Name.'</td>';
					echo '<td>'.$user->Course.'</td>';
					echo '<td>'.$user->State.'</td>';
					if($user->ID != $this->data()->ID){
						echo '<td>';
						echo '<form action method="GET">';
						echo '<input type="hidden" name="deleting" value="'.$user->ID.'"/>';
						echo '<input type="submit" value="delete" />';
						echo '</form>';
						echo '</td>';
					}
					echo '<td>';
					echo '<form action="Profile.php" method="GET">';
					echo '<input type="hidden" name="user_id" value="'.$user->ID.'"/>';
					echo '<input type="submit" value="profile"/>';
					echo '</form>';
					echo '</td>';
					if($GroupNo == 4){
						echo '<td>';
						echo '<form action method="GET">';
						echo '<input type="hidden" name="Verify" value="'.$user->ID.'"/>';
						echo '<input type="submit" value="Verify"/>';
						echo '</form>';
						echo '</td>';
					}
				}
				echo '</tr>';
				echo '</table>';
				
			}
		}
	}
	
	
	
	public function find_Student($stud_id){
		return $this->_db->get('users',array('ID','=',$stud_id);
	}

	//View all student inside a bloack
	//Check vacant room
	//
	
}
?>