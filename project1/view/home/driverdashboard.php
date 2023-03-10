<?php include('view/layout/header.php'); ?>
<style>

.tableBodyScroll {
        height:450px;
        overflow-y: scroll;
        overflow-x: hidden;
    }
</style>

<style>

.box-title {
        text-transform: capitalize;
    }
	[type="checkbox"]:not(:checked),[type="checkbox"]:checked{
    position:absolute;
    left:-9999px;
    opacity:0
}
[type="checkbox"]+label{
    position:relative;
    padding-left:35px;
    cursor:pointer;
    display:inline-block;
    height:25px;
    line-height:25px;
    font-size:1rem;
    -webkit-user-select:none;
    -moz-user-select:none;
    -khtml-user-select:none;
    -ms-user-select:none
}
[type="checkbox"]+label:before,[type="checkbox"]:not(.filled-in)+label:after{
    content:'';
    position:absolute;
    top:0;
    left:0;
    width:18px;
    height:18px;
    z-index:0;
    border:2px solid #5a5a5a;
    border-radius:1px;
    margin-top:2px;
    transition:.2s
}
[type="checkbox"]:not(.filled-in)+label:after{
    border:0;
    -webkit-transform:scale(0);
    transform:scale(0)
}
[type="checkbox"]:not(:checked):disabled+label:before{
    border:none;
    background-color:rgba(0,0,0,0.26)
}
[type="checkbox"].tabbed:focus+label:after{
    -webkit-transform:scale(1);
    transform:scale(1);
    border:0;
    border-radius:50%;
    box-shadow:0 0 0 10px rgba(0,0,0,0.1);
    background-color:rgba(0,0,0,0.1)
}
[type="checkbox"]:checked+label:before{
    top:-4px;
    left:-5px;
    width:12px;
    height:22px;
    border-top:2px solid transparent;
    border-left:2px solid transparent;
    border-right:2px solid #26a69a;
    border-bottom:2px solid #26a69a;
    -webkit-transform:rotate(40deg);
    transform:rotate(40deg);
    -webkit-backface-visibility:hidden;
    backface-visibility:hidden;
    -webkit-transform-origin:100% 100%;
    transform-origin:100% 100%
}
[type="checkbox"]:checked:disabled+label:before{
    border-right:2px solid rgba(0,0,0,0.26);
    border-bottom:2px solid rgba(0,0,0,0.26)
}
[type="checkbox"]:indeterminate+label:before{
    top:-11px;
    left:-12px;
    width:10px;
    height:22px;
    border-top:none;
    border-left:none;
    border-right:2px solid #26a69a;
    border-bottom:none;
    -webkit-transform:rotate(90deg);
    transform:rotate(90deg);
    -webkit-backface-visibility:hidden;
    backface-visibility:hidden;
    -webkit-transform-origin:100% 100%;
    transform-origin:100% 100%
}
[type="checkbox"]:indeterminate:disabled+label:before{
    border-right:2px solid rgba(0,0,0,0.26);
    background-color:transparent
}
[type="checkbox"].filled-in+label:after{
    border-radius:2px
}
[type="checkbox"].filled-in+label:before,[type="checkbox"].filled-in+label:after{
    content:'';
    left:0;
    position:absolute;
    transition:border .25s, background-color .25s, width .20s .1s, height .20s .1s, top .20s .1s, left .20s .1s;
    z-index:1
}
[type="checkbox"].filled-in:not(:checked)+label:before{
    width:0;
    height:0;
    border:3px solid transparent;
    left:6px;
    top:10px;
    -webkit-transform:rotateZ(37deg);
    transform:rotateZ(37deg);
    -webkit-transform-origin:20% 40%;
    transform-origin:100% 100%
}
[type="checkbox"].filled-in:not(:checked)+label:after{
    height:20px;
    width:20px;
    background-color:transparent;
    border:2px solid #5a5a5a;
    top:0px;
    z-index:0
}
[type="checkbox"].filled-in:checked+label:before{
    top:0;
    left:1px;
    width:8px;
    height:13px;
    border-top:2px solid transparent;
    border-left:2px solid transparent;
    border-right:2px solid #fff;
    border-bottom:2px solid #fff;
    -webkit-transform:rotateZ(37deg);
    transform:rotateZ(37deg);
    -webkit-transform-origin:100% 100%;
    transform-origin:100% 100%
}
[type="checkbox"].filled-in:checked+label:after{
    top:0;
    width:20px;
    height:20px;
    border:2px solid #26a69a;
    background-color:#26a69a;
    z-index:0
}
[type="checkbox"].filled-in.tabbed:focus+label:after{
    border-radius:2px;
    border-color:#5a5a5a;
    background-color:rgba(0,0,0,0.1)
}
[type="checkbox"].filled-in.tabbed:checked:focus+label:after{
    border-radius:2px;
    background-color:#26a69a;
    border-color:#26a69a
}
[type="checkbox"].filled-in:disabled:not(:checked)+label:before{
    background-color:transparent;
    border:2px solid transparent
}
[type="checkbox"].filled-in:disabled:not(:checked)+label:after{
    border-color:transparent;
    background-color:#BDBDBD
}
[type="checkbox"].filled-in:disabled:checked+label:before{
    background-color:transparent
}
[type="checkbox"].filled-in:disabled:checked+label:after{
    background-color:#BDBDBD;
    border-color:#BDBDBD
}
.switch,.switch *{
    -webkit-user-select:none;
    -moz-user-select:none;
    -khtml-user-select:none;
    -ms-user-select:none
}
.switch label{
    cursor:pointer
}
.switch label input[type=checkbox]{
    opacity:0;
    width:0;
    height:0
}
.switch label input[type=checkbox]:checked+.lever{
    background-color:#84c7c1
}
.switch label input[type=checkbox]:checked+.lever:after{
    background-color:#26a69a;
    left:24px
}
.switch label .lever{
    content:"";
    display:inline-block;
    position:relative;
    width:40px;
    height:15px;
    background-color:#818181;
    border-radius:15px;
    margin-right:10px;
    transition:background 0.3s ease;
    vertical-align:middle;
    margin:0 16px
}
.switch label .lever:after{
    content:"";
    position:absolute;
    display:inline-block;
    width:21px;
    height:21px;
    background-color:#F1F1F1;
    border-radius:21px;
    box-shadow:0 1px 3px 1px rgba(0,0,0,0.4);
    left:-5px;
    top:-3px;
    transition:left 0.3s ease, background .3s ease, box-shadow 0.1s ease
}
input[type=checkbox]:checked:not(:disabled) ~ .lever:active::after,input[type=checkbox]:checked:not(:disabled).tabbed:focus ~ .lever::after{
    box-shadow:0 1px 3px 1px rgba(0,0,0,0.4),0 0 0 15px rgba(38,166,154,0.1)
}
input[type=checkbox]:not(:disabled) ~ .lever:active:after,input[type=checkbox]:not(:disabled).tabbed:focus ~ .lever::after{
    box-shadow:0 1px 3px 1px rgba(0,0,0,0.4),0 0 0 15px rgba(0,0,0,0.08)
}
.switch input[type=checkbox][disabled]+.lever{
    cursor:default
}
.switch label input[type=checkbox][disabled]+.lever:after,.switch label input[type=checkbox][disabled]:checked+.lever:after{
    background-color:#BDBDBD
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
                                <li><a href="#">Dashboard</a></li>
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
                <div class="col-lg-9 ">
                                <div class="card alert tableBodyScroll">
                                    <div  class="card-body ">
                                   <b> Update Collection Status</b>

                                        <div class="table-responsive ">
                                            <table id = "myTable1" class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Sl No</th>
                                                        <th>Order Id</th>
                                                        <th>Collection Address</th>
                                                        <th>Collection Time</th>
                                                        <th>Collection Reference</th>
                                                        <th>Collected Flag</th>
                                                        <th>Transit Flag</th>
                                                    </thead>
                                                <tbody>
                                                 
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                        <div class="card">
                            <div class="stat-widget-eight">
                                <div class="stat-header">
                                    <div class="header-title pull-left">Kyc Status</div>
                                    <div class="card-option drop-menu pull-right"><i class="ti-more-alt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" role="link"></i>
                                        <ul class="card-option-dropdown dropdown-menu">
                                           
                                        </ul>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="stat-content">
                                    <div class="pull-left">
                                        <i class="ti-arrow-up color-success"></i>
                                        <span class="stat-digit"> <?php if((int) $driver_kyc[0]['kyc_status'] < 1 ){ echo 'Kyc Not Verified';}else{echo 'Kyc Verified User';}?></span>
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
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="stat-widget-eight">
                                <div class="stat-header">
                                    <div class="header-title pull-left">Total Payout Received</div>
                                    <div class="card-option drop-menu pull-right"><i class="ti-more-alt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" role="link"></i>
                                        <ul class="card-option-dropdown dropdown-menu">
                                           
                                        </ul>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="stat-content">
                                    <div class="pull-left">
                                        <i class="ti-arrow-up color-success"></i>
                                        <span class="stat-digit"> 

                                        <?php if(isset($driver_payout[0]['payed'])){echo $driver_payout[0]['payed'];}else{echo 0;}?>
                                        </span>
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
                    <div class="col-lg-9 ">
                                <div class="card alert tableBodyScroll">
                                    <div  class="card-body ">
                                        <b>Update Delivery Status</b>
                                        <div class="table-responsive ">
                                            <table id = "myTable" class="table table-hover">
                                                <thead>
                                                    <tr>
                                                         <th>Sl No</th>
                                                        <th>Order Id</th>
                                                        <th>Collection Address</th>
                                                        <th>Prefered Delivery Time</th>
                                                        <th>Delivery Flag Reference</th>
                                                    </thead>
                                                <tbody>
                                                 
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                    
                   
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="stat-widget-eight">
                                <div class="stat-header">
                                    <div class="header-title pull-left">Total Payout pending</div>
                                    <div class="card-option drop-menu pull-right"><i class="ti-more-alt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" role="link"></i>
                                        <ul class="card-option-dropdown dropdown-menu">
                                        </ul>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="stat-content">
                                    <div class="pull-left">
                                        <i class="ti-arrow-down color-danger"></i>
                                        <?php if(isset($driver_payout[0]['payed'])){echo $driver_payout[0]['pending'];}else{echo 0;}?>
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
                
                       
    
                <!-- /# row -->
                
            </div>
        </div>
    </div>
</div>

<div id="search">
    <button type="button" class="close">??</button>
    <form>
        <input type="search" value="" placeholder="type keyword(s) here" />
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>
<?php include('view/layout/script.php'); ?>
<?php include('view/layout/footer.php'); ?>
<script>

$(document).on('change', '.changeThree', function(){
            var id = $(this).attr('attr-id');
            if($(this).is(":checked") === true){
                $.ajax({
                    url: "<?php echo $base_url; ?>order/updatedelivered",
                    type: 'POST',
                    data: {id:id},
                    success: function (result) {
                        table.ajax.reload(null, false);  //just reload table
                    }
                                            
                });
            }else{
                
            }
        });


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
                "url": "<?php echo $base_url ?>orders_transist_list",
                "type": "POST",
                "data": function (data) {

                }
            }
        });

$(document).on('change', '.changeOne', function(){
            var id = $(this).attr('attr-id');
            if($(this).is(":checked") === true){
                $.ajax({
                    url: "<?php echo $base_url; ?>order/updatecollectionstatus",
                    type: 'POST',
                    data: {id:id},
                    success: function (result) {
                        table1.ajax.reload(null, false);  //just reload table
                        table.ajax.reload(null, false);  //just reload table
                    }
                                            
                });
            }else{
                
            }
        });


        $(document).on('change', '.changeTwo', function(){
            var id = $(this).attr('attr-id');
            if($(this).is(":checked") === true){
                $.ajax({
                    url: "<?php echo $base_url; ?>order/updatetransiststatus",
                    type: 'POST',
                    data: {id:id},
                    success: function (result) {
                        table1.ajax.reload(null, false);  //just reload table
                        table.ajax.reload(null, false);  //just reload table
                    }
                                            
                });
            }else{
                
            }
        });
    var table1;

var now = new Date();
    var jsDate = now.getDate() + '-' + (now.getMonth() + 1) + '-' + now.getFullYear();
    table1 = $("#myTable1").DataTable({
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
            "url": "<?php echo $base_url ?>orders_collection_list",
            "type": "POST",
            "data": function (data) {

            }
        }
    });
</script>