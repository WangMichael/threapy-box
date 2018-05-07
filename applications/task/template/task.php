<div class="task">
    <h1 class="task--heading">task</h1>
    <form class="task-form" action="<?= htmlspecialchars('/task/') ?>" method="post">
        <ul class="task__list">
            <?php foreach($data AS $task) { ?>
                <li class="task__list-item">
                    <input name="<?= htmlspecialchars($task['id'])?>" type="text" value="<?= htmlspecialchars($task['description'])?>"/>
                    <input name="<?= htmlspecialchars($task['id']) ?>" type="hidden"  value="<?= htmlspecialchars($task['id']) ?>"/>
                    <label>
                        <input name="<?= htmlspecialchars($task['id']) ?>" type="hidden"  value="0"/>
                        <input name="<?= htmlspecialchars($task['id']) ?>" type="checkbox" value="1" <?= !empty($task['status']) ? "checked" : ''?>/>
                        <span></span>
                    </label>
                </li>
            <?php } ?>
                <li class="task__list-item task__list-item--plus" data-id="0">
                    <input type="text" value=""/>
                    <label>
                        <input type="hidden"  value="0"/>
                        <input type="checkbox" value="1"/>
                        <span></span>
                    </label>
                </li>
        </ul>
        <button class="content--button" type="submit">Submit</button>
        <form>
</div>