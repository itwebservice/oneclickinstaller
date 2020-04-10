<?php include "../../../model/model.php";?>
<div class="row text-right mg_tp_20"> <div class="col-md-12">
  <button class="btn btn-info btn-sm ico_left" onclick="generic_city_save_modal('master')" id="btn_city_save_modal"><i class="fa fa-plus"></i>&nbsp;&nbsp;City</button>
</div> </div>
<hr/>
<div class="col-md-3 col-md-offset-9">
  <div class="col-md-9"><input type="text" name="search" id="search" placeholder="Search...." class="form-control"></div>
  <div class="col-md-1"><button class="btn btn-sm btn-info ico_right" onclick="SearchData(20,0);">Search&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button></div>
</div>
<div id="div_list_content">
    <div class="row mg_tp_20"> <div class="col-md-12 no-pad"> <div class="table-responsive">
        <table id="list_table" class="table table-hover" style="margin: 20px 0 !important;">
          <thead>
            <tr>
              <th>City_Id</th>
              <th>City</th>
              <th>Edit</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <table id="search_table" class="table table-hover" style="margin: 20px 0 !important;">
        <thead>
          <tr>
            <th>City_Id</th>
            <th>City</th>
            <th>Edit</th>
          </tr>
        </thead>
        <tbody style="height:1238px;">
        </tbody>
        </table>
    </div></div></div>
</div>
<div id="div_city_list_update_modal"></div>

<script>
var action = 'inactive'; //Check if current action is going on or not. If not then inactive otherwise active

function list_reflect(limit , start){
  $('#search_table').hide();
	$('#list_table').show();
  $('#list_table tbody').append('<div class="loader"></div>');
  $.post('cities/list_reflect.php', {limit:limit , start:start}, function(data){
    $(".loader").remove();
      $('#list_table tbody').append(data);
      action = 'inactive';
  });
}

if(action == 'inactive'){
	var limit = 20; //The number of records to display per request
	var start = 0; //The starting pointer of the data
	action = 'active';
	list_reflect(limit, start);
}

$("div.app_content_wrap").on('scroll' , function(event){
  if($('#list_table').is(":visible")==true){
    if($("div.app_content_wrap").scrollTop() + $("div.app_content_wrap").height() > $("#list_table tbody").height() && action == 'inactive'){
      action = 'active';
      start = start + limit;
      setTimeout(function(){
        list_reflect(limit, start);
      }, 0);
    }
  }
});


function SearchData(limit, start){
	var search=$('#search').val();
	$('#search_table').show();
	$('#list_table').hide();
  $('#search_table tbody').append('<div class="loader"></div>');
	$.post('cities/search_data.php', {limit : limit , start:start, search : search}, function(data){
    $(".loader").remove();
		$('#search_table tbody').html(data);
	});
}

function city_master_update_modal(city_id){
 // $('#div_city_list_update_modal').load('cities/update_modal.php', { city_id : city_id }).hide().fadeIn(500);
  $.post('cities/update_modal.php', {city_id : city_id}, function(data){
    $('#div_city_list_update_modal').html(data);
  });
}
</script>