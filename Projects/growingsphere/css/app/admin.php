<?php
include_once('../../model/model.php');
header("Content-type: text/css");

global $theme_color, $theme_color_dark, $theme_color_2, $topbar_color, $sidebar_color;

$success = "#449d44";
$info = "#33b5e5";
$danger = "#ff5b5b";
$primary = "#5d93e4";
$yellow = "#FFC107";
?>
.app_header{
	background: <?= $topbar_color ?>;
	height: 60px;
	position: fixed;
	width: 100%;
	top:0;
	left: 0;
	z-index: 6;
	border-bottom: 2px solid #eaeaea;
}
.app_header .logo_wrap{
	float: left;
	height: 100%;
	width: 240px;
}
.app_header .logo_wrap img{
	height:100%;
	max-width: 100%;
}
.app_header .small_logo_wrap{
	float: left;
	height: 100%;
	width: 80px;
	padding-left: 5px;
	padding-right: 5px;
}
.app_header .small_logo_wrap img{
	height:100%;
	max-width: 100%;
}

.app_header .ico_wrap{
	float: right;
}
.app_header .ico_wrap_mobile{
	margin-top: 15px;
	margin-right: 20px;
}
.app_header .ico_wrap_mobile ul i{
	float: left;
}
.app_header .ico_wrap_mobile ul .btn{
	width: 100%;
	text-align: left;
	border-radius: 0;
	margin-bottom:-1px; 
}
.app_header .ico_wrap_mobile ul .btn pre {
    padding: 0 0 0 8px;
    margin: 0;
    background: transparent;
    border: 0;
}
.app_header .ico_wrap_mobile .dropdown-menu{
	padding: 0;
	border:0;
}
.app_header .ico_wrap ul li{
	display: inline;
    margin: 0 5px;
}
.app_header .ico_wrap ul li:last-child {
    margin-right: 15px !important;
}
.app_header .ico_wrap ul li.financial_yr a {
    width: auto !important;
    line-height: 15px;
}
@media screen and (max-width:768px){
	.app_header .ico_wrap{
		padding-right: 5px;
	}
	.app_btn_out{
		border-color:#fff;
	}
	.app_btn_out:hover{
		border-color:#fff;
	}
}
.app_header .ico_wrap i{
	color: <?= $theme_color ?>;
}
.app_header .ico_wrap .btn{
    border-radius: 4px;
	border:1px solid <?= $theme_color ?>;
    width: 35px;
    height: 35px;
    text-align: center;
    padding: 10px;
}

ul.customer_topar_icons {
    padding: 12px 0;
}
ul.customer_topar_icons li a{
    line-height: 15px;
}

.sidebar_wrap{
	position: fixed;
	width: 240px;
	height: 100%;
	padding-top: 60px;
	top:0;
	left: 0;
	z-index: 5;
	background: <?= $theme_color ?>;
	transition:0.4s;
}
.sidebar_wrap .mCSB_inside>.mCSB_container{
	margin-right: 0;
	z-index: 1;
}
.sidebar_wrap .mCSB_scrollTools{
	width: 5px;
}
.sidebar_wrap .mCSB_scrollTools {
    width: 8px;
}

.app_content_wrap{
    float: left;
    width: 100%;
    padding-top: 60px;
    padding-left: 240px;
    padding-right: 0;
    transition: 0.4s;
    background: #fff;
    height: 100vh;
    overflow-y: scroll;
}
.app_content_wrap::-webkit-scrollbar {
    height: 1px;
    width: 2px;
}
.app_content_wrap::-webkit-scrollbar-track {
      background-color: #b3b3b3;
} /* the new scrollbar will have a flat appearance with the set background color */
 
.app_content_wrap::-webkit-scrollbar-thumb {
      background-color: #ececec; 
} /* this will style the thumb, ignoring the track */
 
.app_content_wrap::-webkit-scrollbar-button {
      background-color: #000;
      width:5px;
      height: 0px;
} /* optionally, you can style the top and the bottom buttons (left and right for horizontal bars) */
 
.app_content_wrap::-webkit-scrollbar-corner {
      background-color: black;
      border-radius:25px;
}
@media screen and (max-width:992px){
	.app_content_wrap{
		padding-left: 10px;
	}
}

.sidebar_toggle_btn{
	margin-top: 15px;
    margin-left: 15px;
}
@media screen and (max-width:768px){
	.sidebar_toggle_btn{
	    margin-left: 10px;
	}
}

/****After Toggle Css start****/
.sidebar_wrap.toggle{
	left: -250px;
	transition:0.4s;
}
.app_content_wrap.toggle{
	padding-left: 10px;
	transition:0.4s;
}
/****After Toggle Css end****/


/**********Reports Css Start**********/
.app_dual_button input[type="radio"]{
	display: none;
}
.app_dual_button{
    padding: 6px 10px;
    border: 1px solid <?= $theme_color ?>;
    margin-right: -5px;
    background: #fff;
    margin-bottom: 0;
    cursor: pointer;
    font-weight: 300;
    font-size: 16px;
}
.app_dual_button label{
	margin:0;
}
.app_dual_button.active{
	background: <?= $theme_color ?>;
	color: #fff;
}
.app_dual_button:first-child{
	border-top-left-radius:20px;
	border-bottom-left-radius:20px;
}
.app_dual_button:last-child{
	border-top-right-radius:20px;
	border-bottom-right-radius:20px;
}
label input.labelauty:checked + label{
    background-color: rgba(0, 0, 0, 0.42);
    color: #ffffff;
}
input.labelauty + label, input.labelauty:checked + label, input.labelauty:checked:not([disabled]) + label:hover{
    background-color:<?= $theme_color ?>;
}
.app_reports_panel_toggle{
	float: right;
}
.app_reports_panel_toggle i{
	color: <?= $theme_color ?>;
	font-size: 18px;
	cursor: pointer;
	padding-top:6px;
}
.report_ico_wrap{
	float: right;
}
.report_ico_wrap i{
	cursor: pointer;
    font-size: 16px;
    margin-right: 7px;
    background: #ddd;
    padding: 5px;
}

.report_ico_wrap .pdf{ color: red; }
.report_ico_wrap .excel{ color: green; }
#span_report_name_title{ font-size: 21px; }
/**********Reports Css end**********/

/**********Bus SSeating Arrangment Start**********/
#bus_seat_arrangment_content .mCSB_inside>.mCSB_container{
	margin-right:0;
}
#bus_seat_arrangment_content .mCSB_scrollTools{
	width:5px;
}
#bus_seat_arrangment_content img{
	width: 100%;
	height:auto;
}
#div_group_tour_area .col-md-3, #div_package_tour_area .col-md-3{
	padding-top:2px;
	padding-bottom:2px;
}
/**********Bus Seating Arrangment End**********/


/**********Group Tour Vendor start**********/
.group_tour_vendor_wrap{
	display: none;
}
.group_tour_vendor_wrap.active{
	display: block;
}
.package_tour_vendor_wrap{
	display: none;
}
.package_tour_vendor_wrap.active{
	display: block;
}
/**********Group Tour Vendor end**********/

.modal-content .owl-carousel.owl-theme {
    max-width: 900px;
    margin: 0 auto;
}
.modal-content .owl-carousel.owl-theme .item img {
    width: 100%;
    max-height: 400px;
}
.owl-prev, .owl-next {
    background: <?= $theme_color ?> !important;
    color: #fff !important;
    opacity: 0.6 !important;
    border-radius: 5px !important;
    font-size: 21px !important;
    padding: 0px 14px !important;
    border: 1px solid #a09d9d;
    line-height: 26px !important;
    position: absolute;
    top: 50%;
}
.owl-prev {
    left: -28px;
}
.owl-next {
    right: -28px !important;
}

/* view booking information modal css */




.profile_box_modal
{
  padding:0px!important;
}
.profile_box_modal .profile_background
{
    background-color:#fff !important;
    margin-bottom: 0px !important;
    padding: 25px !important;
    border: 0;
}
.profile_box_modal ul.nav .close{
    margin-top: 5px;
    margin-right: 5px;
}
.profile_box_modal ul .active a:hover,
.profile_box_modal ul .active a:focus
{
    border-bottom:1px solid #36aae7;
}
.profile_box
{
    border: 2px solid #fff;
    background-color: #FFFFFF;
}
.profile_box h3
{
    margin: 0px;
    margin-bottom: 15px;
    font-size: 14px;
    color: #22262E;
    font-weight: 500;
    text-transform: uppercase;
    font-family: 'Roboto', sans-serif !important;
}
.profile_box label {
    font-weight: 500;
    width: 130px;
    color: #22262e;
}
#display_modal_tour .profile_box label, #dmc_view_modal .profile_box label{
    width: 150px;
}
#transport_view_modal .profile_box label{
   width: 175px;
}
#payment_information .profile_box label, #package_display_modal .profile_box label{
   width: auto;
}
.tab-content #basic_information .profile_box label{
   min-width: auto;
   width: auto;
   float: left;
   height: 20px;	
   line-height: 15px;
}
.tab-content #basic_information .profile_box span .cost_arrow {
    float: left;
    margin-top: 2px;
    margin-right: 2px;
}
.profile_box span i
{
    font-size: 17px;
    width: 22px;
}
.profile_box span .cost_arrow
{
    width: 10px;
    font-size: 13px;
}
.profile_box span
{
    color: #22262e;
    font-size: 13px;
    margin-bottom: 10px;
    font-family: 'Roboto', sans-serif;
}
.profile_box span em
{
    font-weight: 500;
    text-decoration: underline;
    font-style: normal;
}
.profile_box label em
{
	text-decoration: none !important;
    font-style: normal;
    float: right;
    margin-right: 6px;
    font-weight: 500;
}
.profile_box span.main_block a i {
    font-size: 18px;
    color: <?= $theme_color ?>;
}
.profile_box_modal table
{
   margin-bottom:0px !important;
   width:100%;
}

.profile_box_modal table td
{
	padding:0px !important;
    border-bottom:0px !important;
    font-size: 12.5px;
    padding: 10px !important;
    margin-bottom:10px !important;
}
.profile_box_modal table tbody tr
{
    border-bottom:1px solid #ddd;
}
.profile_box_modal table tbody tr:last-child {
    border-bottom:0px !important;
}
.due_date_block
{
    background-color: <?php echo $danger; ?>;
    color: #fff;
    line-height: 35px;
    padding: 0 15px;
    margin-bottom: 15px;
}

#div_app_setting_content label
{
	color: #777777;
    font-weight: 500;
    margin-bottom: 0;
    font-size: 13px;
}
.company_class
{
	width: 25% !important;
    display: inline-block !important;
    float: left !important;
    padding: 0 15px;
    margin-bottom: 10px;
}
.div_acc_report
{
	min-height: 330px;
	border: 1px solid #ddd;
    box-shadow: 0px 1px 4px #ddd;
    background-color: #FFFFFF;
}
.div_acc_heading
{    
	padding: 10px;
    background-color: #96d1b6;
    font-weight: 500;
    font-size: 16px;
    border-bottom: 1px solid #ddd;
    color: #30825c;
}
.row.heighligh-row {
    background: #d5f2e5;
    color: #007575;
    font-weight: 500;
    padding: 8px 0;
    margin-top: 8px;
}



.logged_user {
    display: inline-block;
    color: <?= $theme_color ?>;
    padding: 10px 0px;
    line-height: 40px;
    cursor: pointer;
}
.logged_user span {
    display: inline-block;
}
.logged_user span.logged_user_id {
    margin-bottom: -15px;
}   
.logged_user span img {
    width: 35px;
    height: 35px;
    margin: 0 auto;
    border-radius: 5px;
    border: 1px solid <?= $theme_color ?>;
}

li.logged_user_body {
    position: relative;
}
li.logged_user_body .profile_pic_block {
    position: absolute;
    width: 230px;
    background: #009898;
    color: #fff;
    padding: 3px;
    left: -25px;
    top: 38px;
    display: none;
    transition: 0.5s;
}
.profile_pic_block:before {
    content: '';
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 0 5px 10px 5px;
    border-color: transparent transparent <?= $theme_color ?> transparent;
    position: absolute;
    top: -10px;
    left: 40px;
}
.profile_pic_block .upload {
    position: absolute;
    bottom: 0px;
    width: 100%;
    text-align: center;
    background: rgba(0, 0, 0, 0.73);
    left: 0;
    padding: 5px;
}
.profile_pic_block .upload i {
    margin-right: 5px;
    color: #fff !important;
}
.profile_pic_block .close_profile_pic {
    position: absolute;
    top: 0px;
    right: 0px;
    font-size: 18px;
    color: #fff !important;
    cursor: pointer;
    background: rgba(0, 0, 0, 0.2);
    padding: 4px;
}
.profile_pic_block .close_profile_pic i {
    color: #fff !important;
}
.profile_pic_block_display{
display : inline-block !important; 
}
.profile_pic_block img {
    min-width: 100%;
}



@media screen and (max-width: 992px){
.profile_box h3 {
    font-size: 15px;
}
.company_class{
    width: 50% !important;
}    
}


@media screen and (max-width: 767px){
.app_header .ico_wrap_mobile ul .btn {
    float: left;
    padding: 10px;
}
.profile_box_modal .profile_background {
    padding: 5px 0 0 0 !important;
}
.company_class{
    width: 100% !important;
}   
}
