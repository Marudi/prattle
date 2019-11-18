<?php session_start();

$role=$_SESSION['role'];
$emailme=$_SESSION['email'];
$service_id=$_SESSION['service_id'];
//die($role);

include 'db.php';

include 'function.php';

$page=basename(__FILE__);

//die($role);

if(accesspage($role,$page,$link))

{}

else

{header("Location:no_access.php");}



if($role=='')



{header("Location:index.php");}
$msg = "";
	if(isset($_POST["email"]))
	{
		
		$email = $_POST["email"];
		$len =6;
		$word = array_merge(range('a', 'z'), range('A', 'Z'));
    shuffle($word);
    $password=substr(implode($word), 0, $len);
	
	 shuffle($word);
    $activate=substr(implode($word), 0, $len);
		//$password = $_POST["phone"];
		//$roleb=$_POST["role_val"];

		//$roleb = mysqli_real_escape_string($link, $roleb);
		$email = mysqli_real_escape_string($link, $email);
		//$password = mysqli_real_escape_string($link, $password);
		//$password =$password;
		$name= mysqli_real_escape_string($link,$_POST['name']);
		$biz= mysqli_real_escape_string($link,$_POST['biz']);
		$state= mysqli_real_escape_string($link,$_POST['area']);
		$lga= mysqli_real_escape_string($link,$_POST['lga']);
		$city= mysqli_real_escape_string($link,$_POST['city']);
		$address= mysqli_real_escape_string($link,$_POST['address']);
		$gender= mysqli_real_escape_string($link,$_POST['gender']);
		$phone= mysqli_real_escape_string($link,$_POST['phone']);
		$car= mysqli_real_escape_string($link,$_POST['car']);
		$bank= mysqli_real_escape_string($link,$_POST['bank']);
		$ac= mysqli_real_escape_string($link,$_POST['ac']);
		$licence= mysqli_real_escape_string($link,$_POST['licence']);
		$username= mysqli_real_escape_string($link,$_POST['username']);
		//$secret= mysqli_real_escape_string($link,$_POST['secret']);
		$pic=$_FILES['pic'];
		$cac=$_FILES['cac'];
		$id=$_FILES['id'];
		$agreement=$_FILES['agreement'];
		$registration=$_FILES['registration'];
		
		
    $hash = password_hash($password, PASSWORD_BCRYPT);
   


	
		
		
		//else
		//{
			$service=$_SESSION['service_id'];
			$myself=$_SESSION['email'];
			$sql="INSERT into agents(car_make,activation_code,licence,gender,company_name,agent_name,secret,biz_id,address,city,lga,state,phone,email,bank,
			ac,password,agent_type,service_id,registered_by,registered_on,pwd)
			VALUES ('$car','$activate','$licence','$gender','$name', '$username','','$biz','$address','$city','$lga','$state','$phone','$email',
			'$bank','$ac','$hash','','$service_id','$myself',now(),'$password')";
			$query = mysqli_query($link,$sql) or die ('<div class=msg>'.mysqli_error($link).'</div>');
			$id=mysqli_insert_id($link);
			
			 $picname = $_FILES["pic"]["name"];
	$pic_basename = substr($picname, 0, strripos($picname, '.')); // get file extention
	$pic_ext = substr($picname, strripos($picname, '.')); // get file name
	$picsize = $_FILES["pic"]["size"];
	
	$allowed_file_types = array('.jpg','.jpeg','.png');
	
	$target_dir = "passports/";
		//if (in_array($cac_ext,$allowed_file_types) && ($cacsize < 200000))
	if (in_array($pic_ext,$allowed_file_types))
	{	
		// Rename file
		$newfilename = $id.'pic'. $pic_ext;
		
		
			if(move_uploaded_file($_FILES["pic"]["tmp_name"], "passports/" . $newfilename))
			{
				$sql="update agents set pic='$newfilename' where agentid='$id'";
				$res=mysqli_query($link,$sql) or die (mysqli_error($link));
			}
	}
	
	/*******************************************************************************************/
	 $cacname = $_FILES["cac"]["name"];
	$cac_basename = substr($cacname, 0, strripos($cacname, '.')); // get file extention
	$cac_ext = substr($cacname, strripos($cacname, '.')); // get file name
	$cacsize = $_FILES["cac"]["size"];
	
	
	
	$target_dir = "passports/";
		//if (in_array($cac_ext,$allowed_file_types) && ($cacsize < 200000))
	if (in_array($cac_ext,$allowed_file_types))
	{	
//die('Cac');
		// Rename file
		$newfilename = $id.'cac'. $cac_ext;
		
		
			if(move_uploaded_file($_FILES["cac"]["tmp_name"], "passports/" . $newfilename))
			{
				$sql="update agents set cac='$newfilename' where agentid='$id'";
				$res=mysqli_query($link,$sql) or die (mysqli_error($link));
			}
	}
	//else{die ('no cac');}
	/***************************************************************************************************/
	
				 $idname = $_FILES["id"]["name"];
	$id_basename = substr($idname, 0, strripos($idname, '.')); // get file extention
	$id_ext = substr($idname, strripos($idname, '.')); // get file name
	$idsize = $_FILES["id"]["size"];
	
	//if (in_array($cac_ext,$allowed_file_types) && ($cacsize < 200000))
	if (in_array($id_ext,$allowed_file_types))
	{	
		// Rename file
		$newfilename = $id.'id'. $id_ext;
		
		
			if(move_uploaded_file($_FILES["id"]["tmp_name"], "passports/" . $newfilename))
			{
				$sql="update agents set id='$newfilename' where agentid='$id'";
				$res=mysqli_query($link,$sql) or die (mysqli_error($link));
			}
	}
	/********************************************************************************************/
	
		 $agreement = $_FILES["bill"]["name"];
	$agreement_basename = substr($agreement, 0, strripos($agreement, '.')); // get file extention
	$agreement_ext = substr($agreement, strripos($agreement, '.')); // get file name
	$agreementsize = $_FILES["bill"]["size"];
		//if (in_array($cac_ext,$allowed_file_types) && ($cacsize < 200000))

	if (in_array($agreement_ext,$allowed_file_types))
	{	
		// Rename file
		$newfilename = $id.'bill'. $agreement_ext;
		
		
			if(move_uploaded_file($_FILES["bill"]["tmp_name"], "passports/" . $newfilename))
			{
				$sql="update agents set bill='$newfilename' where agentid='$id'";
				$res=mysqli_query($link,$sql) or die (mysqli_error($link));
			}
	}
	/********************************************************************************************/
	
		 $agreement = $_FILES["agreement"]["name"];
	$agreement_basename = substr($agreement, 0, strripos($agreement, '.')); // get file extention
	$agreement_ext = substr($agreement, strripos($agreement, '.')); // get file name
	$agreementsize = $_FILES["agreement"]["size"];
		//if (in_array($cac_ext,$allowed_file_types) && ($cacsize < 200000))

	if (in_array($agreement_ext,$allowed_file_types))
	{	
		// Rename file
		$newfilename = $id.'agreement'. $agreement_ext;
		
		
			if(move_uploaded_file($_FILES["agreement"]["tmp_name"], "passports/" . $newfilename))
			{
				$sql="update agents set agreement='$newfilename' where agentid='$id'";
				$res=mysqli_query($link,$sql) or die (mysqli_error($link));
			}
	}
	/*******************************************************************/
		 $regname = $_FILES["registration"]["name"];
	$registration_basename = substr($regname, 0, strripos($regname, '.')); // get file extention
	$reg_ext = substr($regname, strripos($regname, '.')); // get file name
	$regsize = $_FILES["registration"]["size"];
	
	//if (in_array($cac_ext,$allowed_file_types) && ($cacsize < 200000))
	if (in_array($reg_ext,$allowed_file_types))
	{	
		// Rename file
		$newfilename = $id.'registration'. $reg_ext;
		
		
			if(move_uploaded_file($_FILES["registration"]["tmp_name"], "passports/" . $newfilename))
			{
				$sql="update agents set registration='$newfilename' where agentid='$id'";
				$res=mysqli_query($link,$sql) or die (mysqli_error($link));
			}
	}
	
	
			if($query)
			{
				$msg = "Driver  Succesfully Registered";
				require( 'PHPMailer/PHPMailerAutoload.php' );



  //Creating the email body to be sent
  //$email_body = "Welcome To Paycluster.Your Details Are Shown Below:";
  //$email_body .= "password: " . $password . "\n";
 // $email_body .= "Email: " . $email . "\n";
  //$email_body .= "State: " . $state . "\n";

$mail = new PHPMailer;

//Enable SMTP debugging. 
//$mail->SMTPDebug = 3;                               
//Set PHPMailer to use SMTP.
$mail->isSMTP();            
//Set SMTP host name                          
$mail->Host = "smtp.gmail.com";
//Set this to true if SMTP host requires authentication to send email
$mail->SMTPAuth = true;                          
//Provide username and password     
$mail->Username = "tizataxiapp@gmail.com";                 
$mail->Password = "12345678****";                           
//If SMTP requires TLS encryption then set it
$mail->SMTPSecure = "tls";                           
//Set TCP port to connect to 
$mail->Port = 587;                                   
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
$mail->From = "tizataxiapp@gmail.com";
$mail->FromName = "Tiza Taxi App";

$mail->addAddress($email,$username);

$mail->isHTML(true);

$mail->Subject = "Tiza Taxi App";
$mail->Body = "<b>Your Tiza Password Is:&nbsp;".$password."</b><br/><b>And Your  Activation Code Is:&nbsp;".$activate."</b>";
$mail->AltBody = "Your Tiza Activation Code Is: ".$activate." And Your Password Is ".$password;

if(!$mail->send()) 
{
   // echo "Mailer Error: " . $mail->ErrorInfo;
} 
else 
{
  //  echo "Message has been sent successfully";
}
			
	}//end of concern
		}	

?>

<!DOCTYPE html>

<html lang="en">

  <head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <!-- Meta, title, CSS, favicons, etc. -->

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">



    <title>Tiza::Add New Driver</title>



    <!-- Bootstrap -->

    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->

    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- iCheck -->

    <link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">

    <!-- bootstrap-progressbar -->

    <link href="vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">

    <!-- jVectorMap -->

    <link href="css/maps/jquery-jvectormap-2.0.3.css" rel="stylesheet"/>



    <!-- Custom Theme Style -->

    <link href="build/css/custom.min.css" rel="stylesheet">

    <script>

$(function() {

  $('#pstart').datepick({dateFormat: 'yyyy-mm-dd'}); 

  $('#pstop').datepick({dateFormat: 'yyyy-mm-dd'}); 

  //$('#inlineDatepicker').datepick({onSelect: showDate});

});



function show() {

   document.getElementById("upload").style.display = 'inline';

}

function show2() {

   document.getElementById("upload2").style.display = 'inline';

}

</script>     

  <style type="text/css">
  
  .msg{
	color:red
}
	</style>


  </head>



  <body class="nav-md">

    <div class="container body">

      <div class="main_container">

        <div class="col-md-3 left_col">

          <div class="left_col scroll-view">

            <div class="navbar nav_title" style="border: 0;">

              <a href="index.html" class="site_title"><i class="fa fa-money"></i> <span>TIZA</span></a>

            </div>



            <div class="clearfix"></div>



            <!-- menu profile quick info -->

            <div class="profile">

              <div class="profile_pic">

                <img src="<?php echo 'matlog.jpg'; ?>" alt="..." class="img-circle profile_img">

              </div>

              <div class="profile_info">

                <span>Welcome</span>

                

              </div>

            </div>

            <!-- /menu profile quick info -->



            <br />



            <!-- sidebar menu -->

            <?php 
			//echo
        // $role=$_SESSION['role'];
			//Administrator
			$menu=getMenu($role); //echo ($menu);
			include $menu;
			
			?>

            <!-- /sidebar menu -->



            <!-- /menu footer buttons -->

            

            <!-- /menu footer buttons -->

          </div>

        </div>



        <!-- top navigation -->

        <div class="top_nav">

          <div class="nav_menu">

            <nav class="" role="navigation">

              <div class="nav toggle">

                <a id="menu_toggle"><i class="fa fa-bars"></i></a>

              </div>



              

            </nav>

          </div>

        </div>

        <!-- /top navigation -->



        <!-- page content -->

		<?php

include 'db.php';



?>

        <div class="right_col" role="main">

          <!-- top tiles -->

          <div class="row">

            <div class="col-md-12 col-sm-12 col-xs-12 tile_stats_count">
			<h1 class=msg align=center><?php echo $msg;?></h1>
             <h3 class="text-center">Add New Driver</h3>

			 								

			 

			 </div>

			 <div class="col-md-12 col-sm-12 col-xs-12 tile_stats_count">

             

								

<form action=""  enctype='multipart/form-data' method='post' class="form-horizontal form-label-left">
<div class="form-group">

<label class="control-label col-md-3 col-sm-3 col-xs-12">Company Name</label>

<div class="col-md-9 col-sm-9 col-xs-12"><input name="name"  required class="form-control"> </div>

</div>

<div class="form-group">

<label class="control-label col-md-3 col-sm-3 col-xs-12">Business ID</label>

<div class="col-md-9 col-sm-9 col-xs-12"><input name="biz"  required class="form-control"> </div>

</div>
<div class="form-group">

<label class="control-label col-md-3 col-sm-3 col-xs-12">State</label>
<div class="col-md-9 col-sm-9 col-xs-12"><select id="area" name="area" required class="form-control"> <option value="">--Select State--</option>



<?php

$bank_sql="select statename from states order by statename";



$res_bank_sql=mysqli_query($link,$bank_sql) or die (mysqli_error($link));

while($row=mysqli_fetch_assoc($res_bank_sql))

  

    {

        ?>

            <option value="<?php echo $row['statename'];?>"><?php echo $row['statename'];?></option>

        <?php

    }

?></select></div></div>

<div class="form-group">

<label class="control-label col-md-3 col-sm-3 col-xs-12">Lga</label>
<div class="col-md-9 col-sm-9 col-xs-12"><select id=street name="lga" required class="form-control"> <option value="">--Select Lga--</option>



</select></div></div>

<div class="form-group">

<label class="control-label col-md-3 col-sm-3 col-xs-12">City</label>

<div class="col-md-9 col-sm-9 col-xs-12"><input name="city"  required class="form-control"> </div>

</div>
<div class="form-group">

<label class="control-label col-md-3 col-sm-3 col-xs-12">Address</label>

<div class="col-md-9 col-sm-9 col-xs-12"><input name="address"  required class="form-control"> </div>

</div>
<div class="form-group">

<label class="control-label col-md-3 col-sm-3 col-xs-12">Phone</label>

<div class="col-md-9 col-sm-9 col-xs-12"><input name="phone" type="number" required class="form-control"> </div>

</div>


<div class="form-group">

<label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>

<div class="col-md-9 col-sm-9 col-xs-12"><input name="email" type="email" required class="form-control"> </div>

</div>
<div class="form-group">

<label class="control-label col-md-3 col-sm-3 col-xs-12">Gender</label>

<div class="col-md-9 col-sm-9 col-xs-12"><select name="gender" required class="form-control"> 
<option value="">--Select Gender--</option>





            <option value="Female">Female</option>
			<option value="Male">Male</option>

  </select> </div>

</div>
<div class="form-group">

<label class="control-label col-md-3 col-sm-3 col-xs-12">Bank</label>

<div class="col-md-9 col-sm-9 col-xs-12">
<select name="bank" required class="form-control"> <option value="">--Select Bank--</option>



<?php

$bank_sql="select bank_id,upper(bank_name) bank from  banks order by bank_name";



$res_bank_sql=mysqli_query($link,$bank_sql) or die (mysqli_error($link));

while($row_bank=mysqli_fetch_assoc($res_bank_sql))

  

    {

        ?>

            <option value="<?php echo $row_bank['bank_id'];?>"><?php echo $row_bank['bank'];?></option>

        <?php

    }

?></select>




 </div>

</div>



<div class="form-group">

<label class="control-label col-md-3 col-sm-3 col-xs-12">Bank Account</label>

<div class="col-md-9 col-sm-9 col-xs-12"><input name="ac"  required class="form-control"> </div>

</div>



<div class="form-group">

<label class="control-label col-md-3 col-sm-3 col-xs-12">Driver Name</label>

<div class="col-md-9 col-sm-9 col-xs-12"><input name="username"  required class="form-control"> </div>

</div>
<div class="form-group">

<label class="control-label col-md-3 col-sm-3 col-xs-12">Car Model</label>

<div class="col-md-9 col-sm-9 col-xs-12"><input name="car"  required class="form-control"> </div>

</div>
<div class="form-group">

<label class="control-label col-md-3 col-sm-3 col-xs-12">Licence Plate</label>

<div class="col-md-9 col-sm-9 col-xs-12"><input name="licence"  required class="form-control"> </div>

</div>

<div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12">Photo (*jpg)</label>

<div class="col-md-9 col-sm-9 col-xs-12">
<input name='pic' class="form-control" type='file' /></div></div>
  
<div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12">CAC Documents (*jpg)</label>

<div class="col-md-9 col-sm-9 col-xs-12">
<input name='cac' class="form-control" type='file' /></div></div>
 
<div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12">Utility Bill (*jpg)</label>

<div class="col-md-9 col-sm-9 col-xs-12">
<input name='bill' class="form-control" type='file' /></div></div>
<div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12">ID  Card(*jpg)</label>

<div class="col-md-9 col-sm-9 col-xs-12">
<input name='id' class="form-control" type='file' /></div></div>
  <div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12">Agreement Form (*jpg)</label>

<div class="col-md-9 col-sm-9 col-xs-12">
<input name='agreement' class="form-control" type='file' /></div></div>
<div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12">Agent Registration Form(*jpg)</label>

<div class="col-md-9 col-sm-9 col-xs-12">
<input name='registration' class="form-control" type='file' /></div></div>
 
  <div class="ln_solid"></div>

                      <div class="form-group">

					  

					  <div class="col-md-12 text-center"> 

<button  class="btn btn-danger">Add Driver</button> 

</div>

                        

                     </div>



</form>

	









 

			 

			 

			 </div>

           

            </div>

            

            

           <div class=row>

		   <div class="col-md-12 col-xs-12">

                       

                   

                    <div class="clearfix"></div>

                  </div>

                  <div class="x_content">

                    <br />

                    

		   

		   

          </div>

		  

		  

           

           

          </div>

          <!-- /top tiles -->



         

          <br />



          





         

        </div>

		<div class=clearfix></div>

        <!-- /page content -->



        <!-- footer content -->

        <footer>

          <div class="pull-right">

           TIZA-- 2017

          </div>

          <div class="clearfix"></div>

        </footer>

        <!-- /footer content -->

      </div>

    </div>



    <!-- jQuery -->

    <script src="vendors/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap -->

    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- FastClick -->

    <script src="vendors/fastclick/lib/fastclick.js"></script>

    <!-- NProgress -->

    <script src="vendors/nprogress/nprogress.js"></script>

    <!-- Chart.js -->

    <script src="vendors/Chart.js/dist/Chart.min.js"></script>

    <!-- gauge.js -->

    <script src="vendors/bernii/gauge.js/dist/gauge.min.js"></script>

    <!-- bootstrap-progressbar -->

    <script src="vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>

    <!-- iCheck -->

    <script src="vendors/iCheck/icheck.min.js"></script>

    <!-- Skycons -->

    <script src="vendors/skycons/skycons.js"></script>

    <!-- Flot -->

    <script src="vendors/Flot/jquery.flot.js"></script>

    <script src="vendors/Flot/jquery.flot.pie.js"></script>

    <script src="vendors/Flot/jquery.flot.time.js"></script>

    <script src="vendors/Flot/jquery.flot.stack.js"></script>

    <script src="vendors/Flot/jquery.flot.resize.js"></script>

    <!-- Flot plugins -->

    <script src="js/flot/jquery.flot.orderBars.js"></script>

    <script src="js/flot/date.js"></script>

    <script src="js/flot/jquery.flot.spline.js"></script>

    <script src="js/flot/curvedLines.js"></script>

    <!-- jVectorMap -->

    <script src="js/maps/jquery-jvectormap-2.0.3.min.js"></script>

    <!-- bootstrap-daterangepicker -->

    <script src="js/moment/moment.min.js"></script>

    <script src="js/datepicker/daterangepicker.js"></script>



    <!-- Custom Theme Scripts -->

    <script src="build/js/custom.min.js"></script>
	<script type="text/javascript">
$(document).ready(function(){
    $('#area').on('change',function(){
        var areaID = $(this).val();
		//alert(areaID);
		
		$.ajax({

            url:"ajaxStreet.php",

            

            type: "POST",

            data:'area_id='+areaID,

            success: function(data, textStatus, jqXHR) {

               $("#street").html(data);

                

            },

            error: function(jqXHR, status, error) {

                alert('An Error Occurred.Please Contact The Administrator');

            }

        });

       
	});
	
    });
    
   
</script>



    <!-- Flot -->

    <script>

      $(document).ready(function() {

        var data1 = [

          [gd(2012, 1, 1), 17],

          [gd(2012, 1, 2), 74],

          [gd(2012, 1, 3), 6],

          [gd(2012, 1, 4), 39],

          [gd(2012, 1, 5), 20],

          [gd(2012, 1, 6), 85],

          [gd(2012, 1, 7), 7]

        ];



        var data2 = [

          [gd(2012, 1, 1), 82],

          [gd(2012, 1, 2), 23],

          [gd(2012, 1, 3), 66],

          [gd(2012, 1, 4), 9],

          [gd(2012, 1, 5), 119],

          [gd(2012, 1, 6), 6],

          [gd(2012, 1, 7), 9]

        ];

        $("#canvas_dahs").length && $.plot($("#canvas_dahs"), [

          data1, data2

        ], {

          series: {

            lines: {

              show: false,

              fill: true

            },

            splines: {

              show: true,

              tension: 0.4,

              lineWidth: 1,

              fill: 0.4

            },

            points: {

              radius: 0,

              show: true

            },

            shadowSize: 2

          },

          grid: {

            verticalLines: true,

            hoverable: true,

            clickable: true,

            tickColor: "#d5d5d5",

            borderWidth: 1,

            color: '#fff'

          },

          colors: ["rgba(38, 185, 154, 0.38)", "rgba(3, 88, 106, 0.38)"],

          xaxis: {

            tickColor: "rgba(51, 51, 51, 0.06)",

            mode: "time",

            tickSize: [1, "day"],

            //tickLength: 10,

            axisLabel: "Date",

            axisLabelUseCanvas: true,

            axisLabelFontSizePixels: 12,

            axisLabelFontFamily: 'Verdana, Arial',

            axisLabelPadding: 10

          },

          yaxis: {

            ticks: 8,

            tickColor: "rgba(51, 51, 51, 0.06)",

          },

          tooltip: false

        });



        function gd(year, month, day) {

          return new Date(year, month - 1, day).getTime();

        }

      });

    </script>

    <!-- /Flot -->



    <!-- jVectorMap -->

    <script src="js/maps/jquery-jvectormap-world-mill-en.js"></script>

    <script src="js/maps/jquery-jvectormap-us-aea-en.js"></script>

    <script src="js/maps/gdp-data.js"></script>

    <script>

      $(document).ready(function(){

        $('#world-map-gdp').vectorMap({

          map: 'world_mill_en',

          backgroundColor: 'transparent',

          zoomOnScroll: false,

          series: {

            regions: [{

              values: gdpData,

              scale: ['#E6F2F0', '#149B7E'],

              normalizeFunction: 'polynomial'

            }]

          },

          onRegionTipShow: function(e, el, code) {

            el.html(el.html() + ' (GDP - ' + gdpData[code] + ')');

          }

        });

      });

    </script>

    <!-- /jVectorMap -->



    <!-- Skycons -->

    <script>

      $(document).ready(function() {

        var icons = new Skycons({

            "color": "#73879C"

          }),

          list = [

            "clear-day", "clear-night", "partly-cloudy-day",

            "partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",

            "fog"

          ],

          i;



        for (i = list.length; i--;)

          icons.set(list[i], list[i]);



        icons.play();

      });

    </script>

    <!-- /Skycons -->



    <!-- Doughnut Chart -->

    <script>

      $(document).ready(function(){

        var options = {

          legend: false,

          responsive: false

        };



        new Chart(document.getElementById("canvas1"), {

          type: 'doughnut',

          tooltipFillColor: "rgba(51, 51, 51, 0.55)",

          data: {

            labels: [

              "Symbian",

              "Blackberry",

              "Other",

              "Android",

              "IOS"

            ],

            datasets: [{

              data: [15, 20, 30, 10, 30],

              backgroundColor: [

                "#BDC3C7",

                "#9B59B6",

                "#E74C3C",

                "#26B99A",

                "#3498DB"

              ],

              hoverBackgroundColor: [

                "#CFD4D8",

                "#B370CF",

                "#E95E4F",

                "#36CAAB",

                "#49A9EA"

              ]

            }]

          },

          options: options

        });

      });

    </script>

    <!-- /Doughnut Chart -->

    

    <!-- bootstrap-daterangepicker -->

    <script>

      $(document).ready(function() {



        var cb = function(start, end, label) {

          console.log(start.toISOString(), end.toISOString(), label);

          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

        };



        var optionSet1 = {

          startDate: moment().subtract(29, 'days'),

          endDate: moment(),

          minDate: '01/01/2012',

          maxDate: '12/31/2015',

          dateLimit: {

            days: 60

          },

          showDropdowns: true,

          showWeekNumbers: true,

          timePicker: false,

          timePickerIncrement: 1,

          timePicker12Hour: true,

          ranges: {

            'Today': [moment(), moment()],

            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],

            'Last 7 Days': [moment().subtract(6, 'days'), moment()],

            'Last 30 Days': [moment().subtract(29, 'days'), moment()],

            'This Month': [moment().startOf('month'), moment().endOf('month')],

            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]

          },

          opens: 'left',

          buttonClasses: ['btn btn-default'],

          applyClass: 'btn-small btn-primary',

          cancelClass: 'btn-small',

          format: 'MM/DD/YYYY',

          separator: ' to ',

          locale: {

            applyLabel: 'Submit',

            cancelLabel: 'Clear',

            fromLabel: 'From',

            toLabel: 'To',

            customRangeLabel: 'Custom',

            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],

            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],

            firstDay: 1

          }

        };

        $('#reportrange span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));

        $('#reportrange').daterangepicker(optionSet1, cb);

        $('#reportrange').on('show.daterangepicker', function() {

          console.log("show event fired");

        });

        $('#reportrange').on('hide.daterangepicker', function() {

          console.log("hide event fired");

        });

        $('#reportrange').on('apply.daterangepicker', function(ev, picker) {

          console.log("apply event fired, start/end dates are " + picker.startDate.format('MMMM D, YYYY') + " to " + picker.endDate.format('MMMM D, YYYY'));

        });

        $('#reportrange').on('cancel.daterangepicker', function(ev, picker) {

          console.log("cancel event fired");

        });

        $('#options1').click(function() {

          $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);

        });

        $('#options2').click(function() {

          $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);

        });

        $('#destroy').click(function() {

          $('#reportrange').data('daterangepicker').remove();

        });

      });

    </script>

    <!-- /bootstrap-daterangepicker -->



    <!-- gauge.js -->

    <script>

      var opts = {

          lines: 12,

          angle: 0,

          lineWidth: 0.4,

          pointer: {

              length: 0.75,

              strokeWidth: 0.042,

              color: '#1D212A'

          },

          limitMax: 'false',

          colorStart: '#1ABC9C',

          colorStop: '#1ABC9C',

          strokeColor: '#F0F3F3',

          generateGradient: true

      };

      var target = document.getElementById('foo'),

          gauge = new Gauge(target).setOptions(opts);



      gauge.maxValue = 6000;

      gauge.animationSpeed = 32;

      gauge.set(3200);

      gauge.setTextField(document.getElementById("gauge-text"));

    </script>

    <!-- /gauge.js -->

  </body>

</html>