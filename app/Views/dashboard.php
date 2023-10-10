<?= $this->extend("layouts/master_app") ?>

<?= $this->section("content") ?>

<section class="content">
    <div class="box-body d-flex px-0">
        <div class="flex-grow-1 p-30 flex-grow-1 bg-img dask-bg bg-none-md" style="background-position: right bottom; background-size: auto 100%; background-image: url(https://eduadmin-template.multipurposethemes.com/bs5/images/svg-icon/color-svg/custom-1.svg)">
            <div class="row">
                <div class="col-12 col-xl-7">
                    <h2>Selamat Datang, <strong>
                            <?= session()->get('email') ?>
                        </strong></h2>

                    <p class="text-dark my-10 fs-16">
                        Selamat datang di Aplikasi <strong class="text-warning">Task Scheduler</strong>
                    </p>
                </div>
                <div class="col-12 col-xl-5"></div>
            </div>
        </div>
    </div>
</section>

<?php if (session()->get('level') == 'Admin') : ?>
    <section class="content">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user-headset"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Admin</span>
                        <span class="info-box-number"><?= $total_admin->total_admin; ?>

                        </span>
                    </div>

                </div>

            </div>

            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user-tie"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Vendor</span>
                        <span class="info-box-number"><?= $total_vendor->total_vendor; ?></span>
                    </div>

                </div>

            </div>

            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fad fa-users-class"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pegawai</span>
                        <span class="info-box-number"><?= $total_pegawai->total_pegawai; ?></span>
                    </div>

                </div>

            </div>

            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fad fa-tasks-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Project</span>
                        <span class="info-box-number"><?= $total_project->total_project; ?></span>
                    </div>

                </div>

            </div>

            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="far fa-file-chart-pie"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Project Vendor</span>
                        <span class="info-box-number">
                            <?= $total_project_vendor->total_project_vendor; ?></span>
                        </span>
                    </div>

                </div>

            </div>

            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fab fa-stack-overflow"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Project Pegawai</span>
                        <span class="info-box-number"> <?= $total_project_pegawai->total_project_pegawai; ?></span>
                    </div>

                </div>

            </div>


        </div>

    </section>
<?php endif; ?>
<?php if (session()->get('level') != 'Pegawai') : ?>
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h4 class="box-title text-capitalize">Data project vendor</h4>
            </div>
            <div class="box-body">
                <table id="data_table_pv" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>project name</th>
                            <th>vendor name</th>
                            <th>role name</th>
                            <th>remaining time</th>
                            <th>link project</th>
                            <th>link pengumpulan</th>
                            <th>status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php if (session()->get('level') != 'Vendor') : ?>
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h4 class="box-title text-capitalize">Data project pegawai</h4>
            </div>
            <div class="box-body">
                <table id="data_table_pp" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>project name</th>
                            <th>pegawai name</th>
                            <th>role name</th>
                            <th>remaining time</th>
                            <th>link project</th>
                            <th>status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
<?php endif; ?>


<?= $this->endSection() ?>

<!-- page script -->
<?= $this->section("script") ?>
<script>
    $(function() {
        var table = $('#data_table_pv').removeAttr('width').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "scrollY": '45vh',
            "scrollX": true,
            "scrollCollapse": false,
            "responsive": false,
            "ajax": {
                "url": '<?php echo base_url($controller . "/getAllProjectVendor") ?>',
                "type": "POST",
                "dataType": "json",
                async: "true"
            }
        });
    });

    $(function() {
        var table = $('#data_table_pp').removeAttr('width').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "scrollY": '45vh',
            "scrollX": true,
            "scrollCollapse": false,
            "responsive": false,
            "ajax": {
                "url": '<?php echo base_url($controller . "/getAllProjectPegawai") ?>',
                "type": "POST",
                "dataType": "json",
                async: "true"
            }
        });
    });
</script>
<?= $this->endSection() ?>