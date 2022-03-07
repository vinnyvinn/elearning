<?php

?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
               <div class="col-lg-6 col-7">
               <h6 class="h2 text-white d-inline-block mb-0">KG  Evaluation Categories</h6>
               </div>
               <div class="col-lg-6 col-5 text-right">
               <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target=".new_slide">New Category</button>
            </div>
            </div>
            <div class="modal fade new_slide" role="dialog" style="display: none">
                <div class="modal modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="card">
                            <div class="card-body">
                                <form class="ajaxForm" loader="true" method="post" action="<?php echo site_url(route_to('admin.settings.save_category')); ?>">
                                    <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" required value="<?php echo old('name'); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Sub Category (optional)</label>
                                        <?php $sub_categories = (new \App\Models\KGSubCategory())->findAll();
                                        ?>
                                        <select name="sub_category_id[]" class="form-control select2" multiple>
                                            <option></option>
                                            <?php foreach ($sub_categories as $category):?>
                                                <option value="<?php echo $category['id']?>"><?php echo $category['name'];?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                        <button type="submit" class="btn btn-success">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-body">
            <?php
            $categories = (new \App\Models\KGCategory())->orderBy('id', 'DESC')->findAll();
            if(isset($categories) && count($categories) > 0) {
                ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 0;
                        foreach ($categories as $category) {
                            $n++;
                            ?>
                            <tr>
                                <td><?php echo $n; ?></td>
                                <td><?php echo word_limiter($category['name'], 10); ?></td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                            data-target=".edit_<?php echo $category['id']; ?>"><i class="fa fa-edit"></i> Edit
                                    </button>
                                    <div class="modal fade edit_<?php echo $category['id']; ?>" role="document"
                                         aria-labelledby="modal-default"
                                         style="display: none;" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form class="ajaxForm" loader="true" method="post"
                                                      action="<?php echo site_url(route_to('admin.settings.category.update', $category['id'])); ?>">
                                                    <input type="hidden" name="id" value="<?php echo $category['id']; ?>" />
                                                    <div class="modal-header">
                                                        <h6 class="modal-title" id="modal-title-default">Update Evaluation</h6>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="sess">Name</label>
                                                            <input type="text" name="name" class="form-control" value="<?php echo $category['name'];?>" required>
                                                        </div>
                                                        <div class="form-group" style="display: grid">
                                                            <label>Sub Category (optional)</label>
                                                            <?php $sub_categories = (new \App\Models\KGSubCategory())->findAll();
                                                            $categories = !empty($category['sub_category_id']) ? json_decode($category['sub_category_id']) : [];
                                                            ?>
                                                            <select name="sub_category_id[]" class="form-control select2" multiple>
                                                                <option></option>
                                                                <?php foreach ($sub_categories as $cat):?>
                                                                <option value="<?php echo $cat['id']?>" <?php if (in_array($cat['id'],$categories)):?> selected="selected" <?php endif;?>><?php echo $cat['name'];?></option>
                                                                 <?php endforeach;?>
                                                            </select>
                                                        </div>
                                                        </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success">Update</button>
                                                        <button type="button" class="btn btn-link  ml-auto"
                                                                data-dismiss="modal">Close
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                            <?php if (isSuperAdmin()):?>
                                    <a class="btn btn-danger btn-sm send-to-server-click" href="<?php echo site_url(route_to('admin.settings.category.delete', $category['id'])); ?>"
                                       loader="true"
                                       warning-title="Delete this Category?"
                                       warning-message="Are you sure you want to delete this Category?"
                                       warning-button="Yes, Delete"
                                       url="<?php echo site_url(route_to('admin.settings.category.delete', $category['id'])); ?>"
                                       data="action:delete|id:<?php echo $category['id']; ?>"
                                    >Delete</a>
                            <?php endif;?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
            } else {
                ?>
                <div class="alert alert-danger">
                    No Category have been posted
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>