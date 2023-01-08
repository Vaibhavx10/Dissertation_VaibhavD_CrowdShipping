<?php include('view/layout/header.php'); ?>
<style>

.tableBodyScroll {
        height:450px;
        overflow-y: scroll;
        overflow-x: hidden;
    }
</style>
<div class="content-wrap">
    <div class="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 p-r-0 title-margin-right">
                    <div class="page-header">
                        <div class="page-title">
                            <h1>Hello, <span>Welcome Here</span></h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="#">Admin Dashboard</a></li>
                                <li class="active">Home</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
            </div>
            <!-- /# row -->
            <div id="main-content">
                <div class="row">

                <div class="col-lg-12">
                                <div class="card alert">
                                    <div class="card-header">
                                        <h4 class="m-l-5">Users Details </h4>
                                        <div class="card-header-right-icon">
                                            <ul>
                                                <li><i class="ti-reload"></i></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="media-stats-content text-center">
                                            <div class="row">
                                                
                                                <div class="col-lg-4 border-bottom">
                                                    <div class="stats-content">
                                                        <div class="stats-digit"><?php echo $user[0]['activeuser'];?></div>
                                                        <div class="stats-text">Active Users</div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 border-bottom border-left">
                                                    <div class="stats-content">
                                                        <div class="stats-digit"><?php echo $driver[0]['cnt'];?></div>
                                                        <div class="stats-text">Active Drivers</div>
                                                    </div>
                                                </div>
                                               
                                                <div class="col-lg-4 border-bottom  border-left">
                                                    <div class="stats-content">
                                                        <div class="stats-digit"><?php echo $user[0]['activesubscriptionusers'];?></div>
                                                        <div class="stats-text">Active Users With Subscription</div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="stats-content">
                                                        <div class="stats-digit"><?php echo $user[0]['consumedsubscriptionusers'];?></div>
                                                        <div class="stats-text">Users With Consumed Subscription</div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 border-left">
                                                    <div class="stats-content">
                                                        <div class="stats-digit"><?php echo $user[0]['expiredsubscriptionusers'];?></div>
                                                        <div class="stats-text">Users With Expired Subscription</div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 border-left">
                                                    <div class="stats-content">
                                                        <div class="stats-digit"><?php echo $user[0]['nosubscriptionusers'];?></div>
                                                        <div class="stats-text">Users With No Subscription</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                        <div class="card">
                            <div class="stat-widget-eight">
                                <div class="stat-header">
                                    <div class="header-title pull-left">Total Truck Miles [KM]</div>
                                    
                                </div>
                                <div class="clearfix"></div>
                                <div class="stat-content">
                                    <div class="pull-left">
                                        <i class="ti-arrow-up color-success"></i>
                                        <span class="stat-digit"> <?php if(isset($net_transaction[0]['net_truck_miles'])){ echo $net_transaction[0]['net_truck_miles'];}else{echo 0;} ?></span>
                                    </div>
                                    <div class="pull-right">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-primary w-70" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="stat-widget-eight">
                                <div class="stat-header">
                                    <div class="header-title pull-left">Total CO2 Saved [Kg CO2/l-km]</div>
                                    
                                </div>
                                <div class="clearfix"></div>
                                <div class="stat-content">
                                    <div class="pull-left">
                                        <i class="ti-arrow-up color-success"></i>
                                        <span class="stat-digit"> <?php if(isset($net_transaction[0]['net_truck_miles'])){ echo $net_transaction[0]['net_co2_saved'];}else{echo 0;} ?></span>
                                    </div>
                                    <div class="pull-right">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-success w-70" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="stat-widget-eight">
                                <div class="stat-header">
                                    <div class="header-title pull-left">Total Fuel Saved</div>
                                    
                                </div>
                                <div class="clearfix"></div>
                                <div class="stat-content">
                                    <div class="pull-left">
                                        <i class="ti-arrow-down color-danger"></i>
                                        <span class="stat-digit"> <?php if(isset($net_transaction[0]['net_truck_miles'])){ echo $net_transaction[0]['net_truck_miles'] * $fuelequivalent;}else{echo 0;} ?></span>
                                    </div>
                                    <div class="pull-right">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-warning w-70" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    
                    <!-- /# column -->
                    <div class="col-lg-12">
                        <div class="row">
                            


                            <div class="col-lg-12 ">
                                <div class="card alert tableBodyScroll">
                                    <div  class="card-body ">
                                        <b>Waition For Collection </b>
                                        <div class="table-responsive ">
                                            <table id = "myTable" class="table table-hover">
                                                <thead>
                                                    <tr>
                                                    <th>Sl No</th>
                                                        <th>User Id</th>
                                                        <th>Pobox Id</th>
                                                        <th>Driver Id</th>
                                                        <th>Order Status</th>
                                                    </thead>
                                                <tbody>
                                                 
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                 
                        
                        </div>
      
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card alert">
                            <div class ="card-header">
                                <h3>Monthly Payout Chart</h3>
                            </div>
                            <div class="card-body">
                                <div class="ct-bar-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                   
                    <!-- /# column -->
                   
                    
                </div>
              
                <!-- /# row -->
                
            </div>
        </div>
    </div>
</div>

<div id="search">
    <button type="button" class="close">×</button>
    <form>
        <input type="search" value="" placeholder="type keyword(s) here" />
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>
<?php include('view/layout/script.php'); ?>
<?php include('view/layout/footer.php'); ?>

<script>
        

 

  

    var table;

    var now = new Date();
        var jsDate = now.getDate() + '-' + (now.getMonth() + 1) + '-' + now.getFullYear();
        table = $("#myTable").DataTable({
            "processing": true,
            'dom': "<'row'<'col-sm-6'l><'col-sm-2  text-right 'B><'col-sm-4 pull-right'f >>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            "buttons": [
                {
                    extend: 'excelHtml5',
                    className: 'btn btn-sm btn-primary',
                    filename: 'Items' + jsDate,
                    text: '<i class="fa fa-file-excel-o"></i>',
                    messageTop: "Printed Date-  " + jsDate,
                    titleAttr: 'Excel',
                    customize: function (xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        $('c[r=A1] t', sheet).text('Items');
                        $('c[r=A2] t', sheet).text("");
                    }
                }
            ],
            "serverSide": true,
            "columnDefs": [{
                    "targets": 'no-sort',
                    "orderable": false,
                    "order": []
                }],
            "ajax": {
                "url": "<?php echo $base_url ?>pending_orders_list",
                "type": "POST",
                "data": function (data) {

                }
            }
        });
        


  var labels = '[';
    var series = '';
    $.ajax({
      url: "<?php echo $base_url; ?>order/getchartdetails",
      async:false,
      success: function (result) {
        $.each(JSON.parse(result), function(i, itemsingle) {
          if(labels !='['){
            labels = labels + ',';
            series = series + ',';
          }
          series = series +itemsingle.payout;
          labels = labels + '"'+itemsingle.monthname.substring(0,3)+'-'+itemsingle.year+'"';
          // labels.push(itemsingle.monthname);
          // series.push(itemsingle.payout);
        });
      }
                              
  });
  var labels =labels + ']';
  var series = '[['+series+']]';
  var lab = JSON.parse(labels);
  var series = JSON.parse(series);
        var data = {
        labels: lab,
        series: series
    };

    var options = {
        seriesBarDistance: 10
    };

    var responsiveOptions = [
  ['screen and (max-width: 640px)', {
            seriesBarDistance: 5,
            axisX: {
                labelInterpolationFnc: function (value) {
                    return value[0];
                }
            }
  }]
];

    new Chartist.Bar('.ct-bar-chart', data, options, responsiveOptions);

</script>
