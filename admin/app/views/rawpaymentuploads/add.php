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
                <form id="form_validation2" class="uk-form-stacked" action="<?php echo base_url('bulk_payment_submit');?>" enctype="multipart/form-data"  method="post">
                    <div class="uk-grid" data-uk-grid-margin>

                        <div class="uk-width-medium-1-3">
                            <span class="uk-text-small uk-text-muted">Partner<span class="req">*</span></span>
                            <div class="parsley-row">
                                <select id="partner_id" name="partner_id" data-parsley-required-message="This field is required."data-parsley-notequalto ="test" required data-md-selectize>
                                    <option value=""> Select Partner</option>
                                    <?php foreach($mrss_partners->result() as $mrss){ ?>
                                        <option value="<?php echo $mrss->partner_id;?>"> <?php echo $mrss->full_name;?></option>
                                    <?php } ?>
                                </select>
                                <div class="error"><?php echo form_error('partner_id'); ?></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-3">
                            <div class="parsley-row">
                                <label for="label">Label<span class="req">*</span></label>
                                <input type="text" name="lable" value="" id="lable"  data-parsley-required-message="This field is required."  required class="md-input"
                                       pattern="[a-zA-Z0-9\s]+"
                                       data-parsley-pattern-message="Only alphabet and number are allowed."
                                />
                                <div class="error"><?php echo form_error('lable'); ?></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-3">
                            <div class="parsley-row">
                                <label for="message" class="uk-form-label">CSV</label>
                                <div class="error"></div>
                            </div>
                            <div class="parsley-row">
                                <input type="file" id="csv_file" name="userfile" class="md-input" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required data-parsley-fileextension='csv'>
                                <div class="error"><?php echo form_error('csv_file'); ?></div>
                            </div>
                        </div>


                        <div class="uk-width-medium-1-3">
                            <div class="parsley-row">
                                <span class="uk-text-small uk-text-muted">File Currency</span>
                                <select id="file_currency_id" name="file_currency_id" required data-parsley-required-message="This field is required."  data-md-selectize>
                                    <option value="">File Currency</option>

                                    <?php if($currency_id > 0){ ?>
                                        <?php foreach ($currencies as $currency){
                                            if($currency_id == $currency->id){
                                                ?>
                                                <option selected value="<?php echo $currency->id;?>"><?php echo $currency->code;?></option>
                                            <?php   }
                                        } ?>
                                    <?php }else{ ?>
                                        <?php foreach ($currencies as $currency){ ?>
                                            <option <?php if($currency->code == 'USD'){ echo "selected"; }?> value="<?php echo $currency->id;?>"><?php echo $currency->code;?></option>
                                            <?php
                                        } ?>
                                    <?php } ?>
                                </select>
                                <div class="error"></div>
                            </div>
                        </div>

                        <div class="uk-width-medium-1-3">
                            <div class="parsley-row">
                                <span class="uk-text-small uk-text-muted">Conversion Rate Currency</span>
                                <select id="conversion_currency_id" name="conversion_currency_id" required data-parsley-required-message="This field is required."   data-md-selectize>
                                    <option value="">Conversion Rate Currency</option>

                                    <?php if($currency_id > 0){ ?>
                                        <?php foreach ($currencies as $currency){
                                            if($currency_id == $currency->id){
                                                ?>
                                                <option selected value="<?php echo $currency->id;?>"><?php echo $currency->code;?></option>
                                            <?php   }
                                        } ?>
                                    <?php }else{ ?>
                                        <?php foreach ($currencies as $currency){ ?>
                                            <option <?php if($currency->code == 'USD'){ echo "selected"; }?> value="<?php echo $currency->id;?>"><?php echo $currency->code;?></option>
                                             <?php
                                        } ?>
                                    <?php } ?>
                                </select>
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-3">
                            <!-- <div class="parsley-row">
                                <label for="label">Conversion Rate<span class="req">*</span></label>
                                <input type="text" name="conversion_rate" value="" id="conversion_rate"  data-parsley-required-message="This field is required." required class="md-input"

                                />
                                <div class="error"><?php echo form_error('lable'); ?></div>
                            </div> -->
                        </div>
                        <br/>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-1-1">
                            <button type="submit" class="md-btn md-btn-primary check" id="submit">Submit</button>
                        </div>
                    </div>
                </form>
			</div>

		</div>

	</div>

</div>
</div>



