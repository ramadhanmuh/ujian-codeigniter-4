<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bolder ms-2 text-none"><?= esc($application['name']) ?></span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item <?= active_menu('admin/dashboard') ?>">
            <a href="<?= url_to('admin.dashboard.index') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>Dashboard</div>
            </a>
        </li>

        <li class="menu-item <?= active_menu('admin/ujian') ?>">
            <a href="<?= url_to('admin.exams.index') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-list-check"></i>
                <div>Ujian</div>
            </a>
        </li>

        <li class="menu-item <?= active_menu('admin/pengguna') ?>">
            <a href="<?= url_to('admin.users.index') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div>Pengguna</div>
            </a>
        </li>

        <li class="menu-item <?= active_menu('admin/aplikasi') ?>">
            <a href="<?= url_to('admin.application.index') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div>Aplikasi</div>
            </a>
        </li>
    </ul>
</aside>