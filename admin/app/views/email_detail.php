<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 5/10/2018
 * Time: 12:49 PM
 */
?>
<div id="page_content">
    <div id="page_content_inner">

        <div class="md-card">
            <div class="md-card-content">
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-1-1">
                        <h3>To:</h3>
                        <?php echo $email->to_email;?>
                    </div>
                </div>
            </div>
        </div>
        <div class="md-card">
            <div class="md-card-content">
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-1-1">
                        <h3>From:</h3>
                        <?php echo $email->from_email;?>
                    </div>
                </div>
            </div>
        </div>
        <div class="md-card">
            <div class="md-card-content">
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-1-1">
                        <h3>Subject:</h3>
                        <?php echo $email->subject;?>
                    </div>
                </div>
            </div>
        </div>
        <div class="md-card">
            <div class="md-card-content">
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-1-1">
                        <?php echo $email->message;?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
