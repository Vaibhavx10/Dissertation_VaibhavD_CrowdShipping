<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>LML CROWD SHIPPING</title>

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
                            <h4> Login</h4>
                            <form id ="loginaction" type = "POST"  >
                                <div class="form-group">
                                    <label>Email address</label>
                                    <input type="email" class="form-control" name= "email" id = "email" placeholder="Email" required>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name= "password" id = "password" class="form-control" placeholder="Password" required>
                                    <label style ="color:red;" id ="spanlogin"  ></label>
                                </div> 
                                <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Sign in</button> 
                                <a style= "color:blue;" href ="<?php echo $base_url;?>userregistration" >Register as User</a>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a class ="pull-right" style= "color:blue;" href ="<?php echo $base_url;?>driverregistration" >Register as Driver</a>

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
    $('#password').keydown(function(){
        $('#spanlogin').html('');
    });
$( "#loginaction" ).submit(function(e) {
    e.preventDefault();
    $.ajax({
            url: "<?php echo $base_url; ?>loginaction",
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (result) {
                var result = JSON.parse(result);
                if(parseFloat(result.status) < 1){
                    $('#spanlogin').html('Invalid Login');
                }else{
                    window.location.replace('<?php echo $base_url; ?>');

                }
            }
                                        
        });
});
</script>