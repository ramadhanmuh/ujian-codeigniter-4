<?= $this->extend('student/layout') ?>

<?= $this->section('page_title') ?>
    Hasil Ujian
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>
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

    <?php if (session('info') !== null) : ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= session('info') ?>
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
                <div class="card-header border-bottom">
                    <h1 class="h5 m-0">Hasil Ujian</h1>
                </div>
                <div class="card-body py-3">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="row">
                                <div class="col-md-6 col-lg-5 col-xl-4">
                                    <form action="" method="get">
                                        <div class="input-group rounded-0">
                                            <input
                                                type="text"
                                                class="form-control rounded-0"
                                                name="kata_kunci"
                                                placeholder="Cari Ujian..."
                                                value="<?= $keyword ?>"
                                            >
                                            <button type="submit" class="btn btn-outline-primary rounded-0">
                                                <i class="bx bx-search"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="row">
                                <?php if (empty($records)) : ?>
                                    <div class="col-12 text-center">
                                        <span class="text-muted">
                                            Belum ada ujian yang diselesaikan.
                                        </span>
                                    </div>
                                <?php else : foreach ($records as $record) : ?>
                                    <div class="col-xl-6">
                                        <div class="card border shadow-none rounded-0">
                                            <div class="card-header border-bottom">
                                                <h2 class="h6 m-0">
                                                    <?= esc($record['title']) ?>
                                                </h2>
                                            </div>
                                            <div class="card-body py-3">
                                                Jadwal Mulai :
                                                <?= date('Y-m-d H:i:s', $record['start_time']) ?>
                                                <br>
                                                Jadwal Selesai :
                                                <?= date('Y-m-d H:i:s', $record['end_time']) ?>
                                                <br>
                                                Nilai :
                                                <span class="badge badge-center rounded-pill bg-primary p-4">
                                                    <?= esc($record['score']) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; endif ?>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row align-items-center">
                                <div class="col-md-auto mb-2 mb-md-0">
                                    Jumlah : <?= $totalRecords ?>
                                </div>
                                <div class="col-md">
                                    <nav>
                                        <ul class="pagination justify-content-md-end">
                                            <?php foreach ($pageItems as $pageItem) : ?>
                                                <li class="page-item <?= isset($pageItem['secondary']) ? 'd-none d-md-inline' : '' ?> <?= isset($pageItem['previous']) ? 'previous' : '' ?> <?= isset($pageItem['first']) ? 'first' : '' ?> <?= isset($pageItem['next']) ? 'next' : '' ?> <?= isset($pageItem['last']) ? 'last' : '' ?> <?= isset($pageItem['active']) ? 'active' : '' ?>">
                                                    <a href="<?= $pageItem['link'] ?>" class="page-link rounded-0">
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
<?= $this->endSection() ?>