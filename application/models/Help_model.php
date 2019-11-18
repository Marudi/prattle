<?php 



class Help_model extends CI_Model {

	

	public function _consruct(){

		parent::_construct();

 	}

// function save_help($data) {

// 	 $result = $this->db->insert('help_table', $data); 

	 

// 	 if($result) {

// 		 return "Success";

// 	 }

// 	 else {

// 		 return "Error";

// 	 }

// 	}





	function save_help($data) {

		 //print_r($data);

		// die();

	 $name = $data['head'];

	

	 $this->db->where('head', $name);



	 $this->db->from('help_table');

	

	 $count = $this->db->count_all_results();

	 

	 if($count > 0) {

		 return "Exist";

	 }

	 else {

	 //	unset($data['created_user']);

	 $result = $this->db->insert('help_table', $data); 

	// $this->db->last_query();

	 if($result) {

		 return "Success";

	 }

	 else {

		 return "Error";

	 }

	 }

	}



	

function get_help() {

	   $query = $this->db->where('status', '1');

		$query = $this->db->order_by("id","desc")->get('help_table');

		$result = $query->result();

		return $result;

	}



function get_single_help($id) {

		$query = $this->db->where('id', $id);

		$query = $this->db->get('help_table');

		$result = $query->row();

		return $result;

	                     }	



// function update_help($data, $id) {

		

// 	 $this->db->where('id', $id);

// 	 $result = $this->db->update('help_table', $data); 

	 

// 	 if($result) {

// 		 return "Success";

// 	 }

// 	 else {

// 		 return "Error";

// 	 }

// 	}







	function update_help($data, $id) {

       $head = $data['head'];

		

	 $this->db->where("id !=",$id);

	 

	 $this->db->where("(head = '$head')");

	 //$this->db->where('username', $username);

	// $this->db->or_where('email_id', $email_id);

	 $query= $this->db->get('help_table');

	 

	   

	    if($query -> num_rows() >0 ) {

	    

		 return "Exist";

	           }

	 else {

	 $this->db->where('id', $id);

	 $result = $this->db->update('help_table', $data); 



		 return "Success";

	 

	 }

	}







function delete_help($id) {

		//print_r($id);



	$data = array(  

				'status' => '0'   

		  ); 

	 $this->db->where('id', $id);

	 $result = $this->db->update('help_table',$data); 

	// echo $this->db->last_query();

	 if($result) {

		 return "Success";

	 }

	 else {

		 return "Error";

	 }

	}



}	