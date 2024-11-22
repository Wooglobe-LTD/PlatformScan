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

		<div class="md-card-content">

			<div>

				<h2><?php echo $title;?></h2>

				<br/>
                <form id="form_validation2" class="uk-form-stacked">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-3">
                            <div class="parsley-row">
                                <label for="first_name">First Name<span class="req">*</span></label>
                                <input type="text" name="first_name" value="" id="first_name"  data-parsley-required-message="This field is required." required class="md-input"
                                       pattern="[a-zA-Z0-9\s]+"
                                       data-parsley-pattern-message="Only alphabet and number are allowed."
                                />
                                <div class="error"></div>
                            </div>
                        </div>

                        <div class="uk-width-medium-1-3">
                            <div class="parsley-row">
                                <label for="last_name">Last Name<span class="req">*</span></label>
                                <input type="text" name="last_name" value="" id="last_name"  data-parsley-required-message="This field is required." required class="md-input"
                                       pattern="[a-zA-Z0-9\s]+"
                                       data-parsley-pattern-message="Only alphabet and number are allowed."
                                />
                                <div class="error"></div>
                            </div>
                        </div>

                        <div class="uk-width-medium-1-3">
                            <div class="parsley-row">
                                <label for="email">Email Address<span class="req">*</span></label>
                                <input type="email" name="email" value="" id="email" data-parsley-required-message="This field is required." required class="md-input" data-parsley-type-message="Please enter the valid email address"
                                       data-parsley-remote="<?php echo $url;?>check_email"
                                       data-parsley-remote-options='{ "type": "POST", "dataType": "jsonp" }'
                                       data-parsley-remote-message="Account Already created"
                                       data-parsley-trigger="change"
                                />
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-3">
                            <div class="parsley-row">
                                <label for="phone">Phone Number<span class="req">*</span></label>
                                <input type="text" name="phone" value="" id="phone"  data-parsley-required-message="This field is required." required class="md-input"
                                       pattern="[0-9+\s]+"
                                       data-parsley-pattern-message="Only plus sign and number are allowed."
                                       maxlength="13"

                                />
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-3">

                            <div class="parsley-row">
                                <label for="video_url">Video URL<span class="req">*</span></label>
                                <input type="url" name="video_url" value="" id="video_url"
                                       data-parsley-required-message="This field is required."
                                       data-parsley-type-message="Please enter the valid url."
                                       required class="md-input" />
                                <div class="error"></div>
                            </div>

                        </div>

                        <div class="uk-width-medium-1-3">
                            <div class="parsley-row">
                                <label for="video_title">Video Title<span class="req">*</span></label>
                                <input type="text" name="video_title" value="" id="video_title"  data-parsley-required-message="This field is required." required class="md-input" />
                                <div class="error"></div>
                            </div>
                        </div>

                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="message" class="uk-form-label">Message</label>
                                <textarea id="message" name="message" class="md-input"></textarea>
                                <div class="error"></div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="get_thumb" id="img">

                            </div>
                        </div>


                       <!-- <div class="uk-width-medium-1-1" style="margin: 50px 0px 20px;">
                            <h4>Deal Information</h4>
                        </div>
                        <br/><br/>
-->
                       <!-- <div class="uk-width-medium-1-1">
                            <table class="uk-table">
                                <thead>
                                <tr>
                                    <th class="uk-width-1-3">Rating Point*</th>
                                    <th class="uk-width-1-1">Your Comments</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr >

                                    <td style="border-bottom: none;">
                                        <div class="parsley-row">
                                            <select  name="rating_point" id="rating" data-parsley-required-message="Rating point is required." required data-md-selectize>
                                                <option value="">Choose..</option>
                                                <?php
/*                                                for($i=1; $i<=10;$i++){*/?>
                                                    <option value="<?php /*echo $i;*/?>"><?php /*echo $i;*/?></option>
                                                <?php /*} */?>
                                            </select>
                                            <div class="error"></div>
                                        </div>
                                    </td>
                                    <td style="border-bottom: none;">
                                        <div class="parsley-row">
                                            <label for="rating_comments">Your Comments</label>
                                            <textarea id="comments" name="rating_comments" style="" class="md-input textarea" rows="4"></textarea>
                                            <div class="error"></div>
                                        </div>

                                    </td>

                                </tr>

                                </tbody>
                            </table>
                            <table class="uk-table" id="rating_detail_div" style="display: none;">
                                <thead>
                                <tr>
                                    <th class="uk-width-1-3">Deal Closing Date*</th>
                                    <th class="uk-width-1-1">Revenue Share - %*</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr style="border-bottom: none;">

                                    <td style="border-bottom: none;">


                                        <div class="parsley-row">
                                            <div class="uk-input-group">
                                                <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                                <div class="md-input-wrapper">
                                                    <label for="closing">Select date</label>
                                                    <input class="md-input" id="closing" data-uk-datepicker="{format:'YYYY-MM-DD',minDate:'<?php /*echo date('Y-m-d',strtotime(date('Y-m-d').' +3 Day'));*/?>'}" type="text" name="closing" data-parsley-required-message="Deal Closing Date is required." value="<?php /*echo date('Y-m-d',strtotime(date('Y-m-d').' +3 Day'));*/?>" readonly>
                                                    <span class="md-input-bar "></span>
                                                </div>
                                                <div class="error"></div>
                                            </div>
                                        </div>


                                    </td>
                                    <td style="border-bottom: none;">
                                        <div class="parsley-row">
                                            <input id="revenue" name="revenue" class="md-input"
                                                   data-parsley-required-message="Revenue Share is required."
                                                   data-parsley-type="integer"
                                                   data-parsley-type-message="Please enter the valid value."
                                                   data-parsley-range="[10, 100]"
                                                   data-parsley-range-message="Revenue Share must be between
                                                           10 to 100."
                                            />
                                            <div class="error"></div>
                                        </div>

                                    </td>

                                </tr>

                                </tbody>
                            </table>
                        </div>-->





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



