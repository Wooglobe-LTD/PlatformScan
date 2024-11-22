<style>
textarea{
	scroll :none;
}
.selectize-input{
	border-width : 0 0 0px !important;
}
.selectize-dropdown {
	margin-top: 0px !important;
}
*+.uk-table {
margin-top: 0px !important;
}
#video_url, #email{
	word-break: break-all;
}
.get_thumb{
	width: 100%;
	float: left;
	text-align: center;
}
</style>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>Bulk Payment Upload</span></li>
        </ul>
    </div>
<div id="page_content_inner">


    <div class="md-card uk-margin-medium-bottom">

		<div class="md-card-content" style="padding: 15px;">

			<div>

				<h2>Bulk Payment Upload</h2>

				<br/>
                <form id="form_validation2" class="uk-form-stacked" action="<?php echo base_url('payment_bulk_upload_edit/'.$file->id);?>" enctype="multipart/form-data"  method="post">
                    <div class="uk-grid" data-uk-grid-margin>

                        <div class="uk-width-medium-1-3">
                            <span class="uk-text-small uk-text-muted">Partner<span class="req">*</span></span>
                            <div class="parsley-row">
                                <select id="partner_id" name="partner_id" data-parsley-required-message="This field is required." required data-md-selectize>
                                    <option value=""> Select Partner</option>
                                    <?php foreach($mrss_partners->result() as $mrss){ ?>
                                        <option <?php if($file->partner_id == $mrss->partner_id){ echo 'selected'; } ?> value="<?php echo $mrss->partner_id;?>"> <?php echo $mrss->full_name;?></option>
                                    <?php } ?>
                                </select>
                                <div class="error"><?php echo form_error('partner_id'); ?></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-3">
                            <div class="parsley-row">
                                <label for="label">Label<span class="req">*</span></label>
                                <input type="text" name="lable" value="<?php echo $file->lable;?>" id="lable"  data-parsley-required-message="This field is required." required class="md-input"
                                       pattern="[a-zA-Z0-9\s]+"
                                       data-parsley-pattern-message="Only alphabet and number are allowed."
                                />
                                <div class="error"><?php echo form_error('lable'); ?></div>
                            </div>
                        </div>

                    </div>



                    <br/>

                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-1-1">
                            <button type="submit" class="md-btn md-btn-primary check">Save</button>
                        </div>
                    </div>
                </form>
			</div>

		</div>

	</div>

</div>
</div>



