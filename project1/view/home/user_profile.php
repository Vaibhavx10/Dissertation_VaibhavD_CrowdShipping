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
                            <h1>User Profile <span> Update</span></h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="#">User Profile</a></li>
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
                                    <input <?php if(isset($user['user_id'])){  echo 'value = "'.$user['user_name'].'" ';}?> type = "text" id = "user_name" name ="user_name" class = "form-control" required/> 
                                </div>  
                                
                                <div class = "col-sm-6">
                                    <label>Phone</label>
                                    <input type = "text" id = "phone" <?php if(isset($user['user_id'])){  echo 'value = "'.$user['phone'].'" ';}?> name ="phone" class = "form-control" /> 
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class = "col-sm-6">
                                    <label> Email</label>
                                    <input type = "hidden" name = "user_id" id ="user_id" value ="<?php echo $id;?>"/>  
                                    <input <?php if(isset($user['user_id'])){  echo 'value = "'.$user['email'].'" ';}?> type = "email" id = "email" name ="email" class = "form-control" required/> 
                                </div>
                                <div class = "col-sm-6">
                                    <label>  password</label>
                                    <input <?php if(isset($user['user_id'])){  echo 'value = "'.$user['password'].'" ';}?> type = "password" id = "password" name ="password" class = "form-control" required/> 
                                </div>  
                               
                            </div>
                            <div class="col-lg-12">
                                <div class = "col-sm-6">
                                    <label>   Eir Code</label>
                                    <input <?php if(isset($user['user_id'])){  echo 'value = "'.$user['user_eir'].'" ';}?> type = "text" id = "user_eir" name ="user_eir" class = "form-control" required/> 
                                </div>  
                                <div class = "col-sm-6">
                                    <label>Address</label>
                                    <textarea  id = "user_address"  name ="user_address" class = "form-control" ><?php if(isset($user['user_id'])){  echo $user['user_address'];}?></textarea> 
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
        url: "<?php echo $base_url; ?>update_user_profile",
        type: 'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function (result) {
            window.location.replace('<?php echo $base_url; ?>');

        }
                                       
    });
});
</script>