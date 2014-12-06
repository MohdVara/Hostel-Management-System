<?php
class Block{
	
	private $_db,
			$_blockinfo;
	
	public function __construct(){
		$this->_db = DB::getInstance();
	}
	
	public function get_block($id,$field){
		return $this->_db->get2('block',$fields);
	}
	
	public function list_all_block(){
		return $this->_db->get('block',array(''))->result();
	}
	
	
	public function add_block($fields){
		if(!$this->_db->insert('block',$fields)){
		}
	}
	
	public function edit_block($id,$fields){
		$this->_db->update('block',$id,$fields,'room_id');
	}
	
	public function delete_block($id){
		if(!$this->_db->delete('block',array('block_id','=',$id))){
			throw new Exception('Block cannot be deleted');
		}
		return true;
	}
	
	public function get_vacant_room_registration_form(){
		if(!$this->_db->get('block',array(''))){
			throw new Exception('Error in getting empty blocks');
		}else{
			$blocks = $this->_db->get('block',array(''));
			echo '<option value="NULL">---</option>';
			foreach($blocks->result() as $block){
				$rooms = $this->_db->get2('room',array(
							array('room_block','=',$block->block_id),
							array('availability','=',1,'AND')
						))->result();
				foreach($rooms as $room){
					echo '<option value='.$room->room_id.'>'.$room->room_id.' ('.$block->block_gen.')</option>';
				}
			}
		}
	}
	
	
	

}
?>