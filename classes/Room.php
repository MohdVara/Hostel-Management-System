<?php
class Room{
	private $_db,
			$_roominfo;
			
	public function __construct($room = null){
		$this->_db = DB::getInstance();	
	}
	
	public function get_room_info($room_id){
		$this->_roominfo = $this->_db->get('room',array('room_id','=',$room_id));
		return _roominfo;
	}
	
	public function get_room($room_id){
		return $this->_db->get('room',array('room_id','=',$room_id))->first();
	}
	
	public function add_room($fields){
		if(!$this->_db->insert('room',$fields)){
			throw new Exception('Cannot add room');
		}
		return true; 
	}
	
	public function edit_room($id,$fields){
		if(!$this->_db->update('room',$id,$fields,'room_id')){
			throw new Exception('Could not edit room');
		}
		return true;
	}
	
	public function delete_room($id){
		if(!$this->_db->delete('room',array('room_id','=','$room_id'))){
			throw new Exception('Could not delete room');			
		}
		return true;
	}
	
	public function list_all_rooms($block_id){
		return $this->_db->get('room',array('room_block','=',$block_id))->result();
	}
	
	public function get_room_tenants($room_id){
		return $this->_db->get('users',array('RoomID','=',$room_id))->result();
	}
	
	public function add_tenant($room_id){
		$room_info = $this->get_roon_info($room_id);
		if(!$this->_db->update('room',$room_id,array('room_occupied' => $room_info->room_occupied+1),'room_id')){
			echo 'Add tenant error';
		}
	}
	
	public function mark_full($room_id){
			if(!$this->_db->update('room',$room_id,array('availability' => false),'room_id')){
			echo 'Mark full error';
		}
	}
	
	public function remove_tenant($room_id){
		$room_info = $this->get_roon_info($room_id);
		if(!$this->_db->update('room',$room_id,array('room_occupied' => $room_info->room_occupied-1),'room_id')){
			echo 'Add tenant error';
		}
	}
	
	public function check_room_capacity($room_id){
		$room = $this->_db->get('room',array('room_id','=',$room_id))->first();
		if(!$room->room_cap <= $room->room_occupied){
			if(!$this->_db->update('room',$room_id,array('availability','=',false),'room_id')){
				throw new Exception('Unable to update room availability');
				return false;
			}
		}else{
			return false;
		}
		return true;
	}
		
	public function get_room_by_block_and_status($block_id,$status){
		if(!$this->_db->get('room',array(''))){
			throw new Exception('Error in getting empty blocks');
			return false;
		}else{
			$rooms = $this->_db->get2('room',array(
				array('room_block','=',$block_id),
				array('availability','=',1,'AND'),
			))->result();
			echo '<table id="data">';
			echo '<tr><td> Room ID </td><td>Room Type </td><td> Room Price per person </td><td> Current number of occupants </td></tr>';
			foreach($rooms as $room){		
					echo '<tr>';
						echo '<td>'.$room->room_id.'</td>';
						echo '<td>'.$room->room_cap.' person </td>';
						echo '<td> RM'.$room->room_price.'</td>';
						echo '<td>'.$room->room_occupied.' person</td>';
					echo '</tr>';
			}
			echo '</table>';
			
		}
		return true;
	}
		

}
?>