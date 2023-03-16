<?php $alerts = ['primary','secondary','success','danger','warning','info','light','dark']; ?>

<?php foreach($alerts as $alert): ?>
    <?php if (session()->has($alert)) : ?>
        <?php $messages = session()->getFlashdata($alert); ?>
        <?php if (is_array($messages)):?>
            <?php foreach($messages as $message):?>
                <div class="alert alert-<?=$alert?>">
                    <?= $message; ?>
                </div>
            <?php endforeach; ?>
        <?php else:?>
            <div class="alert alert-<?=$alert?>">
                <?= $messages; ?>
            </div>
        <?php endif; ?>
    <?php endif ?>
<?php endforeach; ?>