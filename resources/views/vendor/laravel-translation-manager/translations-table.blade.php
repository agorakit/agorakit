<table id="translations" class="table table-condensed table-striped table-translations">
    <thead>
        <tr>
            <?php if($adminEnabled): ?>
            <th width="1%">
                <a href="#" class="auto-delete-key">
                    <span class="glyphicon glyphicon-trash"></span>
                </a>&nbsp;
                <a href="#" class="auto-undelete-key">
                    <span class="glyphicon glyphicon-thumbs-up"></span>
                </a></th>
            <?php endif; ?>
            <?php
            $setWidth = count($displayLocales);
            if ($setWidth > 3) {
                $mainWidth = 25;
            } else if ($setWidth == 3) {
                $mainWidth = 28;
            } else {
                $mainWidth = 42;
            }
            $col = 0;
            $translationRows = count(array_keys($translations));
            ?>
            <th width="15%">@lang($package . '::messages.key')
                <span class="key-filter" id="key-filter"><?=$translationRows?></span>
            </th>
            <?php foreach($locales as $locale): ?>
            <?php $isLocaleEnabled = str_contains($userLocales, ',' . $locale . ','); ?>
            <?php if (!array_key_exists($locale, $displayLocales)) continue; ?>
            <?php if ($col < 3): ?>
            <?php if ($col === 0): ?>
            <th width="<?=$mainWidth?>%"><?= $locale ?>&nbsp;
                <?= ifEditTrans($package . '::messages.auto-fill-disabled') ?>
                <?= ifEditTrans($package . '::messages.auto-fill') ?>
                <a class="btn btn-xs btn-primary" id="auto-fill" role="button" <?= $isLocaleEnabled ? '' : 'disabled' ?>
                data-disable-with="<?=noEditTrans($package . '::messages.auto-fill-disabled')?>"
                        href="#"><?= noEditTrans($package . '::messages.auto-fill') ?></a>
            </th>
            <?php elseif (isset($yandex_key) && $yandex_key): ?>
            <th width="<?=$mainWidth?>%"><?= $locale ?>&nbsp;
                <?= ifEditTrans($package . '::messages.auto-translate-disabled') ?>
                <?= ifEditTrans($package . '::messages.auto-translate') ?>
                <a class="btn btn-xs btn-primary auto-translate" role="button" data-trans="<?=$col?>" data-locale="<?=$locale?>" <?= $isLocaleEnabled ? '' : 'disabled' ?>
                data-disable-with="<?=noEditTrans($package . '::messages.auto-translate-disabled')?>"
                        href="#"><?= noEditTrans($package . '::messages.auto-translate') ?></a>
                <?= ifEditTrans($package . '::messages.auto-prop-case-disabled') ?>
                <a class="btn btn-xs btn-primary auto-prop-case" role="button" data-trans="<?=$col?>" data-locale="<?=$locale?>" <?= $isLocaleEnabled ? '' : 'disabled' ?>
                data-disable-with="<?=noEditTrans($package . '::messages.auto-prop-case-disabled')?>"
                        href="#">Ab Ab <i class="glyphicon glyphicon-share-alt"></i> Ab ab
                </a>
                <!-- split button -->
                <!--
                <div class="btn-group">
                    <button type="button" class="btn btn-xs btn-primary">< ? = noEditTrans($package . '::messages.auto-translate') ? ></button>
                    <button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="#">ab abc<i class="glyphicon glyphicon-share-alt"></i> Ab Abc</a></li>
                        <li><a href="#">Ab Abc <i class="glyphicon glyphicon-share-alt"></i> ab abc</a></li>
                        <li><a href="#">Ab Abc <i class="glyphicon glyphicon-share-alt"></i> Ab abc</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">< ? = noEditTrans($package . '::messages.auto-translate') ? ></a></li>
                    </ul>
                </div>
                -->
            </th>
            <?php else: ?>
            <th width="<?=$mainWidth?>%"><?= $locale ?></th><?php endif;?>
            <?php else: ?>
            <th><?= $locale ?>
                <?= ifEditTrans($package . '::messages.auto-translate-disabled') ?>
                <?= ifEditTrans($package . '::messages.auto-translate') ?>
                <a class="btn btn-xs btn-primary auto-translate" role="button" data-trans="<?=$col?>" data-locale="<?=$locale?>"
                        data-disable-with="<?=noEditTrans($package . '::messages.auto-translate-disabled')?>"
                        href="#"><?= noEditTrans($package . '::messages.auto-translate') ?></a>
                <?= ifEditTrans($package . '::messages.auto-prop-case-disabled') ?>
                <a class="btn btn-xs btn-primary auto-prop-case" role="button" data-trans="<?=$col?>" data-locale="<?=$locale?>"
                        data-disable-with="<?=noEditTrans($package . '::messages.auto-prop-case-disabled')?>"
                        href="#">Ab Ab <i class="glyphicon glyphicon-share-alt"></i> Ab ab
                </a>
            </th>
            <?php endif;
            $col++; ?>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $translator = App::make('translator');
        foreach($translations as $key => $translation)
        {
        $is_deleted = false;
        $has_empty = false;
        $has_nonempty = false;
        $has_changes = false;
        $has_changed = [];
        $has_changes_cached = [];
        $has_used = false;
        foreach ($locales as $locale) {
            if (!array_key_exists($locale, $displayLocales)) continue;

            $has_changed[$locale] = false;
            $has_changes_cached[$locale] = false;

            if (isset($translation[$locale])) {
                $trans = $translation[$locale];
                if ($trans->is_deleted) $is_deleted = true;
                if ($trans->was_used) $has_used = true;
                if ($trans->value != '') {
                    $has_nonempty = true;
                    if ($trans->status != 0 || $trans->value != $trans->saved_value) {
                        $has_changes = true;
                    }
                } else $has_empty = true;

                if ($trans->status !== 0) {
                    if ($trans->status == 1 || $trans->value != $trans->saved_value) $has_changed[$locale] = true;
                    else $has_changes_cached[$locale] = $trans->value != '' && $trans->status === 2;
                }
            }
        }
        ?>
        <tr id="<?= str_replace('.', '-', $key) ?>" class="<?= $is_deleted ? ' deleted-translation' : '' ?><?= $has_empty ? ' has-empty-translation' : '' ?><?= $has_nonempty ? ' has-nonempty-translation' : '' ?><?= $has_changes ? ' has-changed-translation' : '' ?><?= $has_used ? ' has-used-translation' : '' ?>
                ">
            <?php if($adminEnabled): ?>
            <td>
                <a href="<?= action($controller . '@postUndelete', [$group, encodeKey($key)]) ?>"
                        class="undelete-key <?= $is_deleted ? "" : "hidden" ?>" data-method="POST"
                        data-remote="true">
                    <span class="glyphicon glyphicon-thumbs-up"></span>
                </a>
                <a href="<?= action($controller . '@postDelete', [$group, encodeKey($key)]) ?>"
                        class="delete-key <?= !$is_deleted ? "" : "hidden" ?>" data-method="POST"
                        data-remote="true">
                    <span class="glyphicon glyphicon-trash"></span>
                </a>
            </td>
            <?php endif; ?>
            <?php
            $was_used = true;
            $has_source = false;
            $is_auto_added = false;
            if ($show_usage) {
                $was_used = false;
                foreach ($locales as $locale) {
                    $t = isset($translation[$locale]) ? $translation[$locale] : null;
                    if ($t != null && $t->was_used) {
                        $was_used = true;
                        break;
                    }
                }
            }

            foreach ($locales as $locale) {
                $t = isset($translation[$locale]) ? $translation[$locale] : null;
                if ($t != null && $t->has_source) {
                    $has_source = true;
                    $is_auto_added = $t->is_auto_added;
                    break;
                }
            }

            ?>
            <td class="key<?= $was_used ? ' used-key' : ' unused-key' ?>"><?= $key ?><?php
                if ($has_source) {
?><a style="float: right;" href="<?= action($controller . '@postShowSource', [$group, encodeKey($key)]) ?>"
                        class="show-source-refs" data-method="POST" data-remote="true" title="@lang($package . '::messages.show-source-refs')">
                <span class="glyphicon <?= $is_auto_added ? 'glyphicon-question-sign' : 'glyphicon-info-sign' ?>"></span>
                </a><?php
                } ?></td>
            <?php foreach($locales as $locale): ?>
            <?php $isLocaleEnabled = str_contains($userLocales, ',' . $locale . ','); ?>
            <?php if (!array_key_exists($locale, $displayLocales)) continue; ?>
            <?php $t = isset($translation[$locale]) ? $translation[$locale] : null ?>
            <td class="<?= $locale !== $primaryLocale ? 'auto-translatable-' . $locale : ($locale === $primaryLocale ? 'auto-fillable' : '') ?><?= ($has_changed[$locale] ? ' has-unpublished-translation' : '') . ($has_changes_cached[$locale] ? ' has-cached-translation' : '') ?>">
                <?=
                $isLocaleEnabled ? $translator->inPlaceEditLink(!$t ? $t : ($t->value == '' ? null : $t), true, "$group.$key", $locale, null, $group) : $t->value
                ?>
            </td>
            <?php endforeach; ?>
        </tr>
        <?php } ?>
    </tbody>
</table>
