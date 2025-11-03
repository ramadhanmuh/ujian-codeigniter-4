<?= $this->extend('admin/layout') ?>

<?= $this->section('page_title') ?>
    Profil
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            Profil
        </h4>

        <?php if (session('error') !== null) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif ?>

        <?php if (session('success') !== null) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif ?>

        <?php if (session('validationError') !== null) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="m-0">
                    <?php foreach (session('validationError') as $validationError) : ?>
                        <li><?= $validationError ?></li>
                    <?php endforeach ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif ?>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <a href="<?= url_to('admin.profile.edit') ?>" class="btn btn-warning">
                            <i class="bx bx-edit"></i>
                            Ubah
                        </a>
                    </div>
                    <div class="card-body py-3">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <b>Nama Lengkap</b>
                                <br>
                                <span><?= esc($profile['full_name']) ?></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <b>Email</b>
                                <br>
                                <span><?= esc($profile['email']) ?></span>
                            </div>
                            <div class="col-md-6">
                                <b>Username</b>
                                <br>
                                <span><?= esc($profile['username']) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>