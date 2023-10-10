<?= $this->extend("layouts/master_auth") ?>
<?= $this->section("content") ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger d-flex justify-content-between align-items-center"><?= session()->getFlashdata('error'); ?> <i class="fas fa-times"></i></div>
<?php endif ?>

<form action="<?= base_url('login/resetPasswordAction') ?>" method="post">
    <?= csrf_field(); ?>
    <div class="form-group">
        <div class="input-group mb-3">
            <input type="hidden" name="token" value="<?= $token; ?>">

            <?php
            $uri = service('uri');

            ?>
            <input type="hidden" name="role" value="<?= $uri->getSegment(2) ?>">
        </div>
    </div>
    <div class="form-group">
        <div class="input-group mb-3">
            <span class="input-group-text  bg-transparent"><i class="ti-lock"></i></span>
            <input name="password" type="password" class="form-control ps-15 bg-transparent" placeholder="Password">
        </div>
    </div>
    <div class="form-group">
        <div class="input-group mb-3">
            <span class="input-group-text  bg-transparent"><i class="ti-lock"></i></span>
            <input name="cpassword" type="password" class="form-control ps-15 bg-transparent" placeholder="Confirm Password">
        </div>
    </div>

    <div class="row">
        <!-- /.col -->
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-block btn-danger mt-10">Kirim</button>
        </div>
        <!-- /.col -->
    </div>
</form>

<?= $this->endSection() ?>