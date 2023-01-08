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
                            <h1>Subscription <span> Create</span></h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="#">Subscription</a></li>
                                <li class="active">Create</li>
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
                                    <label> Subscription Name</label>
                                    <input type = "hidden" name = "subscription_plan_id" id ="subscription_plan_id" value ="<?php echo $id;?>"/>  
                                    <input <?php if(isset($subscription['subscription_plan_id'])){  echo 'value = "'.$subscription['plan_name'].'" ';}?> type = "text" id = "plan_name" name ="plan_name" class = "form-control" required/> 
                                </div>
                                <div class = "col-sm-6">
                                    <label> Maximum  Transaction</label>
                                    <input type = "number" id = "max_transactions" <?php if(isset($subscription['subscription_plan_id'])){  echo 'value = "'.$subscription['max_transactions'].'" ';}?> name ="max_transactions" class = "form-control" required/> 
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class = "col-sm-6">
                                    <label> Monthly Rate</label>
                                    <input type = "number" id = "monthly_rate" name ="monthly_rate" <?php if(isset($subscription['subscription_plan_id'])){  echo 'value = "'.$subscription['monthly_rate'].'" ';}?> class = "form-control" required/> 
                                </div>
                                <div class = "col-sm-6">
                                    <label> Maximum  Transaction</label>
                                    <input type = "number" id = "max_weight" <?php if(isset($subscription['subscription_plan_id'])){  echo 'value = "'.$subscription['max_weight'].'" ';}?> name ="max_weight" class = "form-control" required/> 
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class = "col-sm-6">
                                     <label>Plan Feature </label>
                                     <input  <?php if(isset($subscription['subscription_plan_id'])){  echo 'value = "'.$subscription['plan_feature'].'" ';}?> type = "text" id = "plan_feature" name ="plan_feature" class = "form-control" required/> 
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
        url: "<?php echo $base_url; ?>insertupdate_subscription",
        type: 'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function (result) {
            var result = JSON.parse(result);
            
                window.location.replace('<?php echo $base_url; ?>subscription');

        }
                                       
    });
});
</script>