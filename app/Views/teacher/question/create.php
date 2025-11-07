<?= $this->extend('teacher/layout') ?>

<?= $this->section('page_title') ?>
    Soal - Tambah
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Soal /</span>
            Tambah
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
                            <input type="hidden" name="id" value="<?= generate_uuid() ?>">
                            <div class="col-12 mb-3">
                                <label for="exam" class="form-label">
                                    Ujian
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="exam"
                                    name="exam"
                                    value="<?= esc($exam['title']) ?>"
                                    disabled
                                >
                            </div>
                            <div class="col-12 mb-3">
                                <label for="text" class="form-label">
                                    Teks Pertanyaan
                                </label>
                                <textarea
                                    name="text"
                                    id="text"
                                    class="form-control"
                                    rows="3"
                                    required
                                ><?= old('text') ?></textarea>
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label for="option_a" class="form-label">
                                    Pilihan A
                                </label>
                                <textarea
                                    name="option_a"
                                    id="option_a"
                                    class="form-control"
                                    rows="2"
                                    required
                                ><?= old('option_a') ?></textarea>
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label for="option_b" class="form-label">
                                    Pilihan B
                                </label>
                                <textarea
                                    name="option_b"
                                    id="option_b"
                                    class="form-control"
                                    rows="2"
                                    required
                                ><?= old('option_b') ?></textarea>
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label for="option_c" class="form-label">
                                    Pilihan C
                                </label>
                                <textarea
                                    name="option_c"
                                    id="option_c"
                                    class="form-control"
                                    rows="2"
                                    required
                                ><?= old('option_c') ?></textarea>
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label for="option_d" class="form-label">
                                    Pilihan D
                                </label>
                                <textarea
                                    name="option_d"
                                    id="option_d"
                                    class="form-control"
                                    rows="2"
                                    required
                                ><?= old('option_d') ?></textarea>
                            </div>
                            <div class="col-xl-6 mb-3">
                                <small class="form-label d-block">
                                    Jawaban Benar
                                </small>
                                <div class="form-check form-check-inline">
                                    <input
                                        type="radio"
                                        class="form-check-input"
                                        id="correct_answer1"
                                        name="correct_answer"
                                        value="a"
                                        <?= old('correct_answer') === 'a' ? 'checked' : '' ?>
                                        required
                                    >
                                    <label for="correct_answer1" class="form-check-label">
                                        A
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input
                                        type="radio"
                                        class="form-check-input"
                                        id="correct_answer2"
                                        name="correct_answer"
                                        value="b"
                                        <?= old('correct_answer') === 'b' ? 'checked' : '' ?>
                                        required
                                    >
                                    <label for="correct_answer2" class="form-check-label">
                                        B
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input
                                        type="radio"
                                        class="form-check-input"
                                        id="correct_answer3"
                                        name="correct_answer"
                                        value="c"
                                        <?= old('correct_answer') === 'c' ? 'checked' : '' ?>
                                        required
                                    >
                                    <label for="correct_answer3" class="form-check-label">
                                        C
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input
                                        type="radio"
                                        class="form-check-input"
                                        id="correct_answer4"
                                        name="correct_answer"
                                        value="d"
                                        <?= old('correct_answer') === 'd' ? 'checked' : '' ?>
                                        required
                                    >
                                    <label for="correct_answer4" class="form-check-label">
                                        D
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row align-items-center">
                                    <div class="col-auto pe-1 mb-2 mb-md-0">
                                        <button class="btn btn-primary" type="submit">
                                            Simpan Soal
                                        </button>
                                    </div>
                                    <div class="col-auto ps-md-1">
                                        <a href="<?= url_to('teacher.questions.index') ?>" class="btn btn-outline-primary">
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