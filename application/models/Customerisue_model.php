<?php 

class Customerissue_model extends CI_Model {
	
	public function _consruct(){
		parent::_construct();
 	}
	


	function get_customer() {
		$query = $this->db->get('customer_issue');
		$result = $query->result();
		return $result;
	}
	

}









