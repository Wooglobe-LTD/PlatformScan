<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 4/2/2018
 * Time: 12:11 PM
 */
?>
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
                            >My All Videos
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
                            <th>Date</th>
                            <th>Video Title</th>
                            <th>Source</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($license_revenue as $revenue){
                            echo '<tr>';

                            echo '<td>';
                            echo $revenue['earning_date'];
                            echo '</td>';

                            echo '<td>';
                            echo $revenue['title'];
                            echo '</td>';

                            echo '<td>';
                            echo $revenue['full_name'];
                            echo '</td>';

                            echo '<td>';
                            echo '$'.$revenue['earning_amount'];
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
