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
            <div class="dt_colVis_buttons_2"></div>
            <table id="table_history" class="uk-table" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th data-name="i.invoice_id">REMITTANCE</th>
                    <th data-name="i.wg_ids">WG IDS</th>
                    <th data-name="i.currency">Currency</th>
                    <th data-name="i.amount">Amount</th>
                    <th data-name="i.status">Status</th>

                </tr>
                </thead>

                <tfoot>
                <tr>

                    <th data-name="i.invoice_id">REMITTANCE</th>
                    <th data-name="i.wg_ids">WG IDS</th>
                    <th data-name="i.currency">Currency</th>
                    <th data-name="i.amount">Amount</th>
                    <th data-name="i.status">Status</th>

                </tr>
                </tfoot>

                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    <div class="uk-modal" id="edit_model">
        <div class="uk-modal-dialog" style="width: 90% !important;">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title" >Payment Detail ( <span id="video-title-id" style="display: inline;"></span> )</h3>
            </div>
            <div class="md-card-content large-padding" id="inv_details">

            </div>
            <div class="uk-modal-footer uk-text-right" style="margin-top: 20px;">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
            </div>
        </div>
    </div>

</div>
</div>





