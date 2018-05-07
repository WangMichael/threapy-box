<a href="/task" class="task__thumbnail">
    <h2 class="task__thumbnail-heading">Task</h2>
    <ul class="task__thumbnail-list">
        <?php foreach($data AS $task) { ?>
            <li class="task__thumbnail-list--item">
                <input type="text" value="<?= htmlspecialchars($task['description'])?>">
                <input type="checkbox" <?= !empty($task['status']) ? "checked" : ''?>>
                <span></span>
            </li>
        <?php } ?>
    </ul>
</a>