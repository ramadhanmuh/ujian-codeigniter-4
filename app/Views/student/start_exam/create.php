<?= $this->extend('student/layout') ?>

<?= $this->section('page_title') ?>
    Mulai Ujian - <?= esc($question['title']) ?>
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
                    <h1 class="h3 text-center">
                        <?= esc($question['title']) ?>
                    </h1>
                </div>
                <div class="card-body py-2">
                    <div class="row">
                        <div class="col-xl-9 order-1 order-xl-0">
                            <h2 class="h5">Pertanyaan</h2>
                            <div class="row">
                                <div class="col-auto">
                                    <?= $question['number'] ?>
                                </div>
                                <div class="col">
                                    <div class="row">
                                        <div class="col-12">
                                            <?= esc($question['text']) ?>
                                        </div>  
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input
                                                    type="radio"
                                                    name="selected_option"
                                                    id="selected_option_1"
                                                    value="a"
                                                    class="form-check-input"
                                                    <?= $question['selected_option'] === 'a' ? 'checked' : '' ?>
                                                >
                                                <label for="selected_option_1" class="form-check-label">
                                                    A. <?= esc($question['option_a']) ?>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input
                                                    type="radio"
                                                    name="selected_option"
                                                    id="selected_option_2"
                                                    value="b"
                                                    class="form-check-input"
                                                    <?= $question['selected_option'] === 'b' ? 'checked' : '' ?>
                                                >
                                                <label for="selected_option_2" class="form-check-label">
                                                    B. <?= esc($question['option_b']) ?>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input
                                                    type="radio"
                                                    name="selected_option"
                                                    id="selected_option_3"
                                                    value="c"
                                                    class="form-check-input"
                                                    <?= $question['selected_option'] === 'c' ? 'checked' : '' ?>
                                                >
                                                <label for="selected_option_3" class="form-check-label">
                                                    C. <?= esc($question['option_c']) ?>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input
                                                    type="radio"
                                                    name="selected_option"
                                                    id="selected_option_4"
                                                    value="d"
                                                    class="form-check-input"
                                                    <?= $question['selected_option'] === 'd' ? 'checked' : '' ?>
                                                >
                                                <label for="selected_option_4" class="form-check-label">
                                                    D. <?= esc($question['option_d']) ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 order-0 order-xl-1 mb-5 mb-xl-0 bg-light">
                            <h2 class="h5">Nomor Soal</h2>
                            <?php for ($i=1; $i < $maxNumber; $i++) : ?>
                                <a
                                    href="<?= url_to('student.start-exam.create', $question['slug'], $question['number']) ?>"
                                    class="btn btn-sm <?= $i == $question['number'] ? 'btn-primary' : 'btn-outline-primary' ?> m-1"
                                >
                                    <?= $i ?>
                                </a>
                            <?php endfor ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>