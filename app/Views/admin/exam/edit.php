<?= $this->extend('admin/layout') ?>

<?= $this->section('page_title') ?>
    Ujian - <?= esc($record['title']) ?> - Ubah
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Ujian / <?= esc($record['title']) ?> /</span>
            Ubah
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
                                <label for="title" class="form-label">
                                    Judul
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="title"
                                    name="title"
                                    value="<?= old('title') === null ? esc($record['title']) : old('title') ?>"
                                    required
                                >
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label for="start_time" class="form-label">
                                    Waktu Mulai
                                </label>
                                <input
                                    type="datetime-local"
                                    class="form-control"
                                    id="start_time"
                                    name="start_time"
                                    value="<?= old('start_time') === null ? date('Y-m-d\TH:i', $record['start_time']) : old('start_time') ?>"
                                    required
                                >
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label for="end_time" class="form-label">
                                    Waktu Selesai
                                </label>
                                <input
                                    type="datetime-local"
                                    class="form-control"
                                    id="end_time"
                                    name="end_time"
                                    value="<?= old('end_time') === null ? date('Y-m-d\TH:i', $record['end_time']) : old('end_time') ?>"
                                    required
                                >
                            </div>
                            <div class="col-12">
                                <div class="row align-items-center">
                                    <div class="col-auto pe-1 mb-2 mb-md-0">
                                        <button class="btn btn-primary" type="submit">
                                            Simpan Ujian
                                        </button>
                                    </div>
                                    <div class="col-auto ps-md-1">
                                        <a href="<?= url_to('admin.exams.index') ?>" class="btn btn-outline-primary">
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