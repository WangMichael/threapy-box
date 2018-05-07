<div class="sport">
    <h1>Sport</h1>
    <input class="sport__input" type="text" placeholder="Please input a team name">
    <?php foreach($teams AS $name => $defeat) { ?>
        <ul class="sport__list" data-name="<?= htmlspecialchars($name)?>">
            <?php foreach($defeat AS $defeatName) {?>
                <li><?= htmlspecialchars($defeatName)?></li>
            <?php } ?>
        </ul>
    <?php } ?>
<div>