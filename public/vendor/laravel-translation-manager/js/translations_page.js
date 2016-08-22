/*jshint browser: true, jquery: true*/
/**
 * Created by vlad on 15-02-10.
 */
var CLIP_TEXT; // we store translation copy/paste here
var YANDEX_TRANSLATOR_KEY;
var URL_YANDEX_TRANSLATOR_KEY;
var PRIMARY_LOCALE;
var CURRENT_LOCALE;
var CURRENT_GROUP;
var TRANSLATING_LOCALE;
var URL_TRANSLATOR_GROUP;
var URL_TRANSLATOR_ALL;
var URL_TRANSLATOR_FILTERS;
var xtranslateText;
var xtranslateService;
var TRANS_FILTERS;
var USER_LOCALES;

jQuery(document).ready(function ($) {
    $('.group-select').on('change', function () {
        var group = $(this).val();
        if (group) {
            window.location.href = URL_TRANSLATOR_GROUP + $(this).val();
        } else {
            window.location.href = URL_TRANSLATOR_ALL;
        }
    });

    $('a.delete-key').on('ajax:success', function (e, data) {
        var row = $(this).closest('tr');
        row.addClass("deleted-translation");

        $(this).addClass("hidden");
        row.find("a.undelete-key").first().removeClass("hidden");
    });

    $('a.undelete-key').on('ajax:success', function (e, data) {
        var row = $(this).closest('tr');
        row.removeClass("deleted-translation");

        $(this).addClass("hidden");
        row.find("a.delete-key").first().removeClass("hidden");
    });

    var successReporter = function (suffix) {
        var elem = $('div.success-' + suffix);
        var originalHtml = elem.html();
        $('.form-' + suffix).on('ajax:success', function (e, data) {
            elem.html(originalHtml.replace(/:count\b/, data.counter));
            elem.closest('div.alert').slideDown();
        });
    };

    successReporter('import-group');
    successReporter('import-all');
    successReporter('find');

    $('.form-publish-group').on('ajax:success', function (e, data) {
        if (data.status === 'errors') {
            var elem = $('div.errors-alert'),
                errors = data.errors;
            elem.html("<p>" + errors.join("</p>\n<p>") + "</p>\n");
            elem.closest('div.alert').slideDown();
        }
        else {
            $('div.success-publish').closest('div.alert').slideDown();
            $('tr.deleted-translation').remove();
        }
    });

    $('.form-publish-all').on('ajax:success', function (e, data) {
        if (data.status === 'errors') {
            var elem = $('div.errors-alert'),
                errors = data.errors;
            elem.html("<p>" + errors.join("</p>\n<p>") + "</p>\n");
            elem.closest('div.alert').slideDown();
        }
        else {
            $('div.success-publish-all').closest('div.alert').slideDown();
            $('tr.deleted-translation').remove();
        }
    });

    $('#form-keyops').on('ajax:success', function (e, data) {
        var elem = $('#wildcard-keyops-results').first();
        elem.html(data);
        elem.find('.vsch_editable').vsch_editable();
    });

    var elemModal = $('#searchModal').first();
    elemModal.on('shown.bs.modal', function (event, data, status, xhr) {
        elemModal.find('#search-form-text').first().focus();
    });

    elemModal.on('ajax:success', function (event, data, status, xhr) {
        var elem = elemModal.find('.results');
        elem.html(data);
        elem.find('.vsch_editable').vsch_editable();
    });

    $('#translate-current-primary').on('click', function () {
        var elemFrom = $('#current-text').first(),
            fromText = elemFrom[0].value;
        xtranslateService(TRANSLATING_LOCALE, fromText, PRIMARY_LOCALE, function (text) {
            var elem = $('#primary-text').first();
            if (elem.length) {
                elem.val(text);
            }
        });
    });

    $('#translate-primary-current').on('click', function () {
        var elemFrom = $('#primary-text').first(),
            fromText = elemFrom[0].value;
        xtranslateService(PRIMARY_LOCALE, fromText, TRANSLATING_LOCALE, function (text) {
            var elem = $('#current-text').first();
            if (elem.length) {
                elem.val(text);
            }
        });
    });

    $('#db-connection').on('change', function () {
        $('#form-interface-locale')[0].submit();
    });

    $('#interface-locale').on('change', function () {
        $('#form-interface-locale')[0].submit();
    });

    $('#translating-locale').on('change', function () {
        $('#form-interface-locale')[0].submit();
    });

    $('#primary-locale').on('change', function () {
        $('#form-interface-locale')[0].submit();
    });

    $('#display-locale-all').on('click', function (e) {
        e.preventDefault();
        $('.display-locale').prop('checked', true);
    });

    $('#display-locale-none').on('click', function (e) {
        e.preventDefault();
        $('.display-locale').prop('checked', false);
    });

    function showMatched(table, matched) {
        table.find('tr').each(function () {
            if (!$(this).hasClass('hidden')) {
                var key = $(this).find('td.key').first();
                if (key.length > 0) {
                    var text = key[0].innerText;
                    if (!matched.exec(text)) {
                        $(this).addClass('hidden');
                    }
                }
            }
        });
    }

    function showAll(table) {
        table.find('tr').removeClass('hidden');
    }

    var updateTranslationList = showAll;
    var updateTranslationFilter = 'show-all';

    function updateMatching(elem) {
        var table = $('#translations').find('tbody').first(),
            matchedText = $('#show-matching-text'),
            matchedTextLabel = $('#show-matching-text-label'),
            matched, totalKeys, filteredKeys, matchedKeys, keyFilterSpan, pattern = '', elemName;

        elem = $(elem);
        if (elem.length > 0) {
            updateTranslationFilter = elem.prop('id');
        }

        totalKeys = table.find('tr').length;
        updateTranslationList(table);
        matchedKeys = filteredKeys = totalKeys - table.find('tr.hidden').length;

        if (elem.length > 0) {
            var elemDiv = elem.parent().parent();

            $('.translation-filter').removeClass('has-success has-error has-feedback has-highlight');
            if (updateTranslationFilter != 'show-all') {
                if (filteredKeys > 0) {
                    // turn the filter green
                    if (filteredKeys == totalKeys) {
                        elemDiv.addClass('has-feedback');
                    } else {
                        elemDiv.addClass('has-highlight');
                    }
                } else {
                    // turn the filter red
                    elemDiv.addClass('has-error');
                }
            }
        }

        if (matchedText.length > 0) {
            pattern = matchedText[0].value.trim();
            var regexError = false;

            try {
                matched = new RegExp(pattern, 'i');
                matchedTextLabel.html("&nbsp;");
            }
            catch (e) {
                matched = new RegExp("");
                matchedTextLabel.text(e.message);
                // matchedTextLabel.removeClass('hidden');
                regexError = true;
            }

            showMatched(table, matched);
            matchedKeys = totalKeys - table.find('tr.hidden').length;
            var matchedTextParent = matchedText.parent('div');

            matchedTextParent.removeClass('has-error has-success has-feedback has-highlight');
            matchedText.removeClass('bg-danger bg-success bg-highlight regex-error');
            if (regexError) {
                matchedTextParent.addClass('has-error');
                matchedText.addClass('regex-error');
            } else {
                if (pattern.length > 0 && filteredKeys > 0) {
                    if (matchedKeys == 0) {
                        matchedTextParent.addClass('has-error');
                        matchedText.addClass('bg-danger');
                    } else if (matchedKeys < filteredKeys) {
                        matchedTextParent.addClass('has-highlight');
                        matchedText.addClass('bg-highlight');
                    }
                }
            }
        }

        var jqxhr = $.ajax({
            type: 'GET',
            url: URL_TRANSLATOR_FILTERS,
            data: {'filter': updateTranslationFilter, 'regex': pattern},

            success: function (json) {
                if (json.status === 'ok') {
                }
            },
            encode: true
        });

        keyFilterSpan = $('#key-filter').first();
        if (keyFilterSpan.length > 0) {
            var html = "";
            if (matchedKeys !== filteredKeys) {
                html += matchedKeys + "/";
            }
            if (filteredKeys !== totalKeys) {
                html += filteredKeys + "/";
            }

            html += totalKeys;

            keyFilterSpan.removeClass('have-filtered');
            if (matchedKeys !== totalKeys) {
                keyFilterSpan.addClass('have-filtered');
            }
            keyFilterSpan[0].innerHTML = html;
        }
    }

    $('#show-all').on('click', function (e) {
        //e.preventDefault();
        updateTranslationList = showAll;
        updateMatching(this);
    });

    $('#show-need-attention').on('click', function (e) {
        //e.preventDefault();
        updateTranslationList = function (table) {
            table.find('tr').addClass('hidden');
            table.find('tr.has-empty-translation').removeClass('hidden');
            table.find('tr.deleted-translation').removeClass('hidden');
            table.find('tr.has-changed-translation').removeClass('hidden');
        };
        updateMatching(this);
    });

    $('#show-unpublished').on('click', function (e) {
        //e.preventDefault();
        updateTranslationList = function (table) {
            table.find('tr').addClass('hidden');
            table.find('tr.deleted-translation').removeClass('hidden');
            table.find('tr.has-changed-translation').removeClass('hidden');
        };
        updateMatching(this);
    });

    $('#show-new').on('click', function (e) {
        //e.preventDefault();
        updateTranslationList = function (table) {
            table.find('tr').removeClass('hidden');
            table.find('tr.has-nonempty-translation').addClass('hidden');
        };
        updateMatching(this);
    });

    $('#show-empty').on('click', function (e) {
        //e.preventDefault();
        updateTranslationList = function (table) {
            table.find('tr').addClass('hidden');
            table.find('tr.has-empty-translation').removeClass('hidden');
        };
        updateMatching(this);
    });

    $('#show-nonempty').on('click', function (e) {
        //e.preventDefault();
        updateTranslationList = function (table) {
            table.find('tr').addClass('hidden');
            table.find('tr.has-nonempty-translation').removeClass('hidden');
        };
        updateMatching(this);
    });

    $('#show-used').on('click', function (e) {
        //e.preventDefault();
        updateTranslationList = function (table) {
            table.find('tr').addClass('hidden');
            table.find('tr.has-used-translation').removeClass('hidden');
        };
        updateMatching(this);
    });

    $('#show-deleted').on('click', function (e) {
        //e.preventDefault();
        updateTranslationList = function (table) {
            table.find('tr').addClass('hidden');
            table.find('tr.deleted-translation').removeClass('hidden');
        };
        updateMatching(this);
    });

    $('#show-changed').on('click', function (e) {
        //e.preventDefault();
        updateTranslationList = function (table) {
            table.find('tr').addClass('hidden');
            table.find('tr.has-changed-translation').removeClass('hidden');
        };
        updateMatching(this);
    });

    var updateMatchingTimer = null,
        matchingText = $('#show-matching-text');

    matchingText.on('keyup change', function () {
        if (updateMatchingTimer) {
            window.clearTimeout(updateMatchingTimer);
            updateMatchingTimer = null;
        }

        updateMatchingTimer = window.setTimeout(function () {
            updateMatchingTimer = null;
            updateMatching();
        }, 100);
    });

    $('#show-matching-clear').on('click', function () {
        if (matchingText.length) {
            matchingText[0].value = '';
            matchingText.focus();
            updateMatching();
        }
    });

    $('div.alert-dismissible').each(function () {
        var elem = $(this), btn = elem.find('button.close').first();
        if (btn.length) {
            btn.on('click', function () {
                elem.slideUp();
            });
        }
    });

    function postTranslationValues(translations, elemButton, processText) {
        if (!translations.length) {
            return;
        }

        var progTotal, progCurrent, btnText = elemButton.text(), btnAlt = elemButton.data('disable-with');
        progTotal = translations.length;
        progCurrent = 0;

        // we could process all the keys in parallel but we will do it a few at a time
        var fireTranslate, translateNext = function (t) {
            processText(t.srcText, function (text, trans) {
                if (text !== "") {
                    var jqxhr = $.ajax({
                        type: 'POST',
                        url: t.dataUrl,
                        data: {'name': t.dataName, 'value': text},
                        success: function (json) {
                            if (json.status === 'ok') {
                                // now can update the element and fire off the next translation
                                t.dstElem.removeClass('editable-empty status-0');
                                t.dstElem.addClass('status-1');
                                t.dstElem.text(text);
                                t.dstElem.editable('setValue', text, false);
                                t.dstElem.closest('tr').addClass('has-changed-translation');
                            }
                            else {
                                elemButton.removeAttr('disabled');
                                elemButton.text(btnText);
                            }
                        },
                        encode: true
                    });

                    jqxhr.done(function () {
                        fireTranslate();
                    });

                    jqxhr.fail(function () {
                        elemButton.removeAttr('disabled');
                        elemButton.text(btnText);
                    });
                }
                else {
                    elemButton.removeAttr('disabled');
                    elemButton.text(btnText);
                }
            });

        };

        fireTranslate = function () {
            if (translations.length) {
                progCurrent++;
                translateNext(translations.pop());
                elemButton.attr('disabled', 'disabled');
                elemButton.text(btnAlt + ' ' + progCurrent + ' / ' + progTotal);
            }
            else {
                elemButton.removeAttr('disabled');
                elemButton.text(btnText);
            }
        };

        for (var i = 0; i < 5; i++) {
            fireTranslate();
        }
    }

    var elemAutoTrans = $('.btn.auto-translate');

    elemAutoTrans.each(function () {
        var colNum = $(this).data('trans');
        var dstLoc = $(this).data('locale');
        var btnElem = $(this);

        btnElem.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var autoTranslate = [];

            // step through all the definitions in the second column and auto translate empty ones
            // here we make a log of assumptions about where the data is.
            // we assume that the source is the child element immediately preceding this one and it is a <td> containing
            // <a> containing the source text
            $(".auto-translatable-" + dstLoc).each(function () {
                var row = $(this).parent().find('.vsch_editable');
                if (row.length > 1) {
                    var srcElem = $(row[0]),
                        dstElem = $(row[colNum]),
                        tr = row.closest('tr');

                    if (!tr.hasClass('hidden') && dstElem.length && srcElem.length) {
                        if (dstElem.hasClass('editable-empty') && !srcElem.hasClass('editable-empty')) {
                            autoTranslate.push({
                                srcText: srcElem.text(),
                                dataUrl: dstElem.data('url'),
                                dataName: dstElem.data('name'),
                                dstElem: dstElem
                            });
                        }
                    }
                }
            });

            (function (fromLoc, toLoc, btnElem) {
                postTranslationValues(autoTranslate, btnElem, function (text, storeText) {
                    xtranslateText(xtranslateService, fromLoc, text, toLoc, function (text, trans) {
                        storeText(text, trans);
                    });
                });
            })(PRIMARY_LOCALE, dstLoc, btnElem);
        });
    });

    var elemAutoFill = $('#auto-fill');
    elemAutoFill.on('click', function (e) {
        var autoFill = [];

        e.preventDefault();
        e.stopPropagation();

        // step through all the definitions in the second column and auto translate empty ones
        // here we make a log of assumptons about where the data is.
        // we assume that the source is the child element immediately preceeding this one and it is a <td> containing
        // <a> containing the source text
        $(".auto-fillable").each(function () {
            var dstElem = $(this).find("a.vsch_editable.editable-empty"),
                tr = dstElem.closest('tr');

            if (dstElem.length && !tr.hasClass('hidden')) {
                autoFill.push({
                    srcText: dstElem.data('name').substr(PRIMARY_LOCALE.length + 1),
                    dataUrl: dstElem.data('url'),
                    dataName: dstElem.data('name'),
                    dstElem: dstElem
                });
            }
        });

        (function (elemButton) {
            postTranslationValues(autoFill, elemButton, function (text, storeText) {
                var regexnodash = /^.*\.|-|_/g,
                    value = text.replace(regexnodash, ' ').toCapitalCase().trim();

                storeText(value, '');
            });
        })(elemAutoFill);
    });

    $('.auto-delete-key').on('click', function () {
        var table = $('#translations').find('tbody').first(),
            keys = table.find('.delete-key');

        keys.each(function () {
            var row = $(this).closest('tr');
            if (!row.hasClass('hidden') && !$(this).hasClass('hidden')) {
                $(this).trigger('click');
            }
        });
    });

    $('.auto-undelete-key').on('click', function () {
        var table = $('#translations').find('tbody').first(),
            keys = table.find('.undelete-key');

        keys.each(function () {
            var row = $(this).closest('tr');
            if (!row.hasClass('hidden') && !$(this).hasClass('hidden')) {
                $(this).trigger('click');
            }
        });
    });

    $('a.show-source-refs').on('ajax:success', function (e, data) {
        var elemModal = $('#sourceRefsModal').first();
        var elemHeader = elemModal.find('#key-name').first();
        elemHeader.text(data.key_name);
        var elemResults = elemModal.find('.results').first();
        var result = data.result.join("<br>");
        elemResults.html(result);
        elemModal.modal('show');
    });

    var elemAutoPropCase = $('.btn.auto-prop-case');
    elemAutoPropCase.each(function () {
        var colNum = $(this).data('trans');
        var dstLoc = $(this).data('locale');
        var btnElem = $(this);

        btnElem.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var autoPropCase = [],
                regex = XRegExp("^(\\p{Alphabetic}|\\p{Number})+(\\p{Space_Separator}+(\\p{Alphabetic}|\\p{Number})+)+\\.?$");

            // step through all the definitions in the second column and auto translate empty ones
            // here we make a log of assumptions about where the data is.
            // we assume that the source is the child element immediately preceding this one and it is a <td> containing
            // <a> containing the source text
            $(".auto-translatable-" + dstLoc).each(function () {
                var row = $(this).closest('tr');
                if (!row.hasClass('hidden')) {
                    var editable = $(this).parent().find('.vsch_editable');
                    // exclude filtered out rows, give a bit of control over what will change
                    if (editable.length > 1) {
                        var srcElem = $(editable[0]),
                            dstElem = $(editable[colNum]);

                        if (dstElem.length) {
                            if (!dstElem.hasClass('editable-empty')) {
                                var text = dstElem.text();
                                var simpleWords = regex.test(text);
                                window.console.log(text + " simple " + simpleWords);
                                if (text !== text.toLocaleProperCaseOrLowerCase() && simpleWords) {
                                    autoPropCase.push({
                                        srcText: dstElem.text(),
                                        dataUrl: dstElem.data('url'),
                                        dataName: dstElem.data('name'),
                                        dstElem: dstElem
                                    });
                                }
                            }
                        }
                    }
                }
            });

            if (autoPropCase.length > 0) {
                (function (fromLoc, toLoc, btnElem) {
                    postTranslationValues(autoPropCase, btnElem, function (text, storeText) {
                        storeText(text.toLocaleProperCaseOrLowerCase(), '');
                    });
                })(dstLoc, dstLoc, btnElem);
            }
        });
    });

    function textareaTandemResize(src, dst, liveupdate) {
        return function () {
            var srcTimeout = {id: null},
                dstTimeout = {id: null};

            // the handler function
            var resizeEvent = function (src, dst, timeout) {
                return function () {
                    if (dst.css("resize") === 'both') {
                        dst.outerWidth(src.outerWidth());
                        dst.outerHeight(src.outerHeight());
                    }
                    else {
                        if (dst.css("resize") === 'horizontal') {
                            dst.outerWidth(src.outerWidth());
                        }
                        else {
                            if (dst.css("resize") === 'vertical') {
                                dst.outerHeight(src.outerHeight());
                            }
                        }
                    }

                    if (timeout.id) {
                        clearTimeout(timeout.id);
                        timeout.id = null;
                    }
                };
            };

            // This provides a "real-time" (actually 15 fps)
            // event, while resizing.
            // Unfortunately, mousedown is not fired on Chrome when
            // clicking on the resize area, so the real-time effect
            // does not work under Chrome.
            if (liveupdate) {
                $(window).on("mousemove", function (e) {
                    if (e.target === src[0]) {
                        if (srcTimeout.id) {
                            clearTimeout(srcTimeout.id);
                            srcTimeout.id = null;
                        }
                        srcTimeout.id = setTimeout(resizeEvent(src, dst, srcTimeout), 1000 / 30);
                    }
                    else {
                        if (e.target === dst[0]) {
                            if (dstTimeout.id) {
                                clearTimeout(dstTimeout.id);
                                dstTimeout.id = null;
                            }
                            dstTimeout.id = setTimeout(resizeEvent(dst, src, dstTimeout), 1000 / 30);
                        }
                    }
                });
            }

            // The mouseup event stops the interval,
            // then call the resize event one last time.
            // We listen for the whole window because in some cases,
            // the mouse pointer may be on the outside of the textarea.
            $(window).on("mouseup", function (e) {
                if (srcTimeout.id !== null) {
                    clearTimeout(srcTimeout.id);
                    srcTimeout.id = null;
                }
                if (e.target === src[0]) {
                    resizeEvent(src, dst, srcTimeout)();
                }
                else {
                    if (e.target === dst[0]) {
                        resizeEvent(dst, src, dstTimeout)();
                    }
                }
            });
        };
    }

    var elem = $("#form-addkeys").first();
    textareaTandemResize(elem.find("textarea[name=keys]"), elem.find("textarea[name=suffixes]"), true)();
    textareaTandemResize($("#primary-text"), $("#current-text"), true)();
    textareaTandemResize($("#srckeys"), $("#dstkeys"), true)();

    if (TRANS_FILTERS && CURRENT_GROUP != '') {
        var filter = TRANS_FILTERS.filter;
        var regex = TRANS_FILTERS.regex;
        var elemRadio = $('#' + filter);
        elemRadio.prop('checked', 'checked');

        if (filter !== 'show-all' || regex) {
            $('#show-matching-text')[0].value = regex;
            elemRadio.trigger('click');
        }
    }

    $(function () {
        $('.user-locales').editable({
            template: '' +
            '<form class="form-inline editableform">' +
            '<div class="control-group">' +
            '<div><div id="x-trans-edit" class="editable-input"></div></div>' +
            '<div class="editable-error-block"></div>' +
            '</div>' +
            '</form>'

            , source: USER_LOCALES

            , emptytext: 'All'
            , showbuttons: false
            , display: function (value, sourceData) {
                //display checklist as comma-separated values
                var html = [],
                    checked = $.fn.editableutils.itemsByValue(value, sourceData);

                if (checked.length) {
                    $.each(checked, function (i, v) {
                        html.push($.fn.editableutils.escape(v.text));
                    });
                    $(this).html(html.join(' '));
                } else {
                    $(this).empty();
                }
            }
        });
    });

});
