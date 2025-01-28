<?php 
$query = $conn->query("SELECT * FROM users where id = '{$_settings->userdata('id')}'");
if($query->num_rows > 0){
    foreach($query->fetch_array() as $k=> $v){
        $$k = $v;
    }
    $query_meta = $conn->query("SELECT * FROM `user_meta` where user_id = '{$id}'");
    while($row = $query_meta->fetch_assoc()){
        $meta[$row['meta_field']] = $row['meta_value'];
    }
}
?>
<style>
     img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>
<div class="container py-5">
    <div class="card card-ouline-primary">
        <div class="card-header">
            <h5 class="card-title">Update Account Information/Credentials</h5>
        </div>
        <div class="card-body">
            <form action="" id="user-register">
                <input type="hidden" name="id" value="<?php echo isset($id)? $id : '' ?>">
                <input type="hidden" name="type" value="2">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="firstname" class="control-label text-info">First Name</label>
                            <input type="text" class="form-control form-control-border" id="firstname" name="firstname" value="<?php echo isset($firstname) ? $firstname : '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="middlename" class="control-label text-info">Middle Name</label>
                            <input type="text" class="form-control form-control-border" id="middlename" name="middlename" placeholder="optional" value="<?php echo isset($middlename) ? $middlename : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="lastname" class="control-label text-info">Last Name</label>
                            <input type="text" class="form-control form-control-border" id="lastname" name="lastname" value="<?php echo isset($lastname) ? $lastname : '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="gender" class="control-label text-info">Gender</label>
                            <select class="form-control form-control-border" id="gender" name="gender" required>
                                <option <?php echo isset($meta['gender']) && $meta['gender'] == "Male" ? "selected" : "" ?>>Male</option>
                                <option <?php echo isset($meta['gender']) && $meta['gender'] == "Female" ? "selected" : "" ?>>Female</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dob" class="control-label text-info">Date of Birt</label>
                            <input type="date" class="form-control form-control-border" id="dob" name="dob" value="<?php echo isset($meta['dob']) ? $meta['dob'] : '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="contact" class="control-label text-info">Contact #</label>
                            <input type="text" class="form-control form-control-border" id="contact" name="contact" value="<?php echo isset($meta['contact']) ? $meta['contact'] : '' ?>" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        
                        <div class="form-group">
                            <label for="address" class="control-label text-info">Address</label>
                            <textarea rows="1" class="form-control form-control-border" id="address" name="address" required><?php echo isset($meta['address']) ? $meta['address'] : '' ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="username" class="control-label text-info">Email</label>
                            <input type="email" class="form-control form-control-border" id="username" name="username" value="<?php echo isset($username) ? $username : '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="password" class="control-label text-info">New Password</label>
                            <input type="password" class="form-control form-control-border" id="password" name="password" >
                            <span class="text-info"><small>Leave this field Blank if you don't want to update your password</small></span>
                        </div>
                        <div class="form-group">
                            <label for="cpassword" class="control-label text-info">Confirm New Password</label>
                            <input type="password" class="form-control form-control-border" id="cpassword" >
                            <span class="text-info"><small>Leave this field Blank if you don't want to update your password</small></span>
                        </div>
                        <div class="form-group">
                            <label for="oldpassword" class="control-label text-info">Current Password</label>
                            <input type="password" class="form-control form-control-border" id="oldpassword" name="oldpassword" required>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Avatar</label>
                            <div class="custom-file">
                            <input type="file" class="custom-file-input rounded-circle" id="customFile" name="img" onchange="displayImg(this,$(this))">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-center">
                            <img src="<?php echo validate_image(isset($avatar) ? $avatar."?v=".strtotime($date_updated) :'') ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
                        </div>
                    </div>
                </div>
                <hr class="bg-light">
                <div class="row">
                    <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill w-50">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#user-register').submit(function(e){
        e.preventDefault();
        $('.pop_msg').remove()
        $('#oldpassword').removeClass('border-danger')
        start_loader()
        var _this = $(this)
        var el = $('<div>')
            el.addClass('pop_msg alert')
            el.hide()
        if($('#password').val() != $('#cpassword').val() && ($('#password').val() != '' || $('#cpassword').val())){
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
        $('#password,#cpassword').removeClass('border-danger')
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
                location.reload()
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
                }else if(resp== 4){
                    el.addClass('alert-danger')
                    el.text('Current Password is Incorrect.')
                    $('#oldpassword').addClass('border-danger').focus()
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