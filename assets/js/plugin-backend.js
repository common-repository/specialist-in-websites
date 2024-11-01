jQuery(document).ready(function ($) {
    $('#wpbody').addClass("loaded");

    $('.login #login form').submit(function (e) {
        var form = this;

        e.preventDefault();
        $('.login').addClass("logging");
        setTimeout(function () {
            form.submit();
            // }, 22000);
        }, 300);
    });

    $(document).on('click', function (e) {
        infobox = $('.analytic-options .fa-question-circle');
        if (!infobox.is(e.target)
            && infobox.has(e.target).length === 0) {
            $('.image-container').removeClass("show");
        }
    });

    $('.analytic-options .fa-question-circle').on("click", function () {
        $('.image-container').toggleClass("show");
    });

    if ($('.upload_img_btn').length > 0) {
        $('.upload_img_btn').click(function (e) {
            let that = $(this);
            e.preventDefault();

            var custom_uploader = wp.media({
                title: 'Custom Image',
                button: {
                    text: 'Upload Image'
                },
                multiple: false  // Set this to true to allow multiple files to be selected
            })
                .on('select', function () {
                    let attachment = custom_uploader.state().get('selection').first().toJSON();
                    that.parent().find('.header_logo').attr('src', attachment.url);
                    that.parent().find('.image_url').val(attachment.url);
                })
                .open();
        });
    }

    if ($('.siw_color_picker').length > 0) {
        $('.siw_color_picker').wpColorPicker();
    }

    if ($("#toggl-projects").length > 0) {
        $("#toggl-projects").on("change", function () {
            document.cookie = "toggl_project_id=" + $(this).find('option:selected').attr("name");
            location.reload();
        })
    }

    if ($(".notice-siwto").length > 0) {
        $(".notice-siwto button").on("click", function () {
            var ajaxurl = window.location.protocol + "//" + window.location.host + '/wp-admin/admin-ajax.php';
            $.ajax({
                url: ajaxurl + "?action=siw_remove_welcomemsg",
                type: 'GET', // of post?
                data: {"toggle_welcomemsg": "true"},
                async: true,
                dataType: 'json',
                success: function (data) {

                },
                error: function (data) {
                    console.log("Something went wrong");
                    console.log(' status : ' + data.status + ' statusText : ' + data.statusText + ' readyState : ' + data.readyState + ' responseText : ' + data.responseText);
                },
                timeout: 0
            });
        })
    }

    let txtarea = $(".siw_syntax-editor");
    let codeEditor = '';
    if (txtarea.length > 0) {
        txtarea.each(function () {
            let settings = '';
            if ($(this).hasClass('siw_syntax-editor_css')) {
                settings = cm_settings.css;
            } else { // mixed
                settings = cm_settings.mixed;
            }
            codeEditor = wp.codeEditor.initialize($(this), settings);
            codeEditor.codemirror.on('change', function (editor) {
                console.log(editor);
                console.log(editor.save());
            })
        })
    }

    function hide_field($that, $checked) {
        if ($checked) {
            let $class = $that.data('if-on-remove');
            if ($that.is(":checked")) {
                $('.' + $class + '_container').hide();
            } else {
                $('.' + $class + '_container').show();
            }
        } else {
            let $class = $that.data('if-off-remove');
            if ($that.is(":checked")) {
                $('.' + $class + '_container').show();
            } else {
                $('.' + $class + '_container').hide();
            }
        }

    }

    if ($('[data-if-on-remove]').length > 0) {
        $('[data-if-on-remove]').on('change', function () {
            hide_field($(this), true);
        });
        $('[data-if-on-remove]').each(function () {
            hide_field($(this), true);
        });
    }
    if ($('[data-if-off-remove]').length > 0) {
        $('[data-if-off-remove]').on('change', function () {
            hide_field($(this), false);
        });
        $('[data-if-off-remove]').each(function () {
            hide_field($(this), false);
        });
    }

    let ajax_form = $('form.siw_wp_ajax_save');
    let ajax_form_btn = ajax_form.find("input[type=submit].button-primary");
    if (ajax_form.length > 0) {
        let oldtxt = ajax_form_btn.val();
        ajax_form_btn.on('click', function (e) {
            e.preventDefault();
            let $that = $(this);
            $that.val(oldtxt);
            $that.parent().addClass('siw-ajax-busy');
            $that.parent().removeClass('siw-ajax-saved');
            ajax_form.ajaxSubmit({
                success: function () {
                    $that.val('Opgeslagen!');
                    $that.parent().addClass('siw-ajax-saved').removeClass('siw-ajax-busy');
                    setTimeout(function () {
                        $that.val(oldtxt);
                        $that.parent().removeClass('siw-ajax-saved');
                    }, 3000);
                },
                timeout: 5000
            });
        })
    }
    // $('#main-toggles tbody>tr, #siw_live_toggl').on("click", function (e) {
    //     if ($(e.target).is("a")) return;
    //     var $checkbox = $(this).find('input:checkbox');
    //     var $tr = $(this);
    //     if ($checkbox.prop('checked')) {
    //         // $checkbox.prop('checked', false);
    //         $tr.removeClass("tr-checked");
    //     } else {
    //         // $checkbox.attr("checked", "checked");
    //         $tr.addClass("tr-checked");
    //     }
    //     $checkbox.addClass("checked");
    //     if($(this).is('#siw_live_toggl')){
    //         $("#liveToggle").ajaxSubmit({
    //             success: function () {
    //                 console.log("Succes");
    //                 location.reload();
    //             },
    //             timeout: 5000
    //         });
    //     } else {
    //         //calcProgressBar();
    //         $('#main-toggles').ajaxSubmit({
    //             success: function () {
    //                 console.log("Succes");
    //                 $checkbox.parent().parent().addClass("updated");
    //                 $checkbox.removeClass("checked");
    //                 setTimeout(function () {
    //                     $checkbox.parent().parent().removeClass("updated");
    //                 }, 2500);
    //             },
    //             timeout: 5000
    //         });
    //     }
    //     // return false;
    // });

    // checklist code

    if ($('#siwChecklist').length > 0) {
        // function calcProgressBar(){
        //     var progressbar = $('.siw_checklist_progress_bar'),
        //         progressbarAuto = $('.siw_checklist_progress_bar_auto'),
        //         questions = parseFloat($('#siwChecklist .form-table input').length),
        //         checked = parseFloat($('#siwChecklist .form-table input:checked').length),
        //         checkedAuto = parseFloat($('#siwChecklist .form-table .siw_sub_checkbox_autod:checked').length),
        //         progress = (checked / questions) * 100,
        //         progressAuto = (checkedAuto / questions) * 100;
        //     progressbar.css("width", progress + '%');
        //     progressbarAuto.css("width", progressAuto + '%');
        //     if(progress < 19) {
        //         progressbar.find(".siw_checklist_progress_percentage").text("");
        //     } else if(progress > 85){
        //         progressbar.find(".siw_checklist_progress_percentage").text(progress.toFixed(2) + '%');
        //     } else {
        //         progressbar.find(".siw_checklist_progress_percentage").text(progress.toFixed(2) + '%');
        //     }
        //     if(progress >= 100){
        //         // console.log(" oeh! ")
        //         $("<div class='checklist-firework'>" +
        //             "<div class=\"pyro\">\n" +
        //             "  <div class=\"before\"></div>\n" +
        //             "  <div class=\"after\"></div>\n" +
        //             "</div></div>").appendTo("body");
        //         setTimeout( function () {
        //             $('.checklist-firework').fadeOut();
        //         }, 5000);
        //     }
        //     progressbar.parent().find('.siw_checklist_progress_min').text(checked);
        //     progressbar.parent().find('.siw_checklist_progress_max').text(questions);
        //     // console.log(questions);
        //     // console.log(checked);
        //     // console.log(progress);
        // }
        //calcProgressBar();
        var navbar = document.getElementById("siw_checklist_progress");
        var $navbar = $("#siw_checklist_progress");
        if (navbar) {
            var sticky = navbar.offsetTop;

            function stickynav() {
                if (window.pageYOffset >= sticky) {
                    $height = $navbar.outerHeight();
                    if (!$('.siw_checklist_progress').hasClass("cloned")) {
                        $navbar.clone().addClass("cloned").css({"height": $height}).insertAfter(navbar);
                    }
                    navbar.classList.add("sticky")
                } else {
                    navbar.classList.remove("sticky");
                    if ($('.siw_checklist_progress').hasClass("cloned")) {
                        $('.siw_checklist_progress.cloned').remove();
                    }
                }
            }

            window.onscroll = function () {
                stickynav()
            };
            stickynav();
        }
        if ($('#siwChecklist tbody>tr').length > 1) {
            $('#siwChecklist tbody>tr').each(function () {
                var $checkbox = $(this).find('input:checkbox');
                var $tr = $(this);
                if ($checkbox.prop('checked')) {
                    $tr.addClass("tr-checked");
                } else {
                    // $tr.removeClass("tr-checked");
                }
            })
        }

        if ($('#siwChecklist tbody>tr, #siw_live_toggl').length > 0) {
            $('#siwChecklist tbody>tr, #siw_live_toggl').on("click", function (e) {
                if ($(e.target).is("a")) return;
                var $checkbox = $(this).find('input:checkbox');
                var $tr = $(this);
                if ($checkbox.prop('checked')) {
                    $checkbox.prop('checked', false);
                    $tr.removeClass("tr-checked");
                } else {
                    $checkbox.attr("checked", "checked");
                    $tr.addClass("tr-checked");
                }
                $checkbox.addClass("checked");
                if ($(this).is('#siw_live_toggl')) {
                    $("#liveToggle").ajaxSubmit({
                        success: function () {
                            console.log("Succes");
                            location.reload();
                        },
                        timeout: 5000
                    });
                } else {
                    //calcProgressBar();
                    $('#siwChecklist').ajaxSubmit({
                        success: function () {
                            console.log("Succes");
                            $checkbox.parent().parent().addClass("updated");
                            $checkbox.removeClass("checked");
                            setTimeout(function () {
                                $checkbox.parent().parent().removeClass("updated");
                            }, 2500);
                        },
                        timeout: 5000
                    });
                }
                return false;
            });
        }
    }
});
