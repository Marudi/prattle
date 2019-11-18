<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pattern extends CI_Controller {


	public function __construct() {
	parent::__construct();

		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('pattern_model');
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
	
public function create() {
	
	$template['page'] = 'Pattern/add-rate';
	$template['nav'] = '<li><a href="'.base_url('pattern').'">Region</a></li><li>Create</li>';
	$template['page_title'] = "Create Region";
	$template['car_type'] = $this->db->where('status','1')->get('car_type')->result();
	      
	    if($_POST) {
			$data = $_POST;
			unset($data['submit']);
			$config = $this->set_upload_options();
			
			$this->load->library('upload');
			$this->upload->initialize($config);
			
			
			
			$result = $this->pattern_model->save_pattern($data);
			
			if($result == "Exist") {
			$this->session->set_flashdata('message', array('message' => 'Pattern already exist','class' => 'danger'));
			   }
			
			else {	
			array_walk($data, "remove_html");
			$this->session->set_flashdata('message', array('message' => 'Pattern Saved successfully','class' => 'success'));
			   }
			
     		redirect(base_url().'pattern');
		
			
			}
	$this->load->view('template', $template);
	}

	

 public function index() {
		$template['page'] = 'Pattern/view-rate';
		$template['nav'] = '<li><a href="'.base_url('pattern').'">Region</a></li><li>View</li>';
		$template['page_title'] = "View Region";
		$template['data'] = $this->pattern_model->get_pattern();
		$this->load->view('template',$template);
	}

// public function edit($id=null) {

// 		if($id==null){
// 			$this->session->set_flashdata('message', array('message' => 'Invalid information','class' => 'error'));
// 			redirect(base_url('pattern'));
// 		}


// 		$template['nav'] = '<li><a href="'.base_url('pattern').'">Region</a></li><li>Edit</li>';
// 		$template['page'] = 'Pattern/edit-rate';
// 		$template['page_title'] = "Edit Region";
// 		$template['car_type'] = $this->db->where('status','1')->get('car_type')->result();
		
// 		$id = $this->uri->segment(3);
// 		$template['data'] = $this->pattern_model->get_single_pattern($id);
// 		if(empty($template['data'])){
// 			$this->session->set_flashdata('message', array('message' => 'Pattern  not found','class' => 'error'));
// 			redirect(base_url('pattern'));
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
// 			$result = $this->pattern_model->update_pattern($data, $id);
// 			array_walk($data, "remove_html");
// 			$this->session->set_flashdata('message', array('message' => 'Pattern Details Updated Successfully','class' => 'success'));
//      		redirect(base_url().'pattern');
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
		
		$template['page'] = 'Pattern/edit-rate';
 		$template['page_title'] = "Edit Driver";
		
		$id = $this->uri->segment(3);
		$template['data'] = $this->pattern_model->get_single_pattern($id);
		$template['car_type'] = $this->db->where('status','1')->get('car_type')->result();
		if(empty($template['data'])){
			redirect(base_url('pattern'));
		}
		if($_POST) {
			$data = $_POST;

			//print_r($_POST);

		//	die();
			
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
					$data['image'] = base_url().$config['upload_path']."/".$upload_data['file_name'];
					//$data['profile_pic'] = base_url().$config['upload_path']."/".$_FILES['profile_pic']['name'];
				}
			}
			// print_r($data);
			// die();
			
			$result = $this->pattern_model->update_pattern($data, $id);
			
			if($result == "Exist") {
				$this->session->set_flashdata('message', array('message' => 'Region already exist','class' => 'danger'));
			   }
			// else if($result == "Already Exist") {
			// 	$this->session->set_flashdata('message', array('message' => 'Email id already exist','class' => 'danger'));
			//    }
				
			else {
				
				array_walk($data, "remove_html");
				$this->session->set_flashdata('message', array('message' => 'Region  Updated Successfully','class' => 'success'));
			   }
			
		
     		redirect(base_url().'pattern');
		}
		else {
   			$this->load->view('template', $template);
		}
	}




public function view_single_pattern() {
		$id = $_POST['id'];
		//print_r($id);
		$template['data'] = $this->pattern_model->get_single_pattern($id);
		$this->load->view('Pattern/view-rate-popup',$template);
	}

public function delete_pattern() {
		$id = $this->uri->segment(3);
		$result = $this->pattern_model->delete_pattern($id);
		$this->session->set_flashdata('message', array('message' => 'Pattern Deleted Successfully','class' => 'success'));
     	redirect(base_url().'pattern');
	}


	public function get_currency(){
		$data = $_POST;
		$currency = $this->pattern_model->get_currency($data['county']);
		echo $currency;
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