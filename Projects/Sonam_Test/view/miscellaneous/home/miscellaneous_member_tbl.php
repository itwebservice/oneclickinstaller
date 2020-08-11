<tr>
    <td><input class="css-checkbox" id="chk_visa<?= $offset ?>1" type="checkbox" checked><label class="css-label" for="chk_visa<?= $offset ?>1"> <label></td>
    <td><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
    <td><input type="text" id="first_name<?= $offset ?>1" name="first_name<?= $offset ?>1" onchange="fname_validate(this.id)" placeholder="*First Name" title="First Name"/></td>
    <td><input type="text" id="middle_name<?= $offset ?>1" onchange="fname_validate(this.id)" name="middle_name<?= $offset ?>1" placeholder="Middle Name" title="Middle Name"/></td>
    <td><input type="text" id="last_name<?= $offset ?>1" name="last_name<?= $offset ?>1" onchange="fname_validate(this.id)" placeholder="Last Name" title="Last Name"/></td>
    <td><input type="text" id="birth_date<?= $offset ?>1" name="birth_date<?= $offset ?>1" placeholder="Birth Date" title="Birth Date" class="app_datepicker" value="<?= date('d-m-Y',  strtotime(' -1 day')) ?>" onchange="adolescence_reflect(this.id)"/></td>
    <td ><input type="text" id="adolescence<?= $offset ?>1" name="adolescence<?= $offset ?>1" placeholder="Adolescence" title="Adolescence" disabled/></td>
    <td><input type="text" id="passport_id<?= $offset ?>1" name="passport_id<?= $offset ?>1" onchange="validate_passport(this.id)" placeholder="Passport ID" title="Passport ID" style="text-transform: uppercase;"/></td>
    <td><input type="text" id="issue_date<?= $offset ?>1" name="issue_date<?= $offset ?>1"  placeholder="Issue Date" class="app_datepicker" title="Issue Date"/ value="<?= date('d-m-Y')?>" ></td>
    <td><input type="text" id="expiry_date<?= $offset ?>1" name="expiry_date<?= $offset ?>1" class="app_datepicker" value="<?= date('d-m-Y') ?>"  placeholder="Expire Date" title="Expire Date"/ ></td>
</tr>

<script>
    $('#visa_country_name<?= $offset ?>1').select2();
</script>