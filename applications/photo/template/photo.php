

<div class="photo">
    <h1 class="photo--heading">Photo</h1>
    <form class="photo-form" action="<?= htmlspecialchars($url) ?>" method="post" enctype="multipart/form-data">
    <div class="photo__list">
        <?php foreach($data AS $key => $photo) { ?>
            <div class="photo__list-item">
                <?php if(isset($photo['photo'])) {?>

                    <img class="photo__list-image" src="<?= htmlspecialchars($photo['photo']) ?>">

                <?php }else { ?>
                    <label for="<?= htmlspecialchars('photo-'.$key);?>" class="photo__list-item--label">
                        <input id="<?= htmlspecialchars('photo-'.$key);?>"  class="photo__list-item--input" type="file" name="photo[]">
                    </label>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
    <button class="content--button" type="submit">Submit</button>
    <form>
</div>