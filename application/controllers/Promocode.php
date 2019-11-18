<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promocode extends CI_Controller {


	public function __construct() {
	parent::__construct();

		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('promocode_model');
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
	
// 	$template['page'] = 'Promocode/add-promocode';
// 	$template['nav'] = '<li><a href="'.base_url('promocode').'">Promocode</a></li><li>Create</li>';
// 	$template['page_title'] = "Create Promocode";
	      
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
// 			$result = $this->promocode_model->save_promocode($data);
			
// 			if($result == "Exist") {
// 			$this->session->set_flashdata('message', array('message' => 'Promocode already exist','class' => 'danger'));
// 			   }
			
// 			else {	
// 			array_walk($data, "remove_html");
// 			$this->session->set_flashdata('message', array('message' => 'Promocode Saved successfully','class' => 'success'));
// 			   }
			
//      		redirect(base_url().'promocode');
// 		}
// 		  }
// 		else {
//    			$this->load->view('template', $template);
// 		}
// 	}

 	public function create() {
	
	$template['page'] = 'Promocode/add-promocode';
	$template['nav'] = '<li><a href="'.base_url('promocode').'">Promocode</a></li><li>Create</li>';
	$template['page_title'] = "Create Promcocode";



	      
	    if($_POST) {
			$data = $_POST;

			$code = preg_replace('/\s+/', ' ',$data['code']);
			$data['code'] = preg_replace('/[^a-zA-Z0-9\s]/', '', $code);

			
			unset($data['submit']);
			$config = $this->set_upload_options();
			
			$this->load->library('upload');
			$this->upload->initialize($config);
			
			
			
			$result = $this->promocode_model->save_promocode($data);
			
			if($result == "Exist") {
			$this->session->set_flashdata('message', array('message' => 'Promocode already exist','class' => 'danger'));
			   }
			
			else {	
			array_walk($data, "remove_html");
			$this->session->set_flashdata('message', array('message' => 'Promocode Saved successfully','class' => 'success'));
			   }
			
     		redirect(base_url().'promocode');
		
			
			}
	$this->load->view('template', $template);
	}




 public function index() {
		$template['page'] = 'Promocode/view-promocode';
		$template['nav'] = '<li><a href="'.base_url('promocode').'">Promocode</a></li><li>View</li>';
		$template['page_title'] = "View Promocode";
		$template['data'] = $this->promocode_model->get_promocode();
		$this->load->view('template',$template);
	}

// public function edit($id=null) {

// 		if($id==null){
// 			$this->session->set_flashdata('message', array('message' => 'Invalid information','class' => 'error'));
// 			redirect(base_url('promocode'));
// 		}


// 		$template['nav'] = '<li><a href="'.base_url('promocode').'">Promocode</a></li><li>Edit</li>';
// 		$template['page'] = 'Promocode/edit-promocode';
// 		$template['page_title'] = "Edit Promocode";
		
// 		$id = $this->uri->segment(3);
// 		$template['data'] = $this->promocode_model->get_single_promocode($id);
// 		if(empty($template['data'])){
// 			$this->session->set_flashdata('message', array('message' => 'Promocode Details not found','class' => 'error'));
// 			redirect(base_url('promocode'));
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
// 			$result = $this->promocode_model->update_promocode($data, $id);
// 			array_walk($data, "remove_html");
// 			$this->session->set_flashdata('message', array('message' => 'Promocode Details Updated Successfully','class' => 'success'));
//      		redirect(base_url().'promocode');
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
		
		$template['page'] = 'Promocode/edit-promocode';
 		$template['page_title'] = "Edit Promocode";
		
		$id = $this->uri->segment(3);
		$template['data'] = $this->promocode_model->get_single_promocode($id);
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
					$data['image'] = base_url().$config['upload_path']."/".$upload_data['file_name'];
					//$data['profile_pic'] = base_url().$config['upload_path']."/".$_FILES['profile_pic']['name'];
				}
			}
			// print_r($data);
			// die();
			
			$result = $this->promocode_model->update_promocode($data, $id);
			
			if($result == "Exist") {
				$this->session->set_flashdata('message', array('message' => 'Promocode already exist','class' => 'danger'));
			   }
			// else if($result == "Already Exist") {
			// 	$this->session->set_flashdata('message', array('message' => 'Email id already exist','class' => 'danger'));
			//    }
				
			else {
				
				array_walk($data, "remove_html");
				$this->session->set_flashdata('message', array('message' => 'Promocode  Updated Successfully','class' => 'success'));
			   }
			
		
     		redirect(base_url().'promocode');
		}
		else {
   			$this->load->view('template', $template);
		}
	}


public function view_single_promocode() {
		$id = $_POST['id'];
		//print_r($id);
		$template['data'] = $this->promocode_model->get_single_promocode($id);
		$this->load->view('Promocode/view-promocode-popup',$template);
	}

public function delete_promocode() {
		$id = $this->uri->segment(3);
		$result = $this->promocode_model->delete_promocode($id);
		$this->session->set_flashdata('message', array('message' => 'Promocode Deleted Successfully','class' => 'success'));
     	redirect(base_url().'promocode');
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