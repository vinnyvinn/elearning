<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">SECTION 3</h6>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 offset-1">
            <div class="card">
                <div class="card-body">
                    <form class="ajaxForm" loader="true" method="post" action="<?php echo site_url(route_to('admin.frontend.save_mission')); ?>" enctype="multipart/form-data">
                        <h4>Mission Statement</h4>
                        <div class="form-group">
                            <textarea id="textarea" class="form-control" name="mission_statement" rows="4" required><?php echo old('mission_statement', get_option('mission_statement')); ?></textarea>
                        </div>
                        <div class="form-group">
                            <input type="file" name="mission_statement_file"
                                   class="form-control" accept="image/*"/>
                        </div>
                        <h4>Vision Statement</h4>
                        <div class="form-group">
                            <textarea id="textarea" class="form-control" name="vision_statement" rows="4" required><?php echo old('vision_statement', get_option('vision_statement')); ?></textarea>
                        </div>
                        <div class="form-group">
                            <input type="file" name="vision_statement_file"
                                   class="form-control" accept="image/*"/>
                        </div>
                        <h4>Goal Statement</h4>
                        <div class="form-group">
                            <textarea id="textarea" class="form-control" name="goal_statement" rows="4" required><?php echo old('goal_statement', get_option('goal_statement')); ?></textarea>
                        </div>
                        <div class="form-group">
                            <input type="file" name="goal_statement_file"
                                   class="form-control" accept="image/*"/>
                        </div>
                        <h3>Footer Items</h3>
                        <?php $footer = get_option('footer_items') ? json_decode(get_option('footer_items')) : '';
                        ?>
                        <?php if(!$footer || !is_array($footer)):?>
                        <div class="table-responsive">
                            <table class="table" id="filesTable">
                                <thead>
                                <tr>
                                    <th>Number</th>
                                    <th>Description</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="tableBody">
                                <tr>
                                    <td>
                                        <input type="text" name="footer_number[]" class="form-control" />
                                    </td>
                                    <td>
                                        <input type="text" name="footer_description[]" class="form-control" />
                                    </td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-default btn-sm" id="addFile"><i class="fa fa-plus"></i> Add Row</button>
                        <br/><br/>
                        <?php endif;?>
                        <?php if($footer && is_array($footer)):?>
                            <div class="table-responsive">
                                <table class="table" id="filesTable">
                                    <thead>
                                    <tr>
                                        <th>Number</th>
                                        <th>Description</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                    <?php foreach ($footer as $ft):?>
                                    <tr>
                                        <td>
                                            <input type="text" name="footer_number[]" class="form-control" value="<?php echo $ft->number;?>"/>
                                        </td>
                                        <td>
                                            <input type="text" name="footer_description[]" class="form-control" value="<?php echo $ft->description;?>"/>
                                        </td>
                                        <td><button type="button" id="removeRow" class="btn btn-sm btn-danger">x</button></td>
                                    </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-default btn-sm" id="addFile"><i class="fa fa-plus"></i> Add Row</button>
                            <br/><br/>
                        <?php endif;?>
                        <div class="form-group">
                            <input type="file" name="footer_file"
                                   class="form-control" accept="image/*"/>
                        </div>


                        <br/>
                        <button type="submit" class="btn btn-success">Save</button>
                    </form>
                </div>
            </div>
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
            '            <input type="text" name="footer_number[]" class="form-control" />\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <input type="text" name="footer_description[]" class="form-control" />\n' +
            '        </td>\n' +
            '        <td>\n' +
            '            <button type="button" id="removeRow" class="btn btn-sm btn-danger">x</button>\n' +
            '        </td>\n' +
            '    </tr>';
        $("#tableBody").append(html);
        //$('#filesTable').append(html);
    });

    function readURL(input, prevHolder) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(prevHolder).attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    $("[type='file']").change(function (e) {
        var prevHolder = "#" + e.target.id + "_prev";
        readURL(this, prevHolder);
    });
</script>
