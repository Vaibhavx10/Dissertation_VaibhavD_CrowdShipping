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
                            <h1>Click &  Collect <span> Creation</span></h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="#"> Click &  Collect</a></li>
                                <li class="active">Creation</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
            </div>
            <!-- /# row -->
            <?php if((int) $user['user_active_flag'] < 1) {?>
                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Please Contact Admin . You do not have the  permission.  <span> </span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="#">&nbsp;</a></li>
                                    <li class="active"></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
            <?php }else if($user['subscription_status'] != 'A'){?>
                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>OOPS! You don't have a active Subscription .  <span> </span></h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="#">&nbsp;</a></li>
                                    <li class="active"><a style ="color:blue;"  href ="<?php echo $base_url;?>profile" >Click here to add a subscription</a></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
            <?php }?>
            
            <div id="main-content">
                <form enctype="multipart/form-data" id = "form_clickandcollect" type ="POST"  >
                <input type ="hidden" name = "user_active_flag" id = "user_active_flag" value = "<?php echo $user['user_active_flag'];?>"/>
                <input type ="hidden" name = "subscription_status" id = "subscription_status" value = "<?php echo $user['subscription_status'];?>"/>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="col-lg-12">
                                <div class = "col-sm-6">
                                    <label>Source Collection Reference</label>
                                    <input  type = "text" id = "source_col_ref" name ="source_col_ref" class = "form-control cls" required/> 
                                </div>  
                                
                                <div class = "col-sm-6">
                                    <label>Source Address</label>
                                    <input type = "text" id = "source_address"  name ="source_address" class = "form-control cls" /> 
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class = "col-sm-6">
                                    <label> Source Eircode</label>
                                    <input  type = "text" id = "source_eir" name ="source_eir" class = "form-control cls" required/> 
                                    <span id ="eir" style = "color:red;" ></span>
                                    <span id ="spancollection" style = "color:red;" ></span>
                                </div>
                                <div class = "col-sm-6">
                                    <label>PObox</label>
                                    <select id = "PObox_id" name ="PObox_id" class = "form-control cls" required>
                                        <option value ="" >Select</option>
                                        <?php foreach($pobox as $po){?>
                                            <option value ="<?php echo $po['PObox_id'];?>" ><?php echo $po['delivery_address'];?></option>
                                        <?php } ?>
                                    </select> 
                                    <span id ="spanpobox" style = "color:red;" ></span>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class = "col-sm-6">
                                    <label>  Collection date</label>
                                    <input  type = "date" id = "collection_date" name ="collection_date" class = "form-control cls" required/> 
                                </div>  
                                <div class = "col-sm-6">
                                    <label>  Collection Time</label>
                                    <input  type = "time" id = "collection_time" name ="collection_time" class = "form-control cls" required/> 
                                    <span id ="spantime" style = "color:red;" ></span>
                                </div>  
                               
                            </div>

                            <div class="col-lg-12">
                                <div class = "col-sm-6">
                                    <label>  Preferred Delivery Start Time</label>
                                    <input  type = "time" id = "delivery_start_time" name ="delivery_start_time" class = "form-control cls" required/> 
                                </div>  

                                <div class = "col-sm-6">
                                    <label>  Preferred Delivery End Time</label>
                                    <input  type = "time" id = "delivery_end_time" name ="delivery_end_time" class = "form-control cls" required/> 
                                </div>  
                            </div>

                            <div style ="display:none;"  class="footer saved">
                                <div style ="color:green;border:1px solid black;padding:10px;margin:10px;"  class = "col-sm-12 ">
                                     <label >Order Placed Successfully</lable>
                                </div>
                            </div>
                            <div class="footer">
                                
                                <div class = "col-sm-6">
                                    <button type ="submit" class = "btn btn-warning cls" ><i class="ti-save"></i></button> 
                                </div>
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
 
 $( "#form_clickandcollect" ).submit(function(e) {
        e.preventDefault();
        var user_active_flag = $('#user_active_flag').val();
        var subscription_status = $('#subscription_status').val();
        $('#eir').html('');
        $('#spantime').html('');
        $('#spancollection').html('');
        $('#spanpobox').html('');
        if(parseFloat(user_active_flag) > 0 &&  subscription_status === 'A'){
            $.ajax({
                url: "<?php echo $base_url; ?>save_order",
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (result) {
                    var result = JSON.parse(result);
                    if(result.status === 1){
                        $('.saved').attr('style','')
                        setTimeout(function() {
                            window.location.replace('<?php echo $base_url; ?>clickandcollect');
                        }, 3000);

                        

                    }else{
                        if(result.errorcode ===201)
                            $('#eir').html('Invalid Eir Code ');
                        if(result.errorcode ===202)
                            $('#spantime').html('Driver not Available In Specified time ');
                        if(result.errorcode ===203)
                            $('#spancollection').html('Driver Not Available in Specified Collection Point');
                        if(result.errorcode ===204)
                            $('#spanpobox').html('Driver Not Available In Specified Pobox Address');
                        if(result.errorcode ===205)
                            $('#spancollection').html('Driver Not Available in Specified Collection Point');
                    }

                }
                                            
            });
        }
});
$( document ).ready(function() {
    var user_active_flag = $('#user_active_flag').val();
    var subscription_status = $('#subscription_status').val();
    if(parseFloat(user_active_flag) > 0 &&  subscription_status === 'A'){

    }else{
        $('.cls').attr('disabled',true);
    }
});
</script>