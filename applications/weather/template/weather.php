<div class="weather__thumbnail">
    <h2 class="weather__thumbnail-heading">Weather</h2>
    <div class="weather__thumbnail-block">
        <span class="weather__thumbnail-block--image" style="background-image:url('<?= htmlspecialchars($image) ?>')"></span>
        <p class="weather__thumbnail-block--degree"><?= htmlspecialchars($degree) ?> <br>Degrees</p>
    </div>
    <p class="weather__thumbnail-city"><?= htmlspecialchars($city) ?></p>
</div>