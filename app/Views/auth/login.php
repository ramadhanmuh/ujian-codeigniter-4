<?= $this->extend('auth/layout') ?>

<?= $this->section('page_title') ?>
    Masuk
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>
    <h4 class="mb-2">Selamat Datang</h4>

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

    <form id="formAuthentication" class="mb-3" action="<?= url_to('login.auth') ?>" method="POST">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="identity" class="form-label">Email atau Username</label>
            <input
            type="text"
            class="form-control"
            id="identity"
            name="identity"
            placeholder="Ketikkan Email atau Username Anda"
            autofocus
            required
            />
        </div>
        <div class="mb-3 form-password-toggle">
            <div class="d-flex justify-content-between">
                <label class="form-label" for="password">Kata Sandi</label>
                <a href="<?= url_to('forgot-password.index') ?>">
                    <small>Lupa Kata Sandi ?</small>
                </a>
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
        <div class="mb-3">
            <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember-me" name="remember_me" value="1" />
            <label class="form-check-label" for="remember-me"> Tetap Masuk </label>
            </div>
        </div>
        <div class="mb-3">
            <button class="btn btn-primary d-grid w-100" type="submit">Masuk</button>
        </div>
    </form>
<?= $this->endSection() ?>