function installer_init()
{
	var product_name = $('#product_name').val();
	var database_name = $('#database_name').val();
	var setup_type = $('#setup_type').val();
	var empty_setup = $('#empty_setup').val();
	var creator_name = $('#creator_name').val();
	var b2c = $('#b2c').is(':checked');
	/////////////////////////////
	var company_name = $('#company_name').val();
	var website = $('#website').val();
	var contact_no = $('#contact_no').val();
	var address = $('#address').val();
	var tax_name = $('#tax_name').val();
	var country = $('#country').val();
	var state = $('#state').val();
	var currency = $('#currency').val();
	var currency_rate = $('#currency_rate').val(); 
	var ffrom_date = $('#ffrom_date').val();
	var fto_date = $('#fto_date').val();
	var location = $('#location').val();
	var branch = $('#branch').val();
	
	if(product_name==""){
		alert("Please enter setup name.");
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
	if(company_name==""){
		alert("Please enter company name.");
		return false;
	}
	if(website==""){
		alert("Please enter website name.");
		return false;
	}
	if(contact_no==""){
		alert("Please enter contact no.");
		return false;
	}
	if(address==""){
		alert("Please enter address.");
		return false;
	}
	if(tax_name==""){
		alert("Please enter tax name.");
		return false;
	}
	if(country==""){
		alert("Please select country.");
		return false;
	}
	if(state==""){
		alert("Please select state.");
		return false;
	}
	if(currency==""){
		alert("Please select currency.");
		return false;
	}
	if(currency_rate==""){
		alert("Please enter currency rate.");
		return false;
	}
	if(ffrom_date==""){
		alert("Please select financial year from date.");
		return false;
	}
	if(fto_date==""){
		alert("Please select financial year to date.");
		return false;
	}
	if(location==""){
		alert("Please enter location.");
		return false;
	}
	if(branch==""){
		alert("Please enter branch.");
		return false;
	}
	var confirm1 = confirm("Are you sure you want to create this setup?");
	if(confirm1==false){
		return false;
	}
	$('button').prop('disabled', true).text('Installing..');

	$.post('installer/installer_init.php', { product_name : product_name, database_name : database_name, empty_setup : empty_setup, setup_type : setup_type, creator_name : creator_name, b2c : b2c, company_name:company_name, website:website, contact_no:contact_no, address:address,tax_name:tax_name, country:country, state:state, currency:currency, currency_rate:currency_rate, ffrom_date:ffrom_date, fto_date:fto_date,location:location, branch:branch }, function(data){
		
		var msg = data.split('=');
		alert(msg[0]);
		var company_details = JSON.parse(msg[1]);

		var result = 'Username : '+company_details[0]['username']+'<br/>'+'Password : '+company_details[0]['password'];
		$('#company_details_result').html(result);

		$('button').prop('disabled', false).text('Create Setup');
		$('#product_name').val('');
		$('#database_name').val('');
		$('#creator_name').val('');
		$('#company_name').val('');
		$('#website').val('');
		$('#contact_no').val('');
		$('#address').val('');
		$('#tax_name').val('');
		$('#country').val('');
		$('#state').val('');
		$('#currency').val('');
		$('#currency_rate').val('');
		$('#ffrom_date').val('');
		$('#fto_date').val('');
		$('#location').val('');
		$('#branch').val('');
	});
}