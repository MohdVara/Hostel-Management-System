<?php
class Admin extends User{
		
		//Assign Student to room available
		//Report payment paid
		//Report student who didn't pay
		//Delete Student
		//private _userdata;
		
		public function __construct(){
			
			parent::__construct($user=null);
		}
		
		public function list_All($GroupNo){
			return $this->_db->get('users',array('GroupNo','=',$GroupNo))->result();
		}
		
		public function get_student($user_id){
			return $this->_db->get('users',array(''));
		}
		
		public function print_All($GroupNo){
			$users = $this->list_All($GroupNo);
			if($users){
				echo '<table id="data">';
				foreach($users as $user){
					echo '<tr>'.'<td>'.$user->ID.'</td>';
					echo '<td>'.$user->Name.'</td>';
					echo '<td>'.$user->Course.'</td>';
					echo '<td>'.$user->State.'</td>';
					if($user->ID != $this->data()->ID){
						echo '<td>';
						echo '<form action method="GET">';
						echo '<input type="hidden" name="deleting" value="'.$user->ID.'"/>';
						echo '<input type="submit" value="delete" onSubmit="Conform()" />';
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
						echo '<input type="hidden" name="verify" value="'.$user->ID.'"/>';
						echo '<input type="submit" value="verify"/>';
						echo '</form>';
						echo '</td>';
					}
				}
				echo '</tr>';
				echo '</table>';
				return true;
			}
			return false;
		}	
		
		
		public function Verify_Student($stud_id){
			
			if(!$this->_db->update('users',$stud_id,array('GroupNo' => 1),'ID')){
				throw new Exception('There was an error verifying');
				return false;
			}
			return true;
		}
		
		public function delete_User($id){
			if(!$this->_db->delete('users',array('ID','=',$id ))){
				throw new Exception('There was a problem deleting.');
				return false;
			}
			
			return true;
		}	
		
		public function get_notPaid(){
			return array_reverse($this->_db->get2('users',array(
												array('GroupNo','=',1),
												array('Debt','=',true,'AND')))->result());
		
		}
		

		
}	
?>