<h1 style="margin:20px auto; text-align: center; font-size: 30px; color: #FFFFFF;">Good Day Swapnil</h1>
<ul class="dashboard--thumbnail">
    <?php foreach($thumbnails AS $name => $thumbnail) { ?>
        <li class="dashboard--thumbnail-item dashboard--thumbnail-item--<?= htmlspecialchars($name)?>">
                <?= $thumbnail?>
        </li>
    <?php } ?>
</ul>