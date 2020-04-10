<tr>
    <td><input class="css-checkbox" id="chk_visa<?= $offset ?>1" type="checkbox" checked><label class="css-label" for="chk_visa<?= $offset ?>1"> <label></td>
    <td><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
    <td><input type="text" id="first_name<?= $offset ?>1" name="first_name<?= $offset ?>1" onchange="fname_validate(this.id)" placeholder="*First Name" title="First Name"/></td>
    <td><input type="text" id="middle_name<?= $offset ?>1" onchange="fname_validate(this.id)" name="middle_name<?= $offset ?>1" placeholder="Middle Name" title="Middle Name"/></td>
    <td><input type="text" id="last_name<?= $offset ?>1" name="last_name<?= $offset ?>1" onchange="fname_validate(this.id)" placeholder="Last Name" title="Last Name"/></td>
    <td><input type="text" id="birth_date<?= $offset ?>1" name="birth_date<?= $offset ?>1" placeholder="Birth Date" title="Birth Date" class="app_datepicker" value="<?= date('d-m-Y',  strtotime(' -1 day')) ?>" onchange="adolescence_reflect(this.id)"/></td>
    <td ><input type="text" id="adolescence<?= $offset ?>1" name="adolescence<?= $offset ?>1" placeholder="Adolescence" title="Adolescence" disabled/></td>
    <td><select name="visa_country_name<?= $offset ?>1" id="visa_country_name<?= $offset ?>1" class="app_select2" title="Visa Country Name" style="width:118px" class="app-select" style="width:100%">
            <option value="">*Visa Country</option>
            <?php 
            $sq_country = mysql_query("select * from country_list_master");
            while($row_country = mysql_fetch_assoc($sq_country)){
                ?>
                <option value="<?= $row_country['country_name'] ?>"><?= $row_country['country_name'] ?></option>
                <?php
            }
            ?>
        </select>
    </td>
    <td><select name="visa_type<?= $offset ?>1" id="visa_type<?= $offset ?>1" title="Visa Type">
            <option value="">*Visa Type</option>
            <?php 
            $sq_visa_type = mysql_query("select * from visa_type_master");
            while($row_visa_type = mysql_fetch_assoc($sq_visa_type)){
                ?>
                <option value="<?= $row_visa_type['visa_type'] ?>"><?= $row_visa_type['visa_type'] ?></option>
                <?php
            }
            ?>
        </select>
    </td>
    <td><input type="text" id="passport_id<?= $offset ?>1" name="passport_id<?= $offset ?>1" onchange="validate_passport(this.id)" placeholder="*Passport ID" title="Passport ID" style="text-transform: uppercase;" required/></td>
    <td><input type="text" id="issue_date<?= $offset ?>1" name="issue_date<?= $offset ?>1" class="app_datepicker" placeholder="Issue Date" title="Issue Date"/ value="<?= date('d-m-Y')?>" ></td>
    <td><input type="text" id="expiry_date<?= $offset ?>1" name="expiry_date<?= $offset ?>1" class="app_datepicker" value="<?= date('d-m-Y') ?>"  placeholder="Expire Date" title="Expire Date"/ ></td>
    <td ><input type="text" id="nationality<?= $offset ?>1" name="nationality<?= $offset ?>1" placeholder="*Nationality" title="Nationality"/></td>
    <td style="width:200px;"><select name="received_documents" id="received_documents<?= $offset ?>1" multiple>
            <option value="Aadhaar Card" style="font-size: 14px;">Aadhaar Card</option>
            <option value="Driving Licence" style="font-size: 14px;">Driving Licence</option>
            <option value="Pan Card" style="font-size: 14px;">Pan Card</option>
            <option value="Voter Identity Card" style="font-size: 14px;">Voter Identity Card</option>
            <option value="PassPort" style="font-size: 14px;">PassPort</option>
            <option value="Telephone Bill" style="font-size: 14px;">Telephone Bill</option>
            <option value="Electricity Bill" style="font-size: 14px;">Electricity Bill</option>
            <option value="Ration Card" style="font-size: 14px;">Ration Card</option>
            <option value="Bank Passbook" style="font-size: 14px;">Bank Passbook</option>
            <option value="Bank Statement" style="font-size: 14px;">Bank Statement</option>
            <option value="Employer Letter" style="font-size: 14px;">Employer Letter</option>
            <option value="Employer Invitation" style="font-size: 14px;">Employer Invitation</option>
            <option value="Passport Front" style="font-size: 14px;">Passport Front</option>
            <option value="Passport Back" style="font-size: 14px;">Passport Back</option>
            <option value="Photographs" style="font-size: 14px;">Photographs</option>
            <option value="Birth Certificate" style="font-size: 14px;">Birth Certificate</option>
            <option value="Parent Employment Visa" style="font-size: 14px;">Parent Employment Visa</option>
            <option value="Parent Passport" style="font-size: 14px;">Parent Passport</option>
    </select></td>
</tr>

<script>
    $('#visa_country_name<?= $offset ?>1').select2();
</script>