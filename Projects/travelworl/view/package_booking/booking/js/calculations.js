function calculate_tour_cost()
{
  var hotel_expenses = $('#txt_hotel_expenses').val();
  var tour_cost = $('#txt_tour_cost').val();
  var tour_service_tax = $('#txt_tour_service_tax').val();
  var rue_cost = $('#rue_cost').val();
  var actual_tour_cost = $('#txt_actual_tour_cost').val();

  if(hotel_expenses==""){ hotel_expenses = 0; }
  if(tour_cost==""){ tour_cost = 0; }
  if(tour_service_tax==""){ tour_service_tax = 0; }
  if(actual_tour_cost==""){ actual_tour_cost = 0; }

  var total = parseFloat(hotel_expenses) + parseFloat(tour_cost);
  $('#subtotal').val(total);
  calculate_total_tour_cost();

}
function calculate_total_tour_cost()
{
  var tour_service_tax = $('#txt_tour_service_tax').val();
  var rue_cost = $('#rue_cost').val();

  if(rue_cost==""){ rue_cost = 1; }

  var total = $('#subtotal').val();

  total = parseFloat(rue_cost)*parseFloat(total);

  $('#subtotal_with_rue').val(total);

  var tour_service_tax1 = (parseFloat(tour_service_tax)/100)*total;
  tour_service_tax1 = Math.round(tour_service_tax1);
  $('#tour_service_tax_subtotal').val(tour_service_tax1.toFixed(2));

  var total_tour_cost = parseFloat(total) + parseFloat(tour_service_tax1);
  $('#txt_actual_tour_cost1').val(total_tour_cost);
  $('#txt_actual_tour_cost2').val(total_tour_cost);

  //Visa fee calculate
  var visa_amount = $('#visa_amount').val();  
  var visa_service_charge = $('#visa_service_charge').val();
  var visa_service_tax = $('#visa_service_tax').val();

  if(visa_amount==""){ visa_amount = 0; }
  if(visa_service_charge==""){ visa_service_charge = 0; }
  if(visa_service_tax==""){ visa_service_tax = 0; }


  var visa_service_tax_per = (parseFloat(visa_service_charge)/100)*parseFloat(visa_service_tax);
  visa_service_tax_per = Math.round(visa_service_tax_per);
  $('#visa_service_tax_subtotal').val(visa_service_tax_per.toFixed(2));  

  var visa_service_tax_subtotal = parseFloat(visa_service_charge) + parseFloat(visa_service_tax_per);

  var total_visa_amount = parseFloat(visa_amount) + parseFloat(visa_service_tax_subtotal);
  total_visa_amount = total_visa_amount.toFixed(2);

  $('#visa_total_amount').val(total_visa_amount);
  $('#visa_total_amount1').val(total_visa_amount);

  //Insrance calculate
  var insuarance_amount = $('#insuarance_amount').val();  
  var insuarance_service_charge = $('#insuarance_service_charge').val();
  var insuarance_service_tax = $('#insuarance_service_tax').val();

  if(insuarance_amount==""){ insuarance_amount = 0; }
  if(insuarance_service_charge==""){ insuarance_service_charge = 0; }
  if(insuarance_service_tax==""){ insuarance_service_tax = 0; }

  var insuarance_service_tax_per = (parseFloat(insuarance_service_charge)/100)*parseFloat(insuarance_service_tax);
  insuarance_service_tax_per = Math.round(insuarance_service_tax_per);
  $('#insuarance_service_tax_subtotal').val(insuarance_service_tax_per.toFixed(2));  
  
  var insuarance_service_tax_subtotal = parseFloat(insuarance_service_charge) + parseFloat(insuarance_service_tax_per);

  var total_insuarance_amount = parseFloat(insuarance_amount) + parseFloat(insuarance_service_tax_subtotal);
  total_insuarance_amount = total_insuarance_amount.toFixed(2);

  $('#insuarance_total_amount').val(total_insuarance_amount);
  $('#insuarance_total_amount1').val(total_insuarance_amount);


  total_tour_cost = parseFloat(total_tour_cost) + parseFloat(total_visa_amount) + parseFloat(total_insuarance_amount);
  total_tour_cost = total_tour_cost.toFixed(2);
  $('#txt_actual_tour_cost').val(total_tour_cost);
}