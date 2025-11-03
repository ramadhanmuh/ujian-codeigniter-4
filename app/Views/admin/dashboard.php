<?= $this->extend('admin/layout') ?>

<?= $this->section('page_title') ?>
    Dashboard
<?= $this->endSection() ?>

<?= $this->section('page_content') ?>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            Dashboard
        </h4>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="text-decoration-underline">
                            Hari Ini (WIB)
                        </h4>
                        <h5 class="fw-normal">
                            Tanggal : <?= date('Y-m-d') ?>
                        </h5>
                        <h5 class="fw-normal">
                            Jam : <?= date('H:i:s') ?>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>