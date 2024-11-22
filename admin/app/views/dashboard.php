<style>

  .tab_content {
    font-family: sans-serif;
    background: #f6f9fa;
  }

  .heading1 {
    color: #ccc;
    text-align: center;
  }

  /*Fun begins*/
  .tab_container {
    width: 90%;
    margin: 0 auto;
    padding-top: 50px;
    position: relative;
  }

  .tab_content {
      background: #eee;
  }

  section {
    clear: both;
    padding-top: 10px;
    display: none;
  }

  .tab_label {
    font-weight: 700;
    font-size: 18px;
    display: block;
    float: left;
    width: 20%;
    padding: 1em;
    color: #888;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    background: #f0f0f0;
  }

  #tab1:checked ~ #content1,
  #tab2:checked ~ #content2 {
    display: block;
    padding: 20px;
    background: #fff;
    color: #999;
    border-bottom: 2px solid #2196f3;
  }

  .tab_container .tab-content p,
  .tab_container .tab-content h3 {
    -webkit-animation: fadeInScale 0.7s ease-in-out;
    -moz-animation: fadeInScale 0.7s ease-in-out;
    animation: fadeInScale 0.7s ease-in-out;
  }
  .tab_container .tab-content h3  {
    text-align: center;
  }

  .tab_container [id^="tab"]:checked + label {
    background: #e0e0e0;
    color: #2196f3;
    box-shadow: inset 0 2px #2196f3;
  }

  .tab_container [id^="tab"]:checked + label .fa {
    color: #2196f3;
  }

  label .fa {
    font-size: 1.3em;
    margin: 0 0.4em 0 0;
  }

  /*Media query*/
  @media only screen and (max-width: 900px) {
    label span {
      display: none;
    }
    
    .tab_container {
      width: 98%;
    }
  }

  /*Content Animation*/
  @keyframes fadeInScale {
    0% {
      transform: scale(0.9);
      opacity: 0;
    }
    
    100% {
      transform: scale(1);
      opacity: 1;
    }
  }

  .no_wrap {
    text-align:center;
    color: #0ce;
  }
  .link {
    text-align:center;
  }
  .chartCard {
    width: 100%;
    height: calc(50vh - 40px);
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .chartBox {
    width: 45%;
    padding: 20px;
    border-radius: 20px;
    border: solid 3px rgba(54, 162, 235, 1);
    background: white;
    margin: 20px;
  }
  .chartCardEnl {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .chartBoxEnl {
    width: 100%;
    padding: 20px;
    border-radius: 20px;
    border: solid 3px rgba(54, 162, 235, 1);
    background: white;
    margin: 0;
  }
  label {
    font-weight: 700;
    display: block;
    float: left;
    padding: 0;
    color: #888;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
  }
  .dash_tabs {
    display: none;
  }
  .chart_modal {
    width: 100%;
    height: 90%;
  }
  .modal_close {
    position: absolute;
    right: 24px;
    top: 24px;
  }
</style>
<div id="page_content">
  <div id="page_content_inner">
      <h1 class="heading1">Welcome Back!</h1>
  </div>

  <?php if(role_permitted_html(false)) { ?>
    <div class="tab_content">
        <div class="tab_container">
            <input id="tab1" type="radio" name="dash_tabs" class="dash_tabs" checked>
            <label for="tab1" class="tab_label"><i class="fa fa-caret-square-o-right"></i><span>Acquisition</span></label>

            <!-- <input id="tab3" type="radio" name="tabs">
            <label for="tab3"><i class="fa fa-bar-chart-o"></i><span>Services</span></label> -->

            <section id="content1" class="tab-content">
                <div class="md-card uk-margin-medium-bottom" style="box-shadow:none;">
                    <div class="md-card-content">
                        <div>
                            <form id="form_search" class="uk-form-stacked">
                                <div style="display:flex;">
                                    <div class="uk-width-medium-1-2">
                                        <div class="uk-width-medium" style="display:inline-block;width: 49%;">
                                            <div class="parsley-row">
                                                <div class="md-input-wrapper">
                                                    <label for="date_from">Date From</label>
                                                    <input class="md-input" id="date_from" style="text-align:center;" data-uk-datepicker="{format:'YYYY-MM-DD',maxDate:''}" type="text" name="date_from" data-parsley-required-message="" value="<?php echo date("Y-m-d", strtotime(date("Y-m"))) ?>" readonly>
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-width-medium" style="display:inline-block;width: 49%;">
                                            <div class="parsley-row">
                                                <div class="md-input-wrapper">
                                                    <label for="date_to">Date To</label>
                                                    <input class="md-input" id="date_to" style="text-align:center;" data-uk-datepicker="{format:'YYYY-MM-DD',maxDate:''}" type="text" name="date_to" data-parsley-required-message="" value="<?php echo date("Y-m-d") ?>" readonly>
                                                    <span class="md-input-bar "></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="uk-width-medium-1-5" style="position:absolute; right:0;">
                                        <div class="parsley-row">
                                            <div class="parsley-row">
                                                <select id="publish_status" name="publish_status[]" data-md-selectize>
                                                        <option value="1" selected="selected">Leads</option>
                                                        <option value="2">Not Rated</option>
                                                        <option value="3">Awaiting Approval</option>
                                                        <option value="4">Acquired Leads</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br/><br/>
                                <div class="uk-grid">
                                    <div class="uk-width-1-1">
                                        <button type="button" id="search" class="md-btn md-btn-primary check">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="chartCard">
                    <div class="chartBox">
                      <div class="sub-grid btm-fx">
                          <p class="scrum_task_info" style="text-align:right;"><a id="chart1-enlarge" class="md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light"><i class="uk-icon-expand uk-icon-small"></i></a></p>
                      </div>
                      <canvas id="vl_chart1"></canvas>
                    </div>
                    <div class="chartBox" label=<?php echo $staff_name; ?>>
                      <div class="sub-grid btm-fx">
                          <p class="scrum_task_info" style="text-align:right;"><a id="chart2-enlarge" class="md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light"><i class="uk-icon-expand uk-icon-small"></i></a></p>
                      </div>
                      <canvas id="vl_chart2"></canvas>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="uk-modal" id="chart1-modal">
      <div class="uk-modal-dialog chart_modal">
          <div class="uk-modal-header uk-text-center">
              <h3 class="uk-modal-title"> Time Chart </h3>
                <button class="btn btn-success btn-cancel uk-modal-close modal_close" id="cancel-closing"><i class="material-icons">close</i></button>
          </div>
          <div class="chartCardEnl">
              <div class="chartBoxEnl">
                  <canvas id="chart1-enlarged"></canvas>
              </div>
          </div>
      </div>
    </div>
    <div class="uk-modal" id="chart2-modal">
      <div class="uk-modal-dialog chart_modal">
          <div class="uk-modal-header uk-text-center">
              <h3 class="uk-modal-title"> Time Chart </h3>
                <button class="btn btn-success btn-cancel uk-modal-close modal_close" id="cancel-closing"><i class="material-icons">close</i></button>
          </div>
          <div class="chartCardEnl">
              <div class="chartBoxEnl">
                  <canvas id="chart2-enlarged"></canvas>
              </div>
          </div>
      </div>
    </div>
  <?php } ?>
</div>
