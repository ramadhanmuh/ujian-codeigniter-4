<?= $this->extend('admin/layout') ?>

<?= $this->section('page_title') ?>
    Pengguna - <?= esc($record['username']) ?> - Ubah
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Pengguna / <?= esc($record['username']) ?> / </span>
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
                            <div class="col-xl-6 mb-3">
                                <label for="full_name" class="form-label">
                                    Nama Lengkap
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="full_name"
                                    name="full_name"
                                    value="<?= old('full_name') === null ? $record['full_name'] : old('full_name') ?>"
                                    required
                                >
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label for="email" class="form-label">
                                    Email
                                </label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    value="<?= old('email') === null ? $record['email'] : old('email') ?>"
                                    required
                                >
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label for="username" class="form-label">
                                    Username
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="username"
                                    name="username"
                                    value="<?= old('username') === null ? $record['username'] : old('username') ?>"
                                    required
                                >
                                <small class="text-muted">
                                    Hanya boleh alfabet, angka, garis bawah, dan strip.
                                </small>
                            </div>
                            <div class="col-xl-6 mb-3">
                                <div class="form-password-toggle">
                                    <label for="password" class="form-label">Kata Sandi</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" class="form-control" name="password" id="password">
                                        <span class="input-group-text cursor-pointer">
                                            <i class="bx bx-hide"></i>
                                        </span>
                                    </div>
                                    <small class="text-muted">
                                        Jika tidak diisi, maka tidak akan berubah.
                                    </small>
                                </div>
                            </div>
                            <div class="col-xl-6 mb-3">
                                <small class="form-label d-block">
                                    Peran
                                </small>
                                <div class="form-check form-check-inline">
                                    <input
                                        type="radio"
                                        class="form-check-input"
                                        id="role1"
                                        name="role"
                                        value="admin"
                                        <?= old('role') === null ? ($record['role'] === 'admin' ? 'checked' : '') : (old('role') === 'admin' ? 'checked' : '') ?>
                                        required
                                    >
                                    <label for="role1" class="form-check-label">
                                        Admin
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input
                                        type="radio"
                                        class="form-check-input"
                                        id="role2"
                                        name="role"
                                        value="teacher"
                                        <?= old('role') === null ? ($record['role'] === 'teacher' ? 'checked' : '') : (old('role') === 'teacher' ? 'checked' : '') ?>
                                        required
                                    >
                                    <label for="role2" class="form-check-label">
                                        Guru
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input
                                        type="radio"
                                        class="form-check-input"
                                        id="role3"
                                        name="role"
                                        value="student"
                                        <?= old('role') === null ? ($record['role'] === 'student' ? 'checked' : '') : (old('role') === 'student' ? 'checked' : '') ?>
                                        required
                                    >
                                    <label for="role3" class="form-check-label">
                                        Murid
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row align-items-center">
                                    <div class="col-auto pe-1 mb-2 mb-md-0">
                                        <button class="btn btn-primary" type="submit">
                                            Simpan Pengguna
                                        </button>
                                    </div>
                                    <div class="col-auto ps-md-1">
                                        <a href="<?= url_to('admin.users.index') ?>" class="btn btn-outline-primary">
                                            Kembali Ke Lis
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>