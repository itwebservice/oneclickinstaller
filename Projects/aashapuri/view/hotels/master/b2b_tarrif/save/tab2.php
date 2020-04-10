<form id="frm_tab3">
<div class="app_panel"> 
<!--=======Header panel======-->
    <div class="app_panel_head mg_bt_20">
      <div class="container">
          <h2 class="pull-left"></h2>
          <div class="pull-right header_btn">
            <button>
                <a>
                    <i class="fa fa-arrow-right"></i>
                </a>
            </button>
          </div>
          <div class="pull-right header_btn">
            <button data-target="#myModalHint" data-toggle="modal">
              <a title="Help">
                <i class="fa fa-question" aria-hidden="true"></i>
              </a>
            </button>
          </div>
      </div>
    </div> 
<!--=======Header panel end======-->
        <div class="container">
        <h5 class="booking-section-heading main_block text-center">Black-Dated Rates</h5>
        <div class="row mg_bt_10">
          <div class="col-md-12 text-right text_center_xs">
            <div class="col-md-6 text-left">
                <input type="button" class="btn btn-sm btnType" onclick="display_format_modal();" value="View CSV">
                <div class="div-upload  mg_bt_20" id="div_upload_button2">
                  <div id="b2btariff_csv_upload1" class="upload-button1"><span>CSV</span></div>
                  <span id="cust_status" ></span>
                  <ul id="files" ></ul>
                  <input type="hidden" id="hotel_tarrif_upload1" name="hotel_tarrif_upload1">
                </div>
              </div>
            <button type="button" class="btn btn-info btn-sm ico_left" onClick="addRow('table_hotel_tarrif3','3')"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
            <button type="button" class="btn btn-danger btn-sm ico_left" onClick="deleteRow('table_hotel_tarrif3','3')"><i class="fa fa-times"></i>&nbsp;&nbsp;Delete</button>
          </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="table-responsive">
            <table id="table_hotel_tarrif3" name="table_hotel_tarrif" class="table table-bordered no-marg pd_bt_51" style="min-width:1500px">
              <tr>
                  <td><input class="css-checkbox" id="chk_ticket2" type="checkbox"><label class="css-label" for="chk_ticket"> </label></td>
              <?php include 'hotel_tarrif_list.php';?>
            </table>
          </div>
        </div>
      </div>
      <div class="row text-center mg_tp_20 mg_bt_150">
        <div class="col-xs-12">
          <button class="btn btn-info btn-sm ico_left" type="button" onclick="switch_to_tab2()"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp Previous</button>
          &nbsp;&nbsp;
          <button class="btn btn-info btn-sm ico_right">Next&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
        </div>
      </div>
</form>
<?= end_panel() ?>

<script>
$('#to_date,#from_date').datetimepicker({ timepicker:false, format:'d-m-Y' });

hotel_tarrif_save2();
function hotel_tarrif_save2(){
    var type="hotel_tariff_list";
	var btnUpload=$('#b2btariff_csv_upload1');
    var status=$('#cust_status');
    new AjaxUpload(btnUpload, {
      action: '../upload_tariff_csv.php',
      name: 'uploadfile',
      onSubmit: function(file, ext){

         if(!confirm('Do you want to import this file?')){
            return false;
         }
         if (! (ext && /^(csv)$/.test(ext))){ 
          // extension is not allowed
          status.text('Only excel sheet files are allowed');
          //return false;
        }
        status.text('Uploading...');
      },
      onComplete: function(file, response){
        //On completion clear the status
        status.text('');
        //Add uploaded file to list
        if(response==="error"){          
          alert("File is not uploaded.");           
        } else{
          document.getElementById("hotel_tarrif_upload1").value = response;
          status.text('Uploading...');
          hotel_tarrif2();
          status.text('');
          
        }
      }
    });
}

function hotel_tarrif2(){
    var cust_csv_dir = document.getElementById("hotel_tarrif_upload1").value;
	var base_url = $('#base_url').val();
    $.ajax({
        type:'post',
        url: base_url+'controller/hotel/b2btariff_csv_save.php',
        data:{cust_csv_dir : cust_csv_dir },
        success:function(result){

            var table = document.getElementById("table_hotel_tarrif3");
            if(table.rows.length == 1){
              for(var k=1; k<table.rows.length; k++){
                  document.getElementById("table_hotel_tarrif3").deleteRow(k);
              }
            }else{
              while(table.rows.length > 1){
                  document.getElementById("table_hotel_tarrif3").deleteRow(k);
                  table.rows.length--;
              }
            }
            
            var pass_arr = JSON.parse(result);
            for(var i=0; i<pass_arr.length; i++){
				    var row = table.rows[i];
            row.cells[2].childNodes[0].value = pass_arr[i]['room_cat'];
            row.cells[3].childNodes[0].value = pass_arr[i]['max_occ'];
            row.cells[4].childNodes[0].value = pass_arr[i]['from_date'];
            row.cells[5].childNodes[0].value = pass_arr[i]['to_date'];
            row.cells[7].childNodes[0].value = pass_arr[i]['double_bed'];
            row.cells[9].childNodes[0].value = pass_arr[i]['cwbed'];
            row.cells[10].childNodes[0].value = pass_arr[i]['cwobed'];			
            row.cells[13].childNodes[0].value = pass_arr[i]['with_bed'];
            row.cells[18].childNodes[0].value = pass_arr[i]['markup_per'];
            row.cells[19].childNodes[0].value = pass_arr[i]['flat_markup'];				
            row.cells[20].childNodes[0].value = pass_arr[i]['meal_plan'];

                if(i!=pass_arr.length-1){
                    if(table.rows[i+1]==undefined){
                        addRow('table_hotel_tarrif3','3');
                    }
                }
            $(row.cells[2].childNodes[0]).trigger('change');
            $(row.cells[20].childNodes[0]).trigger('change');
            }
        }
    });
}
function switch_to_tab2(){ 
	$('#tab2_head').removeClass('active');
	$('#tab1_head').addClass('active');
	$('.bk_tab').removeClass('active');
	$('#tab1').addClass('active');
	$('html, body').animate({scrollTop: $('.bk_tab_head').offset().top}, 200);
 }

$('#frm_tab3').validate({
	rules:{

	},
	submitHandler:function(form){
		var base_url = $('#base_url').val();

		var table = document.getElementById("table_hotel_tarrif3");
		var rowCount = table.rows.length;

    for(var i=0; i<rowCount; i++){
      var row = table.rows[i];           

      if(row.cells[0].childNodes[0].checked){
        var room_cat = row.cells[2].childNodes[0].value;
			  var max_ooc = row.cells[3].childNodes[0].value;
			  var from_date = row.cells[4].childNodes[0].value;
			  var to_date = row.cells[5].childNodes[0].value;
        if(room_cat==''){
          error_msg_alert('Select Room Category in Row-'+(i+1));
          return false;
        }
			  if(max_ooc==''){
				  error_msg_alert('Enter Max occupancy in Row-'+(i+1));
				  return false;
			  }
        if(from_date==''){
          error_msg_alert('Select Valid From Date in Row-'+(i+1));
          return false;
        }
        if(to_date==''){
          error_msg_alert('Select Valid To Date in Row-'+(i+1));
          return false;
        }
      }
    }

	  $('#tab2_head').addClass('done');
		$('#tab3_head').addClass('active');
		$('.bk_tab').removeClass('active');
		$('#tab3').addClass('active');
		$('html, body').animate({scrollTop: $('.bk_tab_head').offset().top}, 200);
	}
});
</script>