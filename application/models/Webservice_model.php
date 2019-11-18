<?php

class Webservice_model extends CI_Model{

    function __construct(){

        parent::__construct();

    }



    public function user_reg($data){

        $num = $this->db->where('phone',$data['phone_number'])->get('customer')->num_rows();

        

        $num1 = $this->db->where('email',$data['email'])->get('customer')->num_rows();

        

        if($num>0){

            $result = array('status'=>'error','message'=>'Mobile number Already Exists');

        }elseif($num1>0){

            $result = array('status'=>'error','message'=>'Email Already Exists');

        } else {

            $unique_id = $this->generate_unique();

            $otp = rand(1111,9999);

            $this->db->insert('customer',array('phone'=>$data['phone_number'],'email'=>$data['email'],'name'=>$data['name'],'password'=>md5($data['password'])));

            $user_id = $this->db->insert_id();

            $this->db->insert('address',array('cust_id'=>$user_id));

            $this->EncryptedPatientKey($unique_id,$user_id);

            if($user_id){

             $result = array('status'=>'success','user_id'=>$user_id,'auth_token'=>$unique_id,'is_phone_verified'=>true); 

         } else {

            $result = array('status'=>'error');

        }





    }



    return $result;

}



public function generate_unique() {

    $unqe = md5(uniqid(time().mt_rand(), true));

    return $unqe;

}



public function EncryptedPatientKey($unique_id,$user_id){

    $this->db->insert('auth_table',array('cust_id'=>$user_id,'unique_id'=>$unique_id));

}







function login($request){



    $this->db->where("(email = '".$request['username']."' OR name ='".$request['username']."' OR phone = '".$request['username']."')");

    $this->db->where('password' , md5($request['password']));

    $query = $this->db->get('customer');

    if($query->num_rows()>0){

        $unique_id = $this->generate_unique();

        $otp = rand(1111,9999);

        $rs = $query->row();

        $this->EncryptedPatientKey($unique_id,$rs->id);

        return $result = array('status'=>'success','user_id'=>$rs->id,'auth_token'=>$unique_id);

    }else{

        return false;

    }



}







function trip($request){



    $query = $this->db->where('unique_id',$request['auth'])->get('auth_table');

        //echo $this->db->last_query();

    if($query->num_rows()>0){

        $rs = $query->row();

        $cust_id = $rs->cust_id;



          //  print_r($cust_id);

        $this->db->last_query();

        $data = "SELECT booking.id AS trip_id,booking.start_time AS time,booking.status AS trip_status,driver.name AS car_name,driver.image AS driver_photo,

       CONCAT(pattern.currency,'',booking.fare) AS rate,booking.source_lat AS source_latitude,booking.source_lng AS source_longitude,

        booking.destination_lat  AS destination_latitude,

        booking.destination_lng  AS destination_longitude FROM `booking` LEFT JOIN driver ON booking.driver_id = driver.id LEFT JOIN pattern ON booking.pattern_id = pattern.id WHERE booking.user_id = '$cust_id' ORDER BY booking.id DESC  ";

        $query1 =$this->db->query($data)->result();

           // echo $this->db->last_query();

        return $query1;

    }else{

        return false;

    }

}







function tripdetails($request){

    $id = $request["id"];







    //     $data = "SELECT driver.driver_name AS driver_name,driver.image AS profile_photo,booking.id,booking.status AS trip_status, 

    // booking.start_time AS time,booking.driver_id,CONCAT(pattern.currency,'',booking.base_fare) AS base_fare,

    // CONCAT(pattern.currency,'',ROUND(booking.km_fare,2)) AS kilometer_fare,

    // CONCAT(pattern.currency,'',booking.min_fare) AS minutes_fare,

    // CONCAT(pattern.currency,'',ROUND(booking.sub_total_fare,2)) AS sub_total_fare,CONCAT(pattern.currency,'',ROUND(booking.fare,2)) AS total_fare,

    // ROUND(booking.distance,3) AS kilometers,booking.time AS minutes,CONCAT(pattern.currency,'',ROUND(booking.promotion_fare,2)) AS promotion_fare, booking.source_lat AS source_latitude,

    // booking.source_lng AS source_longitude,booking.id,booking.destination_lat AS destination_latitude,

    // booking.destination_lng AS destination_longitude, booking.source AS source_name,

    // booking.destination AS destination_name FROM booking LEFT JOIN driver ON driver.id = booking.driver_id 

    // LEFT JOIN pattern ON booking.pattern_id = pattern.id WHERE booking.id = '$id'"; 




    $data = "SELECT driver.driver_name AS driver_name,driver.image AS profile_photo,booking.id,booking.status AS trip_status, 
    booking.start_time AS time,booking.driver_id,CONCAT(pattern.currency,'',booking.base_fare) AS base_fare,
    CONCAT(pattern.currency,'',ROUND(booking.km_fare,2)) AS kilometer_fare,
    CONCAT(pattern.currency,'',ROUND(booking.min_fare,2)) AS minutes_fare,
    CONCAT(pattern.currency,'',ROUND(booking.sub_total_fare,2)) AS sub_total_fare,CONCAT(pattern.currency,'',ROUND(booking.fare,2)) AS total_fare,
    ROUND(booking.distance,3) AS kilometers,booking.time AS minutes,CONCAT(pattern.currency,'',ROUND(booking.promotion_fare,2)) AS promotion_fare, booking.source_lat AS source_latitude,
    booking.source_lng AS source_longitude,booking.id,booking.destination_lat AS destination_latitude,
    booking.destination_lng AS destination_longitude, booking.source AS source_name,
    booking.destination AS destination_name FROM booking LEFT JOIN driver ON driver.id = booking.driver_id 
    LEFT JOIN pattern ON booking.pattern_id = pattern.id WHERE booking.id = '$id'"; 







    $query = $this->db->query($data);

   // echo $this->db->last_query();

    $result = $query->row();

    return $result;

}











function driver_rate($request){
   $id = $request["id"];

    $query = $this->db->query("SELECT rating AS rate FROM `feedback` WHERE trip_id = '$id'")->row();
    return $query->rate;
} 







function details($request){

    $query = $this->db->where('unique_id',$request['auth'])->get('auth_table');

    if($query->num_rows()>0){

        $rs = $query->row();

        $cust_id = $rs->cust_id;

        $data = "SELECT customer.name AS name, customer.email, customer.phone AS mobile_number,customer.image AS profile_photo, address.home, address.work, address.home_lat AS home_latitude, address.home_long AS home_longitude, address.work_lat AS work_latitude,  address.work_long AS work_longitude  FROM customer LEFT JOIN address ON customer.id = address.cust_id  WHERE customer.id = '$cust_id' ";

        $query1 = $this->db->query($data)->row();

        return $query1;

    }else{

        return false;

    }



}









function edit($request){

    $query = $this->db->where('unique_id',$request['auth'])->get('auth_table');

    if($query->num_rows()>0){







        $data = array();

        if(isset($request['name'])&& $request['name']!=''){

            $data['name'] = $request['name'];

        }

        if(isset($request['email'])&& $request['email']!=''){

            $data['email'] = $request['email'];

        }

        if(isset($request['phone'])&& $request['phone']!=''){

            $data['phone'] = $request['phone'];

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



        $this->db->where('id',$rs->cust_id)->update('customer',$data);



        return true;

    }else{

        return false;

    }



}







function save($request){



    $query = $this->db->where('unique_id',$request['auth'])->get('auth_table');

    if($query->num_rows()>0){

        $rs = $query->row();

            //print_r($rs->cust_id);

        $row = $this->db->where('cust_id',$rs->cust_id)->get('address')->row();

        if(count($row)>0){



            if($request['type']==0){

                $data = array('home'=>$request['home'],'home_lat'=>$request['home_latitude'],

                    'home_long'=>$request['home_longitude']);

            }else{

                $data = array('work'=>$request['work'],'work_lat'=>$request['work_latitude'],

                    'work_long'=>$request['work_longitude']);

            }

            $this->db->where('cust_id',$row->cust_id)->update('address',$data);

        } else {



            $data = array('home'=>$request['home'],'work'=>$request['work'],'home_lat'=>$request['home_latitude'],'home_long'=>$request['home_longitude'],'work_lat'=>$request['work_latitude'],'work_long'=>$request['work_longitude'],'cust_id'=>$rs->cust_id);

            $this->db->insert('address',$data);

        }

          // echo $this->db->last_query();

        return true;

    }else{  

        return false;

    }



}







function location_details($request){

    $query = $this->db->where('unique_id',$request['auth'])->get('auth_table');

    if($query->num_rows()>0){

        $rs = $query->row();



        $cust_id = $rs->cust_id;

        $data = "SELECT address.home, address.work, address.home_lat AS home_latitude, address.home_long AS home_longitude, address.work_lat AS work_latitude,  address.work_long AS work_longitude  FROM address WHERE  cust_id = '$cust_id' ";



        $result = $this->db->query($data)->row();

        return $result;

    }else{

        return false;

    }



}







public function ride($request){



    $query = $this->db->where('unique_id',$request['auth'])->get('auth_table');

    if($query->num_rows()>0){

        $rs = $query->row();

        $data = array('cust_id'=>$rs->cust_id,'car_type'=>$request['car_type'],'source'=>$request['source'],'destination'=>$request['destination'],'source_lat'=>$request['source_latitude'],'source_lng'=>$request['source_longitude'],'destination_lat'=>$request['destination_latitude'],'destination_lng'=>$request['destination_longitude']);

        $this->db->insert('request',$data);

            //echo $this->db->last_query();

        $last_id = $this->db->insert_id();

        return $result = array('id'=>$last_id);

    } else {

        return false;

    }

    

}







public function req_status($request){

    $query = $this->db->select('request.trip_id AS trip_id,request.status AS request_status,request.source_lat AS source_latitude,request.source_lng AS source_longitude,

        request.destination_lat AS destination_latitude,request.destination_lng AS destination_longitude,

        request.car_type AS car_type,driver.id AS driver_id,driver.driver_name,driver.phone AS driver_number,

        driver.image AS driver_photo,driver.name AS car_name,driver.vehicle_reg_no AS car_number,

        driver_lat AS car_latitude,driver_lng AS car_longitude')->from('request')->join('driver','request.driver_id = driver.id','left')->where('request.id',$request['id'])->get();

    if($query->num_rows()>0){

        $rs = $query->row();



        $row = array('id'=>$rs->driver_id,'car_type'=>$rs->car_type);

        $driv_id = $row['id'];

        $type = $row['car_type'];



        /********************Time Calculation********************************/

            $rs->time = $time; //Hard coded



            /********************************************************************/

            $rs->rating = $this->driver_rate($driv_id);



            $rs->car_photo = $this->car_photo($type);

            return $rs;



        } else {

            return false;

        }

    }









    function car_photo($type){



        $query = $this->db->query("SELECT image AS photo FROM `car_type` WHERE id = '$type'")->row();



        return $query->photo;

    }









    public function types_car($request){

       $query = $this->db->where('unique_id',$request['auth'])->get('auth_table');

       $new_rs = array();

       if($query->num_rows()>0){

        $rs = $query->row();

        $data = $this->db->query("SELECT * FROM countries WHERE id ='98' ")->row();

        $curr = $data->currrency_symbol;





        $data ="SELECT id AS car_ID,name AS car_name,max_seat AS Max_size,image AS car_image FROM car_type WHERE status = '1'";



        $query = $this->db->query($data);

        //echo $this->db->last_query();

        $result = $query->result();



        foreach ($result as $rs){

            //print_r($rs);

            $pattern = $this->get_pattern($rs->car_ID);

            //print_r($pattern);

            if(!empty($pattern)){



                foreach ($pattern as $res){

                    //print_r($res);

                   if($res->lat=='' || $res->lng==''){

                    continue;

                }



                $check_in_range = $this->check_range($res,$request);

                if($check_in_range){

                    $rs->min_fare = $res->currency.$res->base_price;

                           // $rs->car_ID = $res->id;

                    break;

                }

            }

        } else {



            $rs->fare = 0;

                   // $new_res[] = 0;





        }



        $new_res[] = $rs;

    }



    return $new_res;



}

}



function check_range($rs,$rq){  



    $latitude2 = $rq['latitude'];

    $longitude2 = $rq['longitude'];

    $latitude1= $rs->lat;

    $longitude1 = $rs->lng;

    $range = $this->getDistance($latitude1, $longitude1, $latitude2, $longitude2);

    if($range <= $rs->range){

        $latitude2 = $rq['latitude'];

        $longitude2 = $rq['longitude'];

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













public function trip_feedback($request){

    $query = $this->db->where('unique_id',$request['auth'])->get('auth_table');

    if($query->num_rows()>0){

        $rs = $query->row();

        $data = array('trip_report'=>$request['trip_feedback_type']);



        $this->db->where('trip_id',$request['trip_id'])->update('feedback',$data);



        return true;

    }else{

        return false;

    }



}







public function driver_feedback($request){

    $query = $this->db->where('unique_id',$request['auth'])->get('auth_table');

         //echo $this->db->last_query();

    if($query->num_rows()>0){

            //echo $this->db->last_query();

        $rs = $query->row();

        unset($request['auth']);



        $badfeedbck = implode(', ',$request['bad_feedback']);

        $goodfeedbck = implode(', ',$request['good_feedback']);







        $where = array('trip_id'=>$request['id'],'cust_id'=>$rs->cust_id);

        $data = array('rating'=>$request['rating'],'good_feedback'=>$goodfeedbck,'bad_feedback'=>$badfeedbck,'driver_feedback'=>$request['driver_feedback']);



        $this->db->where($where)->update('feedback',$data);

        //echo $this->db->last_query();



        return true;



    }else{



        return false;

    }



}











public function trip_completion($request){

    $query = $this->db->where('unique_id',$request['auth'])->get('auth_table');

    if($query->num_rows()>0){

       $id = $request['id'];

       $data = "SELECT driver.driver_name AS driver_name,driver.image AS driver_photo,CONCAT(pattern.currency,'',booking.fare) AS fare,

       booking.start_time AS time,booking.source AS Source,booking.destination AS destination,booking.source_lat AS source_latitude,

       booking.source_lng AS source_longitude,booking.destination_lat AS destination_latitude,

       booking.destination_lng AS destination_longitude FROM booking LEFT JOIN driver ON driver.id = booking.driver_id LEFT JOIN pattern ON booking.pattern_id = pattern.id WHERE booking.id = '$id'"; 

       $query = $this->db->query($data);

       $result = $query->row();

       //echo $this->db->last_query();

       return $result;



   }else{



    return false;

}



}







   





public function cancel_trip($request){

    $query = $this->db->where('unique_id',$request['auth'])->get('auth_table');

    $rs = $query->row();

 $cust_id = $rs->cust_id;

   // $driv_id = $rs->driver_id;

   // print_r($cust_id);

    if($query->num_rows()>0){

        $id = $request["trip_id"];

        $result = $this->db->where('id',$id)->update('booking',array('status'=>'0'));

        //echo $this->db->last_query();

        $data2 = "SELECT * FROM booking WHERE user_id = '$cust_id' ";

        $query = $this->db->query($data2);

        $rs2 = $query->row();

        $driv_d = $rs2->driver_id;



        $data3 = "SELECT * FROM driver WHERE id = '$driv_d'";

        $query = $this->db->query($data3);

        $rs3 = $query->row();

       // $driv_d = $rs2->driver_id;



        $fcm = $rs3->fcm_token;



        //echo $this->db->last_query();

        //$fcm = $rs2->fcm_token;

        $fcm_data = array('id'=>$id,'title'=>'LaTaxi','message'=>'Trip Cancelled');

        //print_r($fcm);

       // print_r($fcm_data);

        $this->push_sent_cancel($fcm,$fcm_data);



        return $result;



    }else{



        return false;

    }



}







function push_sent_cancel($fcm_token,$fcm_data){

   // print_r($fcm_data);



 $data= "{ \"notification\": { \"title\": \"".$fcm_data['title']."\", \"text\": \"".$fcm_data['message']."\", \"sound\": \"default\" }, \"time_to_live\": 60, \"data\" : {\"response\" : {\"status\" : \"success\", \"data\" : {\"trip_id\" : \"".$fcm_data['id']."\", \"trip_status\" : 0}}}, \"to\" : \"".$fcm_token."\"}"; 

 $ch = curl_init("https://fcm.googleapis.com/fcm/send");

 $header=array('Content-Type: application/json', "Authorization: key=AIzaSyCkUU9_wA4o6j0300MLDmKREwNhoJAAzM8");

 curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

 curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );

 curl_setopt($ch, CURLOPT_POST, 1);

 curl_setopt($ch, CURLOPT_POSTFIELDS, $data);



 // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

 // curl_close($ch);

  $out=curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_exec($ch);

     curl_close($ch);





}













    // public function cancel_request($request){



    //     $id = $request["request_id"];

    //     $result = $this->db->where('id',$id)->update('request',array('status'=>'3'));

    //     return $result;



    //     }





public function cancel_request($request){

    $query = $this->db->where('unique_id',$request['auth'])->get('auth_table');

    if($query->num_rows()>0){

        $id = $request["request_id"];

        $result = $this->db->where('id',$id)->update('request',array('status'=>'3'));

        return $result;



    }else{



        return false;

    }



}







public function mobile_availability($request){

   $phone = $request['phone'];

   $num = $this->db->where('phone',$phone)->get('customer')->num_rows();

   if($num>0){

       return $result = array('status'=>'success','phone'=>$phone,'is_available'=>'false');

   } else {

       return false;

   }

}













function available_cars($request){



       //echo "string";



 $current_lat = $request['latitude'];



 $current_lng = $request['longitude'];



 $car_type = $request['car_type'];



 $query = $this->db->query("SELECT car_type.max_seat AS max_size,

    3956 * 2 * ASIN(SQRT(POWER(SIN(($current_lat - driver.driver_lat) * pi()/180 / 2), 2) 

        + COS($current_lat * pi()/180 ) * COS(driver.driver_lat * pi()/180) 

            * POWER(SIN(($current_lng - driver.driver_lng) * pi()/180 / 2), 2) )) as distance,

        driver.driver_lat,driver.driver_lng FROM `car_type` LEFT JOIN driver ON driver.car_id = car_type.id 

        WHERE car_type.id = $car_type AND driver.book_status = 0 GROUP BY driver.id HAVING distance < 25 

        ORDER BY distance ASC LIMIT 0,1");

//echo $this->db->last_query();





 if($query->num_rows()>0){

    $rs = $query->row();

    $dist = $this->GetDrivingDistance($request['latitude'],$rs->driver_lat,$request['longitude'],$rs->driver_lng);















    $pattern = $this->get_pattern($car_type);

            //print_r($pattern);

    if(!empty($pattern)){



        foreach ($pattern as $res){

                    //print_r($res);

           if($res->lat=='' || $res->lng==''){

            continue;

        }



        $check_in_range = $this->check_range($res,$request);

        if($check_in_range){

            $rs->min_fare = $res->currency.$res->base_price;

                           // $rs->car_ID = $res->id;

            break;

        }

    }

} else {



    $rs->min_fare = 0;

                   // $new_res[] = 0;





}









$result = array('cars_available'=>'Cars Available',

    'min_fare'=>$rs->min_fare,

    'eta_time'=>$dist['time'],

    'max_size'=>$rs->max_size);



return $result;



} else {

    return false;

}





}







function GetDrivingDistance($lat1, $lat2, $long1, $long2) {

    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&language=pl-PL";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $response = curl_exec($ch);

    curl_close($ch);

    $response_a = json_decode($response, true);

    $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];

    $time = $response_a['rows'][0]['elements'][0]['duration']['text'];



    return array('distance' => $dist, 'time' => $time);

}







function car_fare($request){

 return $this->db->where('id',$request['car_type'])->get('car_type')->row();

      // echo $this->db->last_query();

}







function get_pattern($car_type){

       //print_r($car_type);

       // $this->db->where('id','1')->get('car_type');

    return $this->db->select('pattern.*,CONVERT(pattern.currency USING utf8) AS new_currency')->where('car_type',$car_type)->get('pattern')->result();

    //echo $this->db->last_query();

}







function check_in_range($rs,$rq){  



    $latitude2 = $rq['source_latitude'];

    $longitude2 = $rq['source_longitude'];

    $latitude1= $rs->lat;

    $longitude1 = $rs->lng;

    $range = $this->getDistance($latitude1, $longitude1, $latitude2, $longitude2);

    if($range <= $rs->range){

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





    return $rs;

}









public function save_token($request){



    $query = $this->db->where('unique_id',$request['auth'])->get('auth_table');

    if($query->num_rows()>0){

        $rs = $query->row();

        $id = $rs->cust_id;

        $data = array('fcm_token'=>$request['fcm_token']);

        $result = $this->db->where('id',$id)->update('customer',$data);

        return $result;

    } else {

        return false;

    }

    

}







public function promo_save($request){



    $promo_code = $request['free_ride_code'];

    $auth_token = $request['auth'];

    $data = "SELECT * FROM promocode WHERE code = '$promo_code'"; 

    $query = $this->db->query($data);

    $rs = $query->row();

    if(empty($rs)){

           // return $result = array('status'=>'error','message'=>'Invalid code');

        print json_encode(array('status' => 'error','message'=>'Invalid code'));

        die();

    }else{

            //print_r(date('Y-m-d'));

           // print_r($rs->expiration_date);

        if($rs->expiration_date > date('Y-m-d')){





            $this->update_promo($auth_token,$promo_code);

            return $result = array('status'=>'success','message'=>'Promocode Updated successfully');

        } else {

            return $result = array('status'=>'error','message'=>'Code Expired');

        }

    }





}



public function update_promo($auth_token,$promo_code){

    $query = $this->db->where('unique_id',$auth_token)->get('auth_table');

    if($query->num_rows()>0){

        $rs = $query->row();

        $data = array('promocode'=>$promo_code);

        $this->db->where('id',$rs->cust_id)->update('customer',$data);

        return true;

    }else{

        return false;

    }



}







    // function message($request){



    //     $id = $request['trip_id'];

    //     $fcm_data = array('id'=>$id,'title'=>'LaTaxi','message'=>'Trip Completed');







    //    $data = array('status '=>'3');

    //    $this->db->where('id',$id)->update('booking',$data);



    //    $data = "SELECT * FROM booking WHERE id = '$id' ";

    //    $query = $this->db->query($data);

    //    $rs = $query->row();

    //    $cust_id = $rs->user_id;





    //    $data2 = "SELECT * FROM customer WHERE id = '$cust_id' ";

    //    $query = $this->db->query($data2);

    //    $rs2 = $query->row();

    //    $fcm = $rs2->fcm_token;



    //    $this->push_sent($fcm,$fcm_data);





    // }





function driver_message($id){

//print_r($id);

        //$id = $request['id'];

    $fcm_data = array('id'=>$id,'title'=>'LaTaxi','message'=>'Trip Request');







       // $data = array('status '=>'3');

       // $this->db->where('id',$id)->update('booking',$data);



    $data = "SELECT * FROM request WHERE id = '$id' ";



    $query = $this->db->query($data);

    $rs = $query->row();

      // echo $this->db->last_query();

    $driver_id = $rs->driver_id;

       //echo $this->db->last_query();

      //print_r($driver_id);



    $data2 = "SELECT * FROM driver WHERE id = '$driver_id' ";

    $query = $this->db->query($data2);

    $rs2 = $query->row();

       //echo $this->db->last_query();

    $fcm = $rs2->fcm_token;

     // print_r($fcm);



    $this->push_sent($fcm,$fcm_data);





}









//     function push_sent($fcm_token,$fcm_data){



// //print_r($fcm_token);

//         $ch = curl_init("https://fcm.googleapis.com/fcm/send");

//         $header=array('Content-Type: application/json',

//         "Authorization: key=AIzaSyCkUU9_wA4o6j0300MLDmKREwNhoJAAzM8");

//         curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

//         curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );



//         curl_setopt($ch, CURLOPT_POST, 1);

//         curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"notification\": {    \"title\": \"".$fcm_data['title']."\",

//             \"text\": \"".$fcm_data['message']."\"  }, \"data\" : {\"response\" : {\"status\" : \"success\",

//              \"data\" : {\"id\" : \"".$fcm_data['id']."\"}}}   

//               \"to\" : \"".$fcm_token."\"}");



//        // curl_exec($ch);

//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  

//         curl_close($ch);





//     }





function push_sent($fcm_token,$fcm_data){



    $data= "{ \"notification\": { \"title\": \"".$fcm_data['title']."\", \"text\": \"".$fcm_data['message']."\", \"sound\": \"default\" }, \"time_to_live\": 60, \"data\" : {\"response\" : {\"status\" : \"success\", \"data\" : {\"id\" : \"".$fcm_data['id']."\"}}}, \"to\" : \"".$fcm_token."\"}";

       // print_r($data);

    $ch = curl_init("https://fcm.googleapis.com/fcm/send");

    $header=array('Content-Type: application/json', "Authorization: key=AIzaSyCkUU9_wA4o6j0300MLDmKREwNhoJAAzM8");

    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false ); 

    curl_setopt($ch, CURLOPT_POST, 1);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $out=curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_exec($ch);

     curl_close($ch);



}







public function rides($request){

    $id = $request['id'];

    $query = $this->db->where('unique_id',$request['auth'])->get('driver_auth_table');

    if($query->num_rows()>0){

        $rs = $query->row();

        $data = array('driver_id'=>$rs->driver_id);



        $this->db->where('id',$id)->update('request',$data);



        $data = $this->db->where('id',$id)->get('request')->row_array();





        $data2 = array('car_type'=>$data['car_type'],

          'user_id'=>$data['cust_id'],

          'source'=>$data['source'],

          'destination'=>$data['destination'],

          'source_lat'=>$data['source_lat'],

          'source_lng'=>$data['source_lng'],

          'destination_lat'=>$data['destination_lat'],

          'destination_lng'=>$data['destination_lng'],

          'status'=>'1'

          );

        $this->db->insert('booking',$data2);



        $last_id = $this->db->insert_id();

                 //print_r($last_id);



        $data3 = array('trip_id'=>$last_id,'status'=>'1');





        $this->db->where('id',$id)->update('request',$data3);



        $data4 = array('trip_id'=>$last_id,'cust_id'=>$data['cust_id'],'driver_id'=>$rs->driver_id);



        $this->db->insert('feedback',$data4);



        return true;

    }else{

        return false;

    }



}







function trigger($request){



      //print_r($request);



    $id = $request['id'];



        //$id = '839';



    $data = "SELECT * FROM request WHERE id = '$id' ";

    $query = $this->db->query($data);

    $rs = $query->row();

    $current_lat = $rs->source_lat;

    $current_lng = $rs->source_lng;

    $car_type = $rs->car_type;

//echo $this->db->last_query();

    $driver_list = $this->db->select('driver_id')->where('req_id',$id)->get('driver_request')->result();

//echo $this->db->last_query();

    foreach ($driver_list as $driver_own) {

     $rs_driver[] = $driver_own->driver_id;

 }



 if(!empty($driver_list)){

    $driver_list  = implode(',', $rs_driver);

    $driver_case = "AND driver.id NOT IN($driver_list)";

} else{

    $driver_case = '';

}









$query = $this->db->query("SELECT driver.id,driver.driver_lat,driver.driver_lng,

    driver.car_id,3956 * 2 * ASIN(SQRT(POWER(SIN(($current_lat - driver.driver_lat) * pi()/180 / 2), 2) 

        + COS($current_lat * pi()/180 ) * COS(driver.driver_lat * pi()/180) 

            * POWER(SIN(($current_lng - driver.driver_lng) * pi()/180 / 2), 2) )) as distance,

        driver.driver_lat,driver.driver_lng FROM driver 

        WHERE driver.car_id = $car_type AND driver.book_status = '0' AND driver.is_online = '1' AND driver.status = '1' $driver_case GROUP BY driver.id HAVING distance < 25 

        ORDER BY distance ASC LIMIT 0,10");







//echo $this->db->last_query();





if($query->num_rows()>0){

    $rs = $query->result();







    foreach ($rs as $driver) {

        $driver_lat = $driver->driver_lat;

        $driver_lng = $driver->driver_lng;

        $row = $this->GetDrivingDistance($current_lat,$driver_lat,$current_lng,$driver_lng);

        $driver->time = $row['time'];

        $new_rs[] = $driver;



    }







    usort($new_rs, function($a, $b) {

        $time = $a->time - $b->time;

        $this->req_status($time);

        return $a->time - $b->time;

    });



        //print_r($new_rs);



    $req_array = array('req_id'=>$id,

        'driver_id'=>$new_rs[0]->id,

        'req_date'=>strtotime(date('Y-m-d')));

    $this->db->insert('driver_request',$req_array);

    $drvr_id = $new_rs[0]->id;

//print_r($drvr_id);

    $data = "UPDATE request SET driver_id = '$drvr_id' WHERE id = '$id' ";

    $query = $this->db->query($data);



//         $this->db->where('id',$id)->update('request',array('driver_id',$new_rs[0]->id));

// echo $this->db->last_query();

    $this->driver_message($id);



    return true;



} else {

    return false;

}





}













function statusof_app($request){

//print_r($request);

    $query = $this->db->where('unique_id',$request['auth'])->get('auth_table');

    //echo $this->db->last_query();

    if($query->num_rows()>0){



       $rs = $query->row();

       $cust_id = $rs->cust_id;





 $res = "SELECT * FROM booking WHERE user_id = '$cust_id' ORDER BY id DESC LIMIT 0,1";



 $query = $this->db->query($res);

 // echo $this->db->last_query();

 $rs = $query->row();



       $booking_status = $rs->status;

       $driver_id = $rs->driver_id;

       $book_id = $rs->id;

    //print_r($driver_id);





       $cust_status = $rs->status;



       if($booking_status == 2){

        //echo "string";

        // $data = "SELECT request.trip_id AS trip_id,request.source_lat AS source_latitude,request.source_lng As source_longitude,request.destination_lat AS destnation_latitude,

        // request.destination_lng AS destination_longitude,driver.driver_name AS driver_name,driver.phone AS driver_number,driver.image AS driver_photo,

        // driver.name AS car_name,driver.vehicle_reg_no AS car_number,driver.driver_lat AS car_latitude,

        // driver.driver_lng AS car_longitude FROM request 

        // LEFT JOIN driver ON request.driver_id = driver.id WHERE request.driver_id = '$driver_id' ";



        //   $data = "SELECT booking.id AS trip_id,booking.source_lat AS source_latitude,booking.source_lng As source_longitude,

        //   booking.destination_lat AS destination_latitude,

        // booking.destination_lng AS destination_longitude,driver.driver_name AS driver_name,driver.phone AS driver_number,

        // driver.image AS driver_photo,

        // driver.name AS car_name,driver.vehicle_reg_no AS car_number,driver.driver_lat AS car_latitude,

        // driver.driver_lng AS car_longitude FROM booking 

        // LEFT JOIN driver ON booking.driver_id = driver.id WHERE booking.driver_id = '$driver_id' ";



           $data = "SELECT booking.id AS trip_id,booking.source_lat AS source_latitude,booking.source_lng As source_longitude,

          booking.destination_lat AS destination_latitude,

        booking.destination_lng AS destination_longitude,driver.driver_name AS driver_name,driver.phone AS driver_number,

        driver.image AS driver_photo,

        driver.name AS car_name,driver.vehicle_reg_no AS car_number,driver.driver_lat AS car_latitude,

        driver.driver_lng AS car_longitude FROM booking 

        LEFT JOIN driver ON booking.driver_id = driver.id WHERE booking.id = '$book_id'   ";

           



          //die();

        $result =$this->db->query($data)->row();

       // echo $this->db->last_query();



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









    // function forgot_pswd($request){



    // $email = $request['email'];

    // $data = "SELECT * FROM customer WHERE email = '$email'"; 

    // $query = $this->db->query($data);

    // $count = mysqli_num_rows($res);

    // if($count == 1){

    //     $result = $this->random_password();

    //      $this->db->where('email',$email)->update('customer',array('password'=>md5($result)));

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





























}

?>