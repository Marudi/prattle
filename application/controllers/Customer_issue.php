<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_issue extends CI_Controller {


	public function __construct() {
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('customerissue_model');
		if(!$this->session->userdata('logged_in')) {
			redirect(base_url());
		}
		else {
			$profile = $this->router->fetch_method();
			if($profile != 'profile') {
			$menu = $this->session->userdata('admin');
			 if( $menu!=1  ) {
				 $this->session->set_flashdata('message', array('message' => "You don't have permission to access user page.",'class' => 'danger'));
				 redirect(base_url().'dashboard');
			 }
			}
		}
 	}

  	public function view_customer() {
		$template['page'] = 'Customerissue/view-customerissue';
		$template['page_title'] = "Add Customer Issue";
		$template['data'] = $this->customerissue_model->get_customer();
		$this->load->view('template',$template);
	}
	
}	

