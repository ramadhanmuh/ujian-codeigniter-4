<?= $this->extend('admin/layout') ?>

<?= $this->section('page_title') ?>
    Ujian
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            Ujian
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
                        <a href="<?= url_to('admin.exams.create') ?>" class="btn btn-primary">
                            <i class="bx bx-plus"></i>
                            Tambah
                        </a>
                    </div>
                    <div class="card-body py-3">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="row justify-content-between align-items-center">
                                    <div class="col-md-5 col-xl-4 mb-2 mb-md-0">
                                        <select class="form-select" onchange="window.location.href = this.value">
                                            <?php foreach ($orderOption as $value) : ?>
                                                <option
                                                    value="<?= $value['link'] ?>"
                                                    <?= $value['active'] ? 'selected' : '' ?>
                                                >
                                                    <?= $value['text'] ?>
                                                </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-md-5 col-xl-4">
                                        <form action="" method="get">
                                            <div class="input-group">
                                                <?php if ($orderBy !== '') : ?>
                                                    <input
                                                        type="hidden"
                                                        name="urut_dengan"
                                                        value="<?= esc($orderBy) ?>"
                                                    >
                                                <?php endif ?>
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
                                                <th>Judul</th>
                                                <th class="text-center">Waktu Mulai</th>
                                                <th class="text-center">Waktu Selesai</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($records)) : ?>
                                                <tr>
                                                    <td class="text-center" colspan="5">
                                                        Data tidak ditemukan
                                                    </td>
                                                </tr>
                                            <?php else : foreach ($records as $record) : $offset++; ?>
                                                <tr>
                                                    <td class="text-center align-middle">
                                                        <?= $offset ?>
                                                    </td>
                                                    <td class="align-middle">
                                                        <?= esc($record['title']) ?>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <?= date('Y-m-d H:i:s', $record['start_time']) ?>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <?= date('Y-m-d H:i:s', $record['end_time']) ?>
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
                                                                        <a class="dropdown-item" href="<?= url_to('admin.exams.edit', esc($record['id'])) ?>">
                                                                            Ubah
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <form action="<?= url_to('admin.exams.delete', esc($record['id'])) ?>" method="post">
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
                                    <?php if (!empty($records)) : ?>
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
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>