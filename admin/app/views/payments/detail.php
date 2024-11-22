<style>
textarea{
    scroll :none;
}

</style>


<div id="page_content_inner" style="padding: 0">




    <div class="uk-grid uk-margin-medium-top uk-margin-large-bottom">

        <div class="uk-width-large-1-4">

            <ul class="md-list">
                <li>
                    <div class="md-list-content">
                        <span class="uk-text-small uk-text-muted">Remittance No</span>
                        <span id="edit-closing-area" class="md-list-heading closing-area"><?php echo $invoice->invoice_id; ?></span>

                    </div>
                </li>

                </ul>

        </div>
        <div class="uk-width-large-1-4">

            <ul class="md-list">
                <li>
                    <div class="md-list-content">
                        <span class="uk-text-small uk-text-muted">Paypal No</span>
                        <span id="edit-closing-area" class="md-list-heading closing-area"><?php echo $invoice->paypal_id; ?></span>

                    </div>
                </li>

            </ul>

        </div>
        <div class="uk-width-large-1-4">

            <ul class="md-list">
                <li>
                    <div class="md-list-content">
                        <span class="uk-text-small uk-text-muted">Amount</span>
                        <span id="edit-closing-area" class="md-list-heading closing-area"><?php echo $invoice->currency.' '.$invoice->amount; ?></span>

                    </div>
                </li>

            </ul>

        </div>
        <div class="uk-width-large-1-4">

            <ul class="md-list">
                <li>
                    <div class="md-list-content">
                        <span class="uk-text-small uk-text-muted">Last Update</span>
                        <span id="edit-closing-area" class="md-list-heading closing-area"><?php echo date('Y-m-d H:i:s',strtotime($invoice->updated_at)); ?></span>

                    </div>
                </li>

            </ul>

        </div>

    </div>
    <h2> Videos</h2>
    <div class="md-card uk-margin-medium-bottom">
        <div class="md-card-content">
            <div class=""></div>
            <table id="" class="uk-table" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>WG ID</th>
                    <th>Title</th>
                    <th>URL</th>
                    <th>Client</th>
                    <th>Email</th>
                    <th>Address</th>

                </tr>
                </thead>
                <tbody>
                <?php foreach ($videos->result() as $video){ ?>
                    <tr>
                        <td><?php echo $video->unique_key;?></td>
                        <td><?php echo $video->video_title;?></td>
                        <td><?php echo $video->video_url;?></td>
                        <td><?php echo $video->full_name;?></td>
                        <td><?php echo $video->email;?></td>
                        <td><?php echo $video->address;?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                <tr>

                    <th>WG ID</th>
                    <th>Title</th>
                    <th>URL</th>
                    <th>Client</th>
                    <th>Email</th>
                    <th>Address</th>

                </tr>
                </tfoot>

                <tbody>

                </tbody>
            </table>
        </div>
    </div>

    <h2> Logs</h2>
    <div class="md-card uk-margin-medium-bottom">
        <div class="md-card-content">
            <div class=""></div>
            <table id="" class="uk-table" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Message</th>
                    <th>Created at</th>


                </tr>
                </thead>
                <tbody>
                <?php foreach ($logs->result() as $log){ ?>
                    <tr>
                        <td><?php echo $log->log_message;?></td>
                        <td><?php echo date('Y-m-d H:i:s',strtotime($log->created_at));?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                <tr>

                    <th>Message</th>
                    <th>Created at</th>

                </tr>
                </tfoot>

                <tbody>

                </tbody>
            </table>
        </div>
    </div>


</div>
</div>





