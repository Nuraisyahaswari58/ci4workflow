<?= $this->extend("layouts/master_auth") ?>
<?= $this->section("content") ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger d-flex justify-content-between align-items-center"><?= session()->getFlashdata('error'); ?> <i class="fas fa-times"></i></div>
<?php endif ?>

<form action="<?= base_url('login/Vendor') ?>" method="post">
    <?= csrf_field(); ?>
    <div class="form-group">
        <div class="input-group mb-3">
            <span class="input-group-text bg-transparent"><i class="ti-user"></i></span>
            <input type="text" name="login" class="form-control ps-15 bg-transparent" placeholder="Email/Username">
        </div>
    </div>
    <div class="form-group">
        <div class="input-group mb-3">
            <span class="input-group-text  bg-transparent"><i class="ti-lock"></i></span>
            <input name="password" type="password" class="form-control ps-15 bg-transparent" placeholder="Password">
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="checkbox">
                <input type="checkbox" id="basic_checkbox_1">
                <label for="basic_checkbox_1">Remember Me</label>
            </div>
        </div>
        <!-- /.col -->
        <div class="col-6">
            <div class="fog-pwd text-end">
                <a href="javascript:void(0)" class="hover-warning"><i class="fad fa-lock"></i> Lupa password?</a><br>
            </div>
        </div>
        <!-- /.col -->
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-danger mt-10">Masuk</button>
        </div>
        <!-- /.col -->
    </div>
</form>

<?= $this->endSection() ?>