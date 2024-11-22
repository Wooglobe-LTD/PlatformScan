<style>
textarea{
    scroll :none;
}

</style>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>Earning Requests Management</span></li>
        </ul>
    </div>
<div id="page_content_inner">

    <h4 class="heading_a uk-margin-bottom"><?php echo $title;?></h4>
    <div style="display:flex; align-items:center;">
        <div type="button" id="approve_earnings_bulk" class="bulk_earning_req" data-status="1"> Approve <i class="material-icons" style="color:#90ff20">&#xE5CA;</i> </div>
        <div type="button" id="reject_earnings_bulk" class="bulk_earning_req" data-status="2"> Reject <i class="material-icons" style="color:red">block</i> </div>
    </div>
    <div class="md-card uk-margin-medium-bottom">
        <div class="md-card-content">
            <div style="padding:10px">
                <input type="checkbox" name="earning_req_sel_all" id="earning_req_sel_all" class="email_preview_check" />
                <label for="earning_req_sel_all" class="inline-label"><b>Select All</b></label>
            </div>
            <div class="dt_colVis_buttons"></div>
            <table id="dt_tableExport" class="uk-table" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th data-name="c.action">Actions</th>
                        <th data-name="v.title">Video Title</th>
                        <th data-name="vl.unique_key">WGID</th>
                        <th data-name="e.earning_date">Earning Date</th>
                        <th data-name="e.earning_amount">Earning</th>
                        <th data-name="e.client_net_earning">Client Net Earning</th>
                        <th data-name="e.wooglobe_total_share">Wooglobe Total Share</th>
                        <th data-name="e.actual_amount">Actual Amount</th>
                        <th data-name="e.expense">Expense</th>
                        <th data-name="e.expense_detail">Expense Detail</th>
                        <th data-name="et.earning_type">Earning Source</th>
                        <th data-name="e.transaction_id">Transaction Id</th>
                        <th data-name="e.transaction_detail">Transaction Detail</th>
                    </tr>
                </thead>

                <tfoot>
                    <tr>
                        <th data-name="c.action">Actions</th>
                        <th data-name="v.title">Video Title</th>
                        <th data-name="vl.unique_key">WGID</th>
                        <th data-name="e.earning_date">Earning Date</th>
                        <th data-name="e.earning_amount">Earning</th>
                        <th data-name="e.client_net_earning">Client Net Earning</th>
                        <th data-name="e.wooglobe_total_share">Wooglobe Total Share</th>
                        <th data-name="e.actual_amount">Actual Amount</th>
                        <th data-name="e.expense">Expense</th>
                        <th data-name="e.expense_detail">Expense Detail</th>
                        <th data-name="et.earning_type">Earning Source</th>
                        <th data-name="e.transaction_id">Transaction Id</th>
                        <th data-name="e.transaction_detail">Transaction Detail</th>
                    </tr>
                </tfoot>

                <tbody>
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>
<?php //if($assess['can_add']) { ?>
<!--<div class="md-fab-wrapper">
        <a title="Add New Category" class="md-fab md-fab-accent md-fab-wave waves-effect waves-button" id="add" href="javascript:void(0);">
        <i class="material-icons">&#xE145;</i>
        </a>
    </div>-->
  <?php //} ?>

<div class="uk-modal" id="play_model">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title" id="vt"></h3>
        </div>
        <div class="md-card-content large-padding" id="play">

        </div>
        <div class="uk-modal-footer uk-text-right">
            <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
        </div>
    </div>
</div>

