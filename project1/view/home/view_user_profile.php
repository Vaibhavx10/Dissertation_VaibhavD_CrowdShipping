<?php include('view/layout/header.php'); ?>

<style>
    .contact-title {
    display: inline-block;
    padding-bottom: 15px;
    width: 250px;
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
                            <h1>User <span> Profile</span></h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="#">User</a></li>
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
                        <div class="col-lg-6">
                            <div class="card alert">
                                
                                <div class="card-body">
                                    <div class="user-profile m-t-15">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <input type ="hidden" name= "user_id" id ="user_id" value= "<?php echo $user['user_id'];?>"/>
                                                <div class="user-profile-name">Mr.  <?php echo $user['user_name'];?> &nbsp <a href ="<?php echo $base_url;?>profile/edit"><i class="ti-pencil"></i></a></div>
                                                <div class="custom-tab user-profile-tab">
                                                    <ul class="nav nav-tabs" role="tablist">
                                                        <li role="presentation" class="active"><a href="#1" aria-controls="1" role="tab" data-toggle="tab">About</a></li>
                                                    </ul>
                                                    <div class="tab-content">
                                                        <div role="tabpanel" class="tab-pane active" id="1">
                                                            <div class="contact-information">
                                                                <div class="phone-content">
                                                                    <span class="contact-title">Name:</span>
                                                                    <span class="phone-number"><?php echo $user['user_name'];?></span>
                                                                </div>
                                                                <div class="phone-content">
                                                                    <span class="contact-title">User Flag:</span>
                                                                    <span class="phone-number"><b><?php if($user['user_active_flag'] == 1 ){echo 'Active';}else{echo 'Inactive';}?></b></span>
                                                                </div>
                                                                <div class="phone-content">
                                                                    <span class="contact-title">Phone:</span>
                                                                    <span class="phone-number"><?php echo $user['phone'];?></span>
                                                                </div>
                                                                <div class="phone-content">
                                                                    <span class="contact-title">EirCode:</span>
                                                                    <span class="phone-number"><?php echo $user['user_eir'];?></span>
                                                                </div>
                                                                <div class="website-content">
                                                                    <span class="contact-title">Email:</span>
                                                                    <span class="contact-website"><?php echo $user['email'];?></span>
                                                                </div>
                                                                <div class="skype-content">
                                                                    <span class="contact-title">Subscription Status:</span>
                                                                    <span class="contact-skype"><?php 
                                                                        if($user['subscription_status'] == 'N'){
                                                                            echo  'Inactive ';
                                                                        }else if($user['subscription_status'] == 'A'){
                                                                            echo ' Active';
                                                                        }else if($user['subscription_status'] == 'E'){
                                                                            echo ' Expired';
                                                                        }else if($user['subscription_status'] == 'T'){
                                                                            echo ' Limit Exceeded';
                                                                        }else{
                                                                            echo 'No Active Subscription';
                                                                        }
                                                                    ?></span>
                                                                </div>
                                                                <div class="skype-content">
                                                                    <span class="contact-title">Subscription:</span>
                                                                    <span class="contact-skype"><?php echo $user['plan_name'];?></span>
                                                                </div>
                                                                <div class="gender-content">
                                                                <span class="contact-title">Subscription Start Date:</span>
                                                                    <span class="mail-address"><?php echo $user['subscription_start_date'];?></span>
                                                                </div>
                                                                <div class="gender-content">
                                                                <span class="contact-title">Subscription End Date:</span>
                                                                <span class="mail-address"><?php echo $user['subscription_end_date'];?></span>
                                                                </div>
                                                                <div class="phone-content">
                                                                    <span class="contact-title">Address:</span>
                                                                    <span class="mail-address"><?php echo $user['user_address'];?></span>
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
                        <div class="col-lg-6">
                            <div class="card alert">
                                <div class="card-body">
                                    <div class="user-profile m-t-15">
                                        <div class="row">
                                            <div class="col-lg-12">  
                                            <div class="user-profile-name">Subscription Details</div>
                                                <div class="custom-tab user-profile-tab">
                                                    <ul class="nav nav-tabs" role="tablist">
                                                        <li role="presentation" class="active"><a href="#1" aria-controls="1" role="tab" data-toggle="tab">About</a></li>
                                                    </ul>
                                                    <div class="tab-content">
                                                        <div role="tabpanel" class="tab-pane active" id="1">
                                                            <?php if($user['subscription_status'] == 'A'){?>
                                                                <div class="skype-content">
                                                                    <span class="contact-title">Subscription Status:</span>
                                                                    <span class="contact-skype"><b>Active</b></span>
                                                                </div>
                                                                <div class="skype-content">
                                                                    <span class="contact-title">Subscription:</span>
                                                                    <span class="contact-skype"><?php echo $user['plan_name'];?></span>
                                                                </div>
                                                                <div class="gender-content">
                                                                <span class="contact-title">Subscription Start Date:</span>
                                                                <span class="mail-address"><?php echo $user['subscription_start_date'];?></span>
                                                                </div>
                                                                <div class="gender-content">
                                                                <span class="contact-title">Subscription End Date:</span>
                                                                <span class="mail-address"><?php echo $user['subscription_end_date'];?></span>
                                                                </div>

                                                                <div class="address-content">
                                                                    <span class="contact-title">Monthly Rate:</span>
                                                                    <span class="mail-address"><?php echo $user['monthly_rate'];?></span>
                                                                </div>

                                                                <div class="address-content">
                                                                    <span class="contact-title">Max no of Transactions :</span>
                                                                    <span class="mail-address"><?php echo $user['max_transactions'];?></span>
                                                                </div>

                                                                <div class="address-content">
                                                                    <span class="contact-title">Features :</span>
                                                                    <span class="mail-address"><?php echo $user['plan_feature'];?></span>
                                                                </div>
                                                            <?php }else{?>
                                                                <div class="skype-content">
                                                                    <span class="contact-title">Subscription Status:<b></span>
                                                                    <span class="contact-skype"><?php 
                                                                        if($user['subscription_status'] == 'N'){
                                                                            echo  'Inactive ';
                                                                        }else if($user['subscription_status'] == 'A'){
                                                                            echo ' Active';
                                                                        }else if($user['subscription_status'] == 'E'){
                                                                            echo ' Expired';
                                                                        }else if($user['subscription_status'] == 'T'){
                                                                            echo ' Limit Exceeded';
                                                                        }else{
                                                                            echo 'No Active Subscription';
                                                                        }
                                                                    ?></b></span>
                                                                </div>
                                                                <ul class="nav nav-tabs" role="tablist">
                                                                    <li role="presentation" class="active"><a href="#1" aria-controls="1" role="tab" data-toggle="tab">Add Subscription</a></li>
                                                                </ul>
                                                                <div class="address-content">
                                                                    <br />
                                                                    <span class="contact-title">Choose Subscription</span>
                                                                    <span class="mail-address"> 
                                                                        <select id = "subscription" class ="form-control" name = "subscription">
                                                                            <option value ="">Select</option>
                                                                            <?php foreach($subscription as $item){?>
                                                                                <option value ="<?php echo $item['subscription_plan_id'];?>"><?php echo $item['plan_name'];?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                
                                                                     </span>

                                                                    
                                                                <div class="skype-content">
                                                                    <span class="contact-title">Subscription:</span>
                                                                    <span class="contact-skype clssub plan_name">...............................</span>
                                                                </div>
                                                                <div class="address-content">
                                                                    <span class="contact-title">Subscription Start Date:</span>
                                                                    <span class="mail-address clssub date">...............................</span>
                                                                </div>
                                                                <div class="gender-content">
                                                                <span class="contact-title">Subscription End Date:</span>
                                                                <span class="mail-address clssub expiry_date">...............................</span>
                                                                </div>

                                                                <div class="address-content">
                                                                    <span class="contact-title">Monthly Rate:</span>
                                                                    <span class="mail-address clssub monthly_rate">...............................</span>
                                                                </div>

                                                                <div class="address-content">
                                                                    <span class="contact-title">Max no of Transactions :</span>
                                                                    <span class="mail-address clssub max_transactions">...............................</span>
                                                                </div>

                                                                <div class="address-content">
                                                                    <span class="contact-title">Features :</span>
                                                                    <span class="mail-address plan_feature">...............................</span>
                                                                </div>
                                                                </div>
                                                                <div class="footer">
                                                                    <div class = "col-sm-6">
                                                                        <button type ="button" class = "btn btn-warning savesub" ><i class="ti-save"></i></button> 
                                                                    </div>
                                                                </div>
                                                            <?php }?>
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
        var id = $(this).val();
        $('.clssub').html('...............................');
        <?php foreach($subscription as $item){?>
            var subscription_plan_id = '<?php echo $item['subscription_plan_id'];?>';
            if(parseFloat(subscription_plan_id) === parseFloat(id) ){
                $('.plan_name').html('<?php echo $item['plan_name'];?>');
                $('.max_transactions').html('<?php echo $item['max_transactions'];?>');
                $('.monthly_rate').html('<?php echo $item['monthly_rate'];?>');
                $('.plan_feature').html('<?php echo $item['plan_feature'];?>');
                $('.date').html('<?php echo $item['date'];?>');
                $('.expiry_date').html('<?php echo $item['expiry_date'];?>');
            }
        <?php } ?>
    });
    $(document).on('click', '.savesub', function(){
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
                    window.location.replace('<?php echo $base_url; ?>profile');
                }
                                        
            });
        }

    });


   

   


</script>