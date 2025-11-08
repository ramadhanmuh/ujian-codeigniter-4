<?= $this->extend('teacher/layout') ?>

<?= $this->section('page_title') ?>
    Soal
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            Soal
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
                                <select id="order" class="form-select">
                                    <option data-order="text" data-direction="ASC">
                                        Teks Soal (A-Z)
                                    </option>
                                    <option data-order="text" data-direction="DESC">
                                        Teks Soal (Z-A)
                                    </option>
                                </select>
                            </div>
                            <div class="col-xl-4 mb-2 mb-xl-0">
                                <label for="exam_id" class="form-label">
                                    Ujian
                                </label>
                                <select id="exam_id" class="form-select">
                                    <?php if (empty($exams)) : ?>
                                        <option value="">Ujian Tidak Ditemukan</option>
                                    <?php else : ?>
                                        <?php foreach ($exams as $exam) : ?>
                                            <option value="<?= esc($exam['id']) ?>">
                                                <?= esc($exam['title']) ?>
                                            </option>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                </select>
                            </div>
                            <div class="col-xl-4">
                                <form action="" method="get" id="searchForm">
                                    <label for="keyword" class="form-label">
                                        Pencarian
                                    </label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="keyword"
                                            id="keyword"
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
                    <div class="card-header border-bottom">
                        <div class="row justify-content-end">
                            <div class="col-auto">
                                <a href="" class="btn btn-primary" data-link="<?= url_to('teacher.questions.create') ?>" id="createButton">
                                    <i class="bx bx-plus"></i>
                                    Tambah
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-2">
                        <div class="row align-items-center">
                            <div
                                class="col-12 mb-3"
                                id="question-list-column"
                                data-url="<?= url_to('teacher.questions.list') ?>"
                            >
                                
                            </div>
                            <div class="col-md-auto mb-2 mb-md-0" id="question-total-column"></div>
                        </div>
                    </div>
                    <div
                        class="position-absolute bg-white w-100 start-0 top-0 h-100 d-flex justify-content-center align-items-center"
                        id="loader"
                    >
                        Memuat...
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('additional_script') ?>
    <script defer src="<?= base_url('assets/js/teacher/question/index.js') ?>"></script>
<?= $this->endSection() ?>