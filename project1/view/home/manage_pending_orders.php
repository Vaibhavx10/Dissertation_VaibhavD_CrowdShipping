<?php include('view/layout/header.php'); ?>
<div class="content-wrap">
    <div class="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 p-r-0 title-margin-right">
                    <div class="page-header">
                        <div class="page-title">
                            <h1>Orders <span> Manage</span></h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="#">Orders</a></li>
                                <li class="active">Manage</li>
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
                        <div class="card">
                        <div class="table-responsive">
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
        $(document).on('change', '.rate', function(){
            var id = $(this).attr('attr-id');
            var rate_id = $(this).val();
            $.ajax({
                url: "<?php echo $base_url; ?>driver/setrate",
                type: 'POST',
                data: {id:id,rate_id:rate_id},
                success: function (result) {
                    table.ajax.reload(null, false);  //just reload table
                }
                                        
            });
        });

    $(document).on('click', '.flagactive', function(){
        var id = $(this).attr('attr-id');
        var status =  $(this).attr('status');
        $.ajax({
            url: "<?php echo $base_url; ?>driver/activateflag",
            type: 'POST',
            data: {id:id,status:status},
            success: function (result) {
                table.ajax.reload(null, false);  //just reload table
            }
                                       
        });
    });

    $(document).on('click', '.kycactive', function(){
        var id = $(this).attr('attr-id');
        var status =  $(this).attr('status');
        $.ajax({
            url: "<?php echo $base_url; ?>driver/approvekyc",
            type: 'POST',
            data: {id:id,status:status},
            success: function (result) {
                table.ajax.reload(null, false);  //just reload table

            }
                                       
        });
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
                "url": "<?php echo $base_url ?>pending_orders_list",
                "type": "POST",
                "data": function (data) {

                }
            }
        });

</script>