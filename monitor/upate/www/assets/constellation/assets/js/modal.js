(function($) {
    var templateSetup = new Array();
    $.fn.addTemplateSetup = function(func, prioritary) {
        if (prioritary) {
            templateSetup.unshift(func);
        } else {
            templateSetup.push(func);
        }
    };
    $.fn.applyTemplateSetup = function() {
        var max = templateSetup.length;
        for (var i = 0; i < max; ++i) {
            templateSetup[i].apply(this);
        }
        return this;
    };
    $.fn.addTemplateSetup(function() {
        this.find('fieldset legend > a, .fieldset .legend > a').click(function(event) {
            $(this).toggleFieldsetOpen();
            event.preventDefault();
        });
        this.find('fieldset.collapse, .fieldset.collapse').toggleFieldsetOpen().removeClass('collapse');
        this.find('.tabs.same-height, .side-tabs.same-height, .mini-tabs.same-height, .controls-tabs.same-height').equalizeTabContentHeight();
        this.find('.js-tabs').updateTabs();
        this.find('input[type=radio].switch, input[type=checkbox].switch').hide().after('<span class="switch-replace"></span>').next().click(function() {
            $(this).prev().click();
        }).prev('.with-tip').next().addClass('with-tip').each(function() {
            $(this).attr('title', $(this).prev().attr('title'));
        });
        this.find('input[type=radio].mini-switch, input[type=checkbox].mini-switch').hide().after('<span class="mini-switch-replace"></span>').next().click(function() {
            $(this).prev().click();
        }).prev('.with-tip').next().addClass('with-tip').each(function() {
            $(this).attr('title', $(this).prev().attr('title'));
        });
        this.find('.js-tabs').initTabs();
    });
    $(document).ready(function() {
        $(document.body).applyTemplateSetup();
        $(window).bind('hashchange', function() {
            $(document.body).find('.js-tabs').updateTabs();
        });
    });
    $.fn.initTabs = function() {
        this.find('a[href^="#"]').unbind('click', onTabClick).bind('click', onTabClick);
    };

    function onTabClick(event) {
        event.preventDefault();
        if ($.fn.updateTabs.enabledHash) {
            var element = $(this);
            var hash = $.trim(window.location.hash || '');
            if (hash.length > 1) {
                var hashParts = hash.substring(1).split('&');
                var dummyIndex;
                while ((dummyIndex = $.inArray('', hashParts)) > -1) {
                    hashParts.splice(dummyIndex, 1);
                }
                while ((dummyIndex = $.inArray('none', hashParts)) > -1) {
                    hashParts.splice(dummyIndex, 1);
                }
                element.parent().parent().find('a[href^="#"]').each(function(i) {
                    var index = $.inArray($(this).attr('href').substring(1), hashParts);
                    if (index > -1) {
                        hashParts.splice(index, 1);
                    }
                });
            } else {
                var hashParts = [];
            }
            var defaultTab = getDefaultTabIndex(element.parent().parent());
            if (element.parent().index() != defaultTab) {
                hashParts.push(element.attr('href').substring(1));
            }
            if (hashParts.length == 1) {
                hashParts.unshift('');
            }
            window.location.hash = (hashParts.length > 0) ? '#' + hashParts.join('&') : '#none';
        } else {
            var li = $(this).closest('li');
            li.addClass('current').siblings().removeClass('current');
            li.parent().updateTabs();
        }
    };

    function getDefaultTabIndex(tabGroup) {
        var defaultTab = tabGroup.data('defaultTab');
        if (defaultTab === null || defaultTab === '' || defaultTab === undefined) {
            var firstTab = tabGroup.children('.current:first');
            defaultTab = (firstTab.length > 0) ? firstTab.index() : 0;
            tabGroup.data('defaultTab', defaultTab);
        }
        return defaultTab;
    };
    $.fn.updateTabs = function() {
        if ($.fn.updateTabs.enabledHash) {
            var hash = $.trim(window.location.hash || '');
            var hashParts = (hash.length > 1) ? hash.substring(1).split('&') : [];
        } else {
            var hash = '';
            var hashParts = [];
        }
        var hasHash = (hashParts.length > 0);
        this.each(function(i) {
            var tabGroup = $(this);
            var defaultTab = getDefaultTabIndex(tabGroup);
            var current = false;
            if ($.fn.updateTabs.enabledHash) {
                if (hasHash) {
                    var links = tabGroup.find('a[href^="#"]');
                    links.each(function(i) {
                        var linkHash = $(this).attr('href').substring(1);
                        if (linkHash.length > 0) {
                            var index = $.inArray(linkHash, hashParts);
                            if (index > -1) {
                                current = $(this).parent();
                                return false;
                            }
                        }
                    });
                }
            } else {
                current = tabGroup.children('.current:first');
            }
            if (!current) {
                current = tabGroup.children(':eq(' + defaultTab + ')');
            }
            if (current.length > 0) {
                hash = $.trim(current.children('a').attr('href').substring(1));
                if (hash.length > 0) {
                    current.addClass('current');
                    var tabContainer = $('#' + hash),
                        tabHidden = tabContainer.is(':hidden');
                    if (tabHidden) {
                        tabContainer.show();
                    }
                    current.siblings().removeClass('current').children('a').each(function(i) {
                        var hash = $.trim($(this).attr('href').substring(1));
                        if (hash.length > 0) {
                            var tabContainer = $('#' + hash);
                            if (tabContainer.is(':visible')) {
                                tabContainer.trigger('tabhide').hide();
                            } else if (!tabContainer.data('tabInited')) {
                                tabContainer.trigger('tabhide');
                                tabContainer.data('tabInited', true);
                            }
                        }
                    });
                    if (tabHidden) {
                        tabContainer.trigger('tabshow');
                    } else if (!tabContainer.data('tabInited')) {
                        tabContainer.trigger('tabshow');
                        tabContainer.data('tabInited', true);
                    }
                }
            }
        });
        return this;
    };
    $.fn.updateTabs.enabledHash = true;
    $.fn.resetTabContentHeight = function() {
        this.find('a[href^="#"]').each(function(i) {
            var hash = $.trim($(this).attr('href').substring(1));
            if (hash.length > 0) {
                $('#' + hash).css('height', '');
            }
        });
        return this;
    }
    $.fn.equalizeTabContentHeight = function() {
        var i;
        var g;
        var maxHeight;
        var tabContainers;
        var block;
        var blockHeight;
        var marginAdjustTop;
        var marginAdjustBot;
        var first;
        var last;
        var firstMargin;
        var lastMargin;
        for (i = this.length - 1; i >= 0; --i) {
            maxHeight = -1;
            tabContainers = [];
            this.eq(i).find('a[href^="#"]').each(function(i) {
                var hash = $.trim($(this).attr('href').substring(1));
                if (hash.length > 0) {
                    block = $('#' + hash);
                    if (block.length > 0) {
                        blockHeight = block.outerHeight() + parseInt(block.css('margin-bottom'));
                        marginAdjustTop = 0;
                        first = block.children(':first');
                        if (first.length > 0) {
                            firstMargin = parseInt(first.css('margin-top'));
                            if (!isNaN(firstMargin) && firstMargin < 0) {
                                marginAdjustTop = firstMargin;
                            }
                        }
                        marginAdjustBot = 0;
                        last = block.children(':last');
                        if (last.length > 0) {
                            lastMargin = parseInt(last.css('margin-bottom'));
                            if (!isNaN(lastMargin) && lastMargin < 0) {
                                marginAdjustBot = lastMargin;
                            }
                        }
                        if (blockHeight + marginAdjustTop + marginAdjustBot > maxHeight) {
                            maxHeight = blockHeight + marginAdjustTop + marginAdjustBot;
                        }
                        tabContainers.push([block, marginAdjustTop]);
                    }
                }
            });
            for (g = 0; g < tabContainers.length; ++g) {
                tabContainers[g][0].height(maxHeight - parseInt(tabContainers[g][0].css('padding-top')) - parseInt(tabContainers[g][0].css('padding-bottom')) - parseInt(tabContainers[g][0].css('margin-bottom')) - tabContainers[g][1]);
                if (g > 0) {
                    tabContainers[g][0].hide();
                }
            }
        }
        return this;
    };
    $.fn.showTab = function() {
        this.each(function(i) {
            $('a[href="#' + this.id + '"]').trigger('click');
        });
        return this;
    };
    $.fn.onTabShow = function(callback, runOnce) {
        if (runOnce) {
            var runOnceFunc = function() {
                callback.apply(this, arguments);
                $(this).unbind('tabshow', runOnceFunc);
            }
            this.bind('tabshow', runOnceFunc);
        } else {
            this.bind('tabshow', callback);
        }
        return this;
    };
    $.fn.onTabHide = function(callback, runOnce) {
        if (runOnce) {
            var runOnceFunc = function() {
                callback.apply(this, arguments);
                $(this).unbind('tabhide', runOnceFunc);
            }
            this.bind('tabhide', runOnceFunc);
        } else {
            this.bind('tabhide', callback);
        }
        return this;
    };
    $.fn.blockMessage = function(message, options) {
        var settings = $.extend({}, $.fn.blockMessage.defaults, options);
        this.each(function(i) {
            var block = $(this);
            if (!block.hasClass('block-content')) {
                block = block.find('.block-content:first');
                if (block.length == 0) {
                    block = $(this).closest('.block-content');
                    if (block.length == 0) {
                        return;
                    }
                }
            }
            var messageClass = (settings.type == 'info') ? 'message' : 'message ' + settings.type;
            if (settings.noMargin) {
                messageClass += ' no-margin';
            }
            var finalMessage = (typeof message == 'object') ? '<ul class="' + messageClass + '"><li>' + message.join('</li><li>') + '</li></ul>' : '<p class="' + messageClass + '">' + message + '</p>';
            if (settings.position == 'top') {
                var children = block.find('h1, .h1, .block-controls, .block-header, .wizard-steps');
                if (children.length > 0) {
                    var lastHeader = children.last();
                    var next = lastHeader.next('.message');
                    while (next.length > 0) {
                        lastHeader = next;
                        next = lastHeader.next('.message');
                    }
                    var messageElement = lastHeader.after(finalMessage).next();
                } else {
                    var messageElement = block.prepend(finalMessage).children(':first');
                }
            } else {
                var children = block.find('.block-footer:last-child');
                if (children.length > 0) {
                    var messageElement = children.before(finalMessage).prev();
                } else {
                    var messageElement = block.append(finalMessage).children(':last');
                }
            }
            if (settings.animate) {
                messageElement.expand();
            }
        });
        return this;
    };
    $.fn.blockMessage.defaults = {
        type: 'info',
        position: 'top',
        animate: true,
        noMargin: true
    };
    $.fn.removeBlockMessages = function(options) {
        var settings = $.extend({}, $.fn.removeBlockMessages.defaults, options);
        this.each(function(i) {
            var block = $(this);
            if (!block.hasClass('block-content')) {
                block = block.find('.block-content:first');
                if (block.length == 0) {
                    block = $(this).closest('.block-content');
                    if (block.length == 0) {
                        return;
                    }
                }
            }
            var messages = block.find('.message');
            if (settings.only) {
                if (typeof settings.only == 'string') {
                    settings.only = [settings.only];
                }
                messages = messages.filter('.' + settings.only.join(', .'));
            } else if (settings.except) {
                if (typeof settings.except == 'string') {
                    settings.except = [settings.except];
                }
                messages = messages.not('.' + settings.except.join(', .'));
            }
            if (settings.animate) {
                messages.foldAndRemove();
            } else {
                messages.remove();
            }
        });
        return this;
    };
    $.fn.removeBlockMessages.defaults = {
        only: false,
        except: false,
        animate: true
    };
    $.fn.fold = function(duration, callback) {
        this.each(function(i) {
            var element = $(this);
            var marginTop = parseInt(element.css('margin-top'));
            var marginBottom = parseInt(element.css('margin-bottom'));
            var anim = {
                'height': 0,
                'paddingTop': 0,
                'paddingBottom': 0
            };
            if (!$.browser.msie || $.browser.version > 8) {
                anim.borderTopWidth = '1px';
                anim.borderBottomWidth = '1px';
            }
            var prev = element.prev();
            if (prev.length === 0 && parseInt(element.css('margin-bottom')) + marginTop !== 0) {
                anim.marginTop = Math.min(0, marginTop);
                anim.marginBottom = Math.min(0, marginBottom);
            }
            element.stop(true).css({
                'overflow': 'hidden'
            }).animate(anim, {
                'duration': duration,
                'complete': function() {
                    $(this).css({
                        'display': 'none',
                        'overflow': '',
                        'height': '',
                        'paddingTop': '',
                        'paddingBottom': '',
                        'borderTopWidth': '',
                        'borderBottomWidth': '',
                        'marginTop': '',
                        'marginBottom': ''
                    });
                    if (callback) {
                        callback.apply(this);
                    }
                }
            });
        });
        return this;
    };
    $.fn.expand = function(duration, callback) {
        this.each(function(i) {
            var element = $(this);
            element.css('display', 'block');
            element.stop(true).css({
                'overflow': '',
                'height': '',
                'paddingTop': '',
                'paddingBottom': '',
                'borderTopWidth': '',
                'borderBottomWidth': '',
                'marginTop': '',
                'marginBottom': ''
            });
            var height = element.height();
            var paddingTop = parseInt(element.css('padding-top'));
            var paddingBottom = parseInt(element.css('padding-bottom'));
            var marginTop = parseInt(element.css('margin-top'));
            var marginBottom = parseInt(element.css('margin-bottom'));
            var css = {
                'overflow': 'hidden',
                'height': 0,
                'paddingTop': 0,
                'paddingBottom': 0
            };
            var anim = {
                'height': height,
                'paddingTop': paddingTop,
                'paddingBottom': paddingBottom
            };
            if (!$.browser.msie || $.browser.version > 8) {
                var borderTopWidth = parseInt(element.css('border-top-width'));
                var borderBottomWidth = parseInt(element.css('border-bottom-width'));
                css.borderTopWidth = '1px';
                css.borderBottomWidth = '1px';
                anim.borderTopWidth = borderTopWidth;
                anim.borderBottomWidth = borderBottomWidth;
            }
            var prev = element.prev();
            if (prev.length === 0 && parseInt(element.css('margin-bottom')) + marginTop !== 0) {
                css.marginTop = Math.min(0, marginTop);
                css.marginBottom = Math.min(0, marginBottom);
                anim.marginTop = marginTop;
                anim.marginBottom = marginBottom;
            }
            element.stop(true).css(css).animate(anim, {
                'duration': duration,
                'complete': function() {
                    $(this).css({
                        'display': '',
                        'overflow': '',
                        'height': '',
                        'paddingTop': '',
                        'paddingBottom': '',
                        'borderTopWidth': '',
                        'borderBottomWidth': '',
                        'marginTop': '',
                        'marginBottom': ''
                    });
                    if (callback) {
                        callback.apply(this);
                    }
                    if ($.browser.msie && $.browser.version < 8) {
                        $(this).css('zoom', 1);
                    }
                }
            });
        });
        return this;
    };
    $.fn.foldAndRemove = function(duration, callback) {
        $(this).fold(duration, function() {
            if (callback) {
                callback.apply(this);
            }
            $(this).remove();
        });
        return this;
    }
    $.fn.fadeAndRemove = function(duration, callback) {
        this.animate({
            'opacity': 0
        }, {
            'duration': duration,
            'complete': function() {
                if ($(this).css('position') == 'absolute') {
                    if (callback) {
                        callback.apply(this);
                    }
                    $(this).remove();
                } else {
                    $(this).slideUp(duration, function() {
                        if (callback) {
                            callback.apply(this);
                        }
                        $(this).remove();
                    });
                }
            }
        });
        return this;
    };
    $.fn.toggleFieldsetOpen = function() {
        this.each(function() {
            $(this).closest('fieldset, .fieldset').toggleClass('collapsed');
        });
        return this;
    };
    $.fn.addEffectLayer = function(options) {
        var settings = $.extend({}, $.fn.addEffectLayer.defaults, options);
        this.each(function(i) {
            var element = $(this);
            var refElement = getNodeRefElement(this);
            var layer = $('<div class="loading-mask"><span>' + settings.message + '</span></div>').insertAfter(refElement);
            var elementOffset = element.position();
            layer.css({
                top: elementOffset.top + 'px',
                left: elementOffset.left + 'px'
            }).width(element.outerWidth()).height(element.outerHeight());
            var span = layer.children('span');
            var marginTop = parseInt(span.css('margin-top'));
            span.css({
                'opacity': 0,
                'marginTop': (marginTop - 40) + 'px'
            });
            layer.css({
                'opacity': 0
            }).animate({
                'opacity': 1
            }, {
                'complete': function() {
                    span.animate({
                        'opacity': 1,
                        'marginTop': marginTop + 'px'
                    });
                }
            });
        });
        return this;
    };

    function getNodeRefElement(node) {
        var element = $(node);
        if (node.nodeName.toLowerCase() == 'document' || node.nodeName.toLowerCase() == 'body') {
            var refElement = $(document.body).children(':last').get(0);
        } else {
            var refElement = node;
            var offsetParent = element.offsetParent().get(0);
            var absPos = ['absolute', 'relative'];
            while (refElement && refElement !== offsetParent && !$.inArray($(refElement.parentNode).css('position'), absPos)) {
                refElement = refElement.parentNode;
            }
        }
        return refElement;
    }
    $.fn.addEffectLayer.defaults = {
        message: 'Please wait...'
    };
    $.fn.loadWithEffect = function() {
        this.addEffectLayer({
            message: $.fn.loadWithEffect.defaults.message
        });
        var target = this;
        var callback = false;
        var args = $.makeArray(arguments);
        var index = args.length;
        if (args[2] && typeof args[2] == 'function') {
            callback = args[2];
            index = 2;
        } else if (args[1] && typeof args[1] == 'function') {
            callback = args[1];
            index = 1;
        }
        args[index] = function(responseText, textStatus, XMLHttpRequest) {
            var refElement = getNodeRefElement(this);
            var layer = $(refElement).next('.loading-mask');
            var span = layer.children('span');
            if (textStatus == 'success' || textStatus == 'notmodified') {
                if (callback) {
                    callback.apply(this, arguments);
                }
                layer.stop(true);
                span.stop(true);
                var currentMarginTop = parseInt(span.css('margin-top'));
                var marginTop = parseInt(span.css('margin-top', '').css('margin-top'));
                span.css({
                    'marginTop': currentMarginTop + 'px'
                }).animate({
                    'opacity': 0,
                    'marginTop': (marginTop - 40) + 'px'
                }, {
                    'complete': function() {
                        layer.fadeAndRemove();
                    }
                });
            } else {
                span.addClass('error').html($.fn.loadWithEffect.defaults.errorMessage + '<br><a href="#">' + $.fn.loadWithEffect.defaults.retry + '</a> / <a href="#">' + $.fn.loadWithEffect.defaults.cancel + '</a>');
                span.children('a:first').click(function(event) {
                    event.preventDefault();
                    $.fn.load.apply(target, args);
                    span.removeClass('error').html($.fn.loadWithEffect.defaults.message).css('margin-left', '');
                });
                span.children('a:last').click(function(event) {
                    event.preventDefault();
                    layer.stop(true);
                    span.stop(true);
                    var currentMarginTop = parseInt(span.css('margin-top'));
                    var marginTop = parseInt(span.css('margin-top', '').css('margin-top'));
                    span.css({
                        'marginTop': currentMarginTop + 'px'
                    }).animate({
                        'opacity': 0,
                        'marginTop': (marginTop - 40) + 'px'
                    }, {
                        'complete': function() {
                            layer.fadeAndRemove();
                        }
                    });
                });
                span.css('margin-left', -Math.round(span.outerWidth() / 2));
            }
        };
        $.fn.load.apply(target, args);
        return this;
    };
    $.fn.loadWithEffect.defaults = {
        message: 'Loading...',
        errorMessage: 'Error while loading',
        retry: 'Retry',
        cancel: 'Cancel'
    };
    $.fn.enableBt = function() {
        $(this).attr('disabled', false);
        if ($.browser.msie && $.browser.version < 9) {
            $(this).removeClass('disabled');
        }
    }
    $.fn.disableBt = function() {
        $(this).attr('disabled', true);
        if ($.browser.msie && $.browser.version < 9) {
            $(this).addClass('disabled');
        }
    }
})(jQuery);
(function($) {
    $.modal = function(options) {
		console.log(options);
        var settings = $.extend({}, $.modal.defaults, options),
            root = getModalDiv(),
            winX = 0,
            winY = 0,
            contentWidth = 0,
            contentHeight = 0,
            mouseX = 0,
            mouseY = 0,
            resized, content = '',
            contentObj;
			
		console.log(settings);
        var titleClass = settings.title ? '' : ' no-title';
        var title = settings.title ? '<h1>' + settings.title + '</h1>' : '';
        var borderOpen = settings.border ? '"><div class="block-content' + titleClass : titleClass;
        var borderClose = settings.border ? '></div' : '';
        if (settings.useIframe) {
            var sizeParts = new Array();
            if (settings.width) {
                sizeParts.push('width="' + settings.width + '"');
            } else if (settings.maxWidth) {
                sizeParts.push('width="' + settings.maxWidth + '"');
            } else {
                sizeParts.push('width="' + settings.minWidth + '"');
            }
            if (settings.height) {
                sizeParts.push('height="' + settings.height + '"');
            } else if (settings.maxHeight) {
                sizeParts.push('height="' + settings.maxHeight + '"');
            } else {
                sizeParts.push('height="' + settings.minHeight + '"');
            }
            var contentWrapper = '<div class="modal-iframe-wrapper"><iframe src="' + settings.url + '" class="modal-content" frameborder="0" ' + sizeParts.join(' ') + '></iframe></div>';
        } else {
            if (settings.content) {
                if (typeof(settings.content) == 'string') {
                    content = settings.content;
                } else {
                    contentObj = settings.content.clone(true).show();
                }
            } else {
                content = '';
            }
            var sizeParts = new Array();
            sizeParts.push('min-width:' + settings.minWidth + 'px;');
            sizeParts.push('min-height:' + settings.minHeight + 'px;');
            if (settings.width) {
                sizeParts.push('width:' + settings.width + 'px; ');
            }
            if (settings.height) {
                sizeParts.push('height:' + settings.height + 'px; ');
            }
            if (settings.maxWidth) {
                sizeParts.push('max-width:' + settings.maxWidth + 'px; ');
            }
            if (settings.maxHeight) {
                sizeParts.push('max-height:' + settings.maxHeight + 'px; ');
            }
            var contentStyle = (sizeParts.length > 0) ? ' style="' + sizeParts.join(' ') + '"' : '';
            var scrollClass = settings.scrolling ? ' modal-scroll' : '';
            var contentWrapper = '<div class="modal-content' + scrollClass + '"' + contentStyle + '>' + content + '</div>';
        }
        var win = $('<div class="modal-window block-border' + borderOpen + '">' + title + contentWrapper + '</div' + borderClose + '>').appendTo(root);
        var contentBlock = win.find('.modal-content');
        var contentBlockWrapper = settings.useIframe ? contentBlock.parent() : contentBlock;
        if (contentObj) {
            contentObj.appendTo(contentBlockWrapper);
        }
        if (settings.resizable && settings.border) {
            var resizeFunc = function(event) {
                var offsetX = event.pageX - mouseX,
                    offsetY = event.pageY - mouseY,
                    newWidth = Math.max(settings.minWidth, contentWidth + (resized.width * offsetX)),
                    newHeight = Math.max(settings.minHeight, contentHeight + (resized.height * offsetY)),
                    correctX = 0,
                    correctY = 0;
                if (settings.maxWidth && newWidth > settings.maxWidth) {
                    correctX = newWidth - settings.maxWidth;
                    newWidth = settings.maxWidth;
                }
                if (settings.maxHeight && newHeight > settings.maxHeight) {
                    correctY = newHeight - settings.maxHeight;
                    newHeight = settings.maxHeight;
                }
                if (settings.useIframe) {
                    contentBlock.attr('width', newWidth).attr('height', newHeight);
                } else {
                    contentBlock.css({
                        width: newWidth + 'px',
                        height: newHeight + 'px'
                    });
                }
                win.css({
                    left: (winX + (resized.left * (offsetX + correctX))) + 'px',
                    top: (winY + (resized.top * (offsetY + correctY))) + 'px'
                });
            };
            $('<div class="modal-resize-tl"></div>').appendTo(win).data('modal-resize', {
                top: 1,
                left: 1,
                height: -1,
                width: -1
            }).add($('<div class="modal-resize-t"></div>').appendTo(win).data('modal-resize', {
                top: 1,
                left: 0,
                height: -1,
                width: 0
            })).add($('<div class="modal-resize-tr"></div>').appendTo(win).data('modal-resize', {
                top: 1,
                left: 0,
                height: -1,
                width: 1
            })).add($('<div class="modal-resize-r"></div>').appendTo(win).data('modal-resize', {
                top: 0,
                left: 0,
                height: 0,
                width: 1
            })).add($('<div class="modal-resize-br"></div>').appendTo(win).data('modal-resize', {
                top: 0,
                left: 0,
                height: 1,
                width: 1
            })).add($('<div class="modal-resize-b"></div>').appendTo(win).data('modal-resize', {
                top: 0,
                left: 0,
                height: 1,
                width: 0
            })).add($('<div class="modal-resize-bl"></div>').appendTo(win).data('modal-resize', {
                top: 0,
                left: 1,
                height: 1,
                width: -1
            })).add($('<div class="modal-resize-l"></div>').appendTo(win).data('modal-resize', {
                top: 0,
                left: 1,
                height: 0,
                width: -1
            })).mousedown(function(event) {
                contentWidth = contentBlock.width();
                contentHeight = contentBlock.height();
                var position = win.position();
                winX = position.left;
                winY = position.top;
                mouseX = event.pageX;
                mouseY = event.pageY;
                resized = $(this).data('modal-resize');
                event.preventDefault();
                $(document).bind('mousemove', resizeFunc);
            })
            root.mouseup(function() {
                $(document).unbind('mousemove', resizeFunc);
            });
        }
        win.mousedown(function() {
            $(this).putModalOnFront();
        });
        if (settings.draggable && title) {
            var moveFunc = function(event) {
                var width = win.outerWidth(),
                    height = win.outerHeight();
                win.css({
                    left: Math.max(0, Math.min(winX + (event.pageX - mouseX), $(root).width() - width)) + 'px',
                    top: Math.max(0, Math.min(winY + (event.pageY - mouseY), $(root).height() - height)) + 'px'
                });
            };
            win.find('h1:first').mousedown(function(event) {
                var position = win.position();
                winX = position.left;
                winY = position.top;
                mouseX = event.pageX;
                mouseY = event.pageY;
                event.preventDefault();
                $(document).bind('mousemove', moveFunc);
            });
            root.mouseup(function() {
                $(document).unbind('mousemove', moveFunc);
            });
        }
        if (settings.closeButton) {
			var first = $(location).attr('pathname');
			first.indexOf(1);
			first.toLowerCase();
			first = window.location.protocol + "//" + window.location.host + "/" + first.split("/")[1];


            $('<ul class="action-tabs right"><li><a href="#" title="Close window"><img src="'+first+'/constellation/assets/images/icons/fugue/cross-circle.png" width="16" height="16"></a></li></ul>').prependTo(win).find('a').click(function(event) {
                event.preventDefault();
                $(this).closest('.modal-window').closeModal();
            });
        }
        var buttonsFooter = false;
        $.each(settings.buttons, function(key, value) {
            if (!buttonsFooter) {
                buttonsFooter = $('<div class="block-footer align-' + settings.buttonsAlign + '"></div>').insertAfter(contentBlockWrapper);
            } else {
                buttonsFooter.append('&nbsp;');
            }
            $('<button type="button">' + key + '</button>').appendTo(buttonsFooter).click(function(event) {
                value.call(this, $(this).closest('.modal-window'), event);
            });
        });
        if (settings.onClose) {
            win.bind('closeModal', settings.onClose);
        }
        win.applyTemplateSetup();
        if (!root.is(':visible')) {
            win.hide();
            root.fadeIn('normal', function() {
                win.show().centerModal();
            });
        } else {
            win.centerModal();
        }
        $.modal.current = win;
        $.modal.all = root.children('.modal-window');
        if (settings.onOpen) {
            settings.onOpen.call(win.get(0));
        }
        if (settings.url) {
            win.loadModalContent(settings.url, settings);
        }
        return win;
    };
    $.modal.current = false;
    $.modal.all = $();
    $.fn.modal = function(options) {
        var modals = $();
        this.each(function() {
            modals.add($.modal($.extend(options, {
                content: $(this).clone(true).show()
            })));
        });
        return modals;
    };
    $.fn.getModalContentBlock = function() {
        return this.find('.modal-content');
    }
    $.fn.getModalWindow = function() {
        return this.closest('.modal-window');
    }
    $.fn.setModalContent = function(content, resize) {
        this.each(function() {
            var contentBlock = $(this).getModalContentBlock().not('iframe');
            if (contentBlock.length > 0) {
                if (typeof(content) == 'string') {
                    contentBlock.html(content);
                } else {
                    content.clone(true).show().appendTo(contentBlock);
                }
                contentBlock.applyTemplateSetup();
                if (resize) {
                    contentBlock.setModalContentSize(true, false);
                }
            }
        });
        return this;
    }
    $.fn.setModalContentSize = function(width, height) {
        this.each(function() {
            var contentBlock = $(this).getModalContentBlock(),
                useIframe = contentBlock.is('iframe');
            if (width !== true) {
                if (useIframe) {
                    if (width) {
                        contentBlock.attr('width', width);
                    }
                } else {
                    contentBlock.css('width', width ? width + 'px' : '');
                }
            }
            if (height !== true) {
                if (useIframe) {
                    if (height) {
                        contentBlock.attr('height', height);
                    }
                } else {
                    contentBlock.css('height', height ? height + 'px' : '');
                }
            }
        });
        return this;
    }
    $.fn.loadModalContent = function(url, options) {
        var settings = $.extend({
            loadingMessage: '',
            data: {},
            complete: function(responseText, textStatus, XMLHttpRequest) {},
            resize: true,
            resizeOnMessage: false,
            resizeOnLoad: false
        }, options)
        this.each(function() {
            var win = $(this),
                contentBlock = win.getModalContentBlock(),
                useIframe = contentBlock.is('iframe');
            if (useIframe) {
                contentBlock.attr('src', url);
            } else {
                if (settings.loadingMessage) {
                    win.setModalContent('<div class="modal-loading">' + settings.loadingMessage + '</div>', (settings.resize || settings.resizeOnMessage));
                }
                contentBlock.load(url, settings.data, function(responseText, textStatus, XMLHttpRequest) {
                    var hidden = false;
                    if (win.is(':hidden')) {
                        win.show();
                        hidden = true;
                    }
                    contentBlock.applyTemplateSetup();
                    if (settings.resize || settings.resizeOnLoad) {
                        contentBlock.setModalContentSize(true, false);
                    }
                    settings.complete.call(this, responseText, textStatus, XMLHttpRequest);
                    if (hidden) {
                        win.hide();
                    }
                });
            }
        });
        return this;
    }
    $.fn.setModalTitle = function(newTitle) {
        this.each(function() {
            var win = $(this),
                title = $(this).find('h1'),
                contentBlock = win.hasClass('block-content') ? win : win.children('.block-content:first');
            if (newTitle.length > 0) {
                if (title.length == 0) {
                    contentBlock.removeClass('no-title');
                    title = $('<h1>' + newTitle + '</h1>').prependTo(contentBlock);
                }
                title.html(newTitle);
            } else if (title.length > 0) {
                title.remove();
                contentBlock.addClass('no-title');
            }
        });
        return this;
    }
    $.fn.addButtons = function(buttons, clear) {
        var win = $.modal.current,
            buttonsFooter = win.find('.block-footer');
        contentDiv = win.find('.modal-content');
        if (clear) {
            buttonsFooter.children().remove();
        }
        $.each(buttons, function(key, value) {
            buttonsFooter.append('&nbsp;');
            $('<button type="button">' + key + '</button>').appendTo(buttonsFooter).click(function(event) {
                value.call(this, $(this).getModalWindow(), event);
            });
        });
    };
    $.fn.centerModal = function(animate) {
        var root = getModalDiv(),
            rootW = root.width() / 2,
            rootH = root.height() / 2;
        this.each(function() {
            var win = $(this),
                winW = Math.round(win.outerWidth() / 2),
                winH = Math.round(win.outerHeight() / 2);
            win[animate ? 'animate' : 'css']({
                left: (rootW - winW) + 'px',
                top: (rootH - winH) + 'px'
            });
        });
        return this;
    };
    $.fn.putModalOnFront = function() {
        if ($.modal.all.length > 1) {
            var root = getModalDiv();
            this.each(function() {
                if ($(this).next('.modal-window').length > 0) {
                    $(this).detach().appendTo(root);
                }
            });
        }
        return this;
    };
    $.fn.closeModal = function() {
        this.each(function() {
            var event = $.Event('closeModal'),
                win = $(this);
            win.trigger(event);
            if (!event.isDefaultPrevented()) {
                win.remove();
                var root = getModalDiv();
                $.modal.all = root.children('.modal-window');
                if ($.modal.all.length == 0) {
                    $.modal.current = false;
                    root.fadeOut('normal');
                } else {
                    $.modal.current = $.modal.all.last();
                }
            }
        });
        return this;
    };
    $.modal.defaults = {
        content: false,
        useIframe: false,
        url: false,
        title: false,
        border: true,
        draggable: true,
        resizable: true,
        scrolling: true,
        closeButton: true,
        buttons: {},
        buttonsAlign: 'right',
        onOpen: false,
        onClose: false,
        minHeight: 40,
        minWidth: 200,
        maxHeight: false,
        maxWidth: false,
        height: false,
        width: 450,
        loadingMessage: 'Loading...',
        data: {},
        complete: function(responseText, textStatus, XMLHttpRequest) {},
        resize: true,
        resizeOnMessage: false,
        resizeOnLoad: false
    };

    function getModalDiv() {
        var modal = $('#modal');
        if (modal.length == 0) {
            var target = $(document.body),
                ieDiv = target.children('.ie, ie7');
            if (ieDiv.length > 0) {
                ieDiv.eq(0).append('<div id="modal"></div>');
            } else {
                target.append('<div id="modal"></div>');
            }
            modal = $('#modal').hide();
        }
        return modal;
    };
})(jQuery);
