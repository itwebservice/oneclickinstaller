<tr>
    <td><input class="css-checkbox" id="chk_exc<?= $offset ?>1" type="checkbox" onchange="calculate_exc_expense('tbl_dynamic_exc_booking')" checked><label class="css-label" for="chk_visa<?= $offset ?>1"> <label></td>
    <td><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
    <td><input type="text" id="exc_date-<?= $offset ?>1" name="exc_date-<?= $offset ?>1" placeholder="Excursion Date & Time" title="Excursion Date & Time" class="app_datepicker" value="<?= date('d-m-Y') ?>"></td>
    <td><select id="city_name-" class="app_select2 form-control" name="city_name-" title="City Name" onchange="get_excursion_list(this.id);">
            <option value="">*City</option>
            <?php 
                $sq_city = mysql_query("select * from city_master order by city_name asc");
                while($row_city = mysql_fetch_assoc($sq_city)){?>
                    <option value="<?php echo $row_city['city_id'] ?>"><?php echo $row_city['city_name'] ?></option>
                 <?php } ?>
        </select>
    </td>
    <td><select id="excursion-" class="app_select2 form-control" title="Excursion Name" name="excursion-" onchange="get_excursion_amount(this.id);">
        <option value="">*Excursion Name</option>                                      
    </select></td>
    <td><input type="text" id="total_adult-" name="total_adult-" placeholder="*Total Adult" title="Total Adult" onchange="excursion_amount_calculate(this.id);calculate_exc_expense('tbl_dynamic_exc_booking');validate_balance(this.id)"></td>
    <td><input type="text" id="total_children-" name="total_children-" placeholder="*Total Child" title="Total Child" onchange="excursion_amount_calculate(this.id);calculate_exc_expense('tbl_dynamic_exc_booking');validate_balance(this.id)"></td>
    <td><input type="text" id="adult_cost-" name="adult_cost-" placeholder="Adult Cost" title="Adult Cost" onchange="excursion_amount_calculate(this.id);calculate_exc_expense('tbl_dynamic_exc_booking');validate_balance(this.id)"></td>
    <td><input type="text" id="child_cost-" name="child_cost-" placeholder="Child Cost" title="Child Cost" onchange="excursion_amount_calculate(this.id);calculate_exc_expense('tbl_dynamic_exc_booking');validate_balance(this.id)"></td>
    <td><input type="text" id="total_amount-" name="total_amount-" placeholder="Total Amount" title="Excursion Amount" onchange="validate_balance(this.id)"></td>
</tr>

<script>
    $('#city_name-').select2();
    $('#exc_date-<?= $offset ?>1').datetimepicker({ format:'d-m-Y H:i' });
</script>