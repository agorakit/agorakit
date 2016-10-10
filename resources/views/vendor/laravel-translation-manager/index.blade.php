@extends((isset($package) ? $package . '::' : '') . 'layouts.master')

@section('content')
    <div class="col-sm-12 translation-manager">
        <div class="row">
            <div class="col-sm-8">
                <div class="row">
                    <div class="col-sm-12">
                        <h1>@lang($package . '::messages.translation-manager')</h1>
                        {{-- csrf_token() --}}
                        {{--{!! $userLocales !!}--}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <p>@lang($package . '::messages.export-warning-text')</p>
                        <div class="alert alert-danger alert-dismissible" style="display:none;">
                            <button type="button" class="close" data-hide="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="errors-alert">
                            </div>
                        </div>
                        <?= ifInPlaceEdit("@lang('$package::messages.import-all-done')") ?>
                        <div class="alert alert-success alert-dismissible" style="display:none;">
                            <button type="button" class="close" data-hide="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="success-import-all">
                                <p>@lang($package . '::messages.import-all-done')</p>
                            </div>
                        </div>
                        <?= ifInPlaceEdit("@lang('$package::messages.import-group-done')", ['group' => $group]) ?>
                        <div class="alert alert-success alert-dismissible" style="display:none;">
                            <button type="button" class="close" data-hide="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="success-import-group">
                                <p>@lang($package . '::messages.import-group-done', ['group' => $group]) </p>
                            </div>
                        </div>
                        <?= ifInPlaceEdit("@lang('$package::messages.search-done')") ?>
                        <div class="alert alert-success alert-dismissible" style="display:none;">
                            <button type="button" class="close" data-hide="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="success-find">
                                <p>@lang($package . '::messages.search-done')</p>
                            </div>
                        </div>
                        <?= ifInPlaceEdit("@lang('$package::messages.done-publishing')", ['group' => $group]) ?>
                        <div class="alert alert-success alert-dismissible" style="display:none;">
                            <button type="button" class="close" data-hide="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="success-publish">
                                <p>@lang($package . '::messages.done-publishing', ['group'=> $group])</p>
                            </div>
                        </div>
                        <?= ifInPlaceEdit("@lang('$package::messages.done-publishing-all')") ?>
                        <div class="alert alert-success alert-dismissible" style="display:none;">
                            <button type="button" class="close" data-hide="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="success-publish-all">
                                <p>@lang($package . '::messages.done-publishing-all')</p>
                            </div>
                        </div>
                        <?php if(Session::has('successPublish')) : ?>
                        <div class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-hide="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <?php echo Session::get('successPublish'); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        @if($adminEnabled)
                            <div class="row">
                                <div class="col-sm-12">
                                    <form id="form-import-all" class="form-import-all" method="POST"
                                            action="<?= action($controller . '@postImport', ['group' => '*']) ?>"
                                            data-remote="true" role="form">
                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <?= ifEditTrans($package . '::messages.import-add') ?>
                                                <?= ifEditTrans($package . '::messages.import-replace') ?>
                                                <?= ifEditTrans($package . '::messages.import-fresh') ?>
                                                <div class="input-group-sm">
                                                    <select name="replace" class="import-select form-control">
                                                        <option value="0"><?= noEditTrans($package . '::messages.import-add') ?></option>
                                                        <option value="1"><?= noEditTrans($package . '::messages.import-replace') ?></option>
                                                        <option value="2"><?= noEditTrans($package . '::messages.import-fresh') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <?= ifEditTrans($package . '::messages.import-groups') ?>
                                                <?= ifEditTrans($package . '::messages.loading') ?>
                                                <button id="submit-import-all" type="submit" form="form-import-all"
                                                        class="btn btn-sm btn-success"
                                                        data-disable-with="<?= noEditTrans($package . '::messages.loading') ?>">
                                                    <?= noEditTrans($package . '::messages.import-groups') ?>
                                                </button>
                                                <?= ifEditTrans($package . '::messages.zip-all') ?>
                                                <a href="<?= action($controller . '@getZippedTranslations', ['group' => '*']) ?>"
                                                        role="button" class="btn btn-primary btn-sm">
                                                    <?= noEditTrans($package . '::messages.zip-all') ?>
                                                </a>
                                                <div class="input-group" style="float:right; display:inline">
                                                    <?= ifEditTrans($package . '::messages.publish-all') ?>
                                                    <?= ifEditTrans($package . '::messages.publishing') ?>
                                                    <button type="submit" form="form-publish-all"
                                                            class="btn btn-sm btn-warning input-control"
                                                            data-disable-with="<?= noEditTrans($package . '::messages.publishing') ?>">
                                                        <?= noEditTrans($package . '::messages.publish-all') ?>
                                                    </button><?= ifEditTrans($package . '::messages.publish-all') ?>
                                                    <?= ifEditTrans($package . '::messages.find-in-files') ?>
                                                    <?= ifEditTrans($package . '::messages.searching') ?>
                                                    <button type="submit" form="form-find"
                                                            class="btn btn-sm btn-danger"
                                                            data-disable-with="<?= noEditTrans($package . '::messages.searching') ?>">
                                                        <?= noEditTrans($package . '::messages.find-in-files') ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <?= ifEditTrans($package . '::messages.confirm-find') ?>
                                    <form id="form-find" class="form-inline form-find" method="POST"
                                            action="<?= action($controller . '@postFind') ?>"
                                            data-remote="true" role="form"
                                            data-confirm="<?= noEditTrans($package . '::messages.confirm-find') ?>">
                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div style="min-height: 10px"></div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <?= ifEditTrans($package . '::messages.choose-group'); ?>
                                        <div class="input-group-sm">
                                            <?= Form::select('group', $groups, $group, array(
                                                    'form' => 'form-select',
                                                    'class' => 'group-select form-control'
                                            )) ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <?php if ($adminEnabled): ?>
                                        <?php if ($group): ?>
                                        <?= ifEditTrans($package . '::messages.publishing') ?>
                                        <?= ifEditTrans($package . '::messages.publish') ?>
                                        <button type="submit" form="form-publish-group"
                                                class="btn btn-sm btn-info input-control"
                                                data-disable-with="<?= noEditTrans($package . '::messages.publishing') ?>">
                                            <?= noEditTrans($package . '::messages.publish') ?>
                                        </button>
                                        <?= ifEditTrans($package . '::messages.zip-group') ?>
                                        <a href="<?= action($controller . '@getZippedTranslations', ['group' => $group]) ?>"
                                                role="button" class="btn btn-primary btn-sm">
                                            <?= noEditTrans($package . '::messages.zip-group') ?>
                                        </a>
                                        <?= ifEditTrans($package . '::messages.search'); ?>
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                                data-target="#searchModal"><?= noEditTrans($package . '::messages.search') ?></button>
                                        <?php endif; ?>
                                        <div class="input-group" style="float:right; display:inline">
                                            <?php if ($group): ?>
                                            <?= ifEditTrans($package . '::messages.import-group') ?>
                                            <?= ifEditTrans($package . '::messages.loading') ?>
                                            <button type="submit" form="form-import-group" class="btn btn-sm btn-success"
                                                    data-disable-with="<?= noEditTrans($package . '::messages.loading') ?>">
                                                <?= noEditTrans($package . '::messages.import-group') ?>
                                            </button>
                                            <?= ifEditTrans($package . '::messages.delete') ?>
                                            <?= ifEditTrans($package . '::messages.deleting') ?>
                                            <button type="submit" form="form-delete-group" class="btn btn-sm btn-danger"
                                                    data-disable-with="<?= noEditTrans($package . '::messages.deleting') ?>">
                                                <?= noEditTrans($package . '::messages.delete') ?>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                        <?php else: ?>
                                        <?php if ($group): ?>
                                        <?= ifEditTrans($package . '::messages.search'); ?>
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                                data-target="#searchModal"><?= noEditTrans($package . '::messages.search') ?></button>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    <?= ifEditTrans($package . '::messages.confirm-delete') ?>
                                    <form id="form-delete-group" class="form-inline form-delete-group" method="POST"
                                            action="<?= action($controller . '@postDeleteAll', $group) ?>"
                                            data-remote="true" role="form"
                                            data-confirm="<?= noEditTrans($package . '::messages.confirm-delete', ['group' => $group]) ?>">
                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                    </form>
                                    <form id="form-import-group" class="form-inline form-import-group" method="POST"
                                            action="<?= action($controller . '@postImport', $group) ?>"
                                            data-remote="true" role="form">
                                        <input type="hidden" name="_token"
                                                value="<?php echo csrf_token(); ?>">
                                    </form>
                                    <form role="form" class="form" id="form-select"></form>
                                    <form id="form-publish-group" class="form-inline form-publish-group" method="POST"
                                            action="<?= action($controller . '@postPublish', $group) ?>"
                                            data-remote="true" role="form">
                                        <input type="hidden" name="_token"
                                                value="<?php echo csrf_token(); ?>">
                                    </form>
                                    <form id="form-publish-all" class="form-inline form-publish-all" method="POST"
                                            action="<?= action($controller . '@postPublish', '*') ?>"
                                            data-remote="true" role="form">
                                        <input type="hidden" name="_token"
                                                value="<?php echo csrf_token(); ?>">
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div style="min-height: 10px"></div>
                        <div class="row">
                            <?php if(!$group): ?>
                            <div class="col-sm-10">
                                <p>@lang($package . '::messages.choose-group-text')</p>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                        data-target="#searchModal" style="float:right; display:inline">
                                    <?= noEditTrans($package . '::messages.search') ?>
                                </button>
                                <?= ifEditTrans($package . '::messages.search') ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div style="min-height: 10px"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-12">
                        <div style="min-height: 10px"></div>
                        <form class="form-inline" id="form-interface-locale" method="GET"
                                action="<?= action($controller . '@getInterfaceLocale') ?>">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            <div class="row">
                                <div class=" col-sm-3">
                                    @if($adminEnabled && count($connection_list) > 1)
                                        <div class="input-group-sm">
                                            <label for="db-connection"><?= trans($package . '::messages.db-connection') ?>:</label>
                                            <br>
                                            <select name="c" id="db-connection" class="form-control">
                                                @foreach($connection_list as $connection => $description)
                                                    <option value="<?=$connection?>"<?= $connection_name === $connection ? ' selected="selected"' : ''?>><?= $description ?></option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        &nbsp;
                                    @endif
                                </div>
                                <div class=" col-sm-2">
                                    <div class="input-group-sm">
                                        <label for="interface-locale"><?= trans($package . '::messages.interface-locale') ?>:</label>
                                        <br>
                                        <select name="l" id="interface-locale" class="form-control">
                                            @foreach($locales as $locale)
                                                <option value="<?=$locale?>"<?= $currentLocale === $locale ? ' selected="selected"' : ''?>><?= $locale ?></option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class=" col-sm-2">
                                    <div class="input-group-sm">
                                        <label for="translating-locale"><?= trans($package . '::messages.translating-locale') ?>:</label>
                                        <br>
                                        <select name="t" id="translating-locale" class="form-control">
                                            @foreach($locales as $locale)
                                                @if($locale !== $primaryLocale)
                                                    <option value="<?=$locale?>"<?= $translatingLocale === $locale ? ' selected="selected"' : ''?>><?= $locale ?></option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class=" col-sm-2">
                                    <div class="input-group-sm">
                                        <label for="primary-locale"><?= trans($package . '::messages.primary-locale') ?>:</label>
                                        <br>
                                        <select name="p" id="primary-locale" class="form-control">
                                            @foreach($locales as $locale)
                                                <option value="<?=$locale?>"<?= $primaryLocale === $locale ? ' selected="selected"' : ''?>><?= $locale ?></option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class=" col-sm-3">
                                    <?php if(str_contains($userLocales, ',' . $currentLocale . ',')): ?>
                                    <div class="input-group input-group-sm" style="float:right; display:inline">
                                        <?= ifEditTrans($package . '::messages.in-place-edit') ?>
                                        <label for="edit-in-place">&nbsp;</label>
                                        <br>
                                        <a class="btn btn-sm btn-primary" role="button" id="edit-in-place" href="<?= action($controller . '@getToggleInPlaceEdit') ?>">
                                            <?= noEditTrans($package . '::messages.in-place-edit') ?>
                                        </a>
                                    </div>
                                    <?php endif ?>
                                </div>
                            </div>
                            <div style="min-height: 10px"></div>
                            <div class="row">
                                <div class=" col-sm-4">
                                    <div class="row">
                                        <div class=" col-sm-12">
                                            <?= formSubmit(trans($package . '::messages.display-locales')
                                                    , ['class' => "btn btn-sm btn-primary"]) ?>&nbsp;&nbsp;
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class=" col-sm-12">
                                            <div style="min-height: 10px"></div>
                                            <?= ifEditTrans($package . '::messages.check-all') ?>
                                            <button id="display-locale-all"
                                                    class="btn btn-sm btn-default"><?= noEditTrans($package . '::messages.check-all')?></button>
                                            <?= ifEditTrans($package . '::messages.check-none') ?>
                                            <button id="display-locale-none"
                                                    class="btn btn-sm btn-default"><?= noEditTrans($package . '::messages.check-none')?></button>
                                        </div>
                                    </div>
                                </div>
                                <div class=" col-sm-8">
                                    <div class="input-group-sm">
                                        @foreach($locales as $locale)
                                            <?php $isLocaleEnabled = str_contains($userLocales, ',' . $locale . ','); ?>
                                            <label>
                                                <input <?= $locale !== $primaryLocale && $locale !== $translatingLocale ? ' class="display-locale" ' : '' ?> name="d[]"
                                                        type="checkbox"
                                                        value="<?=$locale?>"
                                                <?= ($locale === $primaryLocale || $locale === $translatingLocale || array_key_exists($locale, $displayLocales)) ? 'checked' : '' ?>
                                                        <?= $locale === $primaryLocale ? ' disabled' : '' ?>><?= $locale ?>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @if($usage_info_enabled)
                    <div class="row">
                        <div class="col-sm-12">
                            <div style="min-height: 10px"></div>
                            <form class="form-inline" id="form-usage-info" method="GET"
                                    action="<?= action($controller . '@getUsageInfo') ?>">
                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                <input type="hidden" name="group" value="<?php echo $group ? $group : '*'; ?>">
                                <div class="row">
                                    <div class=" col-sm-12">
                                        <div class="row">
                                            <div class=" col-sm-4">
                                                <div class="row">
                                                    <div class=" col-sm-12">
                                                        <?= formSubmit(trans($package . '::messages.set-usage-info'), ['class' => "btn btn-sm btn-primary"]) ?>&nbsp;&nbsp;
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class=" col-sm-8">
                                                <label>
                                                    <input id="show-usage-info" name="show-usage-info" type="checkbox" value="1" {!! $show_usage ? 'checked' : '' !!}>
                                                    {!! trans($package . '::messages.show-usage-info') !!}
                                                </label>
                                                <label>
                                                    <input id="reset-usage-info" name="reset-usage-info" type="checkbox" value="1">
                                                    {!! trans($package . '::messages.reset-usage-info') !!}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <br>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        @include($package . '::dashboard')
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        @if($mismatchEnabled && !empty($mismatches))
                            @include($package . '::mismatched')
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        @if($adminEnabled && $userLocalesEnabled && !$group)
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">@lang($package . '::messages.user-admin')</h3>
                                </div>
                                <div class="panel-body">
                                    @include($package . '::user_locales')
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <?= ifEditTrans($package . '::messages.enter-translation') ?>
        <?= ifEditTrans($package . '::messages.missmatched-quotes') ?>
        <script>
            var MISSMATCHED_QUOTES_MESSAGE = "<?= noEditTrans(($package . '::messages.missmatched-quotes'))?>";
        </script>
        <?php if($group): ?>
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    @if($adminEnabled && $userLocalesEnabled)
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingUserAdmin">
                                <?= ifEditTrans($package . '::messages.user-admin') ?>
                                <h4 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseUserAdmin"
                                            aria-expanded="false" aria-controls="collapseUserAdmin">
                                        <?= noEditTrans($package . '::messages.user-admin') ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseUserAdmin" class="panel-collapse collapse" role="tabpanel"
                                    aria-labelledby="headingUserAdmin">
                                <div class="panel-body">
                                    <div class="col-sm-12">
                                        @include($package . '::user_locales')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if($adminEnabled): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <?= ifEditTrans($package . '::messages.suffixed-keyops') ?>
                                <h4 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                                            aria-expanded="false" aria-controls="collapseOne">
                                        <?= noEditTrans($package . '::messages.suffixed-keyops') ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel"
                                    aria-labelledby="headingOne">
                                <div class="panel-body">
                                    <!-- Add Keys Form -->
                                    <div class="col-sm-12">
                                        <?=  Form::open(['id' => 'form-addkeys', 'method' => 'POST', 'action' => [$controller . '@postAdd', $group]]) ?>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label for="keys">@lang($package . '::messages.keys'):</label><?= ifEditTrans($package . '::messages.addkeys-placeholder') ?>
                                                <?=  Form::textarea('keys', Request::old('keys'), [
                                                        'class' => "form-control", 'rows' => "4", 'style' => "resize: vertical",
                                                        'placeholder' => noEditTrans($package . '::messages.addkeys-placeholder')
                                                ]) ?>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="suffixes">@lang($package . '::messages.suffixes'):</label><?= ifEditTrans($package . '::messages.addsuffixes-placeholder') ?>
                                                <?=  Form::textarea('suffixes', Request::old('suffixes'), [
                                                        'class' => "form-control", 'rows' => "4", 'style' => "resize: vertical",
                                                        'placeholder' => noEditTrans($package . '::messages.addsuffixes-placeholder')
                                                ]) ?>
                                            </div>
                                        </div>
                                        <div style="min-height: 10px"></div>
                                        <script>
                                            var currentGroup = '{{$group}}';
                                            function addStandardSuffixes(event) {
                                                event.preventDefault();
                                                $("#form-addkeys").first().find("textarea[name='suffixes']")[0].value = "-type\n-header\n-heading\n-description\n-footer" + (currentGroup === 'systemmessage-texts' ? '\n-footing' : '');
                                            }
                                            function clearSuffixes(event) {
                                                event.preventDefault();
                                                $("#form-addkeys").first().find("textarea[name='suffixes']")[0].value = "";
                                            }
                                            function clearKeys(event) {
                                                event.preventDefault();
                                                $("#form-addkeys").first().find("textarea[name='keys']")[0].value = "";
                                            }
                                            function postDeleteSuffixedKeys(event) {
                                                event.preventDefault();
                                                var elem = $("#form-addkeys").first();
                                                elem[0].action = "<?= action($controller . '@postDeleteSuffixedKeys', $group)?>";
                                                elem[0].submit();
                                            }
                                        </script>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <?= formSubmit(trans($package . '::messages.addkeys'), ['class' => "btn btn-sm btn-primary"]) ?>
                                                <?= ifEditTrans($package . '::messages.clearkeys') ?>
                                                <button class="btn btn-sm btn-primary"
                                                        onclick="clearKeys(event)"><?= noEditTrans($package . '::messages.clearkeys') ?>
                                                </button>
                                                <div class="input-group" style="float:right; display:inline">
                                                    <?= ifEditTrans($package . '::messages.deletekeys') ?>
                                                    <button class="btn btn-sm btn-danger"
                                                            onclick="postDeleteSuffixedKeys(event)">
                                                        <?= noEditTrans($package . '::messages.deletekeys') ?>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <?= ifEditTrans($package . '::messages.addsuffixes') ?>
                                                <button class="btn btn-sm btn-primary"
                                                        onclick="addStandardSuffixes(event)"><?= noEditTrans($package . '::messages.addsuffixes') ?></button>
                                                <?= ifEditTrans($package . '::messages.clearsuffixes') ?>
                                                <button class="btn btn-sm btn-primary"
                                                        onclick="clearSuffixes(event)"><?= noEditTrans($package . '::messages.clearsuffixes') ?></button>
                                            </div>
                                            <div class="col-sm-2">
                                                <span style="float:right; display:inline">
                                                    <?= ifEditTrans($package . '::messages.search'); ?>
                                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                                            data-target="#searchModal"><?= noEditTrans($package . '::messages.search') ?></button>
                                                </span>
                                            </div>
                                        </div>
                                        <?=  Form::close() ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingTwo">
                                <?= ifEditTrans($package . '::messages.wildcard-keyops') ?>
                                <h4 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"
                                            aria-expanded="false" aria-controls="collapseTwo">
                                        <?= noEditTrans($package . '::messages.wildcard-keyops') ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                <div class="panel-body">
                                    <div class="col-sm-12">
                                        <!-- Key Ops Form -->
                                        <div id="wildcard-keyops-results" class="results"></div>
                                        <?=  Form::open([
                                                'id' => 'form-keyops', 'data-remote' => "true", 'method' => 'POST',
                                                'action' => [$controller . '@postPreviewKeys', $group]
                                        ]) ?>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label for="srckeys">@lang($package . '::messages.srckeys'):</label><?= ifEditTrans($package . '::messages.srckeys-placeholder') ?>
                                                <?=  Form::textarea('srckeys', Request::old('srckeys'), [
                                                        'id' => 'srckeys', 'class' => "form-control", 'rows' => "4", 'style' => "resize: vertical",
                                                        'placeholder' => noEditTrans($package . '::messages.srckeys-placeholder')
                                                ]) ?>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="dstkeys">@lang($package . '::messages.dstkeys'):</label><?= ifEditTrans($package . '::messages.dstkeys-placeholder') ?>
                                                <?=  Form::textarea('dstkeys', Request::old('dstkeys'), [
                                                        'id' => 'dstkeys', 'class' => "form-control", 'rows' => "4", 'style' => "resize: vertical",
                                                        'placeholder' => noEditTrans($package . '::messages.dstkeys-placeholder')
                                                ]) ?>
                                            </div>
                                        </div>
                                        <div style="min-height: 10px"></div>
                                        <script>
                                            var currentGroup = '{{$group}}';
                                            function clearDstKeys(event) {
                                                event.preventDefault();
                                                $("#form-keyops").first().find("textarea[name='dstkeys']")[0].value = "";
                                            }
                                            function clearSrcKeys(event) {
                                                event.preventDefault();
                                                $("#form-keyops").first().find("textarea[name='srckeys']")[0].value = "";
                                            }
                                            function postPreviewKeys(event) {
                                                //event.preventDefault();
                                                var elem = $("#form-keyops").first();
                                                elem[0].action = "<?= action($controller . '@postPreviewKeys', $group)?>";
                                                //elem[0].submit();
                                            }
                                            function postMoveKeys(event) {
                                                //event.preventDefault();
                                                var elem = $("#form-keyops").first();
                                                elem[0].action = "<?= action($controller . '@postMoveKeys', $group)?>";
                                                //elem[0].submit();
                                            }
                                            function postCopyKeys(event) {
                                                //event.preventDefault();
                                                var elem = $("#form-keyops").first();
                                                elem[0].action = "<?= action($controller . '@postCopyKeys', $group)?>";
                                                //elem[0].submit();
                                            }
                                            function postDeleteKeys(event) {
                                                //event.preventDefault();
                                                var elem = $("#form-keyops").first();
                                                elem[0].action = "<?= action($controller . '@postDeleteKeys', $group)?>";
                                                //elem[0].submit();
                                            }
                                        </script>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <?= ifEditTrans($package . '::messages.clearsrckeys') ?>
                                                <button class="btn btn-sm btn-primary"
                                                        onclick="clearSrcKeys(event)"><?= noEditTrans($package . '::messages.clearsrckeys') ?></button>
                                                <div class="input-group" style="float:right; display:inline">
                                                    <?= formSubmit(trans($package . '::messages.preview'), [
                                                            'class' => "btn btn-sm btn-primary",
                                                            'onclick' => 'postPreviewKeys(event)'
                                                    ]) ?>
                                                    <?= ifEditTrans($package . '::messages.copykeys'); ?>
                                                    <button class="btn btn-sm btn-primary" onclick="postCopyKeys(event)">
                                                        <?= noEditTrans($package . '::messages.copykeys') ?>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <?= ifEditTrans($package . '::messages.cleardstkeys') ?>
                                                <button class="btn btn-sm btn-primary"
                                                        onclick="clearDstKeys(event)"><?= noEditTrans($package . '::messages.cleardstkeys') ?></button>
                                                <div class="input-group" style="float:right; display:inline">
                                                    <?= ifEditTrans($package . '::messages.movekeys') ?>
                                                    <button class="btn btn-sm btn-warning" onclick="postMoveKeys(event)">
                                                        <?= noEditTrans($package . '::messages.movekeys') ?>
                                                    </button>
                                                    <?= ifEditTrans($package . '::messages.deletekeys') ?>
                                                    <button class="btn btn-sm btn-danger" onclick="postDeleteKeys(event)">
                                                        <?= noEditTrans($package . '::messages.deletekeys') ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <?=  Form::close() ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif ?>
                        @if($yandex_key)
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingThree">
                                    <?= ifEditTrans($package . '::messages.translation-ops') ?>
                                    <h4 class="panel-title">
                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion"
                                                href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            <?= noEditTrans($package . '::messages.translation-ops') ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel"
                                        aria-labelledby="headingThree">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                        <textarea id="primary-text" class="form-control" rows="3" name="keys"
                                                style="resize: vertical;" placeholder="<?= $primaryLocale ?>"></textarea>
                                                <div style="min-height: 10px"></div>
                                        <span style="float:right; display:inline">
                                            <button id="translate-primary-current" type="button" class="btn btn-sm btn-primary">
                                                <?= $primaryLocale ?>&nbsp;<i class="glyphicon glyphicon-share-alt"></i>&nbsp;<?= $translatingLocale ?>
                                            </button>
                                        </span>
                                            </div>
                                            <div class="col-sm-6">
                                        <textarea id="current-text" class="form-control" rows="3" name="keys"
                                                style="resize: vertical;" placeholder="<?= $translatingLocale ?>"></textarea>
                                                <div style="min-height: 10px"></div>
                                                <button id="translate-current-primary" type="button" class="btn btn-sm btn-primary">
                                                    <?= $translatingLocale ?>&nbsp;<i class="glyphicon glyphicon-share-alt"></i>&nbsp;<?= $primaryLocale ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 ">
                <label for="show-matching-text" id="show-matching-text-label" class="regex-error">&nbsp;</label>
                <div class="form-inline">
                    <?= ifEditTrans($package . '::messages.show-matching-text') ?>
                    <div class="input-group input-group-sm">
                        <input class="form-control" style="width: 200px;" id="show-matching-text" type="text" placeholder="{{noEditTrans($package . '::messages.show-matching-text')}}">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default" id="show-matching-clear" style="margin-right: 15px;">
                                &times;
                            </button>
                        </span>
                    </div>
                    <div class="input-group input-group-sm translation-filter">
                        {{--<label>@lang($package . '::messages.show'):&nbsp;</label>--}}
                        <label class="radio-inline">
                            <input id="show-all" type="radio" name="show-options" value="show-all"> @lang($package . '::messages.show-all')
                        </label>
                    </div>
                    <div class="input-group input-group-sm translation-filter">
                        <label class="radio-inline">
                            <input id="show-new" type="radio" name="show-options" value="show-new"> @lang($package . '::messages.show-new')
                        </label>
                    </div>
                    <div class="input-group input-group-sm translation-filter">
                        <label class="radio-inline">
                            <input id="show-need-attention" type="radio" name="show-options" value="show-need-attention"> @lang($package . '::messages.show-need-attention')
                        </label>
                    </div>
                    <div class="input-group input-group-sm translation-filter">
                        <label class="radio-inline">
                            <input id="show-nonempty" type="radio" name="show-options" value="show-nonempty"> @lang($package . '::messages.show-nonempty')
                        </label>
                    </div>
                    <div class="input-group input-group-sm translation-filter">
                        <label class="radio-inline">
                            <input id="show-used" type="radio" name="show-options" value="show-used"> @lang($package . '::messages.show-used')
                        </label>
                    </div>
                    <div class="input-group input-group-sm translation-filter">
                        <label class="radio-inline">
                            <input id="show-unpublished" type="radio" name="show-options" value="show-unpublished"> @lang($package . '::messages.show-unpublished')
                        </label>
                    </div>
                    <div class="input-group input-group-sm translation-filter">
                        <label class="radio-inline">
                            <input id="show-empty" type="radio" name="show-options" value="show-empty"> @lang($package . '::messages.show-empty')
                        </label>
                    </div>
                    <div class="input-group input-group-sm translation-filter">
                        <label class="radio-inline">
                            <input id="show-changed" type="radio" name="show-options" value="show-changed"> @lang($package . '::messages.show-changed')
                        </label>
                    </div>
                    <div class="input-group input-group-sm translation-filter">
                        <label class="radio-inline">
                            <input id="show-deleted" type="radio" name="show-options" value="show-deleted"> @lang($package . '::messages.show-deleted')
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 ">
                <div style="min-height: 10px"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 ">
                @include($package . '::translations-table')
            </div>
        </div>
    <?php endif; ?>
    <!-- Search Modal -->
        <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="searchModalLabel">@lang($package . '::messages.search-translations')</h4>
                    </div>
                    <div class="modal-body">
                        <form id="search-form" method="GET" action="<?= action($controller . '@getSearch') ?>" data-remote="true">
                            <div class="form-group">
                                <div class="input-group">
                                    <input id="search-form-text" type="search" name="q" class="form-control">
                                    <span class="input-group-btn">
                                        <?= formSubmit(trans($package . '::messages.search'), ['class' => "btn btn-default"]) ?>
                                    </span>
                                </div>
                            </div>
                        </form>
                        <div class="results"></div>
                    </div>
                    <div class="modal-footer">
                        <?= ifEditTrans($package . '::messages.close') ?>
                        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><?= noEditTrans($package . '::messages.close') ?></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- KeyOp Modal -->
        <div class="modal fade" id="keyOpModal" tabindex="-1" role="dialog" aria-labelledby="keyOpModalLabel"
                aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header modal-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="keyOpModalLabel">@lang($package . '::messages.keyop-header')</h4>
                    </div>
                    <div class="modal-body">
                        <div class="results"></div>
                    </div>
                    <div class="modal-footer">
                        <?= ifEditTrans($package . '::messages.close') ?>
                        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><?= noEditTrans($package . '::messages.close') ?></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Source Refs Modal -->
        <div class="modal fade" id="sourceRefsModal" tabindex="-1" role="dialog" aria-labelledby="keySourceRefsModal"
                aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header modal-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="keySourceRefsModal">@lang($package . '::messages.source-refs-header')<code style="color:white">'<span id="key-name"></span>'</code></h4>
                    </div>
                    <div class="modal-body">
                        <div class="results"></div>
                    </div>
                    <div class="modal-footer">
                        <?= ifEditTrans($package . '::messages.close') ?>
                        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><?= noEditTrans($package . '::messages.close') ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('body-bottom')
    <script>
        var URL_YANDEX_TRANSLATOR_KEY = '<?= action($controller . '@postYandexKey') ?>';
        var PRIMARY_LOCALE = '{{$primaryLocale}}';
        var CURRENT_LOCALE = '{{$currentLocale}}';
        var TRANSLATING_LOCALE = '{{$translatingLocale}}';
        var URL_TRANSLATOR_GROUP = '<?= action($controller . '@getView') ?>/';
        var URL_TRANSLATOR_ALL = '<?= action($controller . '@getIndex') ?>';
        var URL_TRANSLATOR_FILTERS = '<?= action($controller . '@getTransFilters') ?>';
        var CURRENT_GROUP = '<?= $group ?>';
        var MARKDOWN_KEY_SUFFIX = '<?= $markdownKeySuffix ?>';
    </script>

    <!-- Moved out to allow auto-format in PhpStorm w/o screwing up HTML format -->
    <script src="<?=  $public_prefix . $package ?>/js/xregexp-all.js"></script>
    <script src="<?=  $public_prefix . $package ?>/js/translations_page.js"></script>

    <?php
    $userLocaleList = [];
    foreach ($userList as $user) {
        if ($user->locales) {
            foreach (explode(",", $user->locales) as $userLocale) {
                $userLocale = trim($userLocale);
                if ($userLocale) $userLocaleList[$userLocale] = $userLocale;
            }
        }
    }

    foreach ($displayLocales as $userLocale) {
        $userLocaleList[$userLocale] = $userLocale;
    }

    natsort($userLocaleList);
    ?>

    <script>
        var TRANS_FILTERS = ({
            filter: "<?= $transFilters['filter'] ?>",
            regex: "<?= $transFilters['regex'] ?>"
        });

        var USER_LOCALES = [
                <?php $addComma = false; ?>
                <?php foreach ($userLocaleList as $locale): ?>
                <?php if ($addComma) echo ","; else $addComma = true; ?> {
                value: '<?= $locale ?>', text: '<?= $locale ?>'
            }
            <?php endforeach; ?>
        ];
    </script>
@stop

