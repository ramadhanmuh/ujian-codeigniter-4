<?= $this->extend('student/layout') ?>

<?= $this->section('page_title') ?>
    Beranda
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>
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

    <?php if (session('info') !== null) : ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= session('info') ?>
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
                <div class="card-body">
                    <h5 class="text-center">Selamat Datang,</h5>
                    
                    <div class="row justify-content-center">
                        <div class="col-md-auto text-center mb-2 mb-md-0">
                            <a href="<?= url_to('student.start-exam.index') ?>" class="btn btn-primary">
                                <i class="bx bx-play"></i>
                                <span class="align-middle">
                                    Mulai Ujian
                                </span>
                            </a>
                        </div>
                        <div class="col-md-auto text-center">
                            <a href="" class="btn btn-secondary">
                                <i class="bx bx-file"></i>
                                <span class="align-middle">
                                    Hasil Ujian
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>