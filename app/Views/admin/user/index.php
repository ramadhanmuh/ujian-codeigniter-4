<?= $this->extend('admin/layout') ?>

<?= $this->section('page_title') ?>
    Pengguna
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            Pengguna
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

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <a href="<?= url_to('admin.users.create') ?>" class="btn btn-primary">
                            <i class="bx bx-plus"></i>
                            Tambah
                        </a>
                    </div>
                    <div class="card-body py-3">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="row justify-content-end">
                                    <div class="col-md-6 col-xl-4">
                                        <form action="" method="get">
                                            <div class="input-group">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    name="kata_kunci"
                                                    placeholder="Pencarian"
                                                    value="<?= $keyword ?>"
                                                >
                                                <button type="submit" class="btn btn-outline-primary">
                                                    <i class="bx bx-search"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Nama Lengkap</th>
                                                <th class="text-center">Email</th>
                                                <th class="text-center">Username</th>
                                                <th class="text-center">Peran</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($records === null) : ?>
                                                <tr>
                                                    <td class="text-center" colspan="6">
                                                        Data tidak ditemukan
                                                    </td>
                                                </tr>
                                            <?php else : foreach ($records as $record) : $offset++; ?>
                                                <tr>
                                                    <td class="text-center align-middle">
                                                        <?= $offset ?>
                                                    </td>
                                                    <td class="align-middle">
                                                        <?= esc($record['full_name']) ?>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <?= esc($record['email']) ?>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <?= esc($record['username']) ?>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <?php if ($record['role'] === 'admin') : ?>
                                                            Admin
                                                        <?php elseif ($record['role'] === 'teacher') : ?>
                                                            Guru
                                                        <?php elseif ($record['role'] === 'student') : ?>
                                                            Murid
                                                        <?php else : ?>
                                                            -
                                                        <?php endif ?>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <?php if ($record['id'] !== session('user')['id']) : ?>
                                                            <div class="btn-group">
                                                                <button
                                                                    type="button"
                                                                    class="btn btn-outline-primary btn-icon rounded-pill dropdown-toggle hide-arrow"
                                                                    data-bs-toggle="dropdown"
                                                                    aria-expanded="false"
                                                                >
                                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                    <li>
                                                                        <a class="dropdown-item" href="<?= url_to('admin.users.edit', esc($record['id'])) ?>">
                                                                            Ubah
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <form action="<?= url_to('admin.users.delete', esc($record['id'])) ?>" method="post">
                                                                            <?= csrf_field() ?>
                                                                            <button
                                                                                class="dropdown-item"
                                                                                type="submit"
                                                                            >
                                                                                Hapus
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        <?php endif ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; endif ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-auto mb-2 mb-md-0">
                                        Jumlah Data : <?= $totalRecords ?>
                                    </div>
                                    <div class="col-md">
                                        <nav>
                                            <ul class="pagination justify-content-md-end">
                                                <?php foreach ($pageItems as $pageItem) : ?>
                                                    <li class="page-item <?= isset($pageItem['secondary']) ? 'd-none d-md-inline' : '' ?> <?= isset($pageItem['previous']) ? 'previous' : '' ?> <?= isset($pageItem['first']) ? 'first' : '' ?> <?= isset($pageItem['next']) ? 'next' : '' ?> <?= isset($pageItem['last']) ? 'last' : '' ?> <?= isset($pageItem['active']) ? 'active' : '' ?>">
                                                        <a href="<?= $pageItem['link'] ?>" class="page-link">
                                                            <?= $pageItem['text'] ?>
                                                        </a>
                                                    </li>    
                                                <?php endforeach ?>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>