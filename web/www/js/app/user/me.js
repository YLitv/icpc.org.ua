function appUserMe() {

    /**
     * Init Select2
     */
    $('.form-group .form-control[name=schoolId]').select2({
        'width': 'resolve'
    });

    /**
     * Type and Coordinator checkboxes
     */
    $(':checkbox[name=type], :checkbox[name=coordinator]').on('change', function() {
        var $group = $(this).closest('.btn-group');
        if ($(this).is(':checked')) {
            if ($(this).val() === 'student') {
                $('.btn:nth-child(2), .btn:nth-child(3)', $group).removeClass('active');
                $('.btn:nth-child(2) :checkbox, .btn:nth-child(3) :checkbox', $group).prop('checked', false).change();
            } else if (($(this).val() === 'coach') || ($(this).prop('name') === 'coordinator')) {
                $('.btn:nth-child(1)', $group).removeClass('active');
            }
        }
    });

    $(':checkbox[name=coordinator]').on('change', function() {

        var $this = $(this),
            $btn = $this.closest('.btn'),
            $group = $this.closest('.btn-group'),
            $dropdown = $group.next('.btn-group').find('.dropdown-menu:first');

        // Toggle dropdown menu
        if ($this.is(':checked')) {
            $dropdown.show();
        } else {
            $dropdown.hide();
        }

        // Select value
        $('li a', $dropdown).on('click', function() {
            $this.val($(this).data('val'));
            $('.caption', $btn).html($(this).html());
            $dropdown.hide();
            return false;
        });

        // Bind hide on document click
        if (!$this.data('hide-on-document-click')) {
            $this.data('hide-on-document-click', true);
            $(document).on('click', function(e) {
                var $target = $(e.target)
                if (!$target.hasClass('btn')) {
                    $target = $target.closest('.btn')
                }
                $target = $target.filter(function() {
                    return ($(':checkbox[name=coordinator]', this).length > 0);
                });
                if (!$target.length) {
                    $dropdown.hide();
                    if (!$this.val()) {
                        $btn.removeClass('active');
                        $(':checkbox', $btn).prop('checked', false).change();
                    }
                }
            });
        }

    });

    $('.btn-save').on('click', function() {
        var $this = $(this),
            $form = $this.closest('.form-horizontal');
        $this.prop('disabled', true);
        $.ajax({
            url: app.baseUrl + '/user/me',
            data: {
                firstNameUk:           $('#firstNameUk').val(),
                middleNameUk:          $('#middleNameUk').val(),
                lastNameUk:            $('#lastNameUk').val(),
                firstNameEn:           $('#firstNameEn').val(),
                middleNameEn:          $('#middleNameEn').val(),
                lastNameEn:            $('#lastNameEn').val(),
                schoolId:              $('#schoolId').val(),
                currentPassword:       $('#currentPassword').val(),
                type:                  $('.form-group .btn.active [name=type]').val(),
                coordinator:           $('.form-group .btn.active [name=coordinator]').val(),
                password:              $('#password').val(),
                passwordRepeat:        $('#passwordRepeat').val()
            },
            success: function(response) {
                appShowErrors(response.errors, $form);
                if (response.errors) {
                    $this.prop('disabled', false);
                } else {
                    location.href = app.baseUrl + '/user/me';
                }
            }
        });
    });
}
