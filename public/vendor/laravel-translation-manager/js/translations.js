/*jshint browser: true, jquery: true*/
/**
 * Created by vlad on 15-02-10.
 */
var CLIP_TEXT;
var MISSMATCHED_QUOTES_MESSAGE;
var YANDEX_TRANSLATOR_KEY;
var URL_YANDEX_TRANSLATOR_KEY;
var PRIMARY_LOCALE;
var CURRENT_LOCALE;
var TRANSLATING_LOCALE;
var xtranslateText;
var xtranslateService;
var MARKDOWN_KEY_SUFFIX;

function swapInClass(elem, toAdd, toRemove) {
    'use strict';
    return elem.removeClass(toRemove).addClass(toAdd);
}

function swapOutClass(elem, toRemove, toAdd) {
    'use strict';
    return elem.removeClass(toRemove).addClass(toAdd);
}

function swapClass(elem, swapDir, toAdd, toRemove) {
    'use strict';
    return swapDir ? swapInClass(elem, toAdd, toRemove) : swapOutClass(elem, toAdd, toRemove);
}

String.prototype.toCapitalCase = function () {
    "use strict";
    return this.toLowerCase().replace(/(?:^|\s)\S/g, function (a) {
        return a.toUpperCase();
    });
};

String.prototype.toLocaleCapitalCase = function () {
    "use strict";
    return this.toLocaleLowerCase().replace(/(?:^|\s)\S/g, function (a) {
        return a.toLocaleUpperCase();
    });
};

String.prototype.toLocaleProperCase = function () {
    "use strict";
    return this.toLocaleLowerCase().replace(/(?:^)\S/, function (a) {
        return a.toLocaleUpperCase();
    });
};

String.prototype.toLocaleProperCaseOrLowerCase = function () {
    "use strict";
    return this.substr(0, 1) + this.substr(1).toLocaleLowerCase();
};

function translateYandex(fromLoc, fromText, toLoc, onTranslate) {
    var ERR_OK = 200,
        ERR_KEY_INVALID = 401,
        ERR_KEY_BLOCKED = 402,
        ERR_DAILY_REQ_LIMIT_EXCEEDED = 403,
        ERR_DAILY_CHAR_LIMIT_EXCEEDED = 404,
        ERR_TEXT_TOO_LONG = 413,
        ERR_UNPROCESSABLE_TEXT = 422,
        ERR_LANG_NOT_SUPPORTED = 501,
        errCodes = {
            200: 'Operation completed successfully.',
            401: 'Invalid API key.',
            402: 'This API key has been blocked.',
            403: 'You have reached the daily limit for requests (including calls of the detect method).',
            404: 'You have reached the daily limit for the volume of translated text (including calls of the detect method).',
            413: 'The text size exceeds the maximum.',
            422: 'The text could not be translated.',
            501: 'The specified translation direction is not supported.'
        };

    var jqxhr = $.getJSON("https://translate.yandex.net/api/v1.5/tr.json/translate", {
            key: YANDEX_TRANSLATOR_KEY,
            lang: fromLoc + '-' + toLoc,
            text: fromText
        },
        function (json) {
            if (json.code === ERR_OK) {
                onTranslate(json.text.join("\n"));
            }
            else {
                window.console.log("Yandex API: " + json.code + ': ' + errCodes[json.code] + "\n");
            }
        });

    jqxhr.done(function () {
    });

    jqxhr.fail(function () {
    });
}

function startsWithWord2(word1, word2, prefix) {
    return word2.toLocaleLowerCase().indexOf(prefix) === 0 && word1.toLocaleLowerCase().indexOf(prefix) !== 0;
}

function extractSecondWord(text) {
    var word1, word2, pos = text.indexOf(' ');

    if (pos !== -1) {
        word1 = text.substr(0, pos);
        word2 = text.substr(pos + 1);
        if (startsWithWord2(word1, word2, 'од') || startsWithWord2(word1, word2, 'дв') || startsWithWord2(word1, word2, 'пя')) {
            text = word1;
        } else {
            text = word2;
        }
    }
    return text;
}

function extractPluralForm(pluralForms, index) {
    if (pluralForms.length > index) {
        return extractSecondWord(pluralForms[index]);
    }
    return '';
}

xtranslateService = translateYandex;
xtranslateText = function (translator, srcLoc, srcText, dstLoc, processText) {
    var pos, single, plural, havePlural, src = srcText;

    if ((pos = srcText.indexOf('|')) !== -1) {
        // have pluralization
        single = srcText.substr(0, pos);
        plural = srcText.substr(pos + 1);
        src = 'one ' + single + '\ntwo ' + plural + '\nfive ' + plural;
        havePlural = true;
    }

    // convert all occurences of :parameter to {{#}} where # is the parameter number and store the parameter at index #
    // that way they won't be mangled by translation and we can restore them back on return.
    var lastPos = 0, params = [], haveParams, matches, regexParam = /\:([a-zA-Z0-9_-]*)(?=[^a-zA-Z0-9_-]|$)/g,
        result = '', paramIndex = 0;

    while ((matches = regexParam.exec(src)) !== null) {
        var param = matches[1];

        if (!(paramIndex = params.indexOf(param) + 1)) {
            params.push(param);
            paramIndex = params.length;
        }

        result += src.substr(lastPos, matches.index - lastPos);
        result += '{{' + paramIndex + '}}';
        lastPos = regexParam.lastIndex;
    }

    if (paramIndex) {
        result += src.substr(lastPos, src.length);
        src = result;
    }

    translator(srcLoc, src, dstLoc, function (text) {
        var single, plural, plural2, pluralForms, trans, regexIndex = /\{\{[0-9]*\}\}/g;

        if (paramIndex) {
            text = text.replace(regexIndex, function (index) {
                return ':' + params[parseInt(index.substr(2, index.length - 4)) - 1];
            });
        }

        if (havePlural) {
            trans = text.replace(/$\s*/mg, '|');
            if (trans.substr(trans.length - 1, 1) === '|') {
                trans = trans.substr(0, trans.length - 1);
            }

            pluralForms = text.split('\n', 3);
            single = extractPluralForm(pluralForms, 0);
            plural = extractPluralForm(pluralForms, 1);

            if (dstLoc === 'ru') {
                plural2 = extractPluralForm(pluralForms, 2);
                text = single + '|' + plural + '|' + plural2;
            }
            else {
                // TODO: have to handle other plural forms for complex locales
                text = single + '|' + plural;
            }
        }
        processText(text, trans);
    });
};

$(document).ready(function () {
    'use strict';
    var elem;

    $.ajaxPrefilter(function (options) {
        if (!options.crossDomain) {
            options.headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            };
            //window.console.log('Injected CSRF: ' + $('meta[name="csrf-token"]').attr('content'));
        }
    });

    if (URL_YANDEX_TRANSLATOR_KEY) {
        $.ajax({
            type: 'POST',
            url: URL_YANDEX_TRANSLATOR_KEY,
            data: {},
            success: function (json) {
                if (json.status === 'ok') {
                    YANDEX_TRANSLATOR_KEY = json.yandex_key;
                }
            },
            encode: true
        });
    }

    function validateXEdit(value) {
        // check for open or mismatched quotes in href=  and src=, attributes if any
        var regex = /\b(href=|src=)\s*("|')?([^"'>]*)("|')?/;
        var regexErr = /:string/g;
        var message = MISSMATCHED_QUOTES_MESSAGE || "mismatched or missing quotes in :string attribute";
        var messages = [];
        var offs = 0, pos;
        var matches, val = value, maxlen = value.length;
        var elemDivErr;

        while (offs < maxlen && (pos = val.indexOf('href=', offs)) !== -1 || (pos = val.indexOf('src=', offs)) !== -1) {
            offs = pos;
            val = val.substr(offs > 0 ? offs - 1 : 0);
            matches = val.match(regex);
            if (!matches) {
                break;
            }
            // see if any are missmatched or missing
            if (matches[2] === undefined || matches[2] !== matches[4]) {
                messages.push(message.replace(regexErr, matches[0]));
            }
            offs += matches[0].length;
        }
        if (messages.length) {
            elemDivErr = $(".editableform .editable-error-block");
            if (elemDivErr.length) {
                window.setTimeout(function () {
                    elemDivErr.html(val);
                }, 50);
            }
            val = "<ul><li>" + messages.join('</li><li>') + "</li></ul>";
            return " ";
        }
    }

    $.fn.editableContainer.defaults.placement = 'top';
    $.fn.editableContainer.defaults.onblur = 'submit';
    $.fn.editableContainer.defaults.validate = function (value) {
        return validateXEdit(value);
    };

    $.fn.editableform.template = '' +
        '<form class="form-inline editableform">' +
        '<div class="control-group">' +
        ' <div>' +
        '  <div class="editable-buttons" style="display: block"></div>' +
        '  <div id="x-trans-edit" class="editable-input"></div>' +
        '  <div class="editable-error-block"></div>' +
        ' </div>' +
        '</div>' +
        '</form>';

    $.fn.editableform.buttons = '' +
        '<button type="submit" id="x-submit" class="editable-submit btn btn-sm btn-success"><i class="glyphicon glyphicon-ok"></i></button>' +
        '&nbsp;<button type="button" id="x-cancel" class="editable-cancel btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></button>' +
        '&nbsp;&nbsp;<button id="x-translate" type="button" class="editable-translate btn btn-sm btn-warning hidden"><i class="glyphicon glyphicon-share-alt"></i></button>' +
        '<button id="x-nodash" type="button" class="editable-translate btn btn-sm btn-warning hidden">❉ <i class="glyphicon glyphicon-share-alt"></i> Ab</button>' +
        '&nbsp;&nbsp;<button id="x-plurals" type="button" class="editable-translate btn btn-sm btn-warning hidden">|</i></button>' +
        '<button id="x-clean-markdown" type="button" class="editable-translate btn btn-sm btn-warning hidden"><i class="glyphicon glyphicon-flash"></i></button>' +
        '&nbsp;&nbsp;<button id="x-capitalize" type="button" class="editable-translate btn btn-sm btn-info">ab <i class="glyphicon glyphicon-share-alt"></i> Ab</button>' +
        '<button id="x-lowercase" type="button" class="editable-translate btn btn-sm btn-info">AB <i class="glyphicon glyphicon-share-alt"></i> ab</button>' +
        '<button id="x-propcap" type="button" class="editable-translate btn btn-sm btn-info">A B <i class="glyphicon glyphicon-share-alt"></i> A b</button>' +
        '&nbsp;&nbsp;<button id="x-copy" type="button" class="editable-translate btn btn-sm btn-primary"><i class="glyphicon glyphicon-copy"></i></button>' +
        '<button id="x-paste" type="button" class="editable-translate btn btn-sm btn-primary"><i class="glyphicon glyphicon-paste"></i></button>' +
        '&nbsp;&nbsp;<button id="x-reset-open" type="button" class="editable-translate btn btn-sm btn-success"><i class="glyphicon glyphicon-open"></i></button>' +
        '<button id="x-reset-saved" type="button" class="editable-translate btn btn-sm btn-success"><i class="glyphicon glyphicon-floppy-open"></i></button>' +
        '<br>&nbsp;';

    function textAreaSelectedText(elemTextArea) {
        var selectedText;
        // IE version
        if (document.selection !== undefined) {
            elemTextArea.focus();
            var sel = document.selection.createRange();
            selectedText = sel.text;
            return selectedText;
        }
        // Mozilla version
        else {
            if (elemTextArea.selectionStart !== undefined) {
                var startPos = elemTextArea.selectionStart;
                var endPos = elemTextArea.selectionEnd;
                selectedText = elemTextArea.value.substring(startPos, endPos);
                return selectedText;
            }
        }
    }

    $.fn.editableform.formElements = function (elem) {
        var divElem = $(elem).find('+ div.editable-container');
        if (divElem.length > 0) {
            return {
                elemInput: divElem.find('#x-trans-edit').first().find('textarea.editable-input').first(),
                elemError: divElem.find('.editable-error-block').first(),
                btnTranslate: divElem.find('#x-translate').first(),
                btnCapitalize: divElem.find('#x-capitalize').first(),
                btnLowercase: divElem.find('#x-lowercase').first(),
                btnPlurals: divElem.find('#x-plurals').first(),
                btnPropCap: divElem.find('#x-propcap').first(),
                btnNoDash: divElem.find('#x-nodash').first(),
                btnCopy: divElem.find('#x-copy').first(),
                btnPaste: divElem.find('#x-paste').first(),
                btnResetOpen: divElem.find('#x-reset-open').first(),
                btnResetSaved: divElem.find('#x-reset-saved').first(),
                btnCleanMarkdown: divElem.find('#x-clean-markdown').first()
            };
        }
        return null;
    };

    var srcText, srcLoc, dstLoc, dstElem, elemRow, inEditable = 0,
        xtranslate = function (srcLoc, srcText, dstLoc, dstElem, errElem) {
            return function () {
                xtranslateText(xtranslateService, srcLoc, srcText, dstLoc, function (result, trans) {
                    if (trans) {
                        errElem.html(trans);
                        errElem.css('display', 'block');
                    }
                    dstElem.val(result);
                    dstElem.focus();
                });
            };
        },
        xedit = function (dstElem, editOp, params) {
            return function () {
                var sel = textAreaSelectedText(dstElem[0]);
                if (sel) {
                    dstElem.selection('replace', {text: editOp.apply(sel, params)});
                }
                else {
                    dstElem.val(editOp.apply(dstElem.val(), params));
                }
                dstElem.focus();
            };
        },
        xeditplurals = function (dstElem, editOp, params) {
            return function () {
                var sel = textAreaSelectedText(dstElem[0]);
                if (sel) {
                    dstElem.selection('replace', {text: editOp.apply(sel, params)});
                }
                else {
                    var pluralForms;

                    if (dstElem.val().indexOf('|') !== -1) {
                        pluralForms = dstElem.val().split('|');
                        pluralForms.forEach(function (val, index, arr) {
                            arr[index] = editOp.apply(val, params);
                        });

                        dstElem.val(pluralForms.join('|'));
                    }
                    else {
                        dstElem.val(editOp.apply(dstElem.val(), params));
                    }
                }
                dstElem.focus();
            };
        },
        xfull = function (dstElem, editOp, params) {
            return function () {
                dstElem.val(editOp.apply(dstElem.val(), params));
                dstElem.focus();
            };
        };

    $.fn.vsch_editable = function (options) {
        var defaults = {},
            settings = $.extend({}, defaults, options);

        this.each(function () {
            var elem = $(this);
            var top = elem.offset().top;

            if (top < 300) {
                elem.editable({placement: 'bottom'});
            }
            else {
                elem.editable({placement: 'top'});
            }

            elem.editable().off('hidden');
            elem.editable().on('hidden.vsch', function (e, reason) {
                var locale = $(this).data('locale');
                var elemRow = $(this).parents('tr').first();
                var trans = elemRow.find('a.vsch_editable'), tmp;

                if (reason === 'save') {
                    $(this).removeClass('status-0').addClass('status-1');
                }
                if (reason === 'save' || reason === 'nochange') {
                }

                if ((tmp = trans.filter('.editable-empty').length)) {
                    elemRow.addClass('has-empty-translation');
                    if (tmp === trans.length) {
                        elemRow.removeClass('has-nonempty-translation');
                    } else {
                        elemRow.addClass('has-nonempty-translation');
                    }
                } else {
                    elemRow.removeClass('has-empty-translation');
                    elemRow.addClass('has-nonempty-translation');
                }

                if (trans.filter('.status-1').length) {
                    elemRow.addClass('has-changed-translation');
                } else {
                    elemRow.removeClass('has-changed-translation');
                }

                if (!--inEditable) {
                    $(".editing").removeClass('editing');
                }

                //window.console.log("editable hidden: " + inEditable);
            });

            elem.editable().off('shown');
            elem.editable().on('shown.vsch', function (e, editable) {
                var key, srcId, srcElem,
                    savedValue = $(this).data('saved_value'), openedValue,
                    dstId = $(this).attr('id'),
                    regexnodash = /-|_/g,
                    value;

                dstLoc = $(this).data('locale');
                srcLoc = dstLoc === PRIMARY_LOCALE ? '' : PRIMARY_LOCALE;

                inEditable++;
                //window.console.log("editable shown: " + inEditable);

                srcId = srcLoc + dstId.substr(dstLoc.length);
                key = dstId.substr(dstLoc.length + 1);
                elemRow = $('tr#' + key.replace(/\./, '-')).first();
                value = key.replace(regexnodash, ' ').toCapitalCase();

                var xElem = $.fn.editableform.formElements(this);

                dstElem = xElem.elemInput;
                openedValue = dstElem[0].value.trim();
                dstElem[0].value = openedValue;

                if (MARKDOWN_KEY_SUFFIX !== undefined && MARKDOWN_KEY_SUFFIX !== '' && key.length > MARKDOWN_KEY_SUFFIX.length && key.substring(key.length - MARKDOWN_KEY_SUFFIX.length, key.length) == MARKDOWN_KEY_SUFFIX) {
                    if (xElem.btnCleanMarkdown.length) {
                        xElem.btnCleanMarkdown.removeClass('hidden');
                        xElem.btnCleanMarkdown.on('click', xedit(dstElem, function (params) {
                                // clean up wrapped lines which are not markdown hard breaks or blank lines
                                // TODO: clean up this shit-ball. Check by lines not characrters
                                // split into lines and step through
                                var text = this;
                                var lines = text.split('\n');
                                var iMax = lines.length;
                                var fixedText = "";
                                var lastWasBlank = true;
                                for (var i = 0; i < iMax; i++) {
                                    var line = lines[i];
                                    var isBlankLine = false;

                                    if (line.trim().length == 0) {
                                        if (!lastWasBlank) {
                                            line = "\n";
                                            isBlankLine = true;
                                        } else {
                                            line = "";
                                            isBlankLine = lastWasBlank;
                                        }
                                    } else {
                                        isBlankLine = false;

                                        if (line.length > 2 && line.substring(line.length - 2, line.length) == "  ") {
                                            // we keep the spaces and add an end of line
                                            line += "\n";
                                        } else {
                                            // we check if next line is blank, we keep eol
                                            if (i + 1 >= iMax || lines[i + 1].trim().length == 0) {
                                                line += "\n";
                                            }

                                            if (fixedText.length > 0) {
                                                var lastChar = fixedText.charAt(fixedText.length - 1);
                                                if (lastChar != '\n' && lastChar != ' ' && line.charAt(0) != ' ') {
                                                    // add a space, we will splice to previous line
                                                    fixedText += ' ';
                                                }
                                            }
                                        }
                                    }

                                    fixedText += line;
                                    lastWasBlank = isBlankLine;
                                }
                                return fixedText;
                            }
                        ));
                    }

                    // // this is needed to prevent doubling funky stuff happening with markdown text and blank lines
                    // dstElem.off('paste');
                    // dstElem.on('paste', function (e) {
                    //     var pastedText = undefined;
                    //     if (window.clipboardData && window.clipboardData.getData) { // IE
                    //         pastedText = window.clipboardData.getData('Text');
                    //     } else {
                    //         if (e.originalEvent.clipboardData && e.originalEvent.clipboardData.getData) {
                    //             pastedText = e.originalEvent.clipboardData.getData('text/plain');
                    //         }
                    //     }
                    //
                    //     dstElem[0].value = pastedText;
                    //     return false; // Prevent the default handler from running.
                    // });
                } else {
                    // not markdown
                    if (xElem.btnPlurals.length) {
                        if (dstLoc === PRIMARY_LOCALE || YANDEX_TRANSLATOR_KEY !== '') {
                            xElem.btnPlurals.removeClass('hidden');
                            xElem.btnPlurals.on('click', xfull(dstElem, function () {
                                var val = this;
                                if (val.indexOf('|') === -1) {
                                    switch (dstLoc) {
                                        case 'ru' :
                                            val = this + '|' + this + '|' + this;
                                            break;

                                        case 'en' :
                                            if (PRIMARY_LOCALE === 'en') {
                                                val = value.singularize() + '|' + value.pluralize();
                                            }
                                            else {
                                                val = val.singularize() + '|' + val.pluralize();
                                            }
                                            break;

                                        // TODO: add locale tests and code to create plural forms
                                        default:
                                            val = this + '|' + this;
                                            break;
                                    }
                                    return val.toLocaleLowerCase();
                                }
                                return val;
                            }));
                        }
                    }
                }

                if (elemRow.length) {
                    $(".editing").removeClass('editing');
                    elemRow.addClass('editing');
                }

                if (xElem.btnTranslate.length && dstElem.length && YANDEX_TRANSLATOR_KEY !== '') {
                    if (srcLoc !== '') {
                        srcElem = elemRow.find('#' + srcId.replace(/\./, '-')).first();
                        if (srcElem.length) {
                            srcText = srcElem.text();

                            xElem.btnTranslate.html(srcLoc + ' <i class="glyphicon glyphicon-share-alt"></i> ' + dstLoc);
                            xElem.btnTranslate.removeClass('hidden');
                            xElem.btnTranslate.on('click', xtranslate(srcLoc, srcText, dstLoc, dstElem, xElem.elemError));
                        }
                    }
                }
                if (xElem.btnNoDash.length && dstLoc === PRIMARY_LOCALE) {
                    xElem.btnNoDash.removeClass('hidden');
                    xElem.btnNoDash.on('click', xfull(dstElem, function () {
                        return value;
                    }));
                }
                if (xElem.btnCapitalize.length) {
                    xElem.btnCapitalize.on('click', xeditplurals(dstElem, String.prototype.toLocaleCapitalCase));
                }
                if (xElem.btnLowercase.length) {
                    xElem.btnLowercase.on('click', xedit(dstElem, String.prototype.toLocaleLowerCase));
                }
                if (xElem.btnPropCap.length) {
                    xElem.btnPropCap.on('click', xedit(dstElem, String.prototype.toLocaleProperCase));
                }
                if (xElem.btnCopy.length) {
                    xElem.btnCopy.on('click', xedit(dstElem, function () {
                        CLIP_TEXT = this;
                        return this;
                    }));
                }
                if (xElem.btnPaste.length) {
                    xElem.btnPaste.on('click', xedit(dstElem, function () {
                        return CLIP_TEXT;
                    }));
                }
                if (xElem.btnResetOpen.length) {
                    xElem.btnResetOpen.on('click', xfull(dstElem, function () {
                        return openedValue;
                    }));
                }
                if (xElem.btnResetSaved.length) {
                    xElem.btnResetSaved.on('click', xfull(dstElem, function () {
                        return savedValue;
                    }));
                }
            });
        });
    };

    $('.vsch_editable').vsch_editable();

});
