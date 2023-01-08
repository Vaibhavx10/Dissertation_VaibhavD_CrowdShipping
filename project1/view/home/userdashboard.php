<?php include('view/layout/header.php'); ?>
<style>

.tableBodyScroll {
        height:260px;
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
                                <li><a href="#">$user[0] Dashboard</a></li>
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
                <div class="col-lg-6 ">
                                <div class="card alert tableBodyScroll">
                                    <div  class="card-body ">
                                        <div class="table-responsive ">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Pobox</th>
                                                        <th>Delivery Address</th>
                                                        <th>Delivery Eir</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                
                                                   <?php foreach($pobox as $po ){?>
                                                        <tr>
                                                            <th><?php echo $po['PObox_id'];?></th>
                                                            <th><?php echo $po['delivery_address'];?></th>
                                                            <th><?php echo $po['delivery_eir'];?></th>
                                                        </tr>
                                                    <?php }?>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 ">
                                <div class="card alert tableBodyScroll">
                                    <div  class="card-body ">
                                        <div class="table-responsive ">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Order Id</th>
                                                        <th>Pobox</th>
                                                        <th>Order Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                
                                                   <?php foreach($orders as $item ){?>
                                                        <tr>
                                                            <th><?php echo $item['order_id'];?></th>
                                                            <th><?php echo $item['PObox_id'];?></th>
                                                            <th><?php 
                                                                if($item['order_status'] == 'WC'){
                                                                    echo 'Waiting for Collection';
                                                                }else if($item['order_status'] == 'WI'){
                                                                    echo 'Waiting for Inbox';
                                                                }else if($item['order_status'] == 'C'){
                                                                    echo 'Order Collected';
                                                                }else if($item['order_status'] == 'T'){
                                                                    echo 'Transit';
                                                                }else if($item['order_status'] == 'TC'){
                                                                    echo 'Transit Collection';
                                                                }else if($item['order_status'] == 'CFW'){
                                                                    echo 'Collected From warehouse';
                                                                }
                                                            ?></th>
                                                        </tr>
                                                    <?php }?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="card alert">
                                    <div class="card-header">
                                        <h4 class="m-l-5">Subscription Details </h4>
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
                                                        <div class="stats-digit">Subscription Plan Id</div>
                                                        <div class="stats-text"><?php if($user[0]['subscription_status'] == 'A'){echo $user[0]['subscription_plan_id'];}else{echo "You don't have active Sucscription";}?></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 border-bottom border-left">
                                                    <div class="stats-content">
                                                        <div class="stats-digit">Subscription Plan Name</div>
                                                        <div class="stats-text"><?php if($user[0]['subscription_status'] == 'A'){echo $user[0]['plan_name'];}else{echo "Nil";}?></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 border-bottom  border-left">
                                                    <div class="stats-content">
                                                        <div class="stats-digit">Subscription Start Date</div>
                                                        <div class="stats-text"><?php if($user[0]['subscription_status'] == 'A'){echo $user[0]['stdate'];}else{echo "Nil";}?></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 border-bottom">
                                                    <div class="stats-content">
                                                        <div class="stats-digit">Subscription End Date</div>
                                                        <div class="stats-text"><?php if($user[0]['subscription_status'] == 'A'){echo $user[0]['stdate'];}else{echo "Nil";}?></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 border-bottom border-left">
                                                    <div class="stats-content">
                                                        <div class="stats-digit">Max Transaction</div>
                                                        <div class="stats-text"><?php if($user[0]['subscription_status'] == 'A'){echo $user[0]['max_transactions'];}else{echo "Nil";}?></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 border-bottom  border-left">
                                                    <div class="stats-content">
                                                        <div class="stats-digit">Transaction Processed</div>
                                                        <div class="stats-text"><?php if($user[0]['subscription_status'] == 'A'){echo $used;}else{echo "Nil";}?></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="stats-content">
                                                        <div class="stats-digit">Remaining Days</div>
                                                        <div class="stats-text"><?php if($user[0]['subscription_status'] == 'A'){echo $user[0]['remain'];}else{echo "Nil";}?></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 border-left">
                                                    <div class="stats-content">
                                                        <div class="stats-digit">Subscription Status</div>
                                                        <div class="stats-text"><?php 
                                                                        if($user[0]['subscription_status'] == 'N'){
                                                                            echo  'Inactive ';
                                                                        }else if($user[0]['subscription_status'] == 'A'){
                                                                            echo ' Active';
                                                                        }else if($user[0]['subscription_status'] == 'E'){
                                                                            echo ' Expired';
                                                                        }else if($user[0]['subscription_status'] == 'T'){
                                                                            echo ' Limit Exceeded';
                                                                        }else{
                                                                            echo 'No Active Subscription';
                                                                        }
                                                                    ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 border-left">
                                                    <div class="stats-content">
                                                        <div class="stats-digit"></div>
                                                        <div class="stats-text"></div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                  
                  
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