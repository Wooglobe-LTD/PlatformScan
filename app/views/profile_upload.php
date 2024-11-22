<?php
/**
 * Created by PhpStorm.
 * User: T3500
 * Date: 1/29/2018
 * Time: 12:44 PM
 */
?>

<?php $this->load->view('common_files/profile_header');?>





<section class="author-page-contents">
    <div class="section-padding">
        <div class="container">
            <?php $this->load->view('common_files/profile_nav');?>

            <div class="author-contents">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="about-author">
                            <div class="upload-video">
                                <form action="#" class="upload-form" id="upload">
                                    <div class="col-md-12">
                                        <p class="form-element">
                                            <label for="title">Video Title</label>
                                            <input type="text" id="title" class="title form-control" placeholder="Video Title"
                                                   data-parsley-required-message="Video Title field is required."
                                                   required
                                                   tabindex="1"
                                            >
                                        </p>
                                    </div>
                                    <div class="col-md-12">
                                        <p class="form-element">
                                            <label for="category">Category</label>
                                            <select name="category_id" class="category_id form-control" id="category_id"
                                                    data-parsley-required-message="Category field is required."
                                                    required
                                                    tabindex="2"
                                            >
                                                <option value="">Select a Category</option>
                                                <?php $categoris = parent_categories();
                                                foreach ($categoris->result() as $parent){?>
                                                    <option value="<?php echo $parent->id;?>"><?php echo $parent->title;?></option>
                                                <?php } ?>
                                            </select>
                                        </p>
                                    </div>
                                    <div class="col-md-12">

                                        <p class="form-element">
                                            <label for="privacy">Sub Category</label>
                                            <select name="parent_id" class="parent_id form-control" id="parent_id" tabindex="3">
                                                <option value="">Select a Sub Category</option>
                                            </select>
                                        </p>

                                    </div>
                                    <div class="col-md-12">
                                        <p class="form-element">
                                            <label for="tags">Tags</label>
                                            <input type="text" id="tags" class="tag form-control" placeholder="Enter tags and separate them with a coma">
                                        </p>
                                    </div>
                                    <div class="col-md-12">
                                        <p class="form-element file-type">
                                            <label for="thumb">Video Thumbnail</label>
                                            <input type="file" name="thumb" class="file form-control dropify" id="thumb"
                                                   data-show-remove="false"
                                                   data-errors-position="outside"
                                                   data-allowed-file-extensions="jpg jpeg png gif"
                                                   data-max-file-size="3M"
                                                   data-min-width="50"
                                                   data-max-width="280"
                                                   data-min-height="50"
                                                   data-max-height="280"
                                                   tabindex="4"
                                            >
                                            <span>Supported format jpg, jpeg, png, gif; Max File size 3 MB</span>
                                        </p>
                                    </div>
                                    <div class="col-md-12">

                                        <p class="form-element">
                                            <label for="video_type_id">Video Type</label>
                                            <select name="video_type_id" class="video_type_id form-control" id="video_type_id"
                                                    data-parsley-required-message="Video Type field is required."
                                                    required
                                                    tabindex="5"
                                            >
                                                <option value="">Select a Video Type</option>
                                                <?php foreach ($videosTypes->result() as $type){?>
                                                    <option value="<?php echo $type->id;?>"><?php echo $type->title;?></option>

                                                <?php } ?>
                                            </select>
                                        </p>

                                    </div>
                                    <div class="col-md-12" id="video-div" style="display: none;">
                                        <label for="file">Video</label>
                                        <div id="dZUpload" class="dropzone needsclick">
                                            <div class="dz-default dz-message">
                                                Drop files here or click to upload.
                                            </div>
                                        </div>
                                        <span>Supported format mp4, avi, flv, 3gp, mkv; Max File size 50 MB</span>
                                        <div class="dz-default dz-error">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <p class="form-element">
                                            <label for="tags">Video URL</label>
                                            <input type="text" id="url" class="url form-control" placeholder="Video URL" disabled="disabled"
                                                   data-parsley-required-message="Video URL field is required."
                                                   required
                                                   tabindex="6"
                                            >
                                        </p>
                                    </div>
                                    <div class="col-md-12">
                                        <p class="form-element file-type">
                                            <label for="description">Description</label>
                                            <textarea name="description" class="description form-control" id="description" placeholder="Description"></textarea>
                                        </p>
                                    </div>



                                    <div class="col-md-12">
                                        <input type="submit" value="Upload Now" class="submit" name="submit">
                                    </div>
                                </form>
                            </div><!-- /.upload-video -->
                        </div><!-- /.about-author -->
                    </div>

                    <?php //$this->load->view('common_files/profile_right');?>
                </div>
            </div>
        </div><!-- /.container -->
    </div><!-- /.section-padding -->
</section><!-- /.author-page-contents -->

<?php $timestamp = time();?>
<script>
   var timestamp = '<?php echo $timestamp;?>';
   var token = '<?php echo md5('unique_salt' . $timestamp);?>';

</script>