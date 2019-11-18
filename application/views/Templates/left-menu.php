<?php  
     $menu = $this->session->userdata('admin');
      // $menu = $this->session->set_userdata('logged_in');
// echo  $menu;
//exit;
?>

<div class="col-sm-2 col-lg-2 admin-menu">
            <div class="sidebar-nav">
                <div class="nav-canvas">
                    <div class="nav-sm nav nav-stacked">

                    </div>
                    <ul class="nav nav-pills nav-stacked main-menu">
                        <li class="nav-header">Main Menu</li>
                        <li><a class="ajax-link" href="<?php echo base_url(); ?>dashboard"><i class="glyphicon glyphicon-dashboard"></i><span> Dashboard</span></a> </li>
                        
                       <!-- <div class="admin-menu">-->
                   




 <li class="accordion">



                            <li class="accordion">

                            <a href="#"><i class="glyphicon glyphicon-book"></i><span> Bookings</span></a> 
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?php echo base_url();?>booking/view_all">All Bookings</a></li>
                                <li><a href="<?php echo base_url(); ?>booking/view_completed">Completed</a></li>
                                <li><a href="<?php echo base_url(); ?>booking/view_onprocess">On Processing</a></li>
                                <li><a href="<?php echo base_url(); ?>booking/view_cancelled">Cancelled</a></li>
                            </ul>
                        </li> 



                      <!--    <li class="accordion">

                            <a href="#"><i class="glyphicon glyphicon-user"></i><span> Bookings</span></a> 
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?php echo base_url();?>booking">All Booking</a></li>
                                <li><a href="<?php echo base_url(); ?>driver/">View</a></li>
                            </ul>
                        </li>
 -->




<!--                         <li><a class="ajax-link" href="<?php echo base_url(); ?>booking"><i class="glyphicon glyphicon-book"></i><span> Bookings</span></a> </li>
 -->                        <li><a class="ajax-link" href="<?php echo base_url(); ?>Customers/view_customer"><i class="glyphicon glyphicon-user"></i><span> Customers</span></a> </li>

                        <!--##Car Menu##-->


                        <li class="accordion">

                            <a href="#"><i class="glyphicon glyphicon-user"></i><span> Driver</span></a> 
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?php echo base_url();?>driver/create">Create</a></li>
                                <li><a href="<?php echo base_url(); ?>driver">View</a></li>
                            </ul>
                        </li> 


                        <li class="accordion">

                            <a href="#"><i class="glyphicon glyphicon-briefcase"></i><span> Car Type</span></a> 
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?php echo base_url();?>car/create">Create</a></li>
                                <li><a href="<?php echo base_url(); ?>car">View</a></li>
                            </ul>
                        </li> 


                   

<!-- 
                         <li class="accordion">

                            <a href="#"><i class="glyphicon glyphicon-briefcase"></i><span> Car Type</span></a> 
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?php echo base_url();?>car_type/create_cars">Create</a></li>
                                <li><a href="<?php echo base_url(); ?>car_type/view_cars">View</a></li>
                            </ul>
                        </li>  -->


                        <li class="accordion">

                            <a href="#"><i class="glyphicon glyphicon-briefcase"></i><span> Region</span></a> 
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?php echo base_url();?>pattern/create">Create</a></li>
                                <li><a href="<?php echo base_url(); ?>pattern">View</a></li>
                            </ul>
                        </li> 



                       <!--      <li class="accordion">

                            <a href="#"><i class="glyphicon glyphicon-briefcase"></i><span> Pattern</span></a> 
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?php echo base_url();?>pattern/create_rate">Create </a></li>
                                <li><a href="<?php echo base_url(); ?>pattern/view_rate">View</a></li>
                                
                                
                            </ul>
                        </li>  -->



                         <!-- <li class="accordion">

                            <a href="#"><i class="glyphicon glyphicon-barcode"></i><span> Promocode</span></a> 
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?php echo base_url();?>promocode/create_promocode">Create</a></li>
                                <li><a href="<?php echo base_url(); ?>promocode/view_promocode">View</a></li>
                            </ul>
                        </li>  -->


                         <li class="accordion">

                            <a href="#"><i class="glyphicon glyphicon-barcode"></i><span> Promocode</span></a> 
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?php echo base_url();?>promocode/create">Create</a></li>
                                <li><a href="<?php echo base_url(); ?>promocode">View</a></li>
                            </ul>
                        </li>
             <li><a class="ajax-link" href="<?php echo base_url(); ?>document"><i class="glyphicon glyphicon-user"></i><span> Document</span></a> </li>



                       <!--  <li class="accordion">

                            <a href="#"><i class="glyphicon glyphicon-user"></i><span>Driver Document</span></a> 
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?php echo base_url();?>document/create">Create</a></li>
                                <li><a href="<?php echo base_url(); ?>document">View</a></li>
                                <li><a href="<?php echo base_url(); ?>document/request">Request</a></li>
                            </ul>
                        </li> 
 -->



                       <!--  <li class="accordion">

                            <a href="#"><i class="glyphicon glyphicon-file"></i><span> Driver Document</span></a> 
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?php echo base_url();?>document/view_verified">Verified</a></li>
                                <li><a href="<?php echo base_url(); ?>document/view_request">Request</a></li>
                            </ul>
                        </li>  -->




                        <li><a class="ajax-link" href="<?php echo base_url(); ?>feedback"><i class="glyphicon glyphicon-briefcase"></i><span> Feedback</span></a> </li>



<!-- 
                             <li class="accordion">
                          <a href="#"><i class="glyphicon glyphicon-briefcase"></i><span> Help</span></a>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?php echo base_url(); ?>help/create_help">Add Help</a></li>
                                <li><a href="<?php echo base_url(); ?>help/view_help">View Help</a></li>
                            </ul>
                        </li> -->



                        <li class="accordion">

                            <a href="#"><i class="glyphicon glyphicon-user"></i><span> Help</span></a> 
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?php echo base_url();?>help/create">Create</a></li>
                                <li><a href="<?php echo base_url(); ?>help">View</a></li>
                            </ul>
                        </li>

                        <!--##Pattern Menu##-->

<!--                      <li><a class="ajax-link" href="<?php echo base_url(); ?>Request/view_request"><i class="glyphicon glyphicon-user"></i><span> Requests</span></a> </li>
 -->                    <li><a class="ajax-link" href="<?php echo base_url(); ?>request"><i class="glyphicon glyphicon-briefcase"></i><span> Request</span></a> </li>
    
                        <!--##Driver Menu##-->
                      <!--   <li class="accordion">

                            <a href="#"><i class="glyphicon glyphicon-stats"></i><span> Driver</span></a> 
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?php echo base_url();?>driver/create_driver">Create Driver</a></li>
                                <li><a href="<?php echo base_url(); ?>driver/view_driver">View Driver</a></li>
                            </ul>
                        </li>  -->


                        <!--##Promocode Menu##-->

                       
<!--                         <li><a class="ajax-link" href="<?php echo base_url(); ?>Customers/view_customer"><i class="glyphicon glyphicon-user"></i><span> Customers</span></a> </li>
 -->
<!--                         <li><a class="ajax-link" href="<?php echo base_url(); ?>booking"><i class="glyphicon glyphicon-book"></i><span> Bookings</span></a> </li>
 -->                        <!-- <li><a class="ajax-link" href="<?php echo base_url(); ?>Customer_issue/view_customer"><i class="glyphicon glyphicon-user"></i><span> Customer Issue</span></a> </li> -->

<!--                         <li><a class="ajax-link" href="<?php echo base_url(); ?>Document/view_document"><i class="glyphicon glyphicon-user"></i><span> Driver Document</span></a> </li>
 -->


                       <!--   <li class="accordion">

                            <a href="#"><i class="glyphicon glyphicon-briefcase"></i><span> Help</span></a> 
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?php echo base_url();?>help/create_help">Add Help</a></li>
                                <li><a href="<?php echo base_url(); ?>help/view_help">View Help</a></li>
                            </ul>
                        </li>  -->

<!--


                        <li class="accordion">
                          <a href="#"><i class="glyphicon glyphicon-briefcase"></i><span> Shops</span></a>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?php echo base_url(); ?>shop/add_shop">Add Shop</a></li>
                                <li><a href="<?php echo base_url(); ?>shop/view_shops">View Shop</a></li>
         	                       <li><a href="<?php echo base_url(); ?>shop/assign_service">Assign service </a></li>
                            </ul>
                        </li>
                       <?php
                       if( $menu==1  )
                        {
                       ?>
                        
                        <li>
                            <a href="<?php echo base_url(); ?>service/"><i class="glyphicon glyphicon-list-alt"></i><span> Services</span></a>
                        </li>
                        
                       <?php
						} 
						?>
						
                        <li>
                            <a href="<?php echo base_url(); ?>shop/gallery"><i class="glyphicon glyphicon-picture"></i><span> Gallery</span></a>
                        </li>
                        
                         <?php 
						if( $menu==1 )
                        {
						?>
                        <li class="accordion">
                            <a href="#"><i class="glyphicon glyphicon-certificate"></i><span> Customers</span></a>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="<?php echo base_url(); ?>customer/add_customer">Add Customer</a></li>
                                <li><a href="<?php echo base_url(); ?>customer/view_customer">View Customers</a></li>
                            </ul>
                        </li>
                        
                      
                        <li><a class="ajax-link" href="<?php echo base_url(); ?>user/view_user"><i class="glyphicon glyphicon-user"></i><span> Users</span></a> </li>
                        <?php
						}
						?>
                        
                        <li><a class="ajax-link" href="<?php echo base_url(); ?>booking"><i class="glyphicon glyphicon-book"></i><span> Bookings</span></a> </li>
                        <li><a class="ajax-link" href="<?php echo base_url(); ?>review"><i class="glyphicon glyphicon-list"></i><span> Reviews</span></a> </li>
                       <?php 
						if( $menu==1  )
                        {
						?>
                        <li class="accordion">
                        	<a href="#"><i class="glyphicon glyphicon-fire"></i><span> Trending</span></a> 
                        	<ul class="nav nav-pills nav-stacked">
                                <li><a href="<?php echo base_url(); ?>trend/add_trend">Add Trend</a></li>
                                <li><a href="<?php echo base_url(); ?>trend/view_trend">View Trends</a></li>
                            </ul>
                        </li>
                         
                         <li class="accordion">
                        	<a href="#"><i class="glyphicon glyphicon-cog"></i><span> Settings</span></a> 
                        	<ul class="nav nav-pills nav-stacked">
                                <li><a href="<?php echo base_url();?>settings/home_slider">Home Slider</a></li>
                                <li><a href="<?php echo base_url(); ?>settings/cycle_slider">Cycle Slider</a></li>
                                <li><a href="<?php echo base_url(); ?>settings/whats_new">Whats New</a></li>
                            </ul>
                        </li>
                         
                        <li>
                        	<a href="<?php echo base_url();?>ad"><i class="glyphicon glyphicon-bell"></i><span> Ads</span></a> 
                        </li>

                        
                        <li class="accordion">

                         <li class="accordion">

                        	<a href="#"><i class="glyphicon glyphicon-stats"></i><span> Testimonials</span></a> 
                        	<ul class="nav nav-pills nav-stacked">
                                <li><a href="<?php echo base_url();?>testimonials/add_testimonials">Add Testimonials</a></li>
                                <li><a href="<?php echo base_url(); ?>testimonials/view_testimonials">View Testimonials</a></li>
                            </ul>
                        </li>  



   <?php
 }
?>                       
                        <li>
                        	<a href="<?php echo base_url();?>offers"><i class="glyphicon glyphicon-tags"></i><span> Offers</span></a> 
                        	<ul class="nav nav-pills nav-stacked">
                            </ul>
                        </li> 
                          <?php 
                        if( $menu==1  )
                        {
                        ?>
                        -->
<!--                          <li><a class="ajax-link" href="<?php echo base_url(); ?>Settings"><i class="glyphicon glyphicon-cog"></i><span> Settings </span></a> </li>
 -->
                         <li><a class="ajax-link" href="<?php echo base_url(); ?>settings"><i class="glyphicon glyphicon-cog"></i><span> Settings</span></a> </li>

                         <li><a class="ajax-link" href="<?php echo base_url(); ?>profile"><i class="glyphicon glyphicon-user"></i><span> Profile</span></a> </li>
    
<!-- <li><a class="ajax-link" href="<?php echo base_url(); ?>Websiteinfo"><i class="glyphicon glyphicon-cog"></i><span> Website Info</span></a> </li>
 -->


                        <?php
 }
?>  

                      <!--</div>-->
                      
 
 
                    </ul>
                     
                  
                    
                </div>
            </div>
        </div>
        






