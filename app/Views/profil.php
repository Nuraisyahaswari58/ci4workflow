<?= $this->extend("layouts/master_app") ?>

<?= $this->section("content") ?>
<!-- Main content -->
<section  class="content">
    <div class="row">
        <div class="col-6">
        <div class="box mb-15 pull-up">
        <div class="box-body">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="me-15 bg-light h-100 w-100 l-h-60 rounded text-center">
                        <i class="fad fa-user" style="font-size: 40px; margin-top:30px;"></i>
                    </div>
                    <div class="d-flex flex-column fw-500">
                        <a href="#" class="text-dark hover-primary mb-1 fs-16"><?= session()->get('email') ?></a>
                        <p class="text-fade"></p>
                        <p class="text-fade"></p>
                    </div>
                </div>
                <a href="#">
                    <span class="icon-Arrow-right fs-24"><span class="path1"></span><span class="path2"></span></span>
                </a>
            </div>
        </div>
    </div>
        </div>
    </div>

</section>
<?= $this->endSection() ?>