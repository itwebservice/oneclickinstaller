function exc_id_dropdown_load(customer_id_filter, exc_id_filter)
{
  var customer_id = $('#'+customer_id_filter).val();
  var branch_status = $('#branch_status').val();
  $.post('exc_id_dropdown_load.php', { customer_id : customer_id , branch_status : branch_status}, function(data){
    $('#'+exc_id_filter).html(data);
  });
}
/**Excursion Name load**/
function get_excursion_list(id)
{
  var city_id = $("#"+id).val();
  var base_url = $('#base_url').val();
           
  var count = id.substring(10);
  $.post("inc/excursion_name_load.php" , { city_id : city_id } , function ( data ) {
        $ ("#excursion-"+count).html(data);              
  } ) ;   
}


//Excursion Amounnt calculate
function excursion_amount_calculate(id)
{
  var count = id.split('-');

  var total_adult = $('#total_adult-'+count[1]).val();
  var total_children = $('#total_children-'+count[1]).val();
  var adult_cost = $('#adult_cost-'+count[1]).val();
  var child_cost = $('#child_cost-'+count[1]).val();
  var total_amount = $('#total_amount-'+count[1]).val();
  var payment_cost = $("#exc_issue_amount").val();
  
  if(total_adult == ''){ total_adult = 0; }
  if(total_children == ''){ total_children = 0; }
  if(adult_cost == ''){ adult_cost = 0; }
  if(child_cost == ''){ child_cost = 0; }
  if(total_amount == ''){ total_amount = 0; }
  if(payment_cost == ''){ payment_cost = 0; }
  
  var total_adult_cost = parseFloat(total_adult) * parseFloat(adult_cost);
  var total_child_cost = parseFloat(total_children) * parseFloat(child_cost);

  total_cost = parseFloat(total_adult_cost) + parseFloat(total_child_cost);

  //payment_cost =  parseFloat(payment_cost) + parseFloat(total_cost);
  $("#total_amount-"+count[1]).val(total_cost.toFixed(2));
  //$("#exc_issue_amount").val(payment_cost);
}

/**Excursion Amount load**/
function get_excursion_amount(id)
{
  var service_id = $("#"+id).val();
  var base_url = $('#base_url').val();
  var count = id.substring(10);

  $.post("inc/excursion_amount_load.php" , { service_id : service_id} , function ( data )
   { 
    var amount_arr = JSON.parse(data);
     $("#total_amount-"+count).val(amount_arr[0]['total_cost']);
     $("#adult_cost-"+count).val(amount_arr[0]['adult_cost']); 
     $("#child_cost-"+count).val(amount_arr[0]['child_cost']); 
   }) ;
   //excursion_amount_calculate(id);   
}

//cash reciept
function cash_bank_receipt_generate()
{
  var bank_name_reciept = $('#bank_name_reciept').val();
  var payment_id_arr = new Array();

  $('input[name="chk_exc_payment"]:checked').each(function(){

    payment_id_arr.push($(this).val());

  });

  if(payment_id_arr.length==0){
    error_msg_alert('Please select at least one payment to generate receipt!');
    return false;
  }

  var base_url = $('#base_url').val();

  var url = base_url+"view/bank_receipts/exc_payment/cash_bank_receipt.php?payment_id_arr="+payment_id_arr+'&bank_name_reciept='+bank_name_reciept;
  window.open(url, '_blank');
}

function cheque_bank_receipt_generate()
{
  var bank_name_reciept = $('#bank_name_reciept').val();
  var payment_id_arr = new Array();
  var branch_name_arr = new Array();

  $('input[name="chk_exc_payment"]:checked').each(function(){

    var id = $(this).attr('id');
    var offset = id.substring(16);
    var branch_name = $('#branch_name_'+offset).val();

    payment_id_arr.push($(this).val());
    branch_name_arr.push(branch_name);

  });

  if(payment_id_arr.length==0){
    error_msg_alert('Please select at least one payment to generate receipt!');
    return false;
  }

  $('input[name="chk_exc_payment"]:checked').each(function(){

    var id = $(this).attr('id');
    var offset = id.substring(16);
    var branch_name = $('#branch_name_'+offset).val();

    if(branch_name==""){
      error_msg_alert("Please enter branch name for selected payments!"); 
      //return false;
      exit(0);
    }

  });

  
  var base_url = $('#base_url').val();

  var url = base_url+"view/bank_receipts/exc_payment/cheque_bank_receipt.php?payment_id_arr="+payment_id_arr+'&branch_name_arr='+branch_name_arr+'&bank_name_reciept='+bank_name_reciept;
  window.open(url, '_blank');
}

///////Excursion amount calculate start/////////////////////////////////////////////////
function calculate_exc_expense(id,offset='')
{
  var table = document.getElementById(id);
  var rowCount = table.rows.length;
  var total_expense = 0;
  
  for(var i=0; i<rowCount; i++)
  {
    var row = table.rows[i];
    if(row.cells[0].childNodes[0].checked == true)
    {  
        var amt = row.cells[9].childNodes[0].value;
        if( !isNaN(amt) )
        {
          if(amt == 0) { amt = 0; }  
          total_expense=parseFloat(total_expense)+parseFloat(amt);;
        }     
    }
  }  
  $('#exc_issue_amount'+offset).val(total_expense.toFixed(2));  
  calculate_total_amount(offset);
}
///////Excursion amount calculate end/////////////////////////////////////////////////