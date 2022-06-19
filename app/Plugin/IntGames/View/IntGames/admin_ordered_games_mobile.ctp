<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Casino'); ?></li>
                    <li class="breadcrumb-item"><?= __('Order Games'); ?></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Mobile'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Order Games Mobile'); ?></h1>
            </div>

            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12">
                <?= $this->element('tabs'); ?>
            </div>
            <div class="col-md-12 pt-2">
                <?= __('Please drag and drop the items to change the order. You can select more than one item by using CTRL or Shift key'); ?>
            </div>
            <?php if (!empty($data)) { ?>

                <div class="col-md-12 pt-2">
                    <div class="table-responsive">
                        <?php
                        $model = array_keys($data[0]);
                        $model = $model[0];
                        $titles = $data[0][$model];
                        ?>
                        <ul class="sorta">
                            <?php
                            $i = count($data);
                            foreach ($data as $game):
                                $class = null;
                                if ($i-- % 2 == 0)
                                    $class = ' alt';
                                $k = 0;
                                ?>

                                <li id="id:<?= $game['IntGames']['id']; ?>_order:<?php echo $i + 1; ?>">
                                    <?= $game['IntGames']['id']; ?>. <b><?= $game['IntGames']['name']; ?></b> - <?= $game['IntCategory']['name']; ?>, <?= $game['IntBrand']['name']; ?> <!--(<?= $game['IntGames']['order']; ?>)-->
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?= $this->element('paginator'); ?>
                </div>

            <?php }else { ?>
                <p><?= __('No records found.'); ?></p>
            <?php } ?>
        </div>


    </div>
</div>


<style type="text/css">
    ul.sorta{
        padding-left:0;
    }
    ul.sorta li {
        list-style: none;
        margin: 0 0 4px 0;
        padding: 10px;
        background:#aaa;
        border: #CCCCCC solid 1px;
        color:#000;
        cursor: pointer;
        padding-left:0;
    }

    ul.sorta li.selected {
        background:#CCC;
    }
</style>

<script tyle="text/javascript">
    /**
     * jquery.multisortable.js - v0.2
     * https://github.com/shvetsgroup/jquery.multisortable
     *
     * Author: Ethan Atlakson, Jay Hayes, Gabriel Such, Alexander Shvets
     * Last Revision 3/16/2012
     * multi-selectable, multi-sortable jQuery plugin
     */

    !function ($) {

        $.fn.multiselectable = function (options) {
            if (!options) {
                options = {}
            }
            options = $.extend({}, $.fn.multiselectable.defaults, options);

            function mouseDown(e) {
                var item = $(this),
                        parent = item.parent(),
                        myIndex = item.index();

                var prev = parent.find('.multiselectable-previous');
                // If no previous selection found, start selecting from first selected item.
                prev = prev.length ? prev : $(parent.find('.' + options.selectedClass)[0]).addClass('multiselectable-previous');
                var prevIndex = prev.index();

                if (e.ctrlKey || e.metaKey) {
                    if (item.hasClass(options.selectedClass)) {
                        item.removeClass(options.selectedClass).removeClass('multiselectable-previous')
                        if (item.not('.child').length) {
                            item.nextUntil(':not(.child)').removeClass(options.selectedClass);
                        }
                    } else {
                        parent.find('.multiselectable-previous').removeClass('multiselectable-previous');
                        item.addClass(options.selectedClass).addClass('multiselectable-previous')
                        if (item.not('.child').length) {
                            item.nextUntil(':not(.child)').addClass(options.selectedClass);
                        }
                    }
                }

                if (e.shiftKey) {
                    var last_shift_range = parent.find('.multiselectable-shift');
                    last_shift_range.removeClass(options.selectedClass).removeClass('multiselectable-shift');

                    var shift_range;
                    if (prevIndex < myIndex) {
                        shift_range = item.prevUntil('.multiselectable-previous').add(prev).add(item);
                    } else if (prevIndex > myIndex) {
                        shift_range = item.nextUntil('.multiselectable-previous').add(prev).add(item);
                    }
                    shift_range.addClass(options.selectedClass).addClass('multiselectable-shift');
                } else {
                    parent.find('.multiselectable-shift').removeClass('multiselectable-shift');
                }

                if (!e.ctrlKey && !e.metaKey && !e.shiftKey) {
                    parent.find('.multiselectable-previous').removeClass('multiselectable-previous');
                    if (!item.hasClass(options.selectedClass)) {
                        parent.find('.' + options.selectedClass).removeClass(options.selectedClass);
                        item.addClass(options.selectedClass).addClass('multiselectable-previous');
                        if (item.not('.child').length) {
                            item.nextUntil(':not(.child)').addClass(options.selectedClass);
                        }
                    }
                }

                options.mousedown(e, item);
            }

            function click(e) {
                if ($(this).is('.ui-draggable-dragging')) {
                    return;
                }

                var item = $(this), parent = item.parent();

                // If item wasn't draged and is not multiselected, it should reset selection for other items.
                if (!e.ctrlKey && !e.metaKey && !e.shiftKey) {
                    parent.find('.multiselectable-previous').removeClass('multiselectable-previous');
                    parent.find('.' + options.selectedClass).removeClass(options.selectedClass);
                    item.addClass(options.selectedClass).addClass('multiselectable-previous');
                    if (item.not('.child').length) {
                        item.nextUntil(':not(.child)').addClass(options.selectedClass);
                    }
                }

                options.click(e, item);
            }

            return this.each(function () {
                var list = $(this);

                if (!list.data('multiselectable')) {
                    list.data('multiselectable', true)
                            .delegate(options.items, 'mousedown', mouseDown)
                            .delegate(options.items, 'click', click)
                            .disableSelection();
                }
            })
        };

        $.fn.multiselectable.defaults = {
            click: function (event, elem) {},
            mousedown: function (event, elem) {},
            selectedClass: 'selected',
            items: 'li'
        };


        $.fn.multisortable = function (options) {
            if (!options) {
                options = {}
            }
            var settings = $.extend({}, $.fn.multisortable.defaults, options);

            function regroup(item, list) {
                if (list.find('.' + settings.selectedClass).length > 0) {
                    var myIndex = item.data('i');

                    var itemsBefore = list.find('.' + settings.selectedClass).filter(function () {
                        return $(this).data('i') < myIndex
                    }).css({
                        position: '',
                        width: '',
                        left: '',
                        top: '',
                        zIndex: ''
                    });

                    item.before(itemsBefore);

                    var itemsAfter = list.find('.' + settings.selectedClass).filter(function () {
                        return $(this).data('i') > myIndex
                    }).css({
                        position: '',
                        width: '',
                        left: '',
                        top: '',
                        zIndex: ''
                    });

                    item.after(itemsAfter);

                    setTimeout(function () {
                        itemsAfter.add(itemsBefore).addClass(settings.selectedClass);
                    }, 0);
                }
            }

            return this.each(function () {
                var list = $(this);

                //enable multi-selection
                list.multiselectable({
                    selectedClass: settings.selectedClass,
                    click: settings.click,
                    items: settings.items,
                    mousedown: settings.mousedown
                });

                //enable sorting
                options.cancel = settings.items + ':not(.' + settings.selectedClass + ')';
                options.placeholder = settings.placeholder;
                options.start = function (event, ui) {
                    if (ui.item.hasClass(settings.selectedClass)) {
                        var parent = ui.item.parent();

                        //assign indexes to all selected items
                        parent.find('.' + settings.selectedClass).each(function (i) {
                            $(this).data('i', i);
                        });

                        // adjust placeholder size to be size of items
                        var height = parent.find('.' + settings.selectedClass).length * ui.item.outerHeight();
                        ui.placeholder.height(height);
                    }

                    settings.start(event, ui);
                };

                options.stop = function (event, ui) {
                    regroup(ui.item, ui.item.parent());
                    settings.stop(event, ui);
                };

                options.sort = function (event, ui) {
                    var parent = ui.item.parent(),
                            myIndex = ui.item.data('i'),
                            top = parseInt(ui.item.css('top').replace('px', '')),
                            left = parseInt(ui.item.css('left').replace('px', ''));

                    // fix to keep compatibility using prototype.js and jquery together
                    $.fn.reverse = Array.prototype._reverse || Array.prototype.reverse

                    var height = 0;
                    $('.' + settings.selectedClass, parent).filter(function () {
                        return $(this).data('i') < myIndex;
                    }).reverse().each(function () {
                        height += $(this).outerHeight();
                        $(this).css({
                            left: left,
                            top: top - height,
                            position: 'absolute',
                            zIndex: 1000,
                            width: ui.item.width()
                        })
                    });

                    height = ui.item.outerHeight();
                    $('.' + settings.selectedClass, parent).filter(function () {
                        return $(this).data('i') > myIndex;
                    }).each(function () {
                        var item = $(this);
                        item.css({
                            left: left,
                            top: top + height,
                            position: 'absolute',
                            zIndex: 1000,
                            width: ui.item.width()
                        });

                        height += item.outerHeight();
                    });

                    settings.sort(event, ui);
                };

                options.receive = function (event, ui) {
                    regroup(ui.item, ui.sender);
                    settings.receive(event, ui);
                };

                list.sortable(options).disableSelection();
            })
        };

        $.fn.multisortable.defaults = {
            start: function (event, ui) {},
            stop: function (event, ui) {},
            sort: function (event, ui) {},
            receive: function (event, ui) {},
            click: function (event, elem) {},
            mousedown: function (event, elem) {},
            selectedClass: 'selected',
            placeholder: 'placeholder',
            items: 'li'
        };

    }(jQuery);
</script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("ul.sorta").multisortable();
        jQuery("ul.sorta").sortable({
            axis: 'y', opacity: 0.6, cursor: 'move', update: function () {
                var order = $(this).sortable("serialize");
                console.log(order);
                $.post("/admin/int_games/int_games/reorder", order, function (theResponse) {
                    console.log(theResponse);
                    jQuery('ul.sorta li').flash('255,0,0', 1000);
                });
            }
        });

        jQuery.fn.flash = function (color, duration) {
            var current = this.css('color');
            this.animate({color: 'rgb(' + color + ')'}, duration / 2);
            this.animate({color: current}, duration / 2);
        }
    });
</script>            