function installer_init()
{
	var product_name = $('#product_name').val();
	var database_name = $('#database_name').val();
	var empty_setup = $('#empty_setup').val();
	var country = $('#country').val();
	var tax_name = $('#tax_name').val();
	var setup_type = $('#setup_type').val();
	var creator_name = $('#creator_name').val();

	if(product_name==""){
		alert("Please enter product name.");
		return false;
	}
	if(database_name==""){
		alert("Please enter database name.");
		return false;
	}
	if(creator_name==""){
		alert("Please enter Creator name.");
		return false;
	}
	var confirm1 = confirm("Are you sure you want to create this product?");
	if(confirm1==false){
		return false;
	}

	$('button').prop('disabled', true).text('Installing..');

	$.post('installer/installer_init.php', { product_name : product_name, database_name : database_name, empty_setup : empty_setup, country : country, setup_type : setup_type, creator_name : creator_name }, function(data){
		alert(data);
		$('button').prop('disabled', false).text('Install Application');
		$('#product_name').val('');
		$('#database_name').val('');
		$('#creator_name').val('');
		$('#tax_name').val('');
	});


}
function get_taxes(country)
{
	var country_id = $('#'+country).val();
	$.post('get_tax_name.php', { country_id : country_id}, function(data){
		$('#tax_name').val(data);
	});
}