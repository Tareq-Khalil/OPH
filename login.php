<?php require_once('./config.php') ?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
 <?php 
if($_settings->userdata('id') > 0){
    $_settings->set_flashdata('warning',' You are already in a session.');
    redirect('./');
}
require_once('inc/header.php');
?>
<body class="login-page  dark-mode py-4">
    <?php if($_settings->chk_flashdata('success')): ?>
      <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
      </script>
    <?php endif;?>    
  <script>
    start_loader()
  </script>
  <style>
    html,body{
        height:calc(100%) !important;
        width:calc(100%);
    }
    body:before{
        content:"";
        position:fixed;
        height:calc(100%);
        width:calc(100%);
        top:0;
        left:0;
        background-image: url("<?php echo validate_image($_settings->info('cover')) ?>");
        background-size:cover;
        background-repeat:no-repeat;
        z-index: -1;
    }
    .login-page{
        height:100% !important;
    }
    .login-title{
      text-shadow: 4px 4px black
    }
    img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
  </style>
  <h1 class="text-center py-5 login-title"><b><?php echo $_settings->info('name') ?></b></h1>
<div class="login-box">
    <div class="card card-primary card-outline card-tabs bg-dark-gradient">
        <div class="card-header p-0 pt-1 border-bottom-0">
        <ul class="nav nav-tabs" id="CTab" role="tablist">
            <li class="nav-item">
            <a class="nav-link active" id="login-tab" data-toggle="pill" href="#login" role="tab" aria-controls="login" aria-selected="false">Login</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" id="signup-tab" data-toggle="pill" href="#signup" role="tab" aria-controls="signup" aria-selected="true">Sign Up</a>
            </li>
            
            
        </ul>
        </div>
        <div class="card-body">
        <div class="tab-content" id="CTabContent">
            <div class="tab-pane fade active show" id="login" role="tabpanel" aria-labelledby="login-tab">

                <form id="ulogin-frm" action="" method="post">
                    <div class="input-group mb-3">
                    <input type="email" class="form-control" name="username" placeholder="Email" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-user"></span>
                        </div>
                    </div>
                    </div>
                    <div class="input-group mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-8">
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </div>
                    <!-- /.col -->
                    </div>
                </form>

            </div>
            <div class="tab-pane fade" id="signup" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
               <form action="" id="user-register">
                   <input type="hidden" name="id">
                   <input type="hidden" name="type" value="2">
                   <div class="row">
                       <div class="col-md-6">
                            <div class="form-group">
                                <label for="firstname" class="control-label text-info">First Name</label>
                                <input type="text" autofocus class="form-control form-control-border" id="firstname" name="firstname" required>
                            </div>
                            <div class="form-group">
                                <label for="middlename" class="control-label text-info">Middle Name</label>
                                <input type="text" class="form-control form-control-border" id="middlename" name="middlename" placeholder="optional">
                            </div>
                            <div class="form-group">
                                <label for="lastname" class="control-label text-info">Last Name</label>
                                <input type="text" class="form-control form-control-border" id="lastname" name="lastname" required>
                            </div>
                            <div class="form-group">
                                <label for="gender" class="control-label text-info">Gender</label>
                                <select class="form-control form-control-border" id="gender" name="gender" required>
                                    <option>Male</option>
                                    <option>Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dob" class="control-label text-info">Date of Birt</label>
                                <input type="date" class="form-control form-control-border" id="dob" name="dob" required>
                            </div>
                            <div class="form-group">
                                <label for="contact" class="control-label text-info">Contact #</label>
                                <input type="text" class="form-control form-control-border" id="contact" name="contact" required>
                            </div>
                       </div>
                       
                       <div class="col-md-6">
                            
                            <div class="form-group">
                                <label for="address" class="control-label text-info">Address</label>
                                <textarea rows="1" class="form-control form-control-border" id="address" name="address" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="username" class="control-label text-info">Email</label>
                                <input type="email" class="form-control form-control-border" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password" class="control-label text-info">Password</label>
                                <input type="password" class="form-control form-control-border" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="cpassword" class="control-label text-info">Confirm Password</label>
                                <input type="password" class="form-control form-control-border" id="cpassword" required>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Avatar</label>
                                <div class="custom-file">
                                <input type="file" class="custom-file-input rounded-circle" id="customFile" name="img" onchange="displayImg(this,$(this))">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                                </div>
                            </div>
                            <div class="form-group d-flex justify-content-center">
                                <img src="<?php echo validate_image(isset($meta['avatar']) ? $meta['avatar'] :'') ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
                            </div>
                       </div>
                   </div>
                   <hr class="bg-light">
                   <div class="row">
                       <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill w-50">Register</button>
                       </div>
                   </div>
               </form>
            </div>
            
            
        </div>
        </div>
        <!-- /.card -->
    </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
    function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
  $(document).ready(function(){
    end_loader();
    $('#CTab .nav-link').click(function(){
        if($(this).attr('aria-controls') == 'signup'){
            $('.login-box').addClass('w-75')
        }else{
            $('.login-box').removeClass('w-75')
        }
    })
    $('#ulogin-frm').submit(function(e){
		e.preventDefault()
        $('.pop_msg').remove()
        start_loader()
        var _this = $(this)
        var el = $('<div>')
            el.addClass('pop_msg alert')
            el.hide()
		$.ajax({
			url:_base_url_+'classes/Login.php?f=login_user',
			method:'POST',
			data:$(this).serialize(),
			error:err=>{
				el.addClass('alert-danger')
                el.text('An Error occured')
                _this.prepend(el)
                el.show('slow')
                $('html,body').animate({scrollTop:0},'fast')
			},
			success:function(resp){
				if(resp){
					resp = JSON.parse(resp)
					if(resp.status == 'success'){
						location.replace(_base_url_);
					}else if(resp.status == 'incorrect'){
						el.addClass('alert-danger')
                        el.html("<i class='fa fa-exclamation-triangle'></i> Incorrect username or password");
                        _this.prepend(el)
                        el.show('slow')
                        $('html,body').animate({scrollTop:0},'fast')
						_this.find('input').addClass('is-invalid')
						_this.find('[name="username"]').focus()
					}
						end_loader()
				}
			}
		})
	})
    $('#user-register').submit(function(e){
        e.preventDefault();
        $('.pop_msg').remove()
        start_loader()
        var _this = $(this)
        var el = $('<div>')
            el.addClass('pop_msg alert')
            el.hide()
        if($('#password').val() != $('#cpassword').val()){
            el.addClass('alert-danger')
            el.text('Mismatched Password.')
            _this.prepend(el)
            el.show('slow')
            console.log(el.get(0))
            $('#password,#cpassword').addClass('border-danger')
            $('html,body').animate({scrollTop:0},'fast')
            end_loader()
            return false;
        }
        $('#password,#cpassword').addClass('border-danger')
        $.ajax({
            url:_base_url_+"classes/Users.php?f=save",
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            error:err=>{
                console.log(err)
                el.addClass('alert-danger')
                el.text('An Error occured')
                _this.prepend(el)
                el.show('slow')
                $('html,body').animate({scrollTop:0},'fast')
            },
            success:function(resp){
                if(resp ==1){
                location.href = './login.php';
                }else if(resp== 2){
                    el.addClass('alert-danger')
                    el.text('An Error occured')
                    _this.prepend(el)
                    el.show('slow')
                }else if(resp== 3){
                    el.addClass('alert-danger')
                    el.text('Username already exists.')
                    _this.prepend(el)
                    el.show('slow')
                }
                $('html,body').animate({scrollTop:0},'fast')
                end_loader();
            }
        })
    })
   
  })
</script>
</body>
</html>