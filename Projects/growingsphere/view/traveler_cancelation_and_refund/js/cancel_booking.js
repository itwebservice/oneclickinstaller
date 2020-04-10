
/////////////********** Cancel Traveler Booking Start**********************************************************************
function cancel_traveler_booking(){
  var pass_count = $('#pass_count').val();
  var base_url = $('#base_url').val();
  var tourwise_id = $('#txt_tourwise_id').val();

  var table = document.getElementById('tbl_traveler_cancel');
  var rowCount = table.rows.length;
  
  var traveler_id_arr = new Array(); 
  var first_names_arr = new Array(); 
  for(var i=1; i<rowCount; i++){
    var row = table.rows[i]; 
    if(row.cells[4].childNodes[0].checked == true)
    {
      var temp_id = row.cells[4].childNodes[0].value;
      traveler_id_arr.push(temp_id); 
      first_names_arr.push(row.cells[1].innerHTML);
    }
  }
  if(traveler_id_arr.length != pass_count){
    error_msg_alert("Please select all guest for cancellations.");
    return false;
  }
  else{
    $('#btn_cancel_booking').button('loading');
        $('#vi_confirm_box').vi_confirm_box({
            callback: function(data1){
                if(data1=="yes"){     
                   $.post(base_url+'controller/group_tour/traveler_cancelation_and_refund/cancel_traveler_booking_c.php', { tourwise_id : tourwise_id, traveler_id_arr : traveler_id_arr, first_names_arr : first_names_arr  }, function(data) {
                        var msg = data.split('--');
                        if(msg[0]=="error"){
                          error_msg_alert(msg[1]);
                          $('#btn_cancel_booking').button('reset');
                        }
                        else{
                            msg_alert(data);
                            $('#btn_cancel_booking').button('reset');
                            location.reload();
                        }
                    });                
                }else{
                    $('#btn_cancel_booking').button('reset');
                }
              }
        });
     }
}
/////////////********** Cancel Traveler Booking End**********************************************************************</script>