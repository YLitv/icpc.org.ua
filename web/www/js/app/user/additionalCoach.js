function appUserAdditionalCoach(options) {

    /**
     * Save button handler
     */
    $('.btn-save').on('click', function(){
        var $this = $(this),
            $form = $this.closest('.form-horizontal');
        $this.prop('disabled', true);
        $.ajax({
            url: app.baseUrl + '/user/additionalCoachSave',
            data: {
                language: options.lang,

                phoneHome:                $('[name=phoneHome]').val(),
                phoneMobile:              $('[name=phoneMobile]').val(),
                skype:                    $('[name=skype]').val(),
                acmNumber:                $('[name=acmNumber]').val(),
                schoolName:               $('[name=schoolName]').val(),
                schoolNameShort:          $('[name=schoolNameShort]').val(),
                schoolDivision:           $('[name=schoolDivision]:checked').val(),
                schoolPostEmailAddresses: $('[name=schoolPostEmailAddresses]').val(),

                position:      $('[name=position]').val(),
                officeAddress: $('[name=officeAddress]').val(),
                phoneWork:     $('[name=phoneWork]').val(),
                fax:           $('[name=fax]').val()
            },
            success: function(response) {
                appShowErrors(response.errors, $form);
                if (response.errors) {
                    $this.prop('disabled', false);
                } else {
                    location.href = app.baseUrl + '/user/additional/lang/' + options.lang;
                }
            }
        });
    });

}