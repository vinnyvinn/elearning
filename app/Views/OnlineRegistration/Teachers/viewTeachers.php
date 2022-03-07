<?php




?>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Application Details</h6><br/>
                    <small class="text-white"><?php echo $teacher->name; ?></small>
                </div>
                <div class="col-lg-6 col-5 text-right">

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body">
            <?php
            //d($teacher);
            ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-profile">
                        <img src="<?php echo base_url('assets/img/theme/img-1-1000x600.jpg'); ?>"
                             alt="<?php echo $teacher->name; ?>" class="card-img-top">
                        <div class="row justify-content-center">
                            <div class="col-lg-3 order-lg-2">
                                <div class="card-profile-image">
                                    <a href="#!">
                                        <img src="<?php echo base_url('uploads/avatars/'.$teacher->info->profile_pic); ?>" class="rounded-circle">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-6">
                            <div class="text-center">
                                <h5 class="h3"><?php echo $teacher->name; ?>
                                    <span class="font-weight-light">, <?php echo $teacher->gender ? $teacher->gender : '-'; ?></span>
                                </h5>
                                <div>
                                    <h3>Subject to Teach</h3>
                                    <h1 class="bg-info"><b><?php echo @(new \App\Models\Subjects())->find($teacher->info->subject)->name ?? '-'; ?></b></h1>
                                </div>
                                <div>
                                    <h3>Application Date</h3>
                                    <h5><?php echo $teacher->created_at->format('d/m/Y, h:i A'); ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h5 class="h4">Residence</h5>
                                                <div class="h5 font-weight-300">
                                                    <i class="ni location_pin mr-2"></i><?php echo $teacher->info->subcity; ?>
                                                    , <?php echo $teacher->info->woreda; ?>
                                                    , <?php echo $teacher->info->house_number; ?>
                                                </div>
                                                <h5 class="h4">Phone (Mobile)</h5>
                                                <?php echo $teacher->info->phone_number; ?>
                                                <h5 class="h4">Phone (Work)</h5>
                                                <?php echo $teacher->info->phone_work; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h4>Experience in Years</h4>
                                    <?php
                                    echo @$teacher->info->experience;
                                    ?>
                                </div>
                                <?php
                                if (isset($teacher->info->teacher_required_files) && !empty($teacher->info->teacher_required_files)){
                                    $files = json_decode($teacher->info->teacher_required_files);
                                    ?>
                                    <div class="card-header">
                                        <h3>Files</h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th>File</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($files as $file):?>
                                                <tr>
                                                    <td><?php echo $file->title;?></td>
                                                    <td><a href="#" onclick="downloadFile('<?php echo base_url('uploads/files/'.$file->file)?>')"><i class="fa fa-download"></i> Download</a></td>
                                                </tr>
                                            <?php endforeach;?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    function downloadFile(file){
        window.location.href = file;
    }
</script>