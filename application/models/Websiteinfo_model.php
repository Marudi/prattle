<?php 

class Websiteinfo_model extends CI_Model {
	
	public function _consruct(){
		parent::_construct();
 	}
	
	function website_info($data){
     
    /*value=array(
     	          'title'=>$data['title'],
     	          'logo'=>$data['logo'],
     	          'favicon'=>$data['favicon'],
     	          'smtp_username'=>$data['smtp_username'],
     	          'smtp_host'=>$data['smtp_host'],
     	          'smtp_password'=>$data['smtp_password'],
                  'admin_email'=>$data['admin_email'],
                  'sms_gateway_username'=>$data['sms_gateway_username'],
                  'sms_gateway_password'=>$data['sms_gateway_password']
     	         );   */
        $this->db->where('id',1);
        $query = $this->db->update('set',$data);

	/* $key=array('security_key'=>$data['security_key']);   
        
        $this->db->where('id',1);
        $query = $this->db->update('keys',$key);*/
		return $query; 
	}
	function get_info(){
        
        $this->db->select('set.*,keys.id,keys.security_key');
        $this->db->from('set');
        $this->db->join('keys', '1=1','left');
		$query = $this->db->get();
		$result = $query->row();
		return $result;  
	 }	
}









