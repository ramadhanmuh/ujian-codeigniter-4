<?= $this->extend('auth/layout') ?>

<?= $this->section('page_title') ?>
    Lupa Kata Sandi
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>
    <h4 class="mb-2">Lupa Kata Sandi ? 🔒</h4>

    <?php if (session('success') !== null) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif ?>

    <?php if (session('error') !== null) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif ?>

    <?php if (session('validationError') !== null) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('validationError')['email'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif ?>

    <p class="mb-4">Masukkan email Anda dan kami akan mengirimkan instruksi untuk mengatur ulang kata sandi Anda</p>
    <form id="formAuthentication" class="mb-3" action="<?= url_to('forgot-password.send') ?>" method="POST">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input
                type="email"
                class="form-control"
                id="email"
                name="email"
                placeholder="Masukkan email anda"
                autofocus
                required
            />
        </div>
        <button class="btn btn-primary d-grid w-100">Kirim Tautan Reset</button>
    </form>
    <div class="text-center">
        <a href="<?= url_to('login.view') ?>" class="d-flex align-items-center justify-content-center">
            <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
            Kembali ke login
        </a>
    </div>
<?= $this->endSection() ?>