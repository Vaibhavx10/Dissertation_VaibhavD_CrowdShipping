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
                            <h1>Pobox <span> Create</span></h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="#">Pobox</a></li>
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
                                    <label>Delivery Address</label>
                                    <input type = "hidden" name = "PObox_id" id ="PObox_id" value ="<?php echo $id;?>"/>  
                                    <input <?php if(isset($pobox['PObox_id'])){  echo 'value = "'.$pobox['delivery_address'].'" ';}?> type = "text" id = "delivery_address" name ="delivery_address" class = "form-control" required/> 
                                </div>
                                <div class = "col-sm-6">
                                    <label>EirCode</label>
                                    <input type = "text" id = "delivery_eir" <?php if(isset($pobox['PObox_id'])){  echo 'value = "'.$pobox['delivery_eir'].'" ';}?> name ="delivery_eir" class = "form-control" required/> 
                                    <span id ="label_eir" style ="color:red"></span>  
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
        url: "<?php echo $base_url; ?>insertupdate_pobox",
        type: 'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function (result) {
            var result = JSON.parse(result);
            if(result.status === 1){
                window.location.replace('<?php echo $base_url; ?>pobox');
            }else{
                $('#label_eir').html('Invalid Eircode');
            }

        }
                                       
    });
});
</script>