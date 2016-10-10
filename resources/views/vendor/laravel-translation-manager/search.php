<?php
?>
<h4><?php trans($package . '::messages.search-header', ['count' => $numTranslations]) ?></h4>
<table class="table">
    <thead>
        <tr>
            <th width="15%"><?= trans($package . '::messages.group') ?></th>
            <th width="20%"><?= trans($package . '::messages.key') ?></th>
            <th width=" 5%"><?= trans($package . '::messages.locale') ?></th>
            <th width="60%"><?= trans($package . '::messages.translation') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php $translator = App::make('translator') ?>
        <?php foreach ($translations as $t): ?>
            <?php $groupUrl = action($controller . '@getView', $t->group); ?>
            <?php $isLocaleEnabled = str_contains($userLocales, ',' . $t->locale . ','); ?>
            <tr>
                <td>
                    <a href="<?= $groupUrl ?>#<?= $t->key ?>"><?= $t->group ?></a>
                </td>
                <td><?= $t->key ?></td>
                <td><?= $t->locale ?></td>
                <td>
                    <?= $isLocaleEnabled ? $translator->inPlaceEditLink($t) : $t->value ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

