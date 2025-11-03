<?= $this->extend('teacher/layout') ?>

<?= $this->section('page_title') ?>
    Profil - Ubah
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Profil /</span>
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
                                <label for="full_name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="full_name" id="full_name" value="<?= old('full_name') === null ? $profile['full_name'] : old('full_name') ?>" required>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" id="email" value="<?= old('email') === null ? $profile['email'] : old('email') ?>" required>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" id="username" value="<?= old('username') === null ? $profile['username'] : old('username') ?>" required>
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
                                    Simpan Profil   
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>