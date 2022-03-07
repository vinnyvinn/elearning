<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">ID Card</h6>
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
 <?php
$classes = getSession()->classes->findAll();
?>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <form class="ajaxForm" method="post"  loader="true" action="<?php echo site_url(route_to('admin.settings.save_id')); ?>" data-parsley-validate enctype="multipart/form-data">
              <h1>Front Side</h1>
                <div class="form-group">
                    <label class="control-label">School Name</label>
                    <input type="text" name="id_school_name" class="form-control" value="<?php echo old('id_school_name', get_option('id_school_name', '')); ?>" required />
                </div>
                <div class="form-group">
                    <label class="control-label">School Name(In Amharic)</label>
                    <input type="text" name="id_school_name_amharic" class="form-control" value="<?php echo old('id_school_name_amharic', get_option('id_school_name_amharic', 'አስፓየር ዩዝ አካዳሚ')); ?>" required />
                </div>
                <div class="form-group">
                    <label class="control-label">Grade Range</label>
                    <input type="text" name="id_grade_range" class="form-control" value="<?php echo old('id_grade_range', get_option('id_grade_range', '(KG- Grade 12)')); ?>" required />
                </div>
                <h2>Address</h2>
                <div class="form-group">
                    <label class="control-label">Location</label>
                    <input type="text" name="id_location" class="form-control" value="<?php echo old('id_location', get_option('id_location', '')); ?>" required />
                </div>
                <fieldset>
                    <legend> <h1 style="text-align: center">Phone Number (Kindergarten):</h1></legend>
                    <div class="form-group">
                        <label>Classes(s)</label>
                        <?php $kg_class = get_option('id_kg_class') ? json_decode(get_option('id_kg_class')) : '';
                        ?>
                        <select name="id_kg_class[]" class="form-control select2" multiple required>
                            <?php foreach ($classes as $class):?>
                            <option value="<?php echo $class->id?>" <?php if (is_array($kg_class) && in_array($class->id,$kg_class)):?> selected="selected" <?php endif;?>><?php echo $class->name;?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                <div class="form-group">
                    <?php $kg_phone = get_option('id_kg_phone') ? json_decode(get_option('id_kg_phone')) : '';
                    ?>
                    <?php if(!$kg_phone || !is_array($kg_phone)):?>
                        <div class="table-responsive">
                            <table class="table" id="filesTable">
                                <thead>
                                <tr>
                                    <th>Phone</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="tableBody">
                                <tr>
                                    <td>
                                        <input type="text" name="id_kg_phone[]" class="form-control" />
                                    </td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-default btn-sm" id="addFile"><i class="fa fa-plus"></i> Add Row</button>
                        <br/><br/>
                    <?php endif;?>
                    <?php if($kg_phone && is_array($kg_phone)):?>
                        <div class="table-responsive">
                            <table class="table" id="filesTable">
                                <thead>
                                <tr>
                                    <th>Phone</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="tableBody">
                                <?php foreach ($kg_phone as $p):?>
                                    <tr>
                                        <td>
                                            <input type="text" name="id_kg_phone[]" class="form-control" value="<?php echo $p;?>" />
                                        </td>
                                        <td><button type="button" id="removeRowAdd" class="btn btn-sm btn-danger">x</button></td>
                                    </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-default btn-sm" id="addFile"><i class="fa fa-plus"></i> Add Row</button>
                        <br/><br/>
                    <?php endif;?>
                </div>
                </fieldset>
                <br>
                <fieldset>
                    <legend> <h1 style="text-align: center">Phone Number (Elementary):</h1></legend>
                    <div class="form-group">
                        <label>Classes(s)</label>
                        <?php $el_class = get_option('id_el_class') ? json_decode(get_option('id_el_class')) : '';
                        ?>
                        <select name="id_el_class[]" class="form-control select2" multiple required>
                            <?php foreach ($classes as $class):?>
                                <option value="<?php echo $class->id?>" <?php if (is_array($el_class) && in_array($class->id,$el_class)):?> selected="selected" <?php endif;?>><?php echo $class->name;?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="form-group">
                        <?php $el_phone = get_option('id_el_phone') ? json_decode(get_option('id_el_phone')) : '';
                        ?>
                        <?php if(!$el_phone || !is_array($el_phone)):?>
                            <div class="table-responsive">
                                <table class="table" id="filesTable">
                                    <thead>
                                    <tr>
                                        <th>Phone</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBodyEl">
                                    <tr>
                                        <td>
                                            <input type="text" name="id_el_phone[]" class="form-control" />
                                        </td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-default btn-sm" id="addFileEl"><i class="fa fa-plus"></i> Add Row</button>
                            <br/><br/>
                        <?php endif;?>
                        <?php if($el_phone && is_array($el_phone)):?>
                            <div class="table-responsive">
                                <table class="table" id="filesTable">
                                    <thead>
                                    <tr>
                                        <th>Phone</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBodyEl">
                                    <?php foreach ($el_phone as $p):?>
                                        <tr>
                                            <td>
                                                <input type="text" name="id_el_phone[]" class="form-control" value="<?php echo $p;?>" />
                                            </td>
                                            <td><button type="button" id="removeRowEl" class="btn btn-sm btn-danger">x</button></td>
                                        </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-default btn-sm" id="addFileEl"><i class="fa fa-plus"></i> Add Row</button>
                            <br/><br/>
                        <?php endif;?>
                    </div>
                </fieldset>
                <br>
                <fieldset>
                    <legend> <h1 style="text-align: center">Phone Number (High School):</h1></legend>
                    <div class="form-group">
                        <label>Classes(s)</label>
                        <?php $hs_lass = get_option('id_hs_class') ? json_decode(get_option('id_hs_class')) : '';
                        ?>
                        <select name="id_hs_class[]" class="form-control select2" multiple required>
                            <?php foreach ($classes as $class):?>
                                <option value="<?php echo $class->id?>" <?php if (is_array($hs_lass) && in_array($class->id,$hs_lass)):?> selected="selected" <?php endif;?>><?php echo $class->name;?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="form-group">
                        <?php $hs_phone = get_option('id_hs_phone') ? json_decode(get_option('id_hs_phone')) : '';
                        ?>
                        <?php if(!$hs_phone || !is_array($hs_phone)):?>
                            <div class="table-responsive">
                                <table class="table" id="filesTable">
                                    <thead>
                                    <tr>
                                        <th>Phone</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBodyHs">
                                    <tr>
                                        <td>
                                       <input type="text" name="id_hs_phone[]" class="form-control"/>
                                        </td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                       <button type="button" class="btn btn-default btn-sm" id="addFileHs"><i class="fa fa-plus"></i> Add Row</button>
                            <br/><br/>
                        <?php endif;?>
                        <?php if($hs_phone && is_array($hs_phone)):?>
                            <div class="table-responsive">
                                <table class="table" id="filesTable">
                                    <thead>
                                    <tr>
                                        <th>Phone</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBodyHs">
                                    <?php foreach ($hs_phone as $p):?>
                                        <tr>
                                            <td>
                                                <input type="text" name="id_hs_phone[]" class="form-control" value="<?php echo $p;?>" />
                                            </td>
                                            <td><button type="button" id="removeRowHs" class="btn btn-sm btn-danger">x</button></td>
                                        </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-default btn-sm" id="addFileHs"><i class="fa fa-plus"></i> Add Row</button>
                            <br/><br/>
                        <?php endif;?>
                    </div>
                </fieldset>
                <br>


                    <fieldset>
                        <legend> <h1 style="text-align: center">Date Issued:</h1></legend>
                           <div class="row">
                               <div class="col-md-6">
                                   <div class="form-group">
                                       <label class="control-label">Label</label>
                                       <input type="text" name="id_date_issued_label" class="form-control" required value="<?php echo get_option('id_date_issued_label')?>"/>
                                   </div>
                               </div>
                               <div class="col-md-6">
                                   <div class="form-group">
                                       <label class="control-label">Content</label>
                                       <input type="text" name="id_date_issued" class="form-control" value="<?php echo get_option('id_date_issued')?>"/>
                                   </div>
                               </div>
                               <div class="form-group">
                                   <label>Show Date Issued?</label><br/>
                                   <label class="custom-toggle">
                                       <input type="checkbox" name="id_show_date_issued" class="optional"
                                              value="1" <?php echo old('id_show_date_issued', get_option('id_show_date_issued')) == 1 ? 'checked' : ''; ?> />
                                       <span class="custom-toggle-slider rounded-circle" data-label-off="No"
                                             data-label-on="Yes"></span>
                                   </label>
                               </div>
                           </div>
                    </fieldset>


                <fieldset>
                    <legend> <h1 style="text-align: center">Expiry Date:</h1></legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Label</label>
                                <input type="text" name="id_expiry_date_label" class="form-control" required value="<?php echo get_option('id_expiry_date_label')?>"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Content</label>
                                <input type="text" name="id_expiry_date" class="form-control" value="<?php echo get_option('id_expiry_date')?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Show Expiry Date?</label><br/>
                            <label class="custom-toggle">
                                <input type="checkbox" name="id_show_expiry_date" class="optional"
                                       value="1" <?php echo old('id_show_expiry_date', get_option('id_show_expiry_date')) == 1 ? 'checked' : ''; ?> />
                                <span class="custom-toggle-slider rounded-circle" data-label-off="No"
                                      data-label-on="Yes"></span>
                            </label>
                        </div>
                    </div>
                </fieldset>

                <h1>Back Side</h1>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Parent/Gardian</label>
                            <input type="text" name="id_parent" class="form-control" required value="<?php echo get_option('id_parent')?>"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Address</label>
                            <input type="text" name="id_address" class="form-control" required value="<?php echo get_option('id_address')?>"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Subcity</label>
                            <input type="text" name="id_subcity" class="form-control" required value="<?php echo get_option('id_subcity')?>"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Woreda</label>
                            <input type="text" name="id_woreda" class="form-control" required value="<?php echo get_option('id_woreda')?>"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">House Number</label>
                            <input type="text" name="id_house_no" class="form-control" required value="<?php echo get_option('id_house_no')?>"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Phone Number 1</label>
                            <input type="text" name="id_phone1" class="form-control" required value="<?php echo get_option('id_phone1')?>"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Phone Number 2</label>
                            <input type="text" name="id_phone2" class="form-control" required value="<?php echo get_option('id_phone2')?>"/>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Header</label>
                            <input type="text" name="id_header" class="form-control" required value="<?php echo get_option('id_header')?>"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <h5>Text</h5>
                    <?php $text = get_option('id_text') ? json_decode(get_option('id_text')) : '';
                    ?>
                    <?php if(!$text || !is_array($text)):?>
                        <div class="table-responsive">
                            <table class="table" id="filesTableT">
                                <thead>
                                <tr>
                                    <th>Text</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="tableBodyT">
                                <tr>
                                    <td>
                                        <input type="text" name="id_text[]" class="form-control" />
                                    </td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-default btn-sm" id="addFileT"><i class="fa fa-plus"></i> Add Row</button>
                        <br/><br/>
                    <?php endif;?>
                    <?php if($text && is_array($text)):?>
                        <div class="table-responsive">
                            <table class="table" id="filesTableT">
                                <thead>
                                <tr>
                                    <th>Text</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="tableBodyT">
                                <?php foreach ($text as $t):?>
                                    <tr>
                                        <td>
                                            <input type="text" name="id_text[]" class="form-control" value="<?php echo $t;?>" />
                                        </td>
                                        <td><button type="button" id="removeRowT" class="btn btn-sm btn-danger">x</button></td>
                                    </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-default btn-sm" id="addFileT"><i class="fa fa-plus"></i> Add Row</button>
                        <br/><br/>
                    <?php endif;?>
                </div>
                <div class="form-group">
                    <label class="control-label">Sign</label>
                    <input type="text" name="id_sign" class="form-control" value="<?php echo old('id_sign', get_option('id_sign', '')); ?>" required />
                </div>

                <div class="form-group">
                    <label>Autofill?</label><br/>
                    <label class="custom-toggle">
                        <input type="checkbox" name="id_autofill" class="optional"
                               value="1" <?php echo old('id_autofill', get_option('id_autofill')) == 1 ? 'checked' : ''; ?> />
                        <span class="custom-toggle-slider rounded-circle" data-label-off="No"
                              data-label-on="Yes"></span>
                    </label>
                </div>

    <button class="btn btn-success" type="submit">Save Changes</button>
    </form>
</div>
</div>
</div>

<script>
    $(document).on('click', '#removeRowAdd', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFile', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="text" name="id_kg_phone[]" class="form-control"/>\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRowAdd" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBody").append(html);
    });

    $(document).on('click', '#removeRowEl', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFileHs', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="text" name="id_hs_phone[]" class="form-control"/>\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRowHs" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBodyHs").append(html);
    });

    $(document).on('click', '#removeRowHs', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFileEl', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="text" name="id_el_phone[]" class="form-control"/>\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRowEl" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBodyEl").append(html);
    });

    $(document).on('click', '#removeRowT', function (e) {
        e.preventDefault();
        $(this).parents('tr').remove();
    });

    $(document).on('click', '#addFileT', function (e) {
        //e.preventDefault();
        var html = '<tr>\n' +
            '        <td>\n' +
            '            <input type="text" name="id_text[]" class="form-control"/>\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRowT" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBodyT").append(html);
    });

</script>