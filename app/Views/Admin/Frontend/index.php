<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Section 1</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    fieldset{
        border: 2px solid black !important;
        padding: 3% !important;
    }
    legend{
        margin-bottom: 0 !important;
    }
</style>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <form class="ajaxForm" method="post"  loader="true" action="<?php echo site_url(route_to('admin.frontend.save_general')); ?>" data-parsley-validate enctype="multipart/form-data">
                <div class="form-group">
                    <label class="control-label">Welcome Message</label>
                    <input type="text" name="welcome_message" class="form-control" value="<?php echo old('welcome_message', get_option('welcome_message', '')); ?>" required />
                </div>
                <div class="form-group">
                    <h3>Website Pictures</h3>
                    <div class="table-responsive">
                        <?php if (get_option('website_pictures') && !empty(json_decode(get_option('website_pictures')))):
                            ?>
                        <table class="table">
                            <tr>
                              <th>File</th>
                              <th>Actions</th>
                            </tr>
                            <?php
                            foreach (json_decode(get_option('website_pictures')) as $pic):?>
                            <tr>
                                <td><img src="<?php echo base_url("uploads/files/".$pic)?>" alt="" width="150"></td>
                                <td><button type="button" class="btn btn-danger btn-sm" onclick="removePic('<?php echo $pic?>')">Remove</button></td>
                            </tr>
                            <?php endforeach;?>
                        </table>
                        <?php endif;?>
                        <table class="table" id="filesTable">
                            <thead>
                            <tr>
                                <th>File</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="tableBody">
                            <tr>
                                <td>
                                    <input type="file" name="website_pictures[]" class="form-control" accept="image/*"/>
                                </td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-default btn-sm" id="addFile"><i class="fa fa-plus"></i> Add Row</button>
                    <br/><br/>
                </div>
                <div class="form-group">
                    <label class="control-label">Website Logo</label>
                     <?php if (get_option('website_logo')):?>
                    <table>
                        <tr>
                            <th><img src="<?php echo base_url("uploads/files/".get_option('website_logo'))?>" alt="" width="100"></th>
                            <th><button type="button" class="btn btn-danger btn-sm" onclick="removeLogo()">Remove</button></th>
                        </tr>
                    </table>
                    <?php endif;?>
                    <input type="file" name="website_logo" class="form-control" accept="image/*"/>
                </div>
                <h1>Website Address</h1>
                <div class="form-group">
                    <label class="control-label">Location</label>
                    <input type="text" name="website_location" class="form-control" required value="<?php echo get_option('website_location')?>"/>
                </div>
                <div class="form-group">
                    <?php $web_phone = get_option('website_phone') ? json_decode(get_option('website_phone')) : '';
                    ?>
                    <?php if(!$web_phone || !is_array($web_phone)):?>
                    <div class="table-responsive">
                        <table class="table" id="filesTableAddress">
                            <thead>
                            <tr>
                                <th>Phone Number</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="tableBodyAddress">
                            <tr>
                                <td>
                                    <input type="text" name="website_phone[]" class="form-control" />
                                </td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-default btn-sm" id="addFileAddress"><i class="fa fa-plus"></i> Add Row</button>
                    <br/><br/>
                    <?php endif;?>
                    <?php if($web_phone && is_array($web_phone)):?>
                        <div class="table-responsive">
                            <table class="table" id="filesTableAddress">
                                <thead>
                                <tr>
                                    <th>Phone Number</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="tableBodyAddress">
                                <?php foreach ($web_phone as $web):?>
                                <tr>
                                    <td>
                                        <input type="text" name="website_phone[]" class="form-control" value="<?php echo $web;?>" />
                                    </td>
                                    <td><button type="button" id="removeRowAddress" class="btn btn-sm btn-danger">x</button></td>
                                </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-default btn-sm" id="addFileAddress"><i class="fa fa-plus"></i> Add Row</button>
                        <br/><br/>
                    <?php endif;?>
                </div>
                <h1>Student Registration</h1>
                <div class="form-group">
                    <label class="control-label">Description</label>
                    <input type="text" name="student_description" class="form-control" value="<?php echo old('student_description', get_option('student_description', '')); ?>" required />
                </div>
                <div>
                    <fieldset>
                        <legend> <h3 style="text-align: center">Required Documents</h3></legend>
                    <div class="table-responsive">
                        <?php if (get_option('student_doc') && !empty(json_decode(get_option('student_doc')))):
                            ?>
                            <table class="table">
                                <tr>
                                    <th>File</th>
                                    <th>Actions</th>
                                </tr>
                                <?php
                                foreach (json_decode(get_option('student_doc')) as $pic):?>
                                    <tr>
                                        <td><img src="<?php echo base_url("uploads/files/".$pic)?>" alt="" width="150"></td>
                                        <td><button type="button" class="btn btn-danger btn-sm" onclick="removeStudentDoc('<?php echo $pic?>')">Remove</button></td>
                                    </tr>
                                <?php endforeach;?>
                            </table>
                        <?php endif;?>
                        <table class="table" id="filesTableRequiredDocs">
                            <thead>
                            <tr>
                                <th>File</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="tableBodyRequiredDocs">
                            <tr>
                                <td>
                                    <input type="file" name="student_doc[]" class="form-control" accept="image/*"/>
                                </td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-default btn-sm" id="addFileRequiredDoc"><i class="fa fa-plus"></i> Add Row</button>
                    </fieldset>
                </div>
                <br>
                <div>
                    <h3>Phone Number (For Assistance)</h3>
                    <?php $std_p = get_option('student_phone') ? json_decode(get_option('student_phone')) : '';
                    ?>
                    <?php if(!$std_p || !is_array($std_p)):?>
                    <div class="table-responsive">
                        <table class="table" id="filesTableStdPhone">
                            <thead>
                            <tr>
                                <th>Phone</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="tableBodyStdPhone">
                            <tr>
                                <td>
                                    <input type="text" name="student_phone[]" class="form-control"/>
                                </td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-default btn-sm" id="addFileStdPhone"><i class="fa fa-plus"></i> Add Row</button>
                    <br/><br/>
                    <?php endif;?>

                    <?php if($std_p && is_array($std_p)):?>
                        <div class="table-responsive">
                            <table class="table" id="filesTableStdPhone">
                                <thead>
                                <tr>
                                    <th>Phone</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="tableBodyStdPhone">
                                <?php foreach ($std_p as $p):?>
                                <tr>
                                    <td>
                                        <input type="text" name="student_phone[]" class="form-control" value="<?php echo $p;?>"/>
                                    </td>
                                    <td><button type="button" id="removeRowStdPhone" class="btn btn-sm btn-danger">x</button></td>
                                </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-default btn-sm" id="addFileStdPhone"><i class="fa fa-plus"></i> Add Row</button>
                        <br/><br/>
                    <?php endif;?>
                </div>
                <div class="form-group">
                    <h1>Terms and conditions</h1>
                    <?php $web_terms = get_option('website_terms') ? json_decode(get_option('website_terms')) : '';
                    ?>
                    <?php if(!$web_terms || !is_array($web_terms)):?>
                        <div class="table-responsive">
                            <table class="table" id="filesTableTerm">
                                <thead>
                                <tr>
                                    <th>Description</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="tableBodyTerm">
                                <tr>
                                    <td>
                                        <input type="text" name="website_term[]" class="form-control" />
                                    </td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-default btn-sm" id="addFileTerm"><i class="fa fa-plus"></i> Add Row</button>
                        <br/><br/>
                    <?php endif;?>
                    <?php if($web_terms && is_array($web_terms)):?>
                        <div class="table-responsive">
                            <table class="table" id="filesTableTerm">
                                <thead>
                                <tr>
                                    <th>Description</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="tableBodyTerm">
                                <?php foreach ($web_terms as $term):?>
                                    <tr>
                                        <td>
                                            <input type="text" name="website_term[]" class="form-control" value="<?php echo $term;?>"/>
                                        </td>
                                        <td><button type="button" id="removeRowTerm" class="btn btn-sm btn-danger">x</button></td>
                                    </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-default btn-sm" id="addFileTerm"><i class="fa fa-plus"></i> Add Row</button>
                        <br/><br/>
                    <?php endif;?>
                </div>
                        <div>
                            <fieldset>
                                <legend> <h1 style="text-align: center">Teacher Application</h1></legend>
                                <div class="table-responsive">
                                    <?php if (get_option('teacher_description_file') && !empty(json_decode(get_option('teacher_description_file')))):
                                        ?>
                                        <table class="table">
                                            <tr>
                                                <th>File</th>
                                                <th>Actions</th>
                                            </tr>
                                            <?php
                                            foreach (json_decode(get_option('teacher_description_file')) as $pic):?>
                                                <tr>
                                                    <td><img src="<?php echo base_url("uploads/files/".$pic)?>" alt="" width="150"></td>
                                                    <td><button type="button" class="btn btn-danger btn-sm" onclick="removeTeacherPic('<?php echo $pic?>')">Remove</button></td>
                                                </tr>
                                            <?php endforeach;?>
                                        </table>
                                    <?php endif;?>
                                <table class="table" id="filesTableDesc">
                                    <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBodyDesc">
                                    <tr>
                                        <td>
                                            <input type="file" name="description_file[]" class="form-control" accept="image/*"/>
                                        </td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-default btn-sm" id="addFileDesc"><i class="fa fa-plus"></i> Add Row</button>
                            </fieldset>
                        </div>
                         <br>
                        <div>
                            <fieldset>
                            <legend> <h1 style="text-align: center">Information:</h1></legend>
                            <div class="table-responsive">
                                <?php if (get_option('teacher_information_file') && !empty(json_decode(get_option('teacher_information_file')))):
                                    ?>
                                    <table class="table">
                                        <tr>
                                            <th>File</th>
                                            <th>Actions</th>
                                        </tr>
                                        <?php
                                        foreach (json_decode(get_option('teacher_information_file')) as $pic):?>
                                            <tr>
                                                <td><img src="<?php echo base_url("uploads/files/".$pic)?>" alt="" width="150"></td>
                                                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeInfoPic('<?php echo $pic?>')">Remove</button></td>
                                            </tr>
                                        <?php endforeach;?>
                                    </table>
                                <?php endif;?>
                                <table class="table" id="filesTableInfo">
                                    <thead>
                                    <tr>
                                        <th>File</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBodyInfo">
                                    <tr>
                                        <td>
                                        <input type="file" name="information_file[]" class="form-control" accept="image/*"/>
                                        </td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-default btn-sm" id="addFileInfo"><i class="fa fa-plus"></i> Add Row</button>

                            </fieldset>
                        </div>
                <br>
                <div>
                    <h3>Phone Number (For Assistance)</h3>
                    <?php $tec_p = json_decode(get_option('teacher_phone'));
                    ?>
                    <?php if(!$tec_p || !is_array($tec_p)):?>
                        <div class="table-responsive">
                            <table class="table" id="filesTableTPhone">
                                <thead>
                                <tr>
                                    <th>Phone</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="tableBodyTPhone">
                                <tr>
                                    <td>
                                        <input type="text" name="teacher_phone[]" class="form-control"/>
                                    </td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-default btn-sm" id="addFileTPhone"><i class="fa fa-plus"></i> Add Row</button>
                        <br/><br/>
                    <?php endif;?>

                    <?php if($tec_p && is_array($tec_p)):?>
                        <div class="table-responsive">
                            <table class="table" id="filesTableTPhone">
                                <thead>
                                <tr>
                                    <th>Phone</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="tableBodyTPhone">
                                <?php foreach ($tec_p as $p):?>
                                <tr>
                                        <td>
                                            <input type="text" name="teacher_phone[]" class="form-control" value="<?php echo $p;?>"/>
                                        </td>
                                        <td><button type="button" id="removeRowTPhone" class="btn btn-sm btn-danger">x</button></td>
                                </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-default btn-sm" id="addFileTPhone"><i class="fa fa-plus"></i> Add Row</button>
                        <br/><br/>
                    <?php endif;?>
                </div>
               <h1>ADMINISTRATION</h1>
                <div>
                    <fieldset>
                        <legend> <h4 style="text-align: center">Description</h4></legend>
                        <div class="table-responsive">
                            <?php if (get_option('admin_doc') && !empty(json_decode(get_option('admin_doc')))):
                                ?>
                                <table class="table">
                                    <tr>
                                        <th>File</th>
                                        <th>Actions</th>
                                    </tr>
                                    <?php
                                    foreach (json_decode(get_option('admin_doc')) as $pic):?>
                                        <tr>
                                            <td><img src="<?php echo base_url("uploads/files/".$pic)?>" alt="" width="150"></td>
                                            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeAdminPic('<?php echo $pic?>')">Remove</button></td>
                                        </tr>
                                    <?php endforeach;?>
                                </table>
                            <?php endif;?>
                        <table class="table" id="filesTableAdminFile">
                            <thead>
                            <tr>
                                <th>File</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="tableBodyAdminFile">
                            <tr>
                                <td>
                                    <input type="file" name="admin_doc[]" class="form-control" accept="image/*"/>
                                </td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-default btn-sm" id="addFileAdminFile"><i class="fa fa-plus"></i> Add Row</button>
                    </fieldset>
                </div>
                <br>
                <h3>Phone Number (For Assistance)</h3>
                <?php $admin_p = get_option('admin_phone') ? json_decode(get_option('admin_phone')) : '';?>
                <?php if(!$admin_p || !is_array($admin_p)):?>
                    <div class="table-responsive">
                        <table class="table" id="filesTableAPhone">
                            <thead>
                            <tr>
                                <th>Phone</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="tableBodyAPhone">
                            <tr>
                                <td>
                                    <input type="text" name="admin_phone[]" class="form-control"/>
                                </td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-default btn-sm" id="addFileAPhone"><i class="fa fa-plus"></i> Add Row</button>
                    <br/><br/>
                <?php endif;?>

                <?php if($admin_p && is_array($admin_p)):?>
                    <div class="table-responsive">
                        <table class="table" id="filesTableAPhone">
                            <thead>
                            <tr>
                                <th>Phone</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="tableBodyAPhone">
                            <?php foreach ($admin_p as $p):?>
                                <tr>
                                    <td>
                                        <input type="text" name="admin_phone[]" class="form-control" value="<?php echo $p;?>"/>
                                    </td>
                                    <td><button type="button" id="removeRowAPhone" class="btn btn-sm btn-danger">x</button></td>
                                </tr>
                            <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-default btn-sm" id="addFileAPhone"><i class="fa fa-plus"></i> Add Row</button>
                    <br/><br/>
                <?php endif;?>
        <h1>Contact Us</h1>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Logo 1</label>
                    <input type="file" name="logo1" class="form-control" accept="image/*"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Logo 2</label>
                    <input type="file" name="logo2" class="form-control" accept="image/*"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <h3>Address 1 <?php
                        $phone1 = get_option('phone1') ? json_decode(get_option('phone1')) : '';

                        ?></h3>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control" name="address1" value="<?php echo get_option('address1');?>" required>
                    </div>
                    <?php if(!$phone1 || !$phone1):?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Phone Number</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="tableBodyPhone1">
                                <tr>
                                    <td>
                                        <input type="text" name="phone1[]" class="form-control" />
                                    </td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-default btn-sm" id="addFileAdd1"><i class="fa fa-plus"></i> Add Row</button>
                            <br/><br/>
                        </div>
                    <?php endif;?>

                    <?php if($phone1 || is_array($phone1)):?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Phone</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="tableBodyPhone1">
                                <?php foreach ($phone1 as $key):
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="text" name="phone1[]" class="form-control" value="<?php echo $key;?>"/>
                                        </td>
                                        <td> <button type="button" id="removeRowAdd1" class="btn btn-sm btn-danger">x</button></td>
                                    </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-default btn-sm" id="addFileAdd1"><i class="fa fa-plus"></i> Add Row</button>
                            <br/><br/>
                        </div>
                    <?php endif;?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <h3>Address 2</h3>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control" name="address2" value="<?php echo get_option('address2');?>" required>
                    </div>
                    <?php
                    $phone2 = get_option('phone2') ? json_decode(get_option('phone2')) : '';
                    ?>
                    <?php if(!$phone2 || !is_array($phone2)):?>
                        <div class="table-responsive">
                            <table class="table" id="filesTableAdd2">
                                <thead>
                                <tr>
                                    <th>Phone Number</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="tableBodyAdd2">
                                <tr>
                                    <td>
                                        <input type="text" name="phone2[]" class="form-control"/>
                                    </td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-default btn-sm" id="addFileAdd2"><i class="fa fa-plus"></i> Add Row</button>
                            <br/><br/>
                        </div>
                    <?php endif;?>
                    <?php if($phone2 && is_array($phone2)):?>
                        <div class="table-responsive">
                            <table class="table" id="filesTableAdd2">
                                <thead>
                                <tr>
                                    <th>Phone</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="tableBodyAdd2">
                                <?php foreach ($phone2 as $key2):
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="text" name="phone2[]" class="form-control" value="<?php echo $key2;?>" />
                                        </td>
                                        <td><button type="button" id="removeRowAdd2" class="btn btn-sm btn-danger">x</button></td>
                                    </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-default btn-sm" id="addFileAdd2"><i class="fa fa-plus"></i> Add Row</button>
                            <br/><br/>
                        </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <h1>Links</h1>
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Facebook</span>
                    </div>
                    <input type="text" class="form-control" name="facebook_link" value="<?php echo old('facebook_link', get_option('facebook_link', '')); ?>">
                </div>
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Twitter</span>
                    </div>
                    <input type="text" class="form-control" name="twitter_link" value="<?php echo old('twitter_link', get_option('twitter_link', '')); ?>">
                </div>
                <div style="display: flex">
                    <div class="input-group mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Telegram Name</span>
                        </div>
                        <input type="text" class="form-control" name="telegram_link" value="<?php echo old('telegram_link', get_option('telegram_link', '')); ?>">
                    </div>
                    <div class="input-group mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Telegram Link</span>
                        </div>
                        <input type="text" class="form-control" name="telegram_url" value="<?php echo old('telegram_url', get_option('telegram_url', 'https://telegram.org/')); ?>">
                    </div>
                </div>

                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Youtube</span>
                    </div>
                    <input type="text" class="form-control" name="youtube_link" value="<?php echo old('youtube_link', get_option('youtube_link', '')); ?>">
                </div>
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Website</span>
                    </div>
                    <input type="text" class="form-control" name="website_link" value="<?php echo old('website_link', get_option('website_link', 'http://www.aspireschoolet.com/')); ?>">
                </div>
                <!--                        <div class="form-group">-->
                <!--                            <label class="control-label">Links</label>-->
                <!--                            <input type="file" name="log2" class="form-control" value="--><?php //echo old('logo1', get_option('logo1', '')); ?><!--" required />-->
                <!--                        </div>-->
            </div>
        </div>

    </div>

                <button class="btn btn-success" type="submit">Save Changes</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '#removeRow', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFile', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="file" name="website_pictures[]" class="form-control" accept="image/*"/>\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRow" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBody").append(html);
        //$('#filesTable').append(html);
    });

    //Terms and conditions
    $(document).on('click', '#removeRowTerm', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFileTerm', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="text" name="website_term[]" class="form-control" />\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRowTerm" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBodyTerm").append(html);
        //$('#filesTable').append(html);
    });


// website address
    $(document).on('click', '#removeRowAddress', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFileAddress', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="text" name="website_phone[]" class="form-control" />\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRowAddress" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBodyAddress").append(html);
        //$('#filesTable').append(html);
    });
        //Student required docs
        $(document).on('click', '#removeRowRequiredDoc', function (e) {
            e.preventDefault();
            $(this).parents('tr').remove();
        });

        $(document).on('click', '#addFileRequiredDoc', function (e) {
            //e.preventDefault();
            var html = '<tr>\n' +
                '        <td>\n' +
                '            <input type="file" name="student_doc[]" class="form-control" accept="image/*"/>\n' +
                '        </td>\n' +
                '        <td>\n' +
                '            <button type="button" id="removeRowRequiredDoc" class="btn btn-sm btn-danger">x</button>\n' +
                '        </td>\n' +
                '    </tr>';
            $("#tableBodyRequiredDocs").append(html);
            //$('#filesTable').append(html);
        });

    $(document).on('click', '#removeRowStdPhone', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFileStdPhone', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="text" name="student_phone[]" class="form-control"/>\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRowStdPhone" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBodyStdPhone").append(html);
        //$('#filesTable').append(html);
    });

  //Teacher Application
    $(document).on('click', '#removeRowDesc', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFileDesc', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="file" name="description_file[]" class="form-control" accept="image/*"/>\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRowRequiredDoc" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBodyDesc").append(html);
        //$('#filesTable').append(html);
    });

    //Administration Application
    $(document).on('click', '#removeRowAdmin', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFileAdmin', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="text" name="admin_file_description[]" class="form-control"/>\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <input type="file" name="admin_file[]" class="form-control" accept="application/pdf"/>\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRowAdmin" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBodyAdmin").append(html);
        //$('#filesTable').append(html);
    });

    //Administration Application
    $(document).on('click', '#removeRowAdminFile', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFileAdminFile', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="file" name="admin_doc[]" class="form-control" accept="image/*"/>\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRowAdminFile" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBodyAdminFile").append(html);
        //$('#filesTable').append(html);
    });

    $(document).on('click', '#removeRowTPhone', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFileTPhone', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="text" name="teacher_phone[]" class="form-control"/>\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRowTPhone" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBodyTPhone").append(html);
        //$('#filesTable').append(html);
    });

    $(document).on('click', '#removeRowAPhone', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFileAPhone', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="text" name="admin_phone[]" class="form-control"/>\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRowAPhone" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBodyAPhone").append(html);
        //$('#filesTable').append(html);
    });

    //Teacher Info
    $(document).on('click', '#removeRowInfo', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFileInfo', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="file" name="information_file[]" class="form-control" accept="image/*"/>\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRowInfo" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBodyInfo").append(html);
        //$('#filesTable').append(html);
    });

    //Address 1
    $(document).on('click', '#removeRowAdd1', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFileAdd1', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="text" name="phone1[]" class="form-control" />\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRowAdd1" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBodyPhone1").append(html);
        //$('#filesTable').append(html);
    });

    //Address 2
    $(document).on('click', '#removeRowAdd2', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFileAdd2', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="text" name="phone2[]" class="form-control" />\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRowAdd2" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBodyAdd2").append(html);
        //$('#filesTable').append(html);
    });

   function removePic(data){
       var data = {
           url: "<?php echo site_url('/admin/frontend/remove-picture') ?>",
           data: "picture=" + data,
           loader: true
       };
       ajaxRequest(data, function (data) {
          window.location.reload();
       });
    }
    function removeInfoPic(data){
        var data = {
            url: "<?php echo site_url('/admin/frontend/remove-info-picture') ?>",
            data: "picture=" + data,
            loader: true
        };
        ajaxRequest(data, function (data) {
            window.location.reload();
        });
    }
    function removeTeacherPic(data){
        var data = {
            url: "<?php echo site_url('/admin/frontend/remove-teacher-picture') ?>",
            data: "picture=" + data,
            loader: true
        };
        ajaxRequest(data, function (data) {
            window.location.reload();
        });
    }
    function removeAdminPic(data){
        var data = {
            url: "<?php echo site_url('/admin/frontend/remove-admin-picture') ?>",
            data: "picture=" + data,
            loader: true
        };
        ajaxRequest(data, function (data) {
            window.location.reload();
        });
    }
    function removeStudentDoc(data){
       var data = {
           url: "<?php echo site_url('/admin/frontend/remove-student-doc') ?>",
           data: "doc=" + data,
           loader: true
       };
       ajaxRequest(data, function (data) {
          window.location.reload();
       });
    }

    function removeLogo(){
       var data = {
           url: "<?php echo site_url('/admin/frontend/remove-logo') ?>",
           data: "",
           loader: true
       };
       ajaxRequest(data, function (data) {
          window.location.reload();
       });
    }

</script>