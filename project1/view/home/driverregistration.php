<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Webstrot Admin : Widget</title>

    <!-- ================= Favicon ================== -->
    <!-- Standard -->
    <link rel="shortcut icon" href="http://placehold.it/64.png/000/fff">
    <!-- Retina iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="144x144" href="http://placehold.it/144.png/000/fff">
    <!-- Retina iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="114x114" href="http://placehold.it/114.png/000/fff">
    <!-- Standard iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="72x72" href="http://placehold.it/72.png/000/fff">
    <!-- Standard iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="57x57" href="http://placehold.it/57.png/000/fff">

    <!-- Styles -->
    <link href="<?php echo $asset_url;?>assets/css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo $asset_url;?>assets/css/lib/themify-icons.css" rel="stylesheet">
    <link href="<?php echo $asset_url;?>assets/css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $asset_url;?>assets/css/lib/unix.css" rel="stylesheet">
    <link href="<?php echo $asset_url;?>assets/css/style.css" rel="stylesheet">
</head>

<body class="bg-primary">

    <div class="unix-login">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-lg-offset-3">
                    <div class="login-content">
                     
                        <div class="login-form">
                            <h4>Driver Registration</h4>
                            <form enctype="multipart/form-data" id = "form_registration" type ="POST"  >
                            <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" id ="name" name = "name" required class="form-control" placeholder="Name">
                                </div>
                                <div class="form-group">
                                    <label>Email address</label>
                                    <input type="email" id ="email" name = "email"  class="form-control" placeholder="Email" required>
                                    <span id ="span_email" style= "color:red" ></span>
                                </div>
                                <div class="form-group">
                                    <label>Route Source Eircode</label>
                                    <input type="text"  id ="source_eircode" name = "source_eircode" class="form-control" placeholder="Source EirCode" >
                                    <span id ="span_source" style= "color:red" ></span>
                                </div> 
                                <div class="form-group">
                                    <label>Route Destination Eircode</label>
                                    <input type="text"  id ="destination_eircode" name = "destination_eircode" class="form-control" placeholder="Destinaton EirCode" required>
                                    <span id ="span_destination" style= "color:red" ></span>
                               </div> 

                                <div class="form-group">
                                    <label>Start Time</label>
                                    <input type="time"  id ="start_time" name = "start_time" class="form-control" placeholder="Start Time" >
                                </div> 
                                <div class="form-group">
                                    <label>End Time</label>
                                    <input type="time"  id ="end_time" name = "end_time" class="form-control" placeholder="End Time" required>
                                </div> 


                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" id ="password" name = "password"  class="form-control" placeholder="Password" required>
                                </div> 
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password"  id ="confirm_password" name = "confirm_password" class="form-control" placeholder="Confirm Password" required>
                                    <span id ="span_password" style= "color:red" ></span>
                                </div> 

                                <div class="form-group">
                                    <label>Upload KYC Document</label>
                                    <input type="file"  id ="kyc" name = "kyc" class="form-control" placeholder="KYC" required>
                                    <span id ="span_doc" style= "color:red" ></span>
                                </div> 
                                
                                <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Register</button> 
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
<?php include('view/layout/script.php'); ?>
<script>

$('#source_eircode').keydown(function(){
    $('#span_source').html('');
});
$('#destination_eircode').keydown(function(){
    $('#span_destination').html('');
});
$( "#form_registration" ).submit(function(e) {
    e.preventDefault();
    var password = $('#password').val();
    var confirm_password = $('#confirm_password').val();
    if(confirm_password ===password ){
        $.ajax({
            url: "<?php echo $base_url; ?>insertupdatedriver",
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (result) {
                var result = JSON.parse(result);
                if(parseFloat(result.status) < 1){
                    var errorcodes = result.errorcodes;
                    $.each(errorcodes , function(index, val) { 
                        if(val === 204 ){
                            $('#span_email').html('Already Exists');
                            $('#email').focus();
                        }
                        if(val === 201 ){
                            $('#span_source').html('Invalid Eir Code');
                        }

                        if(val === 202 ){
                            $('#span_destination').html('Invalid Eir Code');
                        }

                        if(val === 203 ){
                           // $('#span_doc').html('Please Upload a kyc Document');
                        }
                    });
                }else{
                    window.location.replace('<?php echo $base_url; ?>');

                }
            }
                                        
        });
    }else{
        $('#span_password').html('Password Mismatch');
    }
});
</script>