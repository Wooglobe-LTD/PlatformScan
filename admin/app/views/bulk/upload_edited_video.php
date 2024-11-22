<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 5/4/2018
 * Time: 3:12 PM
 */
?>
<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span>Videos Bulk Uploading</span></li>
        </ul>
    </div>
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-content large-padding">
                <form id="edited_video" class="uk-form-stacked">
                    <div class="uk-grid" data-uk-grid-margin>
                        <h3 class="heading_a">
                            Videos upload
                            <span class="sub-heading">Allow users to upload videos through a CSV file form element or a placeholder area</span>
                        </h3>

                        <div class="uk-width-1-1">
                            <!--<input type="file" name="csv" class="dropify_my" id="dropify_my" data-allowed-file-extensions="xls" data-show-errors="true"/>-->
                            <input type="file" name="csv" id="dropify_my" />
                            <div class="error"></div>
                        </div>
                    </div>

                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            <button type="submit" class="md-btn md-btn-primary">Upload</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
