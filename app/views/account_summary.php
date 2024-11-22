<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 3/21/2018
 * Time: 12:22 PM
 */?>
<section class="author-page-contents">
    <div class="section-padding">
        <div class="container">
            
            <div class="row">
                
                <div class="col-md-3 col-xs-12">
                <?php include("common_files/dashboard-sidenav.php");?>
                </div>
                <div class="col-md-9 col-xs-12">    
                <div class="form-element">
                <select class="form-control" id="search" style="float: right; width: 29%;">
                    <option value=""
                        <?php $slug = $this->input->get('video');
                            if(empty($slug)){
                                echo 'selected="selected"';
                            }
                        ?>
                    >All Videos
                    </option>
                    <?php foreach($videos_title as $title){ ?>
                        <option value="<?php echo $title['slug'];?>" <?php
                            $slug = $this->input->get('video');

                            if($slug == $title['slug']) {echo 'selected="selected"';}?> >
                                <?php echo $title['title']?>
                        </option>
                    <?php } ?>
                </select>
                </div>
                <?php $this->load->view('common_files/profile_nav');?>
                <table id="datatable" class="display" cellspacing="0" width="100%">
                <thead class="thead-dark">
                <tr>
                    <th>Dates</th>
                    <th>Video Title</th>
                    <th>Source</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($ad_revenue as $revenue){
                        echo '<tr>';

                        echo '<td>';
                        echo $revenue['earning_date'];
                        echo '</td>';

                        echo '<td>';
                        echo $revenue['title'];
                        echo '</td>';

                        echo '<td>';
                        if($revenue['earning_type'] == 'licensing'){
                            echo $revenue['full_name'];
                        }else{
                            echo $revenue['sources'];
                        }

                        echo '</td>';

                        echo '<td>';
                        echo $revenue['symbol'].number_format($revenue['client_net_earning'],2);
                        echo '</td>';

                        echo '<td>';
                        if($revenue['paid'] == 1){
                            echo 'Paid';
                        }
                        else{
                            echo 'Unpaid';
                        }
                        echo '</td>';

                        echo '</tr>';
                    }
                    ?>
                </tbody>
                </table>
                </div>
            
            </div>

        </div>
    </div>
</section>
<script>
    var ad_revenue = 0;
    var license_revenue = 0;
</script>