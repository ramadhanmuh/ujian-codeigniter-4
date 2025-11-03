<?= $this->extend('auth/layout') ?>

<?= $this->section('page_title') ?>
    Masuk
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>
    <h4 class="mb-2">Atur Ulang Kata Sandi</h4>

    <?php if (session('error') !== null) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error') ?>
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

    <p class="mb-4">Masukkan kata sandi baru dan ulangi kata sandi baru anda.</p>

    <form id="formAuthentication" class="mb-3" action="" method="POST">
        <?= csrf_field() ?>
        <div class="mb-3 form-password-toggle">
            <div class="d-flex justify-content-between">
                <label class="form-label" for="password">Kata Sandi</label>
            </div>
            <div class="input-group input-group-merge">
                <input
                    type="password"
                    id="password"
                    class="form-control"
                    name="password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password"
                    required
                />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
            </div>
        </div>
        <div class="mb-3 form-password-toggle">
            <div class="d-flex justify-content-between">
                <label class="form-label" for="password">Konfirmasi Kata Sandi</label>
            </div>
            <div class="input-group input-group-merge">
                <input
                    type="password"
                    id="password_confirmation"
                    class="form-control"
                    name="password_confirmation"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password_confirmation"
                    required
                />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
            </div>
        </div>
        <div class="mb-3">
            <button class="btn btn-primary d-grid w-100" type="submit">Simpan Data</button>
        </div>
    </form>
<?= $this->endSection() ?>