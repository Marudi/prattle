<?php 

class Pattern_model extends CI_Model {
	
	public function _consruct(){
		parent::_construct();
 	}



// function save_pattern($data) {
// 	 $result = $this->db->insert('pattern', $data); 
	 
// 	 if($result) {
// 		 return "Success";
// 	 }
// 	 else {
// 		 return "Error";
// 	 }
// 	}


	function save_pattern($data) {
	 $name = $data['pattern_name'];
	
	 $this->db->where('pattern_name', $name);

	 $this->db->from('pattern');
	
	 $count = $this->db->count_all_results();
	 //$this->db->last_query();
	 if($count > 0) {
		 return "Exist";
	 }
	 else {
	 //	unset($data['created_user']);
	 $result = $this->db->insert('pattern', $data); 
	 
	 if($result) {
		 return "Success";
	 }
	 else {
		 return "Error";
	 }
	 }
	}



function get_pattern() {
	$query = $this->db->where('status', '1');
		$query = $this->db->order_by("id","desc")->get('pattern');


		$result = $query->result();

		return $result;
	}

function get_single_pattern($id) {
		$query = $this->db->where('id', $id);
		$this->db->where('status!=', '2');
		$query = $this->db->get('pattern');

		$result = $query->row();
		return $result;
	                     }	

// function update_pattern($data, $id) {
		
// 	 $this->db->where('id', $id);
// 	 $result = $this->db->update('pattern', $data); 

// 	 if($result) {
// 		 return "Success";
// 	 }
// 	 else {
// 		 return "Error";
// 	 }
// 	}



	function update_pattern($data, $id) {
       $pattern = $data['pattern_name'];
	   
		
	 $this->db->where("id !=",$id);
	 
	 $this->db->where("(pattern_name = '$pattern')");
	 //$this->db->where('username', $username);
	// $this->db->or_where('email_id', $email_id);
	 $query= $this->db->get('pattern');
	 
	   
	    if($query -> num_rows() >0 ) {
	    
		 return "Exist";
	           }
	 else {
	 $this->db->where('id', $id);
	 $result = $this->db->update('pattern', $data); 
	 // $array =array('type'=>$data['car_id']);
	 // $result = $this->db->where('id',$data['car_id'])->update('car', $array);
		 return "Success";
	 
	 }
	}

function get_currency($county){
	$rs = $this->db->where('name',$county)->get('countries')->row();
	if(count($rs)>0){
		return $rs->currrency_symbol;
	} else {
		return '$';
	}
	
}

function delete_pattern($id) {

			$data = array(  
				'status' => '0'   
		  );  
  
		
	 $this->db->where('id', $id);
	$result =  $this->db->update('pattern', $data); 

	 if($result) {
		 return "Success";
	 }
	 else {
		 return "Error";
	 }
	}

}	