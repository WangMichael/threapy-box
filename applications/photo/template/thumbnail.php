

<a href="<?=htmlspecialchars($url)?>" class="photo__thumbnail">
    <h1 class="photo__thumbnail-heading">Photo</h1>
    <div class="photo__thumbnail__list">
        <?php foreach($data AS $key => $photo) { ?>
            <span class="photo__thumbnail__list-item photo__thumbnail__list-image" style="<?= isset($photo['photo']) ? "background-image: url('".htmlspecialchars($photo['photo'])."');": ''?>"></span>
        <?php } ?>
    </div>
</a>