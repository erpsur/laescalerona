<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-7">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin">
                           <?php echo $title; ?>
                       </h4>
                       <hr class="hr-panel-heading" />
                       <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'staff_profile_table','autocomplete'=>'off')); ?>
                     <?php if($current_user->profile_image == NULL){ ?>
                     <div class="form-group">
                        <label for="profile_image" class="profile-image"><?php echo _l('staff_edit_profile_image'); ?></label>
                        <input type="file" name="profile_image" class="form-control" id="profile_image">
                    </div>
                    <?php } ?>
                    <?php if($current_user->profile_image != NULL){ ?>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-9">
                                <?php echo staff_profile_image($current_user->staffid,array('img','img-responsive','staff-profile-image-thumb'),'thumb'); ?>
                            </div>
                            <div class="col-md-3 text-right">
                                <a href="<?php echo admin_url('staff/remove_staff_profile_image'); ?>"><i class="fa fa-remove"></i></a>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="firstname" class="control-label"><?php echo _l('staff_add_edit_firstname'); ?></label>
                        <input type="text" class="form-control" name="firstname" value="<?php if(isset($member)){echo $member->firstname;} ?>">
                    </div>
                    <div class="form-group">
                        <label for="lastname" class="control-label"><?php echo _l('staff_add_edit_lastname'); ?></label>
                        <input type="text" class="form-control" name="lastname" value="<?php if(isset($member)){echo $member->lastname;} ?>">
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label"><?php echo _l('staff_add_edit_email'); ?></label>
                        <input type="email"<?php if(has_permission('staff','','edit')){ ?> name="email"<?php } else { ?> disabled="true"<?php } ?> class="form-control" value="<?php echo $member->email; ?>" id="email">
                    </div>
                    <?php $value = (isset($member) ? $member->phonenumber : ''); ?>
                    <?php echo render_input('phonenumber','staff_add_edit_phonenumber',$value); ?>
                    <?php if(!is_language_disabled()){ ?>
                    <div class="form-group select-placeholder">
                        <label for="default_language" class="control-label"><?php echo _l('localization_default_language'); ?></label>
                        <select name="default_language" data-live-search="true" id="default_language" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                            <option value=""><?php echo _l('system_default_string'); ?></option>
                            <?php foreach($this->app->get_available_languages() as $availableLanguage){
                                $selected = '';
                                if(isset($member)){
                                   if($member->default_language == $availableLanguage){
                                      $selected = 'selected';
                                  }
                              }
                              ?>
                              <option value="<?php echo $availableLanguage; ?>" <?php echo $selected; ?>><?php echo ucfirst($availableLanguage); ?></option>
                              <?php } ?>
                          </select>
                      </div>
                      <?php } ?>
                     
                 
               
                <?php if(count($staff_departments) > 0){ ?>
                <div class="form-group">
                    <label for="departments"><?php echo _l('staff_edit_profile_your_departments'); ?></label>
                    <div class="clearfix"></div>
                    <?php
                    foreach($departments as $department){ ?>
                    <?php
                    foreach ($staff_departments as $staff_department) {
                     if($staff_department['departmentid'] == $department['departmentid']){ ?>
                     <div class="chip-circle mtop20"><?php echo $staff_department['name']; ?></div>
                     <?php }
                 }

                 ?>
                 <?php } ?>
             </div>
             <?php } ?>
             <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
             <?php echo form_close(); ?>
         </div>
     </div>
 </div>
 <div class="col-md-5">
    <div class="panel_s">

        <div class="panel-body">
           <h4 class="no-margin">
            <?php echo _l('staff_edit_profile_change_your_password'); ?>
        </h4>
        <hr class="hr-panel-heading" />
        <?php echo form_open('admin/staff/change_password_profile',array('id'=>'staff_password_change_form')); ?>
        <div class="form-group">
            <label for="oldpassword" class="control-label"><?php echo _l('staff_edit_profile_change_old_password'); ?></label>
            <input type="password" class="form-control" name="oldpassword" id="oldpassword">
        </div>
        <div class="form-group">
            <label for="newpassword" class="control-label"><?php echo _l('staff_edit_profile_change_new_password'); ?></label>
            <input type="password" class="form-control" id="newpassword" name="newpassword">
        </div>
        <div class="form-group">
            <label for="newpasswordr" class="control-label"><?php echo _l('staff_edit_profile_change_repeat_new_password'); ?></label>
            <input type="password" class="form-control" id="newpasswordr" name="newpasswordr">
        </div>
        <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
        <?php echo form_close(); ?>
    </div>
    <?php if($member->last_password_change != NULL){ ?>
    <div class="panel-footer">
        <?php echo _l('staff_add_edit_password_last_changed'); ?>:
        <span class="text-has-action" data-toggle="tooltip" data-title="<?php echo _dt($member->last_password_change); ?>">
        <?php echo time_ago($member->last_password_change); ?>
      </span>
    </div>
    <?php } ?>
   
</div>
</div>
</div>
</div>
</div>
<?php init_tail(); ?>
<script>
 $(function(){
     var qr_loaded = 0;
     var is_g2fa_enabled = "<?php echo $current_user->two_factor_auth_enabled ?>"
    $('input[type=radio][name="two_factor_auth"]').change(function() {
        if (this.value == 'google') {
            if (is_g2fa_enabled == 2) {
                return;
            }

            if (qr_loaded == 0) {
                $('#qr_image').load(admin_url + 'authentication/get_qr', {}, function (response, status) {
                    qr_loaded = 1;
                    $('#qr_image').show();
                });
            } else {
                $('#qr_image').show();
            }
            $('#submit_2fa').prop("disabled",true);
        } else {
            $('#qr_image').hide();
            $('#submit_2fa').prop("disabled",false);
        }
    });
    appValidateForm($('#staff_profile_table'),{firstname:'required',lastname:'required',email:'required'});
    appValidateForm($('#staff_password_change_form'),{oldpassword:'required',newpassword:'required',newpasswordr: { equalTo: "#newpassword"}});
    appValidateForm($('#two_factor_auth_form'),{two_factor_auth:'required'});
 });
</script>
</body>
</html>
