<style>
textarea{
    scroll :none;
}

</style>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>Bulk Payments Management</span></li>
        </ul>
    </div>
<div id="page_content_inner">

    

    <h4 class="heading_a uk-margin-bottom">Preview Data Before Import</h4>
    <form action="<?php echo base_url('import_in_payments');?>" method="post">
        <input type="hidden" name="file_id" value="<?php echo $file->id;?>">
        <div class="md-card uk-margin-medium-bottom">
            <div class="md-card-content">
                <div class="dt_colVis_buttons"><a style="float: right;margin: 10px;" title="Export CSV" class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light" id=""  target="_blank" href="<?php echo base_url('import_payments_csv/'.$file->id);?>">Export CSV</a></div>
                <table id="" class="uk-table" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Action</th>
                        <th>Sr No</th>
                        <th>WG ID</th>
                        <?php 
                            if($payments) {
                                $to_currency_symbol = getCurrencySymbolById($payments[0]->to_currency); 
                                $from_currency_symbol = getCurrencySymbolById($payments[0]->from_currency); 
                                
                            }
                        ?>

                        <th>Earning (<?php echo $to_currency_symbol ?>)</th>
                        <th>Earning Date</th>
                        <th>Transaction Id</th>
                        <th>Transaction Detail</th>
                        <th>Earning Type</th>
                        <th>Expense %</th>
                        <th>Expense Detail</th>
                        <th>Revenue Share</th>
                        <th>Actual Amout (<?php echo $from_currency_symbol ?>)</th>
                    </tr>
                    </thead>



                    <td>
                    <?php
                    if($payments) {
                        foreach ($payments as $i => $payment) { ?>
                            <?php
                            $checkValidation = 0;
                            $query = $this->db->query('SELECT *  FROM `video_leads` WHERE `unique_key` = "' . $payment->wg_id . '"');
                            if($query->num_rows() > 0){
                                $leaddata = $query->row();
                                $lead_id = $leaddata->id;
                                $query = $this->db->query('SELECT *  FROM `videos` WHERE `lead_id` = "' . $lead_id . '"');
                                if($query->num_rows() > 0) {
                                    $videodata = $query->row();
                                    $videoId = $videodata->id;
                                    $checkQuery = $this->db->query(" SELECT * FROM earnings WHERE video_id = $videoId AND partner_id = $payment->partner_id AND transaction_id = '$payment->transaction_id' and deleted = 0");
                                    if($checkQuery->num_rows() > 0) {
                                        $checkValidation = 1;
                                    }
                                }else{
                                    $checkValidation = 1;
                                }
                            }else{
                                $checkValidation = 1;
                            }
                            $query = $this->db->query('SELECT *  FROM `users` WHERE `id` = "' . $leaddata->client_id . '"');
                            if($query->num_rows() > 0){
                                $user_data = $query->row();
                            }
                             log_message("error", "Hello");
                            // if($user_data->currency_id != $file->file_currency_id && $user_data->currency_id == $file->conversion_currency_id){
                            //     $currency_symbol = getUserCurrencySymbolById($user_data->currency_id);
                            //     log_message("error", "multiply here");
                            // }else{
                            //     log_message("error", "No Multiply");
                            //     $currency_symbol = getUserCurrencySymbolById(2);
                            //     $earning = $payment->earning;

                            // }
                            $earning = $payment->earning * $file->conversion_rate;

 ?>
                            <tr <?php if($payment->validation == 1) {
                                echo '';

                                echo '';
                                } ?> <?php if($checkValidation == 1 ){ echo  'style="background-color: #d54e4e ';} ?>">
                                <td><a onclick="return confirm('Are you sure want to delete this?')" title="Delete Bulk Payment Upload" href="<?php echo base_url('delete_raw_payment/'.$payment->id.'/'.$payment->payment_bulk_upload_id);?>"><i class="material-icons">&#xE92B;</i></a>

                        <?php if($payment->validation == 1) {
                            $errors = json_decode($payment->errors);
                            if(is_array($errors)){
                                $msgs = implode('<br>',$errors);
                            }else{
                                $msgs = '';
                            }

                            ?>
                                    <a data-uk-tooltip="{cls:'long-text'}" href="javascript:void(0);" title="<?php echo $msgs;?>"> Errors</a>
                            <?php } ?>
                                </td>
                                <td><?php echo($i + 1); ?></td>
                                <td><input class="md-input" type="text"
                                           name="payments[<?php echo $payment->id; ?>][wg_id]"
                                           value="<?php echo $payment->wg_id; ?>"></td>
                                <td><input class="md-input" type="text"
                                           name="payments[<?php echo $payment->id; ?>][earning]"
                                           value="<?php echo $earning; ?>"></td>
                                <td><input class="md-input" type="text"
                                           name="payments[<?php echo $payment->id; ?>][earning_date]"
                                           value="<?php echo $payment->earning_date; ?>"></td>
                                <td><input class="md-input" type="text"
                                           name="payments[<?php echo $payment->id; ?>][transaction_id]"
                                           value="<?php echo $payment->transaction_id; ?>"></td>
                                <td><input class="md-input" type="text"
                                           name="payments[<?php echo $payment->id; ?>][transaction_detail]"
                                           value="<?php echo $payment->transaction_detail; ?>"></td>
                                <td><input class="md-input" type="text"
                                           name="payments[<?php echo $payment->id; ?>][earning_type]"
                                           value="<?php echo $payment->earning_type; ?>"></td>
                                <td><input class="md-input" type="text"
                                           name="payments[<?php echo $payment->id; ?>][expense]"
                                           value="<?php echo $payment->expense; ?>"></td>
                                <td><input class="md-input" type="text"
                                           name="payments[<?php echo $payment->id; ?>][expense_detail]"
                                           value="<?php echo $payment->expense_detail; ?>"></td>
                            <td><input class="md-input"
                                   name="payments[<?php echo $payment->id; ?>][revenue_share]"
                                            value="<?php echo $leaddata->revenue_share; ?>%"></td>
                            <td><input class="md-input"
                                   name="payments[<?php echo $payment->id; ?>][actual_amount]"
                                   value="<?php echo $payment->earning; ?>"></td>
                                <input class="md-input" type="hidden"
                                       name="payments[<?php echo $payment->id; ?>][partner_id]"
                                       value="<?php echo $payment->partner_id; ?>">
                                <input class="md-input" type="hidden"
                                       name="payments[<?php echo $payment->id; ?>][csv_lable]"
                                       value="<?php echo $payment->csv_lable; ?>">
                                <input class="md-input" type="hidden"
                                       name="payments[<?php echo $payment->id; ?>][payment_bulk_upload_id]"
                                       value="<?php echo $payment->payment_bulk_upload_id; ?>">

                            </tr>

                        <?php }
                    }
                    ?>

                    </tbody>
                </table>
            <?php
            if($payments) { ?>
                <div class="" ><button style="float: right;" class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light" type="submit">Import</button> </div>
            <?php } ?>
    </div>
        </div>
    </form>


</div>
</div>

