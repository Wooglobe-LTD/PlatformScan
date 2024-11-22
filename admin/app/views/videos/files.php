<style>
textarea{
    scroll :none;
}

</style>

<div id="page_content">
<div id="page_content_inner">

    

    <h4 class="heading_a uk-margin-bottom"><?php echo $title;?></h4>
    <div class="md-card uk-margin-medium-bottom">
        <div class="md-card-content">

            <div class="dt_colVis_buttons"></div>
            <table id="dt_tableExport_1" class="uk-table" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th >SR#</th>
                    <th data-name="v.title">Video Title</th>
                    <th data-name="rv.action">Actions</th>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <th >SR#</th>
                    <th data-name="v.title">Video Title</th>
                    <th data-name="rv.action">Actions</th>
                </tr>
                </tfoot>

                <tbody>
               
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>

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
<script>
    var root ='<?php echo $root;?>';
    var id ='<?php echo $id;?>';

</script>