 <div id="page_content">
        <div id="page_content_inner">

            <h2>Finance Report</h2>
            <div class="uk-grid uk-grid-width-large-1-4 uk-grid-width-medium-1-2 uk-grid-medium uk-sortable sortable-handler hierarchical_show" data-uk-sortable="" data-uk-grid-margin="">

                <div>
                    <div class="md-card" style="padding: 25px;text-align: center;">
                        <div class="md-card-content">
                            <span class="uk-text-large"><b>Total Immediate Payable</b></span>
                            <h2 class="uk-margin-remove">$<span class="countUpMe"><a class="show-table" data-table="table-immediate-payable" href="javascript:void(0);"><?php echo round($imgPayable,2);?></a></span></h2>
                            <h2 class="uk-margin-remove">£<span class="countUpMe"><a class="show-table" data-table="table-immediate-payable" href="javascript:void(0);"><?php echo round($imgPayableP,2);?></a></span></h2>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="md-card" style="padding: 25px;text-align: center;">
                        <div class="md-card-content">
                            <span class="uk-text-large"><b>Total Accrual Liability</b></span>
                            <h2 class="uk-margin-remove">$<span class="countUpMe"><a class="show-table" data-table="table-payable" href="javascript:void(0);"><?php echo round($payable,2);?></a></span></h2>
                            <h2 class="uk-margin-remove">£<span class="countUpMe"><a class="show-table" data-table="table-payable" href="javascript:void(0);"><?php echo round($payableP,2);?></a></span></h2>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="md-card" style="padding: 25px;text-align: center;">
                        <div class="md-card-content">
                            <span class="uk-text-large"><b>Total Accrual Reverse</b></span>
                            <!--<span class="uk-text-large"><b>Accrual Reverse / New Revenues</b></span>-->
                            <h2 class="uk-margin-remove">$<span class="countUpMe"><a class="show-table" data-table="table-reverse" href="javascript:void(0);"><?php echo round($reverse,2);?></a></span></h2>
                            <h2 class="uk-margin-remove">£<span class="countUpMe"><a class="show-table" data-table="table-reverse" href="javascript:void(0);"><?php echo round($reverseP,2);?></a></span></h2>
                        </div>
                    </div>
                </div>
            </div>
            <section class="table-hide table-payable" style="padding: 15px;margin: 15px 0px;display: none;">
                <h2>Accrual Liability</h2>
                <div class="uk-width-large-1-1" >
                    <div class="md-card-content">
                        <div class="dt_colVis_buttons"></div>

                        <table id="" class="uk-table dataTable" cellspacing="0" width="100%">
                            <thead>
                            <tr style="line-height: 40px;">

                                <th data-name="" style="text-align: left;">WooGlobe ID</th>
                                <th data-name="" style="text-align: left;">Payment Amounts Accumulated</th>
                                <th data-name="" style="text-align: left;">Currency</th>
                                <th data-name="" style="text-align: left;">PayPal Email</th>
                                <th data-name="" style="text-align: left;">Remaining Months</th>


                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            foreach ($leads->result() as $lead){
                                $date1 = $time;
                                $date2 = $lead->created_at;

                                $months = nb_mois($date1,$date2);

                                if($lead->payment < $setting->minimum_payment_yearly_threshhold && $lead->created_at < $time){  }else{

                                    ?>
                                    <tr style="line-height: 40px;">
                                        <td><a target="_blank" href="<?php echo base_url('deal-detail/'.$lead->lead_id);?>"><?php echo $lead->wid; ?></a></td>
                                        <td><?php echo round($lead->payment,2); ?></td>
                                        <td><?php echo $lead->symbol; ?></td>
                                        <td><?php echo $lead->paypal_email; ?></td>
                                        <?php if($lead->payment < $setting->minimum_payment_yearly_threshhold){ ?>
                                        <td><?php echo $months; ?></td>
                                        <?php }else{ ?>
                                            <td>0</td>
                                        <?php } ?>


                                    </tr>
                                <?php }
                            }
                            ?>
                            </tbody>
                            <tfoot>
                            <tr style="line-height: 40px;">
                                <th data-name="" style="text-align: left;">WooGlobe ID</th>
                                <th data-name="" style="text-align: left;">Payment Amounts Accumulated</th>
                                <th data-name="" style="text-align: left;">Currency</th>
                                <th data-name="" style="text-align: left;">PayPal Email</th>
                                <th data-name="" style="text-align: left;">Remaining Months</th>

                            </tr>
                            </tfoot>




                        </table>
                    </div>
                </div>
            </section>
            <section class="table-hide table-immediate-payable" style="padding: 15px;margin: 15px 0px;display: none;">
                <h2>Immediate Payable</h2>
                <div class="uk-width-large-1-1" >
                    <div class="md-card-content">
                        <div class="dt_colVis_buttons"></div>

                        <table id="" class="uk-table dataTable" cellspacing="0" width="100%">
                            <thead>
                            <tr style="line-height: 40px;">

                                <th data-name="" style="text-align: left;">WooGlobe ID</th>
                                <th data-name="" style="text-align: left;">Payment Amounts</th>
                                <th data-name="" style="text-align: left;">Currency</th>
                                <th data-name="" style="text-align: left;">PayPal Email</th>


                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            foreach ($leads->result() as $lead){
                                if($lead->payment < $setting->minimum_payment_yearly_threshhold && $lead->created_at < $time){

                                }else{
                                    if($lead->payment >= $setting->payment_threshold){
                                    ?>
                                    <tr style="line-height: 40px;">
                                        <td><a target="_blank" href="<?php echo base_url('deal-detail/'.$lead->lead_id);?>"><?php echo $lead->wid; ?></a></td>
                                        <td><?php echo round($lead->payment,2); ?></td>
                                        <td><?php echo $lead->symbol; ?></td>
                                        <td><?php echo $lead->paypal_email; ?></td>

                                    </tr>
                                <?php }
                                }
                            }
                            ?>
                            </tbody>
                            <tfoot>
                            <tr style="line-height: 40px;">
                                <th data-name="" style="text-align: left;">WooGlobe ID</th>
                                <th data-name="" style="text-align: left;">Payment Amounts</th>
                                <th data-name="" style="text-align: left;">Currency</th>
                                <th data-name="" style="text-align: left;">PayPal Email</th>

                            </tr>
                            </tfoot>




                        </table>
                    </div>
                </div>
            </section>
            <section class="table-hide table-reverse" style="padding: 15px;margin: 15px 0px;display: none;">
                <h2>Accrual Reverse</h2>
                <div class="uk-width-large-1-1" >
                    <div class="md-card-content">
                        <div class="dt_colVis_buttons"></div>

                        <table id="" class="uk-table dataTable" cellspacing="0" width="100%">
                            <thead>
                            <tr style="line-height: 40px;">

                                <th data-name="" style="text-align: left;">WooGlobe ID</th>
                                <th data-name="" style="text-align: left;">Payment Amounts</th>
                                <th data-name="" style="text-align: left;">Currency</th>



                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            foreach ($leads->result() as $lead){
                                if($lead->payment < $setting->minimum_payment_yearly_threshhold && $lead->created_at < $time){ ?>
                                    <tr style="line-height: 40px;">
                                        <td><a target="_blank" href="<?php echo base_url('deal-detail/'.$lead->lead_id);?>"><?php echo $lead->wid; ?></a> </td>
                                        <td><?php echo round($lead->payment,2); ?></td>
                                        <td><?php echo $lead->symbol; ?></td>


                                    </tr>

                                <?php }
                            }
                            ?>
                            </tbody>
                            <tfoot>
                            <tr style="line-height: 40px;">
                                <th data-name="" style="text-align: left;">WooGlobe ID</th>
                                <th data-name="" style="text-align: left;">Payment Amounts</th>
                                <th data-name="" style="text-align: left;">Currency</th>


                            </tr>
                            </tfoot>




                        </table>
                    </div>
                </div>
            </section>
            <div class="md-card uk-margin-medium-bottom" style="padding: 15px; margin: 15px 0px;">
                <div class="md-card-content">

                    <h4 class="heading_a uk-margin-bottom">Filters</h4>
                    <?php /*if($role != 11){ */?><!--
                        <a style="display: none;" href="<?php /*echo base_url() */?>array_to_csv_download" class="md-btn buttons-csv buttons-html5 dt_csv">Overall csv download</a>
                    --><?php /*}*/?>
                    <div>
                        <form action="<?php echo base_url('finance_report');?>" method="post" id="form_search" class="uk-form-stacked">

                            <div class="uk-grid" data-uk-grid-margin>

                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">

                                        <div class="parsley-row">
                                            <select id="date" name="date" required data-parsley-required-message=""  data-md-selectize>
                                                <option <?php if($date == 1){ echo "selected"; } ?> value="1">Last Month</option>
                                                <option <?php if($date == 2){ echo "selected"; } ?> value="2">Last 3 months</option>
                                                <option <?php if($date == 3){ echo "selected"; } ?> value="3">Last 1 Year</option>
                                                <option <?php if($date == 4){ echo "selected"; } ?> value="4">Custom</option>
                                            </select>
                                            <div class="error"></div>
                                        </div>

                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2 date_from_to" style="display: none;">

                                    <div class="uk-width-medium-1-3" style="display:inline-block;width: 49%;">
                                        <div class="parsley-row">
                                            <div class="md-input-wrapper">
                                                <label for="date_from">Date From</label>
                                                <input class="md-input" id="date_from" data-uk-datepicker="{format:'YYYY-MM-DD',maxDate:''}" type="text" name="date_from" data-parsley-required-message="" value="<?php if($date == 4){ echo date('Y-m-d',strtotime($dateFrom)); } ?>" readonly>
                                                <span class="md-input-bar "></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-width-medium-1-3" style="display:inline-block;width: 49%;">
                                        <div class="parsley-row">
                                            <div class="md-input-wrapper">
                                                <label for="date_to">Date To</label>
                                                <input class="md-input" id="date_to" data-uk-datepicker="{format:'YYYY-MM-DD',maxDate:''}" type="text" name="date_to" data-parsley-required-message="" value="<?php if($date == 4){ echo date('Y-m-d',strtotime($dateFrom)); } ?>" readonly>
                                                <span class="md-input-bar "></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-grid" style="margin:15px 0px; ">
                                        <div class="uk-width-1-1" style="text-align: right;">
                                            <button type="submit" id="search" class="md-btn md-btn-primary check">Search</button>
                                        </div>

                                    </div>
                                </div>
                                

                            </div>



                            <br/><br/>


                        </form>
                    </div>
                    <div class="uk-grid uk-grid-width-large-1-4 uk-grid-width-medium-1-2 uk-grid-medium uk-sortable sortable-handler hierarchical_show" data-uk-sortable="" data-uk-grid-margin="">

                        <div>
                            <div class="md-card" style="padding: 25px;text-align: center;">
                                <div class="md-card-content">
                                    <span class="uk-text-large"><b>Total Sales</b></span>
                                    <h2 class="uk-margin-remove">$<span class="countUpMe"><?php echo round($sales,2);?></span></h2>
                                    <h2 class="uk-margin-remove">£<span class="countUpMe"><?php echo round($salesP,2);?></span></h2>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="md-card" style="padding: 25px;text-align: center;">
                                <div class="md-card-content">
                                    <span class="uk-text-large"><b>Total Clients Earnings</b></span>
                                    <h2 class="uk-margin-remove">$<span class="countUpMe"><?php echo round($clientEarnings,2);?></span></h2>
                                    <h2 class="uk-margin-remove">£<span class="countUpMe"><?php echo round($clientEarningsP,2);?></span></h2>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="md-card" style="padding: 25px;text-align: center;">
                                <div class="md-card-content">
                                    <span class="uk-text-large"><b>Total Wooglobe Revenue</b></span>
                                    <!--<span class="uk-text-large"><b>Accrual Reverse / New Revenues</b></span>-->
                                    <h2 class="uk-margin-remove">$<span class="countUpMe"><?php echo round(($wooglobeEarnings + $expense),2);?></span></h2>
                                    <h2 class="uk-margin-remove">£<span class="countUpMe"><?php echo round(($wooglobeEarningsP + $expenseP),2);?></span></h2>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="md-card" style="padding: 25px;text-align: center;">
                                <div class="md-card-content">
                                    <span class="uk-text-large"><b>Total Paid</b></span>
                                    <!--<span class="uk-text-large"><b>Accrual Reverse / New Revenues</b></span>-->
                                    <h2 class="uk-margin-remove">$<span class="countUpMe"><?php echo round($paid,2);?></span></h2>
                                    <h2 class="uk-margin-remove">£<span class="countUpMe"><?php echo round($paidP,2);?></span></h2>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="md-card" style="padding: 25px;text-align: center;">
                                <div class="md-card-content">
                                    <span class="uk-text-large"><b>Total Unpaid</b></span>
                                    <!--<span class="uk-text-large"><b>Accrual Reverse / New Revenues</b></span>-->
                                    <h2 class="uk-margin-remove">$<span class="countUpMe"><?php echo round($unpaid,2);?></span></h2>
                                    <h2 class="uk-margin-remove">£<span class="countUpMe"><?php echo round($unpaidP,2);?></span></h2>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="uk-width-large-1-1" >
                        <div class="md-card-content">
                            <div class="dt_colVis_buttons"></div>

                            <table id="" class="uk-table dataTable" cellspacing="0" width="100%">
                                <thead>
                                <tr style="line-height: 40px;">

                                    <th data-name="" style="text-align: left;">WooGlobe ID</th>
                                    <th data-name="" style="text-align: left;">Sale</th>
                                    <th data-name="" style="text-align: left;">Client Earnings</th>
                                    <th data-name="" style="text-align: left;">Wooglobe Earnings</th>
                                    <th data-name="" style="text-align: left;">Expense</th>
                                    <th data-name="" style="text-align: left;">Paid</th>
                                    <th data-name="" style="text-align: left;">Unpaid</th>
                                    <th data-name="" style="text-align: left;">Currency</th>
                                    <th data-name="" style="text-align: left;">PayPal Email</th>


                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                foreach ($reports as $i=>$report){

                                            ?>
                                            <tr style="line-height: 40px;">
                                                <td><a target="_blank" href="<?php echo base_url('deal-detail/'.$lead->lead_id);?>"><?php echo $i; ?></a></td>
                                                <td><?php echo round($report['earning_amount'],2); ?></td>
                                                <td><?php echo round($report['client_net_earning'],2); ?></td>
                                                <td><?php echo round($report['wooglobe_net_earning'],2); ?></td>
                                                <td><?php echo round($report['expense_amount'],2); ?></td>
                                                <td><?php echo round($report['paid'],2); ?></td>
                                                <td><?php echo round($report['unpaid'],2); ?></td>
                                                <td><?php echo $report['data']->symbol; ?></td>
                                                <td><?php echo $report['data']->paypal_email; ?></td>

                                            </tr>
                                        <?php
                                }
                                ?>
                                </tbody>
                                <tfoot>
                                <tr style="line-height: 40px;">
                                    <th data-name="" style="text-align: left;">WooGlobe ID</th>
                                    <th data-name="" style="text-align: left;">Sale</th>
                                    <th data-name="" style="text-align: left;">Client Earnings</th>
                                    <th data-name="" style="text-align: left;">Wooglobe Earnings</th>
                                    <th data-name="" style="text-align: left;">Expense</th>
                                    <th data-name="" style="text-align: left;">Paid</th>
                                    <th data-name="" style="text-align: left;">Unpaid</th>
                                    <th data-name="" style="text-align: left;">Currency</th>
                                    <th data-name="" style="text-align: left;">PayPal Email</th>

                                </tr>
                                </tfoot>




                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>