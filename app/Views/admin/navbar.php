<nav
    class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar"
>
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
        <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <form class="nav-item d-flex align-items-center" action="<?= url_to('admin.exams.index') ?>" method="get">
                <i class="bx bx-search fs-4 lh-0"></i>
                <input
                    type="text"
                    class="form-control border-0 shadow-none"
                    placeholder="Cari Ujian..."
                    name="kata_kunci"
                    value="<?= active_menu('admin/ujian') === 'active' && isset($keyword) ? $keyword : '' ?>"
                />
            </form>
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="bx bxs-user"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <div class="dropdown-item">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block"><?= esc(session('user')['username']) ?></span>
                                    <small class="text-muted">Admin</small>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= url_to('admin.profile.index') ?>">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">Profil</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= url_to('admin.change-password.edit')?>">
                            <i class="bx bx-key me-2"></i>
                            <span class="align-middle">Ubah Kata Sandi</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <form id="logoutForm" action="<?= url_to('admin.logout') ?>" method="post">
                            <?= csrf_field() ?>
                            <button class="dropdown-item" type="submit">
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle">Keluar</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>