<?= $this->extend('student/layout') ?>

<?= $this->section('page_title') ?>
    Ubah Kata Sandi
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
                <div class="card-body py-3">
                    <form class="row" method="post">
                        <?= csrf_field() ?>
                        <div class="col-lg-6 mb-3">
                            <div class="form-password-toggle">
                                <label for="new_password" class="form-label">Kata Sandi Baru</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control" name="new_password" id="new_password" required>
                                    <span class="input-group-text cursor-pointer">
                                        <i class="bx bx-hide"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="form-password-toggle">
                                <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                                    <span class="input-group-text cursor-pointer">
                                        <i class="bx bx-hide"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="form-password-toggle">
                                <label for="current_password" class="form-label">Kata Sandi Saat Ini</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control" name="current_password" id="current_password" required>
                                    <span class="input-group-text cursor-pointer">
                                        <i class="bx bx-hide"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                Simpan Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>