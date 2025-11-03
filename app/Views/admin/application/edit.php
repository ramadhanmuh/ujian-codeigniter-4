<?= $this->extend('admin/layout') ?>

<?= $this->section('page_title') ?>
    Aplikasi - Ubah
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Aplikasi /</span>
            Ubah
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
                    <div class="card-body py-3">
                        <form class="row" method="post">
                            <?= csrf_field() ?>
                            <div class="col-lg-6 mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" name="name" id="name" value="<?= old('name') === null ? $application['name'] : old('name') ?>" required>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="copyright" class="form-label">Hak Cipta</label>
                                <input type="text" class="form-control" name="copyright" id="copyright" value="<?= old('copyright') === null ? $application['copyright'] : old('copyright') ?>" required>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    Simpan Data Aplikasi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>