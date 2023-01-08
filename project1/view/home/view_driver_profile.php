<?php include('view/layout/header.php'); ?>

<style>
    .contact-title {
    display: inline-block;
    padding-bottom: 15px;
    width: 300px;
    font-size: 16px;
    color: #252525
}
</style>

<div class="content-wrap">
    <div class="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 p-r-0 title-margin-right">
                    <div class="page-header">
                        <div class="page-title">
                            <h1>Driver <span> Profile</span></h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="#">Driver</a></li>
                                <li class="active">Profile</li>
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
                                
                                <div class="card-body">
                                    <div class="user-profile m-t-15">
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <div class="user-profile-name">Mr.  <?php echo $user['driver_name'];?> &nbsp <a href ="<?php echo $base_url;?>profile/edit"><i class="ti-pencil"></i></a></div>
                                                <div class="custom-tab user-profile-tab">
                                                    <ul class="nav nav-tabs" role="tablist">
                                                        <li role="presentation" class="active"><a href="#1" aria-controls="1" role="tab" data-toggle="tab">About</a></li>
                                                    </ul>
                                                    <div class="tab-content">
                                                        <div role="tabpanel" class="tab-pane active" id="1">
                                                            <div class="contact-information">
                                                                <div class="phone-content">
                                                                    <span class="contact-title">Name:</span>
                                                                    <span class="phone-number"><?php echo $user['driver_name'];?></span>
                                                                </div>
                                                                <div class="phone-content">
                                                                    <span class="contact-title">User Flag:</span>
                                                                    <span class="phone-number"><b><?php if($user['driver_active_flag'] == 1 ){echo 'Active';}else{echo 'Inactive';}?></b></span>
                                                                </div>
                                                                <div class="phone-content">
                                                                    <span class="contact-title">Phone:</span>
                                                                    <span class="phone-number"><?php echo $user['phone'];?></span>
                                                                </div>
                                                                <div class="phone-content">
                                                                    <span class="contact-title">Source EirCode:</span>
                                                                    <span class="phone-number"><?php echo $user['route_source_eir'];?></span>
                                                                </div>
                                                                <div class="phone-content">
                                                                    <span class="contact-title">Destination  EirCode:</span>
                                                                    <span class="phone-number"><?php echo $user['route_destination_eir'];?></span>
                                                                </div>
                                                                <div class="website-content">
                                                                    <span class="contact-title">Email:</span>
                                                                    <span class="contact-website"><?php echo $user['driver_email'];?></span>
                                                                </div>
                                                                
                                                                <div class="skype-content">
                                                                    <span class="contact-title">Service Start Time:</span>
                                                                    <span class="contact-skype"><?php echo $user['service_start_time'];?></span>
                                                                </div>
                                                                <div class="address-content">
                                                                    <span class="contact-title">Service End Time:</span>
                                                                    <span class="mail-address">#<?php echo $user['service_end_time'];?></span>
                                                                </div>
                                                               
                                                                <div class="phone-content">
                                                                    <span class="contact-title">Address:</span>
                                                                    <span class="mail-address"><?php echo $user['driver_address'];?></span>
                                                                </div>
                                                        
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /# column -->
                        
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
    $(document).on('change', '#subscription', function(){
        $('#spane').html('');
    });
    $(document).on('click', '#clos_btn', function(){
        var subscription = $('#subscription').val();
        var user_id = $('#user_id').val();
        if(subscription === ''){
            $('#spane').html('Please Select a Subsciption Plan');
        }else{
            $.ajax({
                url: "<?php echo $base_url; ?>user/updatesuscription",
                type: 'POST',
                data: {user_id:user_id,subscription:subscription},
                success: function (result) {
                    table.ajax.reload(null, false);  //just reload table
                    $('#myClosureModal').modal('hide');
                }
                                        
            });
        }

    });
    $(document).on('click', '.flagactive', function(){
        var id = $(this).attr('attr-id');
        var status =  $(this).attr('status');
        $.ajax({
            url: "<?php echo $base_url; ?>user/activateflag",
            type: 'POST',
            data: {id:id,status:status},
            success: function (result) {
                table.ajax.reload(null, false);  //just reload table
            }
                                       
        });
    });

    $(document).on('click', '.btnsubscription', function(){
        var id = $(this).attr('attr-id');
        $('#user_id').val(id);
        $('#myClosureModal').modal('show');

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
                "url": "<?php echo $base_url ?>user_list",
                "type": "POST",
                "data": function (data) {

                }
            }
        });

</script>