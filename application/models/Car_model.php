<?php 



class Car_model extends CI_Model {

	

	public function _consruct(){

		parent::_construct();

 	}





// function save_car($data) {

// 	 $result = $this->db->insert('car_type', $data); 

	 

// 	 if($result) {

// 		 return "Success";

// 	 }

// 	 else {

// 		 return "Error";

// 	 }

// 	}







	function save_car($data) {

	 $name = $data['name'];

	

	 $this->db->where('name', $name)->status('status!=','2');



	 $this->db->from('car_type');

	

	 $count = $this->db->count_all_results();

	 //$this->db->last_query();

	 if($count > 0) {

		 return "Exist";

	 }

	 else {

	 //	unset($data['created_user']);

	 $result = $this->db->insert('car_type', $data); 

	 

	 if($result) {

		 return "Success";

	 }

	 else {

		 return "Error";

	 }

	 }

	}







function get_car() {



		//$this->db->where('status', '1');

		$query = $this->db->where('status!=','2')->order_by("id","desc")->get('car_type');

		//$query = $this->$query

		//echo $this->db->last_query();



		$result = $query->result();



		return $result;

	}



function get_single_car($id) {

		$query = $this->db->where('id', $id);

		$this->db->where('status!=', '2');

		$query = $this->db->get('car_type');



		$result = $query->row();

		return $result;

	                     }	



// function update_car($data, $id) {

		

// 	 $this->db->where('id', $id);

// 	 $result = $this->db->update('car_type', $data); 

// 	 echo $this->db->last_query();

// 	 if($result) {

// 		 return "Success";

// 	 }

// 	 else {

// 		 return "Error";

// 	 }

// 	}





function update_car($data, $id) {

       $name = $data['name'];

	   

		

	 $this->db->where("id !=",$id);

	 

	 $this->db->where("(name = '$name')");

	 //$this->db->where('username', $username);

	// $this->db->or_where('email_id', $email_id);

	 $query= $this->db->get('car_type');

	 

	   

	    if($query -> num_rows() >0 ) {

	    

		 return "Exist";

	           }

	 else {

	 $this->db->where('id', $id);

	 $result = $this->db->update('car_type', $data); 

		 return "Success";

	 

	 }

	}



function delete_car($id) {



			$data = array(  

				'status' => '2'   

		  );  

  

		

	 $this->db->where('id', $id);

	$result =  $this->db->update('car_type', $data); 

	 //$result = $this->db->delete('driver');

	 //$data = array('status'=>'0') 

	  //= $this->db->update('driver'); 

	 

	 if($result) {

		 return "Success";

	 }

	 else {

		 return "Error";

	 }

	}



}	