<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Driver extends CI_Controller {


	public function __construct() {
	parent::__construct();

		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('driver_model');
		if(!$this->session->userdata('logged_in')) {
			redirect(base_url());
		}
		else {
			$menu = $this->session->userdata('admin');
			 if( $menu!=1  ) {
				 $this->session->set_flashdata('message', array('message' => "You don't have permission to access testimonials page.",'class' => 'danger'));
				 redirect(base_url().'dashboard');
			 }
		}
		
 	}
	
// public function create() {
	
// 	$template['page'] = 'Driver/add-driver';
// 	$template['nav'] = '<li><a href="'.base_url('driver').'">Driver</a></li><li>Create</li>';
// 	$template['page_title'] = "Create Driver";
// 	$template['car_type'] = $this->db->where('status','1')->get('car_type')->result();
	      
// 	      if($_POST) {
// 			$data = $_POST;
// 			unset($data['submit']);
// 			$config = $this->set_upload_options();
			
// 			$this->load->library('upload');
// 			$this->upload->initialize($config);
			
// 			if ( ! $this->upload->do_upload('image')) {
// 				$this->session->set_flashdata('message', array('message' => 'Error Occured While Uploading Files','class' => 'danger'));
// 				//echo $this->upload->display_errors();
// 			}
// 			else {
			
// 			//$data['image'] = base_url().$config['upload_path']."/".$_FILES['image']['name'];
// 			$upload_data = $this->upload->data();
// 			$data['image'] = base_url().$config['upload_path']."/".$upload_data['file_name'];
// 			$result = $this->driver_model->save_driver($data);
			
// 			if($result == "Exist") {
// 			$this->session->set_flashdata('message', array('message' => 'Driver already exist','class' => 'danger'));
// 			   }
			
// 			else {	
// 			array_walk($data, "remove_html");
// 			$this->session->set_flashdata('message', array('message' => 'Driver Saved successfully','class' => 'success'));
// 			   }
			
//      		redirect(base_url().'driver');
// 		}
// 		  }
// 		else {
//    			$this->load->view('template', $template);
// 		}
// 	}





	public function create() {
			$message = '';
			$template['page'] = 'Driver/add-driver';
			$template['nav'] = '<li><a href="'.base_url('driver').'">Driver</a></li><li>Create</li>';
			$template['page_title'] = "Create Driver";
			$template['car_type'] = $this->db->where('status','1')->get('car_type')->result();
		if($_POST) {
			$data = $_POST;
			
			$driver_name = preg_replace('/\s+/', ' ',$data['driver_name']);
			$data['driver_name'] = preg_replace('/[^a-zA-Z0-9\s]/', '', $driver_name);
			
			unset($data['submit']);
			//$data['created_user'] = $this->session->userdata('logged_in')['id'];
			 $config = $this->set_upload_options();
			
			$this->load->library('upload');
			$this->upload->initialize($config);

			$result = $this->driver_model->save_driver($data);

			$driver_id = $this->db->insert_id();

			if($result == "Exist") {
				$num = $this->db->where('email',$data['email'])->get('driver')->num_rows();
   				if($num>0){
   					$message = 'Email already exist';
   				}else{
   					$message = 'Phone already exist';
   				}

				
			} else {	
				$message = 'Driver Saved successfully';
			}
			
			if ( ! $this->upload->do_upload('image')) {
				//$result = array('error' => $this->upload->display_errors());
				$message .= $this->upload->display_errors().'Error Occured While Uploading Files. ';
				//echo $this->upload->display_errors();
			}
			else {
			$upload_data = $this->upload->data();
			$driver_image = $config['upload_path']."/".$upload_data['file_name'];
			
				
			
				//$data['image'] = $driver_image;
				$this->db->where('id',$driver_id)->update('driver',array('image'=>$driver_image));
			


			


			

			
			
     		
		}

		if($result == "Exist") {
			$this->session->set_flashdata('message', array('message' => $message,'class' => 'danger'));
		} else {	
			$this->session->set_flashdata('message', array('message' => $message,'class' => 'success'));
		}


		
		redirect(base_url().'driver/view_driver');
	} else {
   			$this->load->view('template', $template);
		}
	}



 public function index() {
		$template['page'] = 'Driver/view-driver';
		$template['nav'] = '<li><a href="'.base_url('driver').'">Driver</a></li><li>View</li>';
		$template['page_title'] = "View Driver";
		$template['data'] = $this->driver_model->get_driver();
		$this->load->view('template',$template);
	}

	 public function view_driver() {
		$template['page'] = 'Driver/view-driver';
		$template['nav'] = '<li><a href="'.base_url('driver').'">Driver</a></li><li>View</li>';
		$template['page_title'] = "View Driver";
		$template['data'] = $this->driver_model->get_driver();
		$this->load->view('template',$template);
	}

// public function edit($id=null) {

// 		if($id==null){
// 			$this->session->set_flashdata('message', array('message' => 'Invalid information','class' => 'error'));
// 			redirect(base_url('driver'));
// 		}


		
// 		$template['page'] = 'Driver/edit-driver';
// 		$template['page_title'] = "Edit Driver";
// 		$template['car_type'] = $this->db->where('status','1')->get('car_type')->result();
		
// 		$id = $this->uri->segment(3);
// 		$template['data'] = $this->driver_model->get_single_driver($id);
// 		if(empty($template['data'])){
// 			$this->session->set_flashdata('message', array('message' => 'Driver Details not found','class' => 'error'));
// 			redirect(base_url('driver'));
// 		}
// 		if(!empty($template['data'])) {
// 		if($_POST) {
// 			$data = $_POST;
// 			unset($data['submit']);
			
// 			if(isset($_FILES['image'])) {
// 				$config = $this->set_upload_options();
			
// 				$this->load->library('upload');
// 				$this->upload->initialize($config);
				
// 				if ( ! $this->upload->do_upload('image')) {
// 					unset($data['image']);
// 				}
// 				else {
// 					//$data['image'] = base_url().$config['upload_path']."/".$_FILES['image']['name'];
// 					$upload_data = $this->upload->data();
// 					$data['image'] = base_url().$config['upload_path']."/".$upload_data['file_name'];
// 				}
// 			}
			
// 			//$data['created_user'] = $this->session->userdata('logged_in')['id'];
// 			$result = $this->driver_model->update_driver($data, $id);
// 			array_walk($data, "remove_html");
// 			$this->session->set_flashdata('message', array('message' => 'Driver Details Updated Successfully','class' => 'success'));
//      		redirect(base_url().'driver');
// 		}
// 		else {
//    			$this->load->view('template', $template);
// 		}
// 		}
		
// 	}




	public function edit($id=null) {

		if($id==null){
			$this->session->set_flashdata('message', array('message' => 'Invalid information','class' => 'error'));
			redirect(base_url('driver'));
		}
		
		$template['page'] = 'Driver/edit-driver';
 		$template['page_title'] = "Edit Driver";
		$template['car_type'] = $this->db->where('status','1')->get('car_type')->result();
		
		$id = $this->uri->segment(3);
		$template['data'] = $this->driver_model->get_single_driver($id);
		if(empty($template['data'])){
			redirect(base_url('driver'));
		}
		if($_POST) {
			$data = $_POST;
			
			unset($data['submit']);
			$notify = '';
			if(isset($data['nofity'])) {
			$notify = $data['nofity'];
			unset($data['notify']);
			}
			if(isset($_FILES['image'])) {
				$config = $this->set_upload_options();
			
				$this->load->library('upload');
				$this->upload->initialize($config);
				
				if ( ! $this->upload->do_upload('image')) {
					//unset($data['image']);
					$message = $this->upload->display_errors().'Error Occured While Uploading Files. ';
				}
				else {
					$upload_data = $this->upload->data();
					$data['image'] = $config['upload_path']."/".$upload_data['file_name'];
					//$data['profile_pic'] = base_url().$config['upload_path']."/".$_FILES['profile_pic']['name'];
				}
			}
			// print_r($data);
			// die();
			
			$result = $this->driver_model->update_driver($data, $id);
			
			if($result == "Exist") {
				$this->session->set_flashdata('message', array('message' => 'Driver already exist','class' => 'danger'));
			   }
			else if($result == "Already Exist") {
				$this->session->set_flashdata('message', array('message' => 'Email id already exist','class' => 'danger'));
			   }
				
			else {
				
				array_walk($data, "remove_html");
				$this->session->set_flashdata('message', array('message' => 'Driver Details Updated Successfully','class' => 'success'));
			   }
			
		
     		redirect(base_url().'driver');
		}
		else {
   			$this->load->view('template', $template);
		}
	}






	
public function view_single_driver() {
		$id = $_POST['id'];
		//print_r($id);
		$template['data'] = $this->driver_model->get_single_driver($id);
		$this->load->view('Driver/view-driver-popup',$template);
	}

public function delete_driver() {
		$id = $this->uri->segment(3);
		$result = $this->driver_model->delete_driver($id);
		$this->session->set_flashdata('message', array('message' => 'Driver Deleted Successfully','class' => 'success'));
     	redirect(base_url().'driver');
	}

private function set_upload_options() {   
		//upload an image options
		$config = array();
		$config['upload_path'] = 'assets/uploads/testimonials';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size']      = '5000';
		$config['overwrite']     = FALSE;
	
		return $config;
	}		
 	
}