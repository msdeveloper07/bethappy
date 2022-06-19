<div class="container-fluid">
    <div class="row">

        <?php foreach ($games as $row): //var_dump($row);?>
            <div class="card col-md-3">
                <img class="card-img-top" src="<?= $row['image_filled']; ?>" alt="<?= $row['name']; ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= $row['name']; ?></h5>
                    <p class="card-text"><?= $row['provider_name']; ?></p>
                    <a href="https://bethappy.com/#!/game/<?= $row['id']; ?>" class="btn btn-primary">Live</a>
                    <a href="https://bethappy.com/#!/game/<?= $row['id']; ?>/true" class="btn btn-primary">Demo</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

