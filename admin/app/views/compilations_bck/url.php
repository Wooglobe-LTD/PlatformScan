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
            <li><span><?php echo $title;?></span></li>
        </ul>
    </div>
<div id="page_content_inner">


    <div class="md-card uk-margin-medium-bottom">

		<div class="md-card-content" style="padding: 20px;">

			<div>

				<h2><?php echo $title;?></h2>

				<br/>
                <form id="form_validation2" class="uk-form-stacked" action="<?php echo base_url('compilations_urls');?>" method="post">


                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="message" class="uk-form-label">Video URLs</label>
                                <textarea id="urls" name="urls" class="md-input" required></textarea>
                                <div class="error"><?php echo form_error('urls');?></div>
                            </div>
                        </div>

                    </div>


                    <br/>

                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            <button type="submit" class="md-btn md-btn-primary check">Submit</button>
                        </div>
                    </div>
                </form>
			</div>

		</div>

	</div>

</div>
</div>

<script>
    var uid = '';
    var watertype  = '';
    var access   = '';
</script>

