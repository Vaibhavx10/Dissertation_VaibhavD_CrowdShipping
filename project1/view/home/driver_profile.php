<?php include('view/layout/header.php'); ?>
<style>
    .card div{
        padding-bottom:20px;
    }
</style>
<div class="content-wrap">
    <div class="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 p-r-0 title-margin-right">
                    <div class="page-header">
                        <div class="page-title">
                            <h1>Driver <span> Update</span></h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="#">Driver</a></li>
                                <li class="active">Update</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
            </div>
            <!-- /# row -->
            <div id="main-content">
                <form enctype="multipart/form-data" id = "form_subscription" type ="POST"  >

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="col-lg-12">
                                <div class = "col-sm-6">
                                    <label>  Name</label>
                                    <input <?php if(isset($user['driver_id'])){  echo 'value = "'.$user['driver_name'].'" ';}?> type = "text" id = "driver_name" name ="driver_name" class = "form-control" required/> 
                                </div>  
                                
                                <div class = "col-sm-6">
                                    <label>Phone</label>
                                    <input type = "text" id = "phone" <?php if(isset($user['driver_id'])){  echo 'value = "'.$user['phone'].'" ';}?> name ="phone" class = "form-control" /> 
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class = "col-sm-6">
                                    <label> Email</label>
                                    <input type = "hidden" name = "driver_id" id ="driver_id" value ="<?php echo $id;?>"/>  
                                    <input <?php if(isset($user['driver_id'])){  echo 'value = "'.$user['driver_email'].'" ';}?> type = "email" id = "driver_email" name ="driver_email" class = "form-control" required/> 
                                </div>
                                <div class = "col-sm-6">
                                    <label>  Password</label>
                                    <input <?php if(isset($user['driver_id'])){  echo 'value = "'.$user['password'].'" ';}?> type = "password" id = "Password" name ="Password" class = "form-control" required/> 
                                </div>  
                               
                            </div>
                            <div class="col-lg-12">
                                <div class = "col-sm-6">
                                    <label>  Source Eir Code</label>
                                    <input <?php if(isset($user['driver_id'])){  echo 'value = "'.$user['route_source_eir'].'" ';}?> type = "text" id = "route_source_eir" name ="route_source_eir" class = "form-control" required/> 
                                    <span id ="span_source" style= "color:red" ></span>
                                </div>  
                                
                                <div class = "col-sm-6">
                                <label>  Destination Eir Code</label>
                                    <input type = "text" id = "route_destination_eir" <?php if(isset($user['driver_id'])){  echo 'value = "'.$user['route_destination_eir'].'" ';}?> name ="route_destination_eir" class = "form-control" /> 
                                    <span id ="span_destination" style= "color:red" ></span>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class = "col-sm-6">
                                    <label>  Service Start Time</label>
                                    <input <?php if(isset($user['driver_id'])){  echo 'value = "'.$user['service_start_time'].'" ';}?> type = "time" id = "service_start_time" name ="service_start_time" class = "form-control" required/> 
                                </div>  
                                
                                <div class = "col-sm-6">
                                   <label>  Service End Time</label>
                                    <input type = "time" id = "service_end_time" <?php if(isset($user['driver_id'])){  echo 'value = "'.$user['service_end_time'].'" ';}?> name ="service_end_time" class = "form-control" /> 
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class = "col-sm-6">
                                    <label>Address</label>
                                    <textarea  id = "driver_address"  name ="driver_address" class = "form-control" ><?php if(isset($user['driver_id'])){  echo $user['driver_address'];}?></textarea> 
                                </div>
                                <div class = "col-sm-6">
                                    <label>Upload KYC Document</label>
                                    <input type="file"  id ="kyc" name = "kyc" class="form-control" placeholder="KYC">
                                    <span id ="span_doc" style= "color:red" ></span>
                                </div>
                            </div>
                            </div>

                            <div class="footer">
                                <div class = "col-sm-6">
                                    <button type ="submit" class = "btn btn-warning" ><i class="ti-save"></i></button> 
                                </div>
                            </div>
                        </div>
                    </div>
                
                </div>
           
          
                <!-- /# row -->
                
            </form>
            </div>
        </div>
    </div>
</div>


<?php include('view/layout/script.php'); ?>
<?php include('view/layout/footer.php'); ?>
<script>
 
 $( "#form_subscription" ).submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: "<?php echo $base_url; ?>update_driver_profile",
        type: 'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function (result) {
            var result = JSON.parse(result);
            if(parseFloat(result.status) < 1){
                var errorcodes = result.errorcodes;
                $.each(errorcodes , function(index, val) { 
                    if(val === 201 ){
                        $('#span_source').html('Invalid Eir Code');
                    }

                    if(val === 202 ){
                        $('#span_destination').html('Invalid Eir Code');
                    }

                    if(val === 202 ){
                        $('#span_doc').html('Please Upload a kyc Document');
                    }
                });
            }else{
                window.location.replace('<?php echo $base_url; ?>');

            }

        }
                                       
    });
});
</script>