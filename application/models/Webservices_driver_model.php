<?php

class Webservices_driver_model extends CI_Model{

	function __construct(){

		parent::__construct();

	}










   //  public function driver_reg($data){





   //      $num = $this->db->where('phone',$data['phone'])->get('driver')->num_rows();

   //      if($num>0){

   //          $result = array('status'=>'error');

   //      } else {

   //          $unique_id = $this->generate_unique();

   //          $otp = rand(1111,9999);

   //          $this->db->insert('driver',array('phone'=>$data['phone'],'email'=>$data['email'],'driver_name'=>$data['name'],'city'=>$data['city'],'password'=>md5($data['password'])));

   // // echo $this->db->last_query();

   //          $user_id = $this->db->insert_id();

   //          $this->db->insert('car',array('driv_id'=>$user_id));





   //          for($i=1;$i<=12;$i++){

   //              $data1 = array('driver_id'=>$user_id,

   //                            'type'=>$i);

   //              $new_result[] = $data1;

   //          }



   //          $this->db->insert_batch('driver_document',$new_result);



   //         // $data1 = array('driver_id'=>'$user_id','type'=>'')

   //          $this->db->insert('car',array('driver_id'=>$user_id));

   //          //echo $this->db->last_query();

          

   //          $this->EncryptedPatientKey($unique_id,$user_id);

            

   //          if($user_id){

   //               //print_r($data);



   //             $result = array('status'=>'success','user_id'=>$user_id,'auth_token'=>$unique_id,'is_phone_verified'=>true,'name'=>$data['name'],'email'=>$data['email'],'city'=>$data['city'],'phone'=>$data['phone']); 

            

   //         } else {

   //              $result = array('status'=>'error');

   //         }

            

            

   //      }

       

   //      return $result;

   //  }



     public function driver_reg($data){

        $num = $this->db->where('phone',$data['phone'])->get('driver')->num_rows();

        $num1 = $this->db->where('email',$data['email'])->get('driver')->num_rows();

          if($num>0){
            $result = array('status'=>'error','message'=>'Mobile number Already Exists');
        }elseif($num1>0){
            $result = array('status'=>'error','message'=>'Email Already Exists');
        }else{
                $unique_id = $this->generate_unique();
                 $otp = rand(1111,9999);
                 $this->db->insert('driver',array('phone'=>$data['phone'],'email'=>$data['email'],'driver_name'=>$data['name'],'city'=>$data['city'],'password'=>md5($data['password'])));
                 $user_id = $this->db->insert_id();
                 $this->db->insert('car',array('driv_id'=>$user_id));
                 for($i=1;$i<=12;$i++){
                 $data1 = array('driver_id'=>$user_id,
                             'type'=>$i);
                 $new_result[] = $data1;
                }
                 $this->db->insert_batch('driver_document',$new_result);
                 $this->db->insert('car',array('driver_id'=>$user_id));
                 $this->EncryptedPatientKey($unique_id,$user_id);

                 if($user_id){

                $result = array('status'=>'success','user_id'=>$user_id,'auth_token'=>$unique_id,'is_phone_verified'=>true,'name'=>$data['name'],'email'=>$data['email'],'city'=>$data['city'],'phone'=>$data['phone']); 
            
            } else {
                 $result = array('status'=>'error');
            }
            
            
         }
       
         return $result;
             
        
      
    }

 









    public function generate_unique(){

        $unqe = md5(uniqid(time().mt_rand(), true));

        return $unqe;

    }



    public function EncryptedPatientKey($unique_id,$user_id){

        $this->db->insert('driver_auth_table',array('driver_id'=>$user_id,'unique_id'=>$unique_id));

    }







    function login($request){

        

        $this->db->where("email",$request['email']);

        $this->db->where('password' , md5($request['password']));



      //  $this->db->where('status' ,IN (1,0));

        $this->db->where('status !=', 2);

        $query = $this->db->get('driver');

     //  echo $this->db->last_query();

        if($query->num_rows()>0){

            $unique_id = $this->generate_unique();

            $otp = rand(1111,9999);

            $rs = $query->row();

            $this->EncryptedPatientKey($unique_id,$rs->id);

            return $result = array('status'=>'success','user_id'=>$rs->id,'auth_token'=>$unique_id,'name'=>$rs->driver_name,'phone'=>$rs->phone,'email'=>$rs->email,'city'=>$rs->city,'profile_photo'=>$rs->image);

        }else{

            return false;

        }



    }







    public function update_fcm($request){

        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        if($query->num_rows()>0){

            $rs = $query->row();

            $data = array('fcm_token'=>$request['fcm_token']);

            $this->db->where('id',$rs->driver_id)->update('driver',$data);

            return true;

        } else {

            return false;

        }

    

    }







    public function mobile_availability($request){



         $phone = $request['phone'];

         $num = $this->db->where('phone',$phone)->get('driver')->num_rows();

         if($num>0){

             return $result = array('status'=>'success','phone'=>$phone,'is_available'=>'false');

         } else {

             return false;

         }



    }







    public function profile($request){



    $id = $request['id'];

    if($id){



        $data = "SELECT * FROM driver WHERE id = '$id' ";

        $query = $this->db->query($data);

        $rs = $query->row();

        return $result = array('status'=>'success','id'=>$rs->id,'name'=>$rs->driver_name,'phone'=>$rs->phone,'email'=>$rs->email,'address'=>$rs->address,'city'=>$rs->city,'state'=>$rs->state,'postal_code'=>$rs->post_code,'profile_photo'=>$rs->image);





    }else{



        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        if($query->num_rows()>0){

            $rs = $query->row();

            $driv_id = $rs->driver_id;



            $data1 = "SELECT * FROM driver WHERE id = '$driv_id' ";

            $query1 = $this->db->query($data1);

            $rs = $query1->row();



            return $result = array('status'=>'success','id'=>$rs->id,'name'=>$rs->driver_name,'phone'=>$rs->phone,'email'=>$rs->email,'address'=>$rs->address,'city'=>$rs->city,'state'=>$rs->state,'postal_code'=>$rs->post_code,'profile_photo'=>$rs->image);



        } else {

            return false;

        }



    }





  }







  // public function prof_update($request){

  //       $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

  //       if($query->num_rows()>0){

  //           $rs = $query->row();

  //           print_r($rs->driver_id);

  //           $data = array('driver_name'=>$request['name'],'email'=>$request['email'],'phone'=>$request['phone'],'address'=>$request['address'],'city'=>$request['city'],'state'=>$request['state'],'post_code'=>$request['postal_code'],'image'=>$request['image']);

  //           $this->db->where('id',$rs->driver_id)->update('driver',$data);

  //           //echo $this->db->last_query();

  //           return $result = array('id'=>$rs->driver_id,'name' => $data['driver_name'],'phone'=>$data['phone'],'email'=>$data['email'],'address'=>$data['address'],'city'=>$data['city'],'state'=>$data['state'],'post_code'=>$data['post_code'],'image'=>$data['image']);

  //       }else{

  //           return false;

  //       }



  //   }







  function prof_update($request){

   // print_r($request);

        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        if($query->num_rows()>0){



            $data = array();

            if(isset($request['name'])&& $request['name']!=''){

                $data['driver_name'] = $request['name'];

            }

            if(isset($request['email'])&& $request['email']!=''){

                $data['email'] = $request['email'];

            }

            if(isset($request['phone'])&& $request['phone']!=''){

                $data['phone'] = $request['phone'];

            }

            if(isset($request['address'])&& $request['address']!=''){

                $data['address'] = $request['address'];

            }

            if(isset($request['city'])&& $request['city']!=''){

                $data['city'] = $request['city'];

            }

            if(isset($request['state'])&& $request['state']!=''){

                $data['state'] = $request['state'];

            }

            if(isset($request['postal_code'])&& $request['postal_code']!=''){

                $data['post_code'] = $request['postal_code'];

            }

            if(isset($_FILES['profile_photo'])){





                $image = '';



                if(is_uploaded_file($_FILES['profile_photo']['tmp_name'])){ 

                    $uploads_dir = './assets/uploads/profile_pic/';

                    $tmp_name = $_FILES['profile_photo']['tmp_name'];

                    $pic_name = $_FILES['profile_photo']['name']; 

                    $pic_name = str_replace(' ', '_', mt_rand().$pic_name);

                    move_uploaded_file($tmp_name, $uploads_dir.$pic_name);

                    $image = $uploads_dir.$pic_name;    

                }

                



                if($image!=''){

 

                    $data['image'] = $image;

                }   

            }





            $rs = $query->row();

   

            $this->db->where('id',$rs->driver_id)->update('driver',$data);

            $res = $this->db->query("SELECT * FROM `driver` WHERE id = '$rs->driver_id'")->row();

           // print_r($res);

            return $res;

            

            //return true;

        }else{

            return false;

        }



    }







    function doc_upload($request){

        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        if($query->num_rows()>0){

            $rs = $query->row();

            $driv_id = $rs->driver_id;

            $data = array('image'=>$request['image'],'status'=>'1');

            //$this->db->update('driver_document',$data);

            $this->db->where('type',$request['type'],'driver_id',$driv_id)->update('driver_document',$data);

          // echo $this->db->last_query();

            return true;

        }else{

            return false;

        }



    }







    //  public function doc_status($request){

        

        

    //     $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        

    //     if($query->num_rows()>0){





    //          $rs = $query->row();

    //          $id = $rs->driver_id;



    //         $data = "SELECT * FROM driver_document WHERE driver_id = '$id'";

    //         $query = $this->db->query($data);

    //         $result = $query->result_array();







    //         return $result;

    //     } else {

    //         return false;

    //     }

    

    // }







     public function doc_status($request){



        $this->load->helper('general');



        

        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        

        if($query->num_rows()>0){





             $rs = $query->row();

             $id = $rs->driver_id;



            $data = "SELECT * FROM driver_document WHERE driver_id = '$id'";

            $query = $this->db->query($data);

            //echo $this->db->last_query();

            $result = $query->result_array();

            $array_type = array();

            



            foreach ($result as $rs) {

                $array_type[] = $rs['type'];

            }



            $dr_doc = array();



            for($i=1;$i<=12;$i++){

                if(in_array($i,$array_type)){

                    //

                } else {

                    $dr_doc[] = array('driver_id'=>$id,

                                    'type'=>$i);

                }

            }



            if(!empty($dr_doc)){

                $this->db->insert_batch('driver_document',$dr_doc);

                $data = "SELECT * FROM driver_document WHERE driver_id = '$id'";

                $query = $this->db->query($data);

                //echo $this->db->last_query();

                $result = $query->result_array();

            }



            



            



            foreach ($result as $rs) {



                





            if ($rs['status'] != 0){



                $is_uploaded = 'true';



             }else{



                 $is_uploaded = 'false';

             }



             $name = get_document_name($rs['type']);



                $new_array = array('id'=>$rs['id'],

                                    'type'=>(int)$rs['type'],

                                    'document_status' => (int)$rs['status'],

                                    'is_uploaded' =>filter_var ($is_uploaded, FILTER_VALIDATE_BOOLEAN),

                                    'name'=> $name,

                                    'document_url'=>$rs['image']

                                    );



                $result_array[] = $new_array;

                # code...

            }

           // echo $this->db->last_query();

           // $result[] =

            // if ($result['status'] != '0'){



            //     $is_uploaded = 'false';



            //  }else{



            //      $is_uploaded = 'true';

            //  }

//print_r($result_array);

            return $result_array;

        } else {

            return false;

        }

    

    }









    function type_driver($request){

        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        if($query->num_rows()>0){

            $rs = $query->row();

            $driv_id =  $rs->driver_id;

            



            $result = $this->db->where('id',$driv_id)->update('driver',array('driver_type'=>$request['driver_type']));

            return true;

        }else{

            return false;

        }



    }



    function driver_status($request){

        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        if($query->num_rows()>0){

            

           

            $rs = $query->row();



            

           $query =  $this->db->where('id',$rs->driver_id)->get('driver')->row();

           

           $res = $query->is_online;

       

           $new_array = array(

                            'driver_status' =>filter_var ($res, FILTER_VALIDATE_BOOLEAN)

                                    

                        );







            return $new_array;

        }else{

            return false;

        }



    }







    function photo_upload($request){

        //print_r($request);

        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        if($query->num_rows()>0){

            $rs = $query->row();

            $data = array('image'=>$request['image']);

            $this->db->where('id',$rs->driver_id)->update('driver',$data);

            

            return true;

        }else{

            return false;

        }



    }







   function status($request){



        

        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        if($query->num_rows()>0){

            

            

            $rs = $query->row();

            $data = array('is_online'=>filter_var ($request['driver_status'], FILTER_VALIDATE_BOOLEAN));

            $this->db->where('id',$rs->driver_id)->update('driver',$data);

          // echo $this->db->last_query();

            return true;

        }else{

            return false;

        }



    }





    // function status($request){

        

    //     $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

    //     if($query->num_rows()>0){

    //          $rs = $query->row();

    //          $driv_id = $rs->driver_id;

    //         if($request['driver_status']=='true'){

    //             $array = array('driver_id'=>$driv_id,

    //                             'cur_date'=>date('Y-m-d'),

    //                             'sign_in'=>time());

    //             $this->db->insert('driver_online',$array); 

    //             //$result = array('status'=>'success');  

    //             return true;             



    //         } else{



    //             $cur_date = date('Y-m-d');

    //             $rs = $this->db->query("SELECT * FROM driver_online WHERE driver_id='$driv_id' AND cur_date='$cur_date' ORDER BY id DESC LIMIT 0,1")->row();

    //             if(count($rs)>0){

    //                 if($rs->sign_out==''){

    //                     $array = array('sign_out'=>time());

    //                     $this->db->where('id',$rs->id)->update('driver_online',$array);

    //                     //$result = array('status'=>'success');

    //                     return true;

    //                 } else {

    //                     //$result = array('status'=>'error');

    //                     return false;

    //                 }

    //             } else {

    //                 //$result = array('status'=>'error');

    //                 return false;

    //             }

    //         }



    //     } else {

    //         //$result = array('status'=>'error');

    //         return false;

    //     }



    //     print json_encode($result);



    // }











//      public function accept($request){

//         $id = $request['request_id'];

// //print_r($id);

//         $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

//         if($query->num_rows()>0){



//             $rs = $query->row();

//             //print_r($rs);

//             $data = array('driver_id'=>$rs->driver_id);

            

//             $this->db->where('id',$id)->update('request',$data);

// //echo $this->db->last_query();

//             $data = $this->db->where('id',$id)->get('request')->row_array();

//           //  $data = $this->db->where('id',$id)->get('request')->row_array();

//             $rs = $this->db->select('booking_code')->get('settings')->row();

//             $code = $rs->booking_code;

//            //print_r($test);

//            // $book_id = "LTX".rand(1111,99999);

//            // $book_id = .$code.rand(1111,99999);

//            $rand = rand(1111,99999);

//            $book_id = "$code"."$rand";

//            //print_r($book_id);

           

//             //$date = date();

//             //print_r($date);

//             $data2 = array('car_type'=>$data['car_type'],

//                             'driver_id'=>$data['driver_id'], 

//                           'user_id'=>$data['cust_id'],

//                           'source'=>$data['source'],

//                             'book_date'=>time(),

//                           'destination'=>$data['destination'],

//                           'source_lat'=>$data['source_lat'],

//                           'source_lng'=>$data['source_lng'],

//                           'destination_lat'=>$data['destination_lat'],

//                           'destination_lng'=>$data['destination_lng'],

//                           'status'=>'1'

//                           );

//                  $this->db->insert('booking',$data2);



//                  $last_id = $this->db->insert_id();



//                  $bookdata = array('booking_id'=>$book_id);

//                   $this->db->where('id',$last_id)->update('booking',$bookdata);



//             $data3 = array('trip_id'=>$last_id,'status'=>'1');





//             $this->db->where('id',$id)->update('request',$data3);



//             $data4 = array('trip_id'=>$last_id,'cust_id'=>$data['cust_id'],'driver_id'=>$rs->driver_id);



//             $this->db->insert('feedback',$data4);





//             $data5 = "SELECT booking.id AS trip_id,booking.status AS trip_status,booking.driver_id AS driver_id, driver.driver_name AS driver_name,driver.image AS driver_photo,customer.id AS customer_id,customer.name AS customer_name, customer.image AS customer_photo,booking.source AS source_location , booking.source_lat AS source_latitude,booking.source_lng AS source_longitude,booking.destination AS destination_location, booking.destination_lat AS destination_latitude,booking.destination_lng AS destination_longitude FROM booking LEFT JOIN driver ON booking.driver_id = driver.id LEFT JOIN customer ON booking.user_id = customer.id WHERE booking.id ='$last_id'

//             ";

//             $query = $this->db->query($data5);

//             //echo $this->db->last_query();

//             $result = $query->row();

//             return $result;





//             return true;

//         }else{

//             return false;

//         }



//     }



    public function accept($request){
        $id = $request['request_id'];
//print_r($id);
        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');
        if($query->num_rows()>0){

            $rs = $query->row();
            //print_r($rs);
            $data = array('driver_id'=>$rs->driver_id);
            
            $this->db->where('id',$id)->update('request',$data);
            $data = $this->db->where('id',$id)->get('request')->row_array();
            $res = $this->db->select('booking_code')->get('settings')->row();
            $code = $res->booking_code;
   
           $rand = rand(1111,99999);
           $book_id = "$code"."$rand";
           
            $data2 = array('car_type'=>$data['car_type'],
                            'driver_id'=>$data['driver_id'], 
                          'user_id'=>$data['cust_id'],
                          'source'=>$data['source'],
                            'book_date'=>time(),
                            'start_time'=>time(),
                          'destination'=>$data['destination'],
                          'source_lat'=>$data['source_lat'],
                          'source_lng'=>$data['source_lng'],
                          'destination_lat'=>$data['destination_lat'],
                          'destination_lng'=>$data['destination_lng'],
                          'status'=>'1'
                          );
                 $this->db->insert('booking',$data2);

                 $last_id = $this->db->insert_id();

                 $bookdata = array('booking_id'=>$book_id);
                  $this->db->where('id',$last_id)->update('booking',$bookdata);

            $data3 = array('trip_id'=>$last_id,'status'=>'1');


            $this->db->where('id',$id)->update('request',$data3);
            $data4 = array('trip_id'=>$last_id,'cust_id'=>$data['cust_id'],'driver_id'=>$rs->driver_id);

            $this->db->insert('feedback',$data4);

            $data5 = "SELECT booking.id AS trip_id,booking.status AS trip_status,booking.driver_id AS driver_id, driver.driver_name AS driver_name,driver.image AS driver_photo,customer.id AS customer_id,customer.name AS customer_name, customer.image AS customer_photo,booking.source AS source_location , booking.source_lat AS source_latitude,booking.source_lng AS source_longitude,booking.destination AS destination_location, booking.destination_lat AS destination_latitude,booking.destination_lng AS destination_longitude FROM booking LEFT JOIN driver ON booking.driver_id = driver.id LEFT JOIN customer ON booking.user_id = customer.id WHERE booking.id ='$last_id'
            ";
            $query = $this->db->query($data5);
            
            $result = $query->row();
            return $result;


            return true;
        }else{
            return false;
        }

    }







     public function start_trip($request){

       // print_r($request);



        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

// echo $this->db->last_query();

        if($query->num_rows()>0){

        $id = $request['trip_id'];



        $start_time = time();

        $result = $this->db->where('id',$id)->update('booking',array('status'=>'2','start_time'=>$start_time));

      //  echo $this->db->last_query();

        return $result;



        }else{

            return false;

        }

    }





    public function help_pages($request){

        

        $id = $request['id'];

        

        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        

        if($query->num_rows()>0){



            $data = "SELECT * FROM help_table WHERE id ='$id' ";

            $query = $this->db->query($data);

            $rs = $query->row();

            return $result = array('id'=>$rs->id,'title'=>$rs->head,'icon'=>$rs->image,'content'=>$rs->content);

        } else {

            return false;

        }

    

    }



    function is_help_status($driv_id,$id){

        $data = $this->db->query("SELECT help_status AS is_helpful FROM `help_review` WHERE driver_id = '$driv_id' AND help_id = '$id' ")->row();

        

        //return $data->is_helpful;

        return filter_var ($data->is_helpful, FILTER_VALIDATE_BOOLEAN);



     }







    public function help_list($request){

        

        

        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        

        if($query->num_rows()>0){



            $data = "SELECT id AS id,head  AS title,image AS icon FROM help_table WHERE status = 1 ORDER BY id ASC";

            $query = $this->db->query($data);

            $result = $query->result_array();

            return $result;

        } else {

            return false;

        }

    

    }







    function help_review($request){

        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        if($query->num_rows()>0){

            $rs = $query->row();

            $driv_id =  $rs->driver_id;

            $data = array('help_status'=>filter_var ($request['is_helpful'], FILTER_VALIDATE_BOOLEAN),'help_id'=>$request['id'],'driver_id'=>$driv_id);

            $this->db->insert('help_review',$data);

            return true;

        }else{

            return false;

        }



    }







    function update_vehicle($request){

        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        if($query->num_rows()>0){

            

            

            $rs = $query->row();

            $data = array('model'=>$request['car_model'],'max_seat'=>$request['seats_available'],'vehicle_reg_num'=>$request['vehicle_registration_number'],'car_owner'=>$request['car_owner']);

            $this->db->where('driv_id',$rs->driver_id)->update('car',$data);

 

            return true;

        }else{

            return false;

        }



    }







    function update_settings($request){

        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        if($query->num_rows()>0){

            

            

            $rs = $query->row();

            $data = array('is_deaf'=>filter_var ($request['is_deaf'], FILTER_VALIDATE_BOOLEAN),'is_flash_required'=>filter_var ($request['is_flash_required_for_requests'], FILTER_VALIDATE_BOOLEAN));

            $this->db->where('id',$rs->driver_id)->update('driver',$data);

            

            return true;

        }else{

            return false;

        }



    }







    function fetch_settings($request){

         $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        if($query->num_rows()>0){

            $rs = $query->row();

            $driv_id = $rs->driver_id;



            $data1 = "SELECT * FROM driver WHERE id = '$driv_id' ";

            $query1 = $this->db->query($data1);

            $rs = $query1->row();

            //print_r($rs->is_flash_required);

            //echo $this->db->last_query();

            return $result = array('status'=>'success','is_deaf'=>$rs->is_deaf,'is_flash_required_for_requests'=>$rs->is_flash_required);



        } else {

            return false;

        }



    }







     function driver_location($request){

        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        if($query->num_rows()>0){

            

            

            $rs = $query->row();

            if (!empty($request["latitude"]&&$request['longitude'])){

                $data = array('driver_lat'=>$request['latitude'],'driver_lng'=>$request['longitude']);

                $this->db->where('id',$rs->driver_id)->update('driver',$data);

                return $result = array('status' =>'success');

            }else{

                return false;

            }



        }else{

            return false;

        }



    }







        function req_details($request){



            $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

           if($query->num_rows()>0){

            $id = $request["request_id"];

               

           

              

            $data = "SELECT request.car_type,request.source AS customer_location,request.source_lat AS customer_latitude,request.source_lng AS customer_longitude,customer.id AS customer_id,

                     customer.name AS customer_name,customer.image AS customer_photo FROM request LEFT JOIN customer ON customer.id = request.cust_id

                    WHERE request.id = '$id' ";

                    

             $query = $this->db->query($data);

             $result = $query->row();





             return $result;



            }else{

                return false;

            

    }

        }





        function car_type_image($id){

        $data = $this->db->query("SELECT image AS image FROM `car_type` WHERE id = '$id' ")->row();

       // echo $this->db->last_query();

        return $data->image;



     }





     //  }





        function car_type($id){



      //  $data = $this->db->query("SELECT car_type  FROM `request` WHERE id = '$id' ")->row();

        

        //$car_type_id =  $data->car_type;

        $data = $this->db->query("SELECT name  FROM `car_type` WHERE id = '$id' ")->row();

        //echo $this->db->last_query();

        return $data->name;



     }









    public function summary_trip($request){



        $id = $request['trip_id'];



        //$currency = '&#8377';

            

            $data = "SELECT booking.id,booking.status AS trip_status,booking.user_id AS customer_id,CONCAT(pattern.currency,'',booking.fare) AS fare, 

            CONCAT(pattern.currency,'',booking.tax) AS tax,CONCAT(pattern.currency,'',booking.fee) as fee,CONCAT(pattern.currency,'',booking.discount) AS discount,

            CONCAT(pattern.currency,'',booking.payout) AS estimated_payout, booking.time AS duration,

            ROUND(booking.distance, 2) AS distance FROM booking LEFT JOIN pattern ON booking.pattern_id = pattern.id WHERE booking.id = '$id'  ";   



            // $query1 =$this->db->query($data)->result();

            $query1 =$this->db->query($data)->row();



            // print_r($query1);

    

            return $query1;

        

    }







    // function statusof_app($request){

    //     $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

    //      if($query->num_rows()>0){

    //          $rs = $query->row();

    //          $driv_id = $rs->driver_id;





    //         $data1 = "SELECT * FROM booking WHERE driver_id = '$driv_id'";

    //          $query = $this->db->query($data1);

    //          $res = $query->row();

    //          $driver_status = $res->status;

    //          $result = (object) array();

    //          //echo $this->db->last_query();

    //          if($driver_status == 1 || $driver_status == 2){

    //             $data = "SELECT booking.id AS trip_id,booking.status AS trip_status,booking.driver_id AS driver_id,

    //             driver.driver_name,driver.image AS driver_photo,booking.driver_id,booking.source AS source_location,

    //             booking.source_lat AS source_latitude,booking.source_lng AS source_longitude,booking.destination AS destination_location,

    //             booking.destination_lat AS destination_latitude,booking.destination_lng AS destination_longitude, customer.id AS customer_id,customer.name AS customer_name,

    //             customer.image AS customer_photo FROM booking

    //             LEFT JOIN driver ON driver.id = booking.driver_id LEFT JOIN customer ON booking.user_id = customer.id

    //             WHERE booking.driver_id = '$driv_id' AND booking.status IN(1,2) ORDER BY booking.id DESC LIMIT 0,1";

    //             $result =$this->db->query($data)->row();

    //             $result->app_status = 1;

    //             return $result;

    //          }else{

    //             $result->app_status = 0;

    //             return $result;

    //          }



    //      }else{

    //         return false;



    //      }



    // }





    function statusof_app($request){



        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

         if($query->num_rows()>0){

             $rs = $query->row();

             $driv_id = $rs->driver_id;





            $data1 = "SELECT * FROM booking WHERE driver_id = '$driv_id' ORDER BY id DESC LIMIT 0,1";

             $query = $this->db->query($data1);

             $res = $query->row();

             $driver_status = $res->status;

             //echo $this->db->last_query();

             if($driver_status == 1 || $driver_status == 2){

                $data = "SELECT booking.id AS trip_id,booking.status AS trip_status,booking.driver_id AS driver_id,booking.start_time,

                driver.driver_name,driver.image AS driver_photo,booking.driver_id,booking.source AS source_location,

                booking.source_lat AS source_latitude,booking.source_lng AS source_longitude,booking.destination AS destination_location,

                booking.destination_lat AS destination_latitude,booking.destination_lng AS destination_longitude, customer.id AS customer_id,customer.name AS customer_name,

                customer.image AS customer_photo,request.id AS request_id FROM booking

                LEFT JOIN driver ON driver.id = booking.driver_id LEFT JOIN customer ON booking.user_id = customer.id LEFT JOIN request ON booking.id = request.trip_id

                WHERE booking.driver_id = '$driv_id' AND booking.status IN(1,2) ORDER BY booking.id DESC LIMIT 0,1";

            // echo $this->db->last_query();

                $result =$this->db->query($data)->row();

                

           //echo $this->db->last_query();

                return $result;

             }else{

                return $result;

             }



         }else{

            //return $result = array('status'=>'error');

           // return $result;

            print json_encode(array('status' => 'error','code'=>'209','message'=>'Something Went wrong'));

            die();

         }



    }



     function status_driver($id){



   

        $data = $this->db->query("SELECT *  FROM `booking` WHERE id = '$id' ")->row();

        //echo $this->db->last_query();

        if($data->car_arrival == 1){

            $driver_status = 1;

            return $driver_status;

        }elseif($data->status == 2){

            $driver_status = 2;

            return $driver_status;

        }elseif($data->status == 3){

            $driver_status = 3;

            return $driver_status;

        }else{

            $driver_status = 4;

            return $driver_status;

        }

        



     }







    function confirm_arrival($request){

        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        if($query->num_rows()>0){

            

            

            $data = array('car_arrival'=>'1');

            $this->db->where('id',$request['trip_id'])->update('booking',$data);

            

            return true;

        }else{

            return false;

        }



    }







     function confirm_cash($request){

        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        if($query->num_rows()>0){

            

            

            $rs = $query->row();

            $driv_id = $rs->driver_id;

            $data = array(' cash_collection'=>'1');

            $this->db->where('id',$request['trip_id'] && 'driver_id',$driv_id)->update('booking',$data);



            return true;

        }else{

            return false;

        }



    }







     public function ride_feedback($request){



        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        if($query->num_rows()>0){

            $rs = $query->row();

            $driv_id = $rs->driver_id;

            //$data = "SELECT feedback.id,feedback.bad_feedback AS issue,feedback.driver_feedback AS customer_comment,feedback.cust_id AS customer_id,feedback.trip_id AS trip_id FROM feedback WHERE driver_id = '$driv_id' AND feedback.driver_feedback!=''";
                        $data = "SELECT feedback.id,IF(feedback.trip_report!='', feedback.trip_report, feedback.bad_feedback) AS issue,feedback.driver_feedback AS customer_comment,feedback.cust_id AS customer_id,feedback.trip_id AS trip_id FROM feedback WHERE driver_id = '$driv_id' AND feedback.driver_feedback!=''";

            $query1 =$this->db->query($data)->result();



            return $query1;

        }else{

            return false;

        }

    }







    // public function feedback_comments($request){



    //     $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

    //     if($query->num_rows()>0){

    //         $rs = $query->row();

    //         $driv_id = $rs->driver_id;

    //         $data = "SELECT feedback.id,feedback.driver_feedback AS customer_comment,feedback.cust_id AS customer_id,feedback.trip_id AS trip_id,feedback.rating AS rating FROM feedback WHERE driver_id = '$driv_id'";

    //         $query1 =$this->db->query($data)->result();



    //         return $query1;

    //     }else{

    //         return false;

    //     }

    // }







    public function feedback_comments($request){



        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        if($query->num_rows()>0){

            $rs = $query->row();

            $driv_id = $rs->driver_id;

             $data = "SELECT feedback.id,feedback.driver_feedback AS customer_comment,

            feedback.trip_id,feedback.cust_id AS customer_id,feedback.trip_id AS trip_id,booking.start_time AS time,

            feedback.rating AS rating FROM feedback INNER JOIN booking ON feedback.trip_id = booking.id WHERE feedback.driver_id = '$driv_id' AND feedback.driver_feedback!='' ORDER BY booking.id DESC ";

            

            $query1 =$this->db->query($data)->result();

            //echo $this->db->last_query();

            return $query1;

        }else{

            return false;

        }

    }







     public function rating($request){

        

        

        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        

        if($query->num_rows()>0){

            $rs = $query->row();

            $driv_id = $rs->driver_id;

           // print_r($driv_id);

            //$data = "SELECT count(*) AS total_rating,AVG(feedback.rating) AS average_rating FROM feedback WHERE driver_id = '$driv_id'";

            $data = "SELECT count(*) AS total_ratings FROM feedback WHERE driver_id = '$driv_id' AND rating > 0";



           // COALESCE(sum(balance),0)

            $query = $this->db->query($data); 

            //echo $this->db->last_query();

            //echo $this->db->last_query();

            $result = $query->row();

            //$rolltext = (int)implode( $result->average_rating);

           // print_r($rolltext);

            //var_dump($myVar)

            



            return $result;

        } else {

            return false;

        }

    

    }



    function avg_rating($driv_id){



        //$data = $this->db->query("SELECT COALESCE(AVG(feedback.rating),0) AS average_rating FROM `feedback` WHERE driver_id = '94' ")->row();

        $data = $this->db->query("SELECT AVG(feedback.rating) AS average_rating FROM `feedback` WHERE driver_id = '$driv_id' AND rating > 0")->row();

        //echo $this->db->last_query();

        return $data->average_rating;





     }



     function num_rides($driv_id){

        $data = $this->db->query("SELECT count(id) AS total_requests FROM `driver_request` WHERE driver_id = '$driv_id'")->row();

        return $data->total_requests;





     }



     function num_requests($driv_id){



        $data = $this->db->query("SELECT count(id) AS requests_accepted FROM `request` WHERE driver_id = '$driv_id' AND status = 1")->row();

        return $data->requests_accepted;





     }



     function num_trips($driv_id){

      

        $data = $this->db->query("SELECT count(id) AS total_trips FROM `booking` WHERE driver_id = '$driv_id'")->row();

        return $data->total_trips;





     }



     function num_cancelled($driv_id){

      

        $data = $this->db->query("SELECT count(id) AS trips_cancelled FROM `booking` WHERE driver_id = '$driv_id' AND status = 0")->row();

        return $data->trips_cancelled;





     }











      function tripdetails($request){

        $id = $request["trip_id"];

        

        //print_r($id);

        // $data = "SELECT driver.id,driver.driver_name AS driver_name,driver.image AS driver_photo,booking.id,

        // booking.status AS trip_status,booking.driver_id,booking.source AS source_location,booking.source_lat AS source_latitude,

        //         booking.source_lng AS source_longitude,booking.destination AS destination_location,

        //         booking.destination_lat AS destination_latitude,

        //         booking.destination_lng AS destination_longitude, IF(booking.start_time='',booking.book_date,booking.start_time) AS start_time,booking.end_time AS end_time,

        //         CONCAT(pattern.currency,'',booking.fare) AS fare,CONCAT(ROUND(booking.time,2),' ','hrs') AS duration,CONCAT(ROUND(booking.distance,3),' ','KM') AS distance,booking.status AS trip_status,

        //         CONCAT(pattern.currency,'',booking.fee) AS fee,CONCAT(pattern.currency,'',booking.tax) AS tax,

        //         CONCAT(pattern.currency,'',booking.payout) AS estimated_payout,customer.id AS customer_id,

        //         customer.name AS customer_name,customer.image AS customer_photo FROM 

        //         booking LEFT JOIN driver ON driver.id = booking.driver_id LEFT JOIN customer ON booking.user_id = customer.id

        //         LEFT JOIN pattern ON booking.pattern_id = pattern.id WHERE booking.id = '$id'";







                      $data = "SELECT driver.id,driver.driver_name AS driver_name,driver.image AS driver_photo,booking.id,

        booking.status AS trip_status,booking.driver_id,booking.source AS source_location,booking.source_lat AS source_latitude,

                booking.source_lng AS source_longitude,booking.destination AS destination_location,

                booking.destination_lat AS destination_latitude,

                booking.destination_lng AS destination_longitude, IF(booking.start_time='',booking.book_date,booking.start_time) AS start_time,booking.end_time AS end_time,

                CONCAT(pattern.currency,'',ROUND(booking.fare,2)) AS fare,CONCAT(ROUND(booking.time,2),' ','hrs') AS duration,CONCAT(ROUND(booking.distance,3),' ','KM') AS distance,booking.status AS trip_status,

                CONCAT(pattern.currency,'',ROUND(booking.fee,2)) AS fee,CONCAT(pattern.currency,'',ROUND(booking.tax,2)) AS tax,

                CONCAT(pattern.currency,'',ROUND(booking.payout,2)) AS estimated_payout,customer.id AS customer_id,

                customer.name AS customer_name,customer.image AS customer_photo FROM 

                booking LEFT JOIN driver ON driver.id = booking.driver_id LEFT JOIN customer ON booking.user_id = customer.id

                LEFT JOIN pattern ON booking.pattern_id = pattern.id WHERE booking.id = '$id'";



                

        $query = $this->db->query($data);

        $result = $query->row();



        return $result;

        

    }





     function driver_rate($trip_id){

        $query = $this->db->query("SELECT feedback.rating AS rate FROM `feedback` WHERE trip_id = $trip_id")->row();

        return $query->rate;

    }







//     function history_trips($request){

// //echo "string";

//         $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

//         if($query->num_rows()>0){

//             $rs = $query->row();

//             $driv_id = $rs->driver_id;

//             //print_r($driv_id);

//             // $data = "SELECT driver.id,driver.driver_name AS driver_name,driver.image AS driver_photo,

//             // booking.id,booking.status AS trip_status,booking.driver_id,booking.source AS source_location,

//             // booking.source_lat AS source_latitude,booking.source_lng AS source_longitude,

//             // booking.destination AS destination_location,booking.destination_lat AS destination_latitude,

//             // booking.destination_lng AS destination_longitude,booking.start_time AS start_time,

//             // booking.end_time AS end_time,booking.fare AS fare,booking.time AS duration,booking.distance AS distance,

//             // booking.status AS trip_status ,booking.fee AS fee,booking.tax AS tax,booking.payout AS estimated_payout,

//             // customer.id AS customer_id,customer.name AS customer_name,

//             // customer.image AS customer_photo FROM booking

//             // LEFT JOIN driver ON driver.id = booking.driver_id LEFT JOIN customer ON booking.user_id = customer.id

//             // WHERE booking.driver_id = '$driv_id'";





//             $query = "SELECT driver.id,driver.driver_name AS driver_name,driver.image AS driver_photo,

//             booking.id,booking.status AS trip_status,booking.driver_id,booking.source AS source_location,

//             booking.source_lat AS source_latitude,booking.source_lng AS source_longitude,

//             booking.destination AS destination_location,booking.destination_lat AS destination_latitude,

//             booking.destination_lng AS destination_longitude,booking.start_time AS start_time,

//             booking.end_time AS end_time,booking.fare AS fare,booking.time AS duration,booking.distance AS distance,

//             booking.status AS trip_status ,booking.fee AS fee,booking.tax AS tax,booking.payout AS estimated_payout,

//             customer.id AS customer_id,customer.name AS customer_name,

//             customer.image AS customer_photo FROM booking

//             LEFT JOIN driver ON driver.id = booking.driver_id LEFT JOIN customer ON booking.user_id = customer.id

//             WHERE booking.driver_id = '$driv_id'";

// //echo $this->db->last_query();

//            // $total = $this->db->query->num_rows();



//             return $query;

//             //return $data;

//         }else{

//             return false;

//         }

//     }









     function history_trips($request){

//echo "string";

        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        if($query->num_rows()>0){

            $rs = $query->row();

            //print_r($rs);

            $driv_id = $rs->driver_id;

            //print_r($driv_id);

            // $data = "SELECT driver.id,driver.driver_name AS driver_name,driver.image AS driver_photo,

            // booking.id,booking.status AS trip_status,booking.driver_id,booking.source AS source_location,

            // booking.source_lat AS source_latitude,booking.source_lng AS source_longitude,

            // booking.destination AS destination_location,booking.destination_lat AS destination_latitude,

            // booking.destination_lng AS destination_longitude,booking.start_time AS start_time,

            // booking.end_time AS end_time,booking.fare AS fare,booking.time AS duration,booking.distance AS distance,

            // booking.status AS trip_status ,booking.fee AS fee,booking.tax AS tax,booking.payout AS estimated_payout,

            // customer.id AS customer_id,customer.name AS customer_name,

            // customer.image AS customer_photo FROM booking

            // LEFT JOIN driver ON driver.id = booking.driver_id LEFT JOIN customer ON booking.user_id = customer.id

            // WHERE booking.driver_id = '$driv_id'";





          $query = "SELECT driver.id,driver.driver_name AS driver_name,driver.image AS driver_photo,

            booking.id,booking.status AS trip_status,booking.driver_id,booking.source AS source_location,

            booking.source_lat AS source_latitude,booking.source_lng AS source_longitude,

            booking.destination AS destination_location,booking.destination_lat AS destination_latitude,

            booking.destination_lng AS destination_longitude, IF(booking.start_time='',booking.book_date,booking.start_time) AS start_time,

            booking.end_time AS end_time,CONCAT(pattern.currency,'',booking.fare) AS fare,booking.time AS duration,booking.distance AS distance,

            booking.status AS trip_status ,CONCAT(pattern.currency,'',booking.fee) AS fee,CONCAT(pattern.currency,'',booking.tax) AS tax,

            CONCAT(pattern.currency,'',booking.payout) AS estimated_payout,

            customer.id AS customer_id,customer.name AS customer_name,

            customer.image AS customer_photo FROM booking

            LEFT JOIN driver ON driver.id = booking.driver_id LEFT JOIN customer ON booking.user_id = customer.id

            LEFT JOIN pattern ON booking.pattern_id = pattern.id WHERE booking.driver_id = '$driv_id'";





         

            

            return $query;

            //return $data;

        }else{

            return false;

        }

    }











    function trip_rate($id){

        $query = $this->db->query("SELECT feedback.rating AS rate FROM `feedback` WHERE id = '$id'")->row();

        return $query->rate;

    }









    function total_rides_history($driv_id){



        $query = $this->db->query("SELECT count(id)  AS rides FROM booking WHERE driver_id = '$driv_id'")->row();

        return $query->rides;



    }









    function total_online_time($driv_id){



        $query = $this->db->query("SELECT * FROM driver_online WHERE driver_id = '$driv_id'")->result();



//echo $this->db->last_query();

        $total_time = 0;

        foreach($query as $rs){

            $start_time = $rs->sign_in;

            if($rs->sign_out!=''){

                $end_time = $rs->sign_out;

            } else {

                if($rs->cur_date==date('Y-m-d')){

                    $end_time = time();

                } else {

                    $end_time = strtotime($rs->cur_date.' 23:59:59');

                }

            }

            $end_time = $rs->sign_out!=''?$rs->sign_out:time();

            $total_time +=  $end_time - $start_time;



            



        }            

        $dateDiff = intval(($total_time)/60);

        $hours = intval($dateDiff/60);

        $minutes = $dateDiff%60;





        // $start =  date('m/d/Y H:i:s', $start_time);

        // $end =  date('m/d/Y H:i:s', $end_time);





        /*$interval  = abs($end_time - $start_time)/1000;

        $minutes   = round($interval / 60);

        $min = $minutes;*/



        //$hours = floor($min / 60).':'.($min -   floor($min / 60) * 60);

        return $hours.":".$minutes;







    }











    // function total_fare($driv_id){



    //     $query = $this->db->query("SELECT SUM(fare)  AS fare FROM booking WHERE driver_id = '$driv_id'")->row();

    //     return $query->fare;

    //     echo $this->db->last_query();



    // }





    function total_fare($driv_id){



        $data1 = $this->db->query("SELECT pattern_id FROM booking WHERE driver_id = $driv_id ")->row();

        $pattern = $data1->pattern_id;

      //  $query = $this->db->query("SELECT CONCAT(patternSUM(fare) AS fare FROM booking WHERE driver_id = '$driv_id'")->row();

        $query = $this->db->query("SELECT CONCAT(pattern.currency,'',SUM(fare)) AS fare,pattern_id   FROM booking LEFT JOIN pattern ON booking.pattern_id = pattern.id WHERE driver_id = '$driv_id' ")->row();



        //echo $this->db->last_query();

        if(count($query)>0){

              return $query->fare;

          } else {

            return 0;

          }

      

        //echo $this->db->last_query();



    }









    function today_trips($request,$start_time,$end_time){



        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');



        if($query->num_rows()>0){



            $rs = $query->row();

            $driv_id = $rs->driver_id;





            // $data = "SELECT driver.id,driver.driver_name AS driver_name,driver.image AS driver_photo,

            // booking.id,booking.status AS trip_status,booking.driver_id,booking.source AS source_location,

            // booking.source_lat AS source_latitude,booking.source_lng AS source_longitude,

            // booking.destination AS destination_location,booking.destination_lat AS destination_latitude,

            // booking.destination_lng AS destination_longitude,booking.start_time AS start_time,

            // booking.end_time AS end_time,booking.fare AS fare,booking.time AS duration,booking.distance AS distance,

            // booking.status AS trip_status ,booking.fee AS fee,booking.tax AS tax,booking.payout AS estimated_payout,customer.id AS customer_id,customer.name AS customer_name,

            // customer.image AS customer_photo FROM booking

            // LEFT JOIN driver ON driver.id = booking.driver_id LEFT JOIN customer ON booking.user_id = customer.id

            // WHERE booking.driver_id = '$driv_id' AND booking.book_date BETWEEN $start_time AND $end_time";





       //       $query = "SELECT driver.id,driver.driver_name AS driver_name,driver.image AS driver_photo,

       //      booking.id,booking.status AS trip_status,booking.driver_id,booking.source AS source_location,

       //      booking.source_lat AS source_latitude,booking.source_lng AS source_longitude,

       //      booking.destination AS destination_location,booking.destination_lat AS destination_latitude,

       //      booking.destination_lng AS destination_longitude,booking.start_time AS start_time,

       //      booking.end_time AS end_time,booking.fare AS fare,booking.time AS duration,booking.distance AS distance,

       //      booking.status AS trip_status ,booking.fee AS fee,booking.tax AS tax,booking.payout AS estimated_payout,customer.id AS customer_id,customer.name AS customer_name,

       //      customer.image AS customer_photo FROM booking

       //      LEFT JOIN driver ON driver.id = booking.driver_id LEFT JOIN customer ON booking.user_id = customer.id

       //      LEFT JOIN pattern ON booking.pattern_id = pattern.id WHERE booking.driver_id = '$driv_id' AND booking.book_date BETWEEN $start_time AND $end_time";

       // // echo $this->db->last_query();





              $query = "SELECT driver.id,driver.driver_name AS driver_name,driver.image AS driver_photo,

            booking.id,booking.status AS trip_status,booking.driver_id,booking.source AS source_location,

            booking.source_lat AS source_latitude,booking.source_lng AS source_longitude,

            booking.destination AS destination_location,booking.destination_lat AS destination_latitude,

            booking.destination_lng AS destination_longitude,booking.start_time AS start_time,

            booking.end_time AS end_time,CONCAT(pattern.currency,'',booking.fare) AS fare,

            booking.time AS duration,booking.distance AS distance,

            booking.status AS trip_status ,CONCAT(pattern.currency,'',booking.fee) AS fee,CONCAT(pattern.currency,'',booking.tax) AS tax,

            CONCAT(pattern.currency,'',booking.payout) AS estimated_payout,customer.id AS customer_id,customer.name AS customer_name,

            customer.image AS customer_photo FROM booking

            LEFT JOIN driver ON driver.id = booking.driver_id LEFT JOIN customer ON booking.user_id = customer.id

            LEFT JOIN pattern ON booking.pattern_id = pattern.id WHERE booking.driver_id = '$driv_id' AND booking.book_date BETWEEN $start_time AND $end_time AND booking.status != 0";



            return $query;

        }else{





            return false;

        }

    }







    function totalfare_today($start_time,$end_time,$driv_id){

        $data1 = $this->db->query("SELECT pattern_id FROM booking WHERE driver_id = $driv_id ")->row();

        $pattern = $data1->pattern_id;

       // $data2 = $this->db->query("SELECT currency FROM pattern WHERE id = $pattern ")->row();

       // $currency = $data2->currency;



       // print_r($currency);

      //  $query = $this->db->query("SELECT SUM(fare)  AS fare FROM booking WHERE driver_id = '$driv_id' AND book_date BETWEEN $start_time AND $end_time")->row();

        $query = $this->db->query("SELECT CONCAT(pattern.currency,'',SUM(fare)) AS fare,pattern_id   FROM booking LEFT JOIN pattern ON booking.pattern_id = pattern.id WHERE driver_id = '$driv_id' AND book_date BETWEEN $start_time AND $end_time")->row();

        

        return $query->fare;



    }





    // function total_rides($driv_id){

    //     $date = strtotime(date('y-m-d'));

    //     $query = $this->db->query("SELECT (count(id)  AS rides FROM booking WHERE driver_id = '$driv_id' AND book_date = '$date' ")->row();

    //     return $query->rides;



    // }



    function total_rides($start_time,$end_time,$driv_id){

        //print_r($end_time);

        //print_r($end_time);

        $query = $this->db->query("SELECT (count(id))  AS rides FROM booking WHERE driver_id = '$driv_id' AND book_date BETWEEN $start_time AND $end_time")->row();

        //echo $this->db->last_query();

        return $query->rides;



        //$query = $this->db->query("SELECT SUM(fare)  AS fare FROM booking WHERE driver_id = '$driv_id' AND book_date BETWEEN $start_time AND $end_time")->row();

       // return $query->fare;



    }







//     function total_online_time($driv_id){



//         $query = $this->db->query("SELECT *  FROM driver_online WHERE driver_id = '$driv_id'")->row();



// //echo $this->db->last_query();

//         $start_time = $query->sign_in;

//         $end_time = $query->sign_out;







//         // $start =  date('m/d/Y H:i:s', $start_time);

//         // $end =  date('m/d/Y H:i:s', $end_time);





//         $interval  = abs($end_time - $start_time);

//         $minutes   = round($interval / 60);

//         $min = $minutes;



//         $hours = floor($min / 60).':'.($min -   floor($min / 60) * 60);

//         return $hours;







//     }









   







    





function message($request){



        $id = $request;

        $fcm_data = array('id'=>$id,'title'=>'LaTaxi','message'=>'Trip Completed');







       $data = array('status '=>'3');

       $this->db->where('id',$id)->update('booking',$data);



       $data = "SELECT * FROM booking WHERE id = '$id' ";

       $query = $this->db->query($data);

       $rs = $query->row();

       $cust_id = $rs->user_id;





       $data2 = "SELECT * FROM customer WHERE id = '$cust_id' ";

       $query = $this->db->query($data2);

       $rs2 = $query->row();

       $fcm = $rs2->fcm_token;



       $this->push_sent($fcm,$fcm_data);

 



    }



//     function driver_message($id){

// //print_r($id);

//         //$id = $request['id'];

//         $fcm_data = array('id'=>$request,'title'=>'LaTaxi','message'=>'Trip Request');







//        // $data = array('status '=>'3');

//        // $this->db->where('id',$id)->update('booking',$data);



//        $data = "SELECT * FROM request WHERE id = '$id' ";



//        $query = $this->db->query($data);

//        $rs = $query->row();

//       // echo $this->db->last_query();

//        $driver_id = $rs->driver_id;

//        //echo $this->db->last_query();

//       //print_r($driver_id);



//        $data2 = "SELECT * FROM driver WHERE id = '$driver_id' ";

//        $query = $this->db->query($data2);

//        $rs2 = $query->row();

//        //echo $this->db->last_query();

//        $fcm = $rs2->fcm_token;

//       print_r($fcm);



//        $this->push_sent($fcm,$fcm_data);

 



//     }





    // function push_sent($fcm_token,$fcm_data){

    //    // / print_r($fcm_data);

    //     $ch = curl_init("https://fcm.googleapis.com/fcm/send");

    //     $header=array('Content-Type: application/json',

    //     "Authorization: key=AIzaSyCkUU9_wA4o6j0300MLDmKREwNhoJAAzM8");

    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    //     curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );



    //     curl_setopt($ch, CURLOPT_POST, 1);

    //     //curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"notification\": {    \"title\": \"olakka title\",    \"text\": \"Olakka message\"  }, \"data\" : {\"response\" : {\"status\" : \"success\", \"data\" : {\"id\" : \"$request[id]\"}}}    \"to\" : \"cAAoKqSKvgk:APA91bH-mvqwAp1JYW1oBZl_aheVDb7HOWx0YjXU6Wmmm-G_CB-60T2M6WKwsjPDMUfu47nCf1qi4a9SREiwRifAFBWszVTL8XA1y4_nb7ZiI9EmX7rpKsWogsrzqXSwKt56cd8sH5ox\"}");

    //     curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"notification\": {    \"title\": \"".$fcm_data['title']."\",

    //         \"text\": \"".$fcm_data['message']."\"  }, \"data\" : {\"response\" : {\"status\" : \"success\",

    //          \"data\" : {\"id\" : \"".$fcm_data['id']."\"}}}   

    //           \"to\" : \"".$fcm_token."\"}");



    //     curl_exec($ch);

    //     curl_close($ch);

    // }





    function push_sent($fcm_token,$fcm_data){

       // / print_r($fcm_data);

         $data = "{ \"notification\": { \"title\": \"".$fcm_data['title']."\", \"text\": \"".$fcm_data['message']."\" , \"sound\": \"default\" }, \"time_to_live\": 60, \"data\" : {\"response\" : {\"status\" : \"success\", \"data\" : {\"id\" : \"".$fcm_data['id']."\"}}}, \"to\" : \"".$fcm_token."\"}";

          $ch = curl_init("https://fcm.googleapis.com/fcm/send");

           $header=array('Content-Type: application/json', "Authorization: key=AIzaSyCkUU9_wA4o6j0300MLDmKREwNhoJAAzM8"); 

            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );

            curl_setopt($ch, CURLOPT_POST, 1);

            //curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"notification\": { \"title\": \"olakka title\", \"text\": \"Olakka message\" }, \"data\" : {\"response\" : {\"status\" : \"success\", \"data\" : {\"id\" : \"$request[id]\"}}} \"to\" : \"cAAoKqSKvgk:APA91bH-mvqwAp1JYW1oBZl_aheVDb7HOWx0YjXU6Wmmm-G_CB-60T2M6WKwsjPDMUfu47nCf1qi4a9SREiwRifAFBWszVTL8XA1y4_nb7ZiI9EmX7rpKsWogsrzqXSwKt56cd8sH5ox\"}");

             curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 

           

            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // curl_close($ch);

             $out=curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_exec($ch);

     curl_close($ch);

    }















    function driver_onstatus($request){

        

        $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

        if($query->num_rows()>0){

             $rs = $query->row();

             $driv_id = $rs->driver_id;

            // print_r($request);

            if($request['driver_status']=='true'){



                $array = array('driver_id'=>$driv_id,

                                'cur_date'=>date('Y-m-d'),

                                'sign_in'=>time());

                $this->db->insert('driver_online',$array); 

                //$result = array('status'=>'success'); 

              //  echo $this->db->last_query(); 

                return true;             



            } else{



                $cur_date = date('Y-m-d');

                $rs = $this->db->query("SELECT * FROM driver_online WHERE driver_id='$driv_id' AND cur_date='$cur_date' ORDER BY id DESC LIMIT 0,1")->row();

                //echo $this->db->last_query();

                if(count($rs)>0){

                    if($rs->sign_out==''){

                        $array = array('sign_out'=>time());

                        $this->db->where('id',$rs->id)->update('driver_online',$array);

                        //$result = array('status'=>'success');

                        return true;

                    } else {

                        //$result = array('status'=>'error');

                        return false;

                    }

                } else {

                    //$result = array('status'=>'error');

                    return false;

                }

            }



        } else {

            //$result = array('status'=>'error');

            return false;

        }



        print json_encode($result);



    }

















// function get_pattern($car_type){

//         return $this->db->where('car_type',$car_type)->get('pattern')->result();

//     }















//      public function fare_calculate($request){



//         //$request = $_GET;



//         $pattern = 0;



//         $pattern = $this->get_pattern($request['car_type']);

        







//         if(!empty($pattern)){

//             foreach ($pattern as $rs) {

//                 $check_in_range = $this->check_in_range($rs,$request);

//                 if($check_in_range){

//                     $pattern_id = $rs->id;

//                     break;

//                 }

//             }

        





        

//         if($pattern_id!=0){





//             $distance = $request['distance'];

//             $time = $request['time'];

//             $booking_time = strtotime('Y-m-d H:i:s');//$request['booking_time'];





//             /************************************/



//                 //get which pattern used



//                 //Assume kochi value = 1

//                 //$pattern = 1;

            

//                 $rs = $this->cal_equation($pattern_id);



                

//             /***********************************/

//             if($rs){

//                 $fare_cal = $rs->base_price+($rs->km_rate*$distance);

//                 $least_min = $time * $rs->min_rate;

//                 //$max_min = $max_time * $rs->min_rate;



//                 $min_fare = $fare_cal + $least_min; 

//                // $max_fare = $fare_cal + $max_min;



//                 $extra_cost = 0;



//                 if(!empty($rs->extra)){

//                     //print_r($rs->extra);

//                     foreach ($rs->extra as $row) {

//                         $start_time = $row->start_time;

//                         $end_time = $row->end_time;

//                         if($start_time>$end_time){

//                             $end_time = date('Y-m-d '.$end_time.':s');

//                             $end_time = strtotime($stop_date . ' +1 day');

//                             //date('Y-m-d '.$end_time, strtotime($stop_date . ' +1 day'));

//                         } else {

//                             $end_time = date('Y-m-d '.$end_time.':s');

//                             $end_time = strtotime($stop_date);

//                         }

//                         $start_time = strtotime(date('Y-m-d '.$row->start_time.':s'));



//                         if($start_time<=$booking_time && $booking_time<=$end_time){

//                             $extra_cost = $extra_cost + $row->fare;

//                         }

//                     }

//                 }



//                 $min_fare = floor($min_fare + $extra_cost);



//                 $setting = $this->db->get('settings')->row();



//                 $fee = (($min_fare * $setting->admin_charge)/100);

//                 $tax = (($min_fare * $setting->tax)/100);



//                 $data = array('base_fare'=>$rs->base_price,

//                       'km_fare'=>$rs->km_rate*$distance,

//                       'min_fare'=>$least_min,

//                       'sub_total_fare'=>$min_fare,

//                       'fare'=>$min_fare,

//                       'fee'=>$fee,

//                       'tax'=>$tax,

//                       'pattern_id'=>$pattern_id);



//                 //print_r($data);



//                 $this->db->where('id',$request['id'])->update('booking',$data);



//                 //print_r($rs->currency);

//                 return $min_fare;



        



    

        

//                 }

//             }



//         }



//     }







//      function check_in_range($rs,$rq){        

//         $latitude2 = $rq['source_latitude'];

//         $longitude2 = $rq['source_longitude'];

//         $latitude1= $rs->lat;

//         $longitude1 = $rs->lng;

//         $range = $this->getDistance($latitude1, $longitude1, $latitude2, $longitude2);

//         if($range <= $rs->range){

//            // echo $rs->range;

//             $latitude2 = $rq['destination_latitude'];

//             $longitude2 = $rq['destination_longitude'];

//             $range = $this->getDistance($latitude1, $longitude1, $latitude2, $longitude2);

//             if($range <= $rs->range){

//                 return true;

//             } else {

//                 return false;

//             }

//         } else {

//             return false;

//         }

        

        

//     }





//      function getDistance( $latitude1, $longitude1, $latitude2, $longitude2 ) {  



//         $earth_radius = 6371;



//         $dLat = deg2rad( $latitude2 - $latitude1 );  

//         $dLon = deg2rad( $longitude2 - $longitude1 );  



//         $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);  

//         $c = 2 * asin(sqrt($a));  

//         $d = $earth_radius * $c;  



//         return $d;  

//     }





    public function fare_calculate($request,$trip_id){









        //$request = $_GET;



        //print_r($request);



        $pattern = 0;



        $pattern = $this->get_pattern($request['car_type']);

       





        if(!empty($pattern)){



            foreach ($pattern as $rs) {



                if($rs->lat=='' || $rs->lng==''){

                    continue;

                }





                $check_in_range = $this->check_in_range($rs,$request);

                if($check_in_range){

                    $pattern_id = $rs->id;

                    //print_r($pattern_id);

                    break;

                }

            }

        





        

        if($pattern_id!=0){







            $distance = $request['distance'];

            $time = $request['time'];

            $booking_time = strtotime('Y-m-d H:i:s');//$request['booking_time'];





            /************************************/



                //get which pattern used



                //Assume kochi value = 1

                //$pattern = 1;

            

                $rs = $this->cal_equation($pattern_id);





            /***********************************/



             if($rs){



             



            $fare_cal = $rs->base_price+($rs->km_rate*$distance);

             // $fare_cal = $rs->km_rate*$distance;

             // print_r($fare_cal);

                $least_min = $time * $rs->min_rate;  //total minute rate

                //$max_min = $max_time * $rs->min_rate;



                $min_fare = $fare_cal + $least_min; // total fare calculated

               // $max_fare = $fare_cal + $max_min;

                //print_r($min_fare);



                $extra_cost = 0;



                if(!empty($rs->extra)){

                    //print_r($rs->extra);

                    foreach ($rs->extra as $row) {

                        $start_time = $row->start_time;

                        $end_time = $row->end_time;

                        if($start_time>$end_time){

                            $end_time = date('Y-m-d '.$end_time.':s');

                            $end_time = strtotime($stop_date . ' +1 day');

                            //date('Y-m-d '.$end_time, strtotime($stop_date . ' +1 day'));

                        } else {

                            $end_time = date('Y-m-d '.$end_time.':s');

                            $end_time = strtotime($stop_date);

                        }

                        $start_time = strtotime(date('Y-m-d '.$row->start_time.':s'));



                        if($start_time<=$booking_time && $booking_time<=$end_time){

                            $extra_cost = $extra_cost + $row->fare;

                        }

                    }

                }



                $min_fare = floor($min_fare + $extra_cost);

                  //  print_r($min_fare);

                 $query = $this->db->query("SELECT user_id FROM booking WHERE id = '$trip_id'")->row();

                 $cust_id = $query->user_id;

                 $query1 = $this->db->where('id',$cust_id)->get('customer')->row();

                 $promo = $query1->promocode;

                 $current_date = date('Y/m/d');

                // print_r($current_date);

                 $query2 = $this->db->query("SELECT off FROM promocode WHERE code ='$promo' AND expiration_date >= '$current_date' AND code = '$promo' ORDER BY id DESC")->row();

                //echo $this->db->last_query();

                 $promo_off = $query2->off;



              // print_r($promo_off);



               // print_r($min_fare);



                // if($min_fare < $rs->base_price){

                //     $final_fare = $rs->base_price;

                // }else{

                //     $final_fare = $min_fare;

                // }

               //print_r($final_fare);

                 

                if(!empty($promo_off)){

                    $fare = $min_fare - $promo_off;

                }else{

                    $fare = $min_fare;

                }

                //print_r($fare);

                $setting = $this->db->get('settings')->row();



                $fee = (($fare * $setting->admin_charge)/100);

                $tax = (($fare * $setting->tax)/100);

                $driver_charge = $fare - ($fee + $tax);





                $data = array('base_fare'=>$rs->base_price,

                      'km_fare'=>$rs->km_rate*$distance,

                      'total_km'=>'distance',

                      'min_fare'=>$least_min,

                      'sub_total_fare'=>$fare_cal,

                      'fare'=>$fare,

                      'promotion_fare'=>$promo_off,

                      'discount'=>$promo_off,

                      'fee'=>$fee,

                      'payout'=>$driver_charge,

                      'tax'=>$tax,

                      'pattern_id'=>$pattern_id);



                //print_r($data);



                $this->db->where('id',$request['id'])->update('booking',$data);

                //echo $this->db->last_query();

                //print_r($rs->currency);

                return $min_fare;



        



    

        

                }

            // if($rs){



            //     $fare_cal = $rs->base_price+($rs->km_rate*$distance);

            //     $least_min = $time * $rs->min_rate;  //total minute rate

            //     //$max_min = $max_time * $rs->min_rate;



            //     $min_fare = $fare_cal + $least_min; // total fare calculated

            //    // $max_fare = $fare_cal + $max_min;



            //     $extra_cost = 0;





            //     if(!empty($rs->extra)){

            //         //print_r($rs->extra);

            //         foreach ($rs->extra as $row) {

            //             $start_time = $row->start_time;

            //             $end_time = $row->end_time;

            //             if($start_time>$end_time){

            //                 $end_time = date('Y-m-d '.$end_time.':s');

            //                 $end_time = strtotime($stop_date . ' +1 day');

            //                 //date('Y-m-d '.$end_time, strtotime($stop_date . ' +1 day'));

            //             } else {

            //                 $end_time = date('Y-m-d '.$end_time.':s');

            //                 $end_time = strtotime($stop_date);

            //             }

            //             $start_time = strtotime(date('Y-m-d '.$row->start_time.':s'));



            //             if($start_time<=$booking_time && $booking_time<=$end_time){

            //                 $extra_cost = $extra_cost + $row->fare;

            //             }

            //         }

            //     }



            //     $min_fare = floor($min_fare + $extra_cost);

            //     // if($min_fare<$base_price){

            //     //     $final_fare = $base_price;

            //     // }else{

            //     //     $final_fare = $min_fare;

            //     // }

            //     //print_r($final_fare);

            //    // die();



            //     $setting = $this->db->get('settings')->row();



            //     $fee = (($min_fare * $setting->admin_charge)/100);

            //     $tax = (($min_fare * $setting->tax)/100);



            //     $data = array('base_fare'=>$rs->base_price,

            //           'km_fare'=>$rs->km_rate*$distance,

            //           'min_fare'=>$least_min,

            //           'sub_total_fare'=>$min_fare,

            //           'fare'=>$min_fare,

            //           'fee'=>$fee,

            //           'tax'=>$tax,

            //           'pattern_id'=>$pattern_id);



            //     //print_r($data);



            //     $this->db->where('id',$request['id'])->update('booking',$data);



            //     //print_r($rs->currency);

            //     return $min_fare;



        



    

        

            //     }

            }



        }



    }





      function get_pattern($car_type){

        return $this->db->where('car_type',$car_type)->get('pattern')->result();

    }







        function check_in_range($rs,$rq){        

        $latitude2 = $rq['source_latitude'];

        $longitude2 = $rq['source_longitude'];

        $latitude1= $rs->lat;

        $longitude1 = $rs->lng;

        $range = $this->getDistance($latitude1, $longitude1, $latitude2, $longitude2);

        if($range <= $rs->range){

           // echo $rs->range;

            $latitude2 = $rq['destination_latitude'];

            $longitude2 = $rq['destination_longitude'];

            $range = $this->getDistance($latitude1, $longitude1, $latitude2, $longitude2);

            if($range <= $rs->range){

                return true;

            } else {

                return false;

            }

        } else {

            return false;

        }

        

        

    }



    function getDistance( $latitude1, $longitude1, $latitude2, $longitude2 ) {  

        

        $earth_radius = 6371;



        $dLat = deg2rad( $latitude2 - $latitude1 );  

        $dLon = deg2rad( $longitude2 - $longitude1 );  



        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);  

        $c = 2 * asin(sqrt($a));  

        $d = $earth_radius * $c;  



        return $d;  

    }







     function cal_equation($pat_id){

        $rs = $this->db->where('id',$pat_id)->get('pattern')->row();

        $rs->extra = $this->db->where('pat_id',$pat_id)->get('charge_condition')->result();

        //echo $this->db->last_query();

     

        return $rs;

    }





     function get_trip_info($trip_id){

                $rs = $this->db->query("SELECT booking.id,(CASE booking.status WHEN 0 THEN 'cancelled' WHEN 1 THEN 'booking' WHEN 2 THEN 'inprocess' ELSE 'completed' END) AS status,

                    booking.driver_id,booking.user_id AS customer_id,booking.source AS source_location,

                    booking.source_lat AS source_latitude,booking.source_lng AS source_longitude,

                    booking.destination AS destination_location,booking.destination_lat AS destination_latitude,

                    booking.destination_lng AS destination_longitude,booking.start_time,booking.end_time, CONCAT(pattern.currency,'',booking.fare) as fare,

                    booking.distance,booking.time AS duration,driver.driver_name,customer.name AS customer_name,

                    customer.image AS customer_photo FROM `booking` LEFT JOIN driver ON booking.driver_id = driver.id LEFT JOIN customer ON booking.user_id = customer.id LEFT JOIN pattern ON booking.pattern_id = pattern.id WHERE booking.id =".$trip_id)->row();



                if($rs){

                    return $rs;

                } else {

                    return false;

                }



            }















    // function forgot_pswd($request){



    // $email = $request['email'];

    // $data = "SELECT * FROM customer WHERE email = '$email'"; 

    // $query = $this->db->query($data);

    // $count = mysqli_num_rows($res);

    // if($count == 1){

    //     $result = random_password();

    //     return $result;

    // }else{

        

    //    return false;

    // }





    //  }



    // function random_password() {

    





    //     $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

    //     $password = array(); 

    //     $alpha_length = strlen($alphabet) - 1; 



    //    for ($i = 0; $i < 8; $i++) 

    //     {

    //         $n = rand(0, $alpha_length);

    //         $password[] = $alphabet[$n];





           

    //     }



    //     return implode($password); 



    // }











    // function forgetpassword($email) {

    //  $this->db->where('email', $email); 

    //  $query = $this->db->get('customer');

    //   $query = $query->row(); $settings = get_icon();

    //    if ($query) {

    //     $username = $query->first_name; 

    //     $from_email = $settings->admin_email; 

    //     $this->load->helper('string'); 

    //     $rand_pwd = random_string('alnum', 8); 

    //     $password = array( 'password' => md5($rand_pwd) ); 

    //     $this->db->where('email', $email);

    //      $query = $this->db->update('customer', $password);

    //       if ($query) { 

    //       //return $result; 

    //         $configs = array( 'protocol' => 'smtp', 'smtp_host' => $settings->smtp_host, 'smtp_user' => $settings->smtp_username, 'smtp_pass' => $settings->smtp_password, 'smtp_port' => '587' );



    //         $this->load->library('email'); $this->email->initialize($configs); $this->email->from($from_email, $username); $this->email->to($email); $this->email->subject('Forget Password');

    //          $this->email->message('New Password is ' . $rand_pwd . ' '); $this->email->send(); return "EmailSend"; } } else { return "EmailNotExist"; } }













        // function get_payout($weeks,$driver_id){



        //     $start = $weeks[0];

        //     $end = $weeks[6];



        //     $pay = "SELECT SUM(fare) AS total_payout FROM booking WHERE book_date BETWEEN '$start' AND '$end' WHERE driver_id = $driver_id ";

            

        //     $query1 =$this->db->query($pay);





        // }





        function get_payout($weeks,$driver_id){



           $first = reset($weeks);

           $last = end($weeks);

          // print_r($first);

           $start = strtotime($first);

         

          

           $end = strtotime($last);

 

           //$daydate10_end = strtotime(date("$last 23:59:59"));

           //print_r($daydate10_end);

          // echo strtotime(date("Y-m-d 00:00:01"));

          // $start_time = 

           //print_r($timestamp);

           // $cur = $this->db->select('currency')->from('pattern')->join('booking','booking.pattern_id=pattern.id')->where('book_date ',$weeks)->get()->row();



           $total_payout = $this->db->select('SUM(payout) AS total')->where('driver_id',$driver_id)->where('book_date >=',$start)->where('book_date <=',$end)->get('booking')->row();

           //echo $this->db->last_query();

           $i = 0;

           foreach ($weeks as $rs) {

                ++$i;



                $day_start = strtotime(date("$rs 00:00:00"));

                $day_end = strtotime(date("$rs 23:59:59"));

//print_r(strtotime($rs));

            //   $total = $this->db->select('SUM(payout) AS amount')->where('driver_id',$driver_id)->where('book_date ',strtotime($rs))->get('booking')->row();

            $total =  $this->db->select('SUM(payout) AS amount')->where('driver_id',$driver_id)->where('book_date BETWEEN "'.$day_start. '" and "'.$day_end.'"')->get('booking')->row();

             // echo $this->db->last_query();

               $curr = $this->db->select('currency')->from('pattern')->join('booking','booking.pattern_id=pattern.id')->where('book_date ',strtotime($rs))->get()->row();

//echo $this->db->last_query();

               $query = $this->db->query("SELECT *  FROM driver_online WHERE driver_id = '$driver_id' AND cur_date='$rs'")->result();

//echo $this->db->last_query();

               $interval = 0;

               foreach ($query as $ros) {

                   $start_time = $ros->sign_in;

                   $end_time = $ros->sign_out;

                   $interval  += abs($end_time - $start_time);

               }



               $minutes   = round($interval / 60);

               $min = $minutes;

               $hours = floor($min / 60).':'.($min -   floor($min / 60) * 60);

               







        







               $array = array('day'=>$i,

                              'amount_payable'=>$curr->currency.intval($total->amount),

                              'amount'=>intval($total->amount),



                              'hours_online'=>$hours);



               $new_result[] = $array;



           }

            $currency = $this->db->select('currency')->from('pattern')->join('booking','booking.pattern_id=pattern.id')->get()->row();



           return $result = array('total_payout'=>$currency->currency.intval($total_payout->total),

                            'weekly_earnings'=>$new_result);



           



           



           //$data = array('week_of_the_year'=>)



           



        }





















    







    







       

        













	



    



}



?>