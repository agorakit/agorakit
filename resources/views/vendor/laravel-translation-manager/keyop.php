<div class="alert alert-default alert-dismissible" role="alert" style="padding-top: 0; padding-bottom: 0; margin: 0;">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><?= \Lang::get($package . '::messages.keyop-header-' . $op, ['group' => $group]) ?></h4>
            </div>
            <div class="panel-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <ul>
                            <?php foreach ($errors as $err): ?>
                                <li><?= $err ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif ?>
                <?php if (!empty($keymap)): ?>
                    <table class="table table-condensed" style="margin-bottom: 0;">
                        <thead>
                        <tr>
                            <th width="15%"><?= trans($package . '::messages.srckey') ?></th>
                            <th width="15%"><?= trans($package . '::messages.dstkey') ?></th>
                            <th width="70%">&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($keymap as $src => $map): ?>
                            <tr>
                                <?php if (array_key_exists('errors', $map)): ?>
                                    <td><?= $src ?></td>
                                    <td><?= $map['dst'] ?></td>
                                    <td>
                                        <?php if (array_key_exists('errors', $map)): ?>
                                            <div class="alert alert-danger alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <ul>
                                                    <?php foreach ($map['errors'] as $err): ?>
                                                        <li><?= $err ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                <?php else: ?>
                                    <td><?= $src ?></td>
                                    <td><?= $map['dst'] ?></td>
                                    <?php if (!empty($map['rows'])): ?>
                                        <td>
                                            <table class="table table-striped table-condensed">
                                                <thead>
                                                <tr>
                                                    <th width="5%"><?= trans($package . '::messages.locale') ?></th>
                                                    <th width="25%"><?= trans($package . '::messages.src-preview') ?></th>
                                                    <th width="5%">&nbsp;</th>
                                                    <th width="25%"><?= trans($package . '::messages.dst-preview') ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($map['rows'] as $row): ?>
                                                    <tr>
                                                        <td><?= $row->locale ?></td>
                                                        <td><?= $row->group . '.' . $row->key ?></td>
                                                        <td><?= $row->dst === null ? '✘' : '➽' ?></td>
                                                        <td><?= $row->dst === null ? '' : $row->dstgrp . '.' . $row->dst ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </td>
                                    <?php else: ?>
                                        <td>
                                            <div class="alert alert-info alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <ul>
                                                    <li><?= trans($package . '::messages.keyop-no-match') ?></li>
                                                </ul>
                                            </div>
                                        </td>
                                    <?php endif ?>
                                <?php endif ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif ?>
            </div>
        </div>
</div>

