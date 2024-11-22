
<style type="text/css">
    .box-set{
        float: left;
    }
    .md-card .md-card-head {
        border-bottom: 0px solid rgba(0,0,0,.12) !important;
    }
    .box-set .md-bg-grey-900 {
        margin-right: 40px;
    }
    .selectize-dropdown {
        margin-top: 0px !important;
    }
    .selectize-control.plugin-remove_button [data-value] .remove:after{
        content: ''!important;
    }
    .chosen-select{
        width: 100% !important;
    }
    .selectize-control {
        z-index: 99 !important;
    }
    .selectize-control.plugin-remove_button [data-value] {
        padding-right: 24px!important;
    }

    .selectize-control.plugin-remove_button [data-value] .remove {
        padding: 0px 10px 0 0 !important;
        font-size: 10px;
        top: -2px;
    }
    span.mandatory{
        color: #f5544d;
        font-size: 12px;
    }
    .uk-close:after {
        content: '' !important;
    }
</style>
<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><a href="<?php echo $url;?>mobile-app-videos">Mobile App Videos Management</a></li>
            <li><span>Edit Video (<?php echo $data->title;?>)</span></li>
        </ul>
    </div>
    <div id="page_content_inner">

        <h3 class="heading_b uk-margin-bottom"><?php echo $title;?></h3>



        <div class="md-card">

            <div class="md-card-content large-padding">
                <?php $total = count($raw_video);
                $i = 1;
                ?>
                <?php foreach ($raw_video as $video){
                    if($i == 1 || (($i%2) == 1)){
                        ?>
                        <div class="uk-grid" data-uk-grid-margin>
                    <?php }
                    ?>
                    <div class="uk-width-medium-3-6">
                        <div class="uk-width-medium-3-6 box-set">
                            <div class="md-card-head md-bg-grey-900">
                                <div class="uk-cover uk-position-relative uk-height-1-1 transform-origin-50" >
                                    <video style="width: 100%;height: 100%;" controls ><source src="<?php echo $root.$video['url'];?>">Your browser does not support HTML5 video.</video>
                                </div>
                            </div>
                        </div>

                        <div class="uk-width-medium-3-6 box-set">
                            <?php $file_data = $getid3->analyze('./../'.$video['url']);?>
                            <div><b> Size :</b> <?php echo formatBytes($file_data['filesize']);?></div>
                            <div><b> Quality :</b> <?php
                                if($file_data['video']['resolution_x'] < 1280 && $file_data['video']['resolution_y'] < 720){
                                    echo 'Low Quality';
                                }else if(($file_data['video']['resolution_x'] >= 1280 && $file_data['video']['resolution_y'] >= 720) && ($file_data['video']['resolution_x'] <= 1920 && $file_data['video']['resolution_y'] <= 1080)){

                                    echo '720P';
                                }else if(($file_data['video']['resolution_x'] >= 1920 && $file_data['video']['resolution_y'] >= 1080) && ($file_data['video']['resolution_x'] <= 2048 )){

                                    echo '1080P';
                                }else if(($file_data['video']['resolution_x'] >= 2048) && ($file_data['video']['resolution_x'] <= 4096 )){

                                    echo '2K';
                                }else if($file_data['video']['resolution_x'] >= 4096){

                                    echo '4K';
                                }else{
                                    echo 'Low Quality';
                                }

                                ?>
                            </div>

                            <div><b> Dimension :</b> <?php echo $file_data['video']['resolution_x'].' X '.$file_data['video']['resolution_y'];?></div>
                            <div><b> Frame Rate :</b> <?php echo $file_data['video']['frame_rate'];?></div>
                            <div><b> Duration :</b> <?php echo $file_data['playtime_string'];?></div>
                            <div>
                                <button class="delete_video_btn delete_btn" data-url="<?php echo $video['url'];?>" data-id="<?php echo $video['id'];?>" ><i class="material-icons">delete</i>Delete Video</button>


                            </div>


                        </div>


                    </div>





                    <?php if($i == $total || (($i%2) == 0)){?>
                        </div>
                    <?php }
                    $i++;
                }?>

                <div class="md-card-content large-padding">
                    <form id="form_validation3" class="uk-form-stacked">
                        <input type="hidden" name="id" value="<?php echo $data->id;?>" id="id" />



                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="title">Video Title<span class="req">*</span></label>
                                    <input type="text" name="title" value="<?php echo $data->title;?>" id="title"  data-parsley-required-message="This field is required." required class="md-input" />
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="title">Video URL<span class="req">*</span></label>
                                    <input type="text" name="url" value="<?php echo $data->url;?>" id="url"  data-parsley-required-message="This field is required." required class="md-input" />
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="title">Video Thumbnail<span class="req">*</span></label>
                                    <input type="text" name="thumbnail" value="<?php echo $data->thumbnail;?>" id="thumbnail"  data-parsley-required-message="This field is required." required class="md-input" />
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="title">Youtube ID<span class="req">*</span></label>
                                    <input type="text" name="youtube_id" value="<?php echo $data->youtube_id;?>" id="youtube_id"  data-parsley-required-message="This field is required." required class="md-input" />
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="description" class="uk-form-label">Description</label>
                                    <textarea id="description" name="description" cols="30" rows="10" class="md-input"><?php echo $data->description;?></textarea>
                                    <div class="error"></div>
                                </div>
                            </div>

                        </div>


                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="parent" class="uk-form-label">Category<span class="req">*</span></label>
                                    <select id="category_id" name="category_id[]" data-placeholder="Select Category" class="selectize-control" data-parsley-required-message="This field is required." multiple style="width:100%;">
                                        <?php
                                        $categ = explode(',',$data->category_id);
                                        $catrory = '';
                                        foreach($categories->result() as $cat){
                                            if(in_array($cat->id,$categ)){
                                                ?>
                                                <?php //echo $catrory = $cat->title;;?>
                                                <option selected value="<?php echo $cat->id;?>"><?php echo $cat->title;?></option>
                                            <?php }else{?>
                                                //$catrory = $cat->title;
                                                <option value="<?php echo $cat->id;?>"><?php echo $cat->title;?></option>
                                            <?php }
                                        } ?>
                                    </select>

                                    <div class="error"></div>
                                </div>
                            </div>
                            <!--<div class="uk-width-medium-1-2">
                                <div class="parsley-row">
                                    <label for="category_id" class="uk-form-label">Sub Categories<span class="req">*</span></label>
                                    <select id="category_id" name="category_id" data-parsley-required-message="This field is required." required data-md-selectize>
                                        <option value="">Choose..</option>

                                    </select>
                                    <div class="error"></div>
                                </div>
                            </div>-->


                        </div>


                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="tags" class="uk-form-label">Tags</label>
                                    <textarea id="tags" name="tags" class="md-input"><?php echo $data->tags;?></textarea>
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="parsley-row">

                                <select id="status" name="status" required data-parsley-required-message="This field is required." class="md-input" select >
                                    <option value="">Status*</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>

                                </select>
                            </div>
                        </div>


                        <!--<div class="uk-grid" data-uk-grid-margin >
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="question_video_taken" class="uk-form-label">Where was this taken? Country/City etc.</label>
                                    <input type="text" name="question_video_taken" value="<?php /*echo $data->question_video_taken;*/?>" id="question_video_taken"  data-parsley-required-message="This field is required." required class="md-input" />
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin >
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="question_when_video_taken" class="uk-form-label">When was this video Taken?</label>
                                    <input data-uk-datepicker="{format:'YYYY-MM-DD'}" type="text" name="question_when_video_taken" value="<?php /*echo $data->question_when_video_taken;*/?>" id="question_when_video_taken"  data-parsley-required-message="This field is required." required class="md-input" readonly />
                                    <div class="error"></div>

                                </div>
                            </div>
                        </div>-->
                        <!--<div class="uk-grid" data-uk-grid-margin >
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="question_video_information" class="uk-form-label">Any information such as names, locations, or any other interesting elements are important to us</label>
                                    <textarea id="question_video_information" name="question_video_information" class="md-input"><?php /*echo $data->question_video_information;*/?></textarea>
                                    <div class="error"></div>

                                </div>
                            </div>
                        </div>-->
                        <input type="hidden" name="is_category_verified" id="is_category_verified" value=""/>
                        <input type="hidden" name="is_tags_verified" id="is_tags_verified" value="" />
                        <input type="hidden" name="is_title_verified" id="is_title_verified" value="" />
                        <input type="hidden" name="is_description_verified" id="is_description_verified" value=""/>
                        <input type="hidden" name="video_verified" id="video_verified" value=""/>
                        <input type="hidden" name="is_orignal_video_verified" id="is_orignal_video_verified" value=""/>




                        <!--
                        <div class="uk-grid" data-uk-grid-margin>

                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="video_type_id" class="uk-form-label">Video Type<span class="req">*</span></label>
                                    <select id="video_type_id" name="video_type_id" data-parsley-required-message="This field is required." required data-md-selectize>
                                        <option value="">Choose..</option>
                                        <?php
                        /* $typ = '';
                         foreach($videoTypes->result() as $type){
                             if($data->video_type_id != $type->id){?>
                             ?>
                             <option value="<?php echo $type->id;?>"><?php echo $type->title;?></option>
                         <?php }else{
                                 $typ = $type->title;
                             }
                         }*/
                        ?>
                                    </select>
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-grid" id="upload" data-uk-grid-margin style="display:none;">
                            <div class="uk-width-medium-1-1">
                                <div id="file_upload-drop" class="uk-file-upload">
                                <p class="uk-text">Drop file to upload</p>
                                <p class="uk-text-muted uk-text-small uk-margin-small-bottom">or</p>
                                <a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file"></a>
                            </div>
                            <div id="file_upload-progressbar" class="uk-progress uk-hidden">
                                <div class="uk-progress-bar" style="width:0">0%</div>
                            </div>
                                <div class="error"></div>
                            </div>

                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="url" class="uk-form-label">Embed Code/URL</label>
                                    <textarea id="url" name="url" class="md-input" data-parsley-required-message="This field is required." required><?php echo $data->url;?></textarea>
                                    <div class="error"></div>
                                </div>
                            </div>

                        </div>
                        -->
                        <!-- <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="md-card">
                                    <div class="md-card-content">
                                        <h3 class="heading_a uk-margin-small-bottom">
                                            Video Thumbnail
                                        </h3>
                                        <input type="file" id="input-file-b" name="thumb" class="dropify" data-default-file="<?php /*echo $root.$data->thumbnail;*/?>"/>
                                        <div class="error"></div>
                                    </div>
                                </div>
                            </div>
                        </div>-->

                        <br/>
                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <button type="submit" class="md-btn md-btn-primary check">Save</button>
                            </div>
                        </div>
                    </form>
                    <br>
                </div>

            </div>
        </div>

    </div>

    <div class="uk-modal" id="add_model">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title">Checklist</h3>
            </div>

            <div class="md-card-content large-padding">
                <form id="" class="uk-form-stacked">
                    <input type="hidden" name="lead_id" id="lead_id" value="">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <input type="checkbox" name="category_verified" id="category_verified" value="1" data-md-icheck />
                            <label for="is_category_verified" class="inline-label"><b>Is Category Have been verified?</b></label>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <input type="checkbox" name="tags_verified" id="tags_verified" value="1" data-md-icheck />
                            <label for="is_tags_verified" class="inline-label"><b>Is Tags Have been verified?</b></label>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <input type="checkbox" name="title_verified" id="title_verified" value="1" data-md-icheck />
                            <label for="is_title_verified" class="inline-label"><b>Is Title have been verified?</b></label>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <input type="checkbox" name="description_verified" id="description_verified" value="1" data-md-icheck />
                            <label for="description_verified" class="inline-label"><b>Is Description have been verified?</b></label>
                        </div>
                    </div><div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <input type="checkbox" name="orignal_video_verified" id="orignal_video_verified" value="1" data-md-icheck />
                            <label for="orignal_video_verified" class="inline-label"><b>Is Orignal Video have been verified?</b></label>
                        </div>
                    </div>

                </form>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="verified_form" class="md-btn md-btn-flat md-btn-flat-primary ">Vierified</button>
            </div>
        </div>
    </div>

    <script>
        var edit_data = '<?php echo $edit_data;?>';
        var caterory = '<?php echo $catrory;?>';
        var usr = '<?php //echo $usr;?>';
        var sts = '<?php //echo $status;?>';
        var ukey = '';
        //  var typ = '<?php //echo $typ;?>';
    </script>
