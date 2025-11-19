<?= $this->extend('teacher/layout') ?>

<?= $this->section('page_title') ?>
    Hasil Ujian
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            Hasil Ujian
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

        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 mb-2 mb-xl-0">
                                <label for="order" class="form-label">
                                    Sortir
                                </label>
                                <select id="order" class="form-select" data-url="<?= url_to('teacher.exam-results.index') ?>">
                                    <option
                                        data-order="student"
                                        data-direction="asc"
                                        <?= $orderBy === 'student' && $direction === 'asc' ? 'selected' : '' ?>
                                    >
                                        Murid (A-Z)
                                    </option>
                                    <option
                                        data-order="student"
                                        data-direction="desc"
                                        <?= $orderBy === 'student' && $direction === 'desc' ? 'selected' : '' ?>
                                    >
                                        Murid (Z-A)
                                    </option>
                                    <option
                                        data-order="exam"
                                        data-direction="asc"
                                        <?= $orderBy === 'exam' && $direction === 'asc' ? 'selected' : '' ?>
                                    >
                                        Ujian (A-Z)
                                    </option>
                                    <option
                                        data-order="exam"
                                        data-direction="desc"
                                        <?= $orderBy === 'exam' && $direction === 'desc' ? 'selected' : '' ?>
                                    >
                                        Ujian (Z-A)
                                    </option>
                                </select>
                            </div>
                            <div class="col-xl-4 mb-2 mb-xl-0">
                                <label for="exam_id" class="form-label">
                                    Ujian
                                </label>
                                <select id="exam_id" class="form-select" data-url="<?= url_to('teacher.exam-results.index') ?>">
                                    <option value="">-- Pilih --</option>
                                    <?php if (empty($exams)) : ?>
                                        <option value="">Ujian Tidak Ditemukan</option>
                                    <?php else : ?>
                                        <?php foreach ($exams as $exam) : ?>
                                            <option
                                                value="<?= esc($exam['id']) ?>"
                                                <?= $exam_id === $exam['id'] ? 'selected' : '' ?>
                                            >
                                                <?= esc($exam['title']) ?>
                                            </option>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                </select>
                            </div>
                            <div class="col-xl-4">
                                <form action="" method="get" id="searchForm">
                                    <?php if ($orderBy !== '' && $direction !== '') : ?>
                                        <input type="hidden" name="orderBy" value="<?= $orderBy ?>">
                                        <input type="hidden" name="direction" value="<?= $direction ?>">
                                    <?php endif ?>

                                    <?php if ($exam_id !== '') : ?>
                                        <input type="hidden" name="exam_id" value="<?= $exam_id ?>">
                                    <?php endif ?>

                                    <label for="keyword" class="form-label">
                                        Pencarian
                                    </label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="keyword"
                                            id="keyword"
                                            placeholder="Ketikkan murid atau ujian..."
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
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body py-3">
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 10px;">No</th>
                                        <th>Murid</th>
                                        <th>Ujian</th>
                                        <th class="text-center">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($records)) :  ?>
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                Data tidak ditemukan.
                                            </td>
                                        </tr>
                                    <?php else : foreach ($records as $record) : $offset++ ?>
                                        <tr>
                                            <td class="text-center">
                                                <?= $offset ?>
                                            </td>
                                            <td>
                                                <?= esc($record['full_name']) ?>
                                            </td>
                                            <td>
                                                <?= esc($record['title']) ?>
                                            </td>
                                            <td class="text-center">
                                                <?= esc($record['score']) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; endif ?>
                                </tbody>
                            </table>
                        </div>
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
<?= $this->endSection() ?>

<?= $this->section('additional_script') ?>
    <script defer src="<?= base_url('assets/js/teacher/exam-result/index.js') ?>?>"></script>
<?= $this->endSection()  ?>?>