<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= url_to('student.home.index') ?>"><?= esc($application['name']) ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <i class="bx bx-menu border"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link active" href="<?= url_to('student.home.index') ?>">Beranda</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= url_to('student.start-exam.index') ?>">Mulai Ujian</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Hasil Ujian</a>
            </li>
        </ul>

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle" href="javascript:void(0);" data-bs-toggle="dropdown">
                    Pengguna
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="#">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <span class="fw-semibold d-block"><?= session('user')['username'] ?></span>
                            <small class="text-muted">Murid</small>
                        </div>
                    </div>
                    </a>
                </li>
                <li>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <a class="dropdown-item" href="<?= url_to('student.profile.index') ?>">
                        <i class="bx bx-user me-2"></i>
                        <span class="align-middle">Profil</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="<?= url_to('student.change-password.edit') ?>">
                        <i class="bx bx-key me-2"></i>
                        <span class="align-middle">Ubah Kata Sandi</span>
                    </a>
                </li>
                <li>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <form action="<?= url_to('student.logout') ?>" method="post">
                        <?= csrf_field() ?>
                        <button class="dropdown-item" type="submit">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">Keluar</span>
                        </button>
                    </form>
                </li>
                </ul>
            </li>
        </ul>
    </div>
  </div>
</nav>

<!-- / Navbar -->