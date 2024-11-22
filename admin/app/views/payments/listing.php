<style>
textarea{
    scroll :none;
}

</style>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>Payments Management</span></li>
        </ul>
    </div>
<div id="page_content_inner">

    

    <h4 class="heading_a uk-margin-bottom"><?php echo $title;?></h4>
    <div class="md-card uk-margin-medium-bottom">
        <div class="md-card-content">

            <div class="table_option_heading">
                <h4 class="uk-width-medium-2-3">Filters</h4>
            </div>
            <div class="md-card-content table_options">
                <div class="sheet_filters uk-width-medium-1-3">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="above-100-fltr">
                                    <input type="checkbox" name="above-100-fltr" id="above-100-fltr" />
                                    <span><b>$100 or more</b></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dt_colVis_buttons"></div>
            <table id="dt_tableExport" class="uk-table" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th data-name="c.action">Actions</th>
                    <th data-name="u.full_name">Client</th>
                    <th data-name="vl.unique_key">WGID</th>
                    <th data-name="u.email">Email</th>
                    <th data-name="u.paypal_email">Paypal Email</th>
                    <th data-name="u.address">Address</th>
                    <th data-name="u.address">Status</th>
                    <th data-name="e.client_net_earning">Due Payment</th>

                </tr>
                </thead>

                <tfoot>
                <tr>

                    <th data-name="c.action">Actions</th>
                    <th data-name="u.full_name">Client</th>
                    <th data-name="vl.unique_key">WGID</th>
                    <th data-name="u.email">Email</th>
                    <th data-name="u.paypal_email">Paypal Email</th>
                    <th data-name="u.address">Address</th>
                    <th data-name="u.address">Status</th>
                    <th data-name="e.client_net_earning">Due Payment</th>

                </tr>
                </tfoot>

                <tbody>
               
                </tbody>
            </table>
        </div>
    </div>


    <div class="uk-modal" id="edit_model">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title" >Make Payment(<span id="video-title-id" style="display: inline;"></span>)</h3>
            </div>
            <div class="md-card-content large-padding">
                <form id="make_payment_form" class="uk-form-stacked">
                    <input type="hidden" name="user_id" id="id" value="">
                    <input type="hidden" name="authenticate" id="authenticate" value="0">

                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="transaction_id_">Transaction Id</label>
                                <input type="text" id="transaction_id_e" name="transaction_id" required class="md-input" >

                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin="">
                        <div class="uk-width-medium-1-1 uk-row-first">
                            <div class="parsley-row">
                                <div class="md-input-wrapper">
                                    <label for="transaction_detail_e" class="uk-form-label">Transaction Detail</label>
                                    <textarea id="transaction_detail_e" name="transaction_detail" class="md-input autosized" required="" style="overflow: hidden; overflow-wrap: break-word; height: 73.1167px;"></textarea>
                                    <span class="md-input-bar "></span></div>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="tax-rate">Tax Rate (%)</label>
                                <input type="number" id="tax-rate" name="tax_rate" required data-parsley-required-message="This field is required." class="md-input" >
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <div style="width:100%; display:flex; justify-content:flex-end;" data-uk-grid-margin>
                        <label for="send-email-check">
                            <span><b>Send Email</b></span>
                            <input type="checkbox" name="send_email_check" id="send-email-check" value="1" checked />
                        </label>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin="">
                        <div class="uk-width-medium-1-1 uk-row-first">
                            <div class="parsley-row">
                                <div>
                                    <div style="margin-bottom:20px;">
                                        <div>
                                            <label class="uk-text-muted uk-text-small">Country</label>
                                            <span id="country"></span>
                                        </div>
                                        <div>
                                            <label class="uk-text-muted uk-text-small">Address</label>
                                            <span id="address"></span>
                                        </div>
                                    </div>
                                    <div class="payment_amount_details">
                                        <div>
                                            <label class="uk-text-muted uk-text-small">Total Amount</label>
                                            <span id="total-amount"></span>
                                        </div>
                                        <div>
                                            <label class="uk-text-muted uk-text-small">Tax Amount</label>
                                            <span id="tax-amount"></span>
                                        </div>
                                        <div>
                                            <label class="uk-text-muted uk-text-small">Final Amount</label>
                                            <span id="final-amount"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="uk-modal-footer uk-text-right" style="margin-top: 20px;">
                <button type="button" id="send-paypal-email" class="md-btn md-btn-flat" style="color:#b9403e;float:left;">Request PayPal Email</button>
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
                <button type="button" id="edit_from" class="md-btn md-btn-flat md-btn-flat-primary">Save</button>
            </div>
        </div>
    </div>

    <div class="uk-modal" id="email_model">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title" >Update Paypal Email(<span id="video-title-id-2" style="display: inline;"></span>)</h3>
            </div>
            <div class="md-card-content large-padding">
                <form id="form_validation4" class="uk-form-stacked">
                    <input type="hidden" name="user_id" id="id2" value="">

                    <div class="uk-grid" data-uk-grid-margin>

                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="paypal_email">PayPal Email</label>
                                <input type="email" id="paypal_email" name="paypal_email" required data-parsley-required-message="This field is required." class="md-input" >

                                <div class="error"></div>
                            </div>
                        </div>
                    </div>



                </form>
            </div>
            <div class="uk-modal-footer uk-text-right" style="margin-top: 20px;">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="edit_email_from" class="md-btn md-btn-flat md-btn-flat-primary">Save</button>
            </div>
        </div>
    </div>

</div>
</div>





