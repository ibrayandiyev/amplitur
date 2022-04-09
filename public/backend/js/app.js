function Application() {
    var self = this;

  /**
   * Show delete warning modal
   *
   * @param  event
   * @return void
   */
    this.showDeleteWarning = function (event) {
        event.preventDefault();
        var element = $(this);
        var href = element.attr("href");
        var token = element.attr("token");
        if(token === undefined){
            token = $("input[name=_token").val();
        }

        swal(
            {
                title: i18next.t('question.warning.delete.title'),
                text: i18next.t('question.warning.delete.msg'),
                type: "warning",
                showCancelButton: true,
                cancelButtonText: i18next.t('question.warning.delete.cancel'),
                confirmButtonText: i18next.t('question.warning.delete.confirm'),
            },
            function (result) {
                if (result) {
                    $('<form>', {
                        "id": "showDeleteWarning",
                        "html": '<input type="hidden" name="_method" value="DELETE" /><input type="hidden" name="_token" value="'+token+'">',
                        "action": href,
                        "method": "POST"
                    }).appendTo(document.body).submit();
                    //window.location = href;
                }
            }
        );
    };

    /**
   * Show delete warning modal
   *
   * @param  event
   * @return void
   */
     this.showDeleteLineWarning = function (event) {
        event.preventDefault();
        var element = $(this);
        var target = element.attr("target");
        swal(
            {
                title: i18next.t('question.warning.delete.title'),
                text: i18next.t('question.warning.delete.msg'),
                type: "warning",
                showCancelButton: true,
                cancelButtonText: i18next.t('question.warning.delete.cancel'),
                confirmButtonText: i18next.t('question.warning.delete.confirm'),
            },
            function (result) {
                if (result) {
                    $("."+target).remove();
                }
            }
        );
    };

    this.showSaveWarning = function (event) {
        event.preventDefault();
        let target = event.target;
        var form = target.tagName == 'I' ? $(target).parent()[0].form : target.form;
        var title = i18next.t('question.warning.save.msg');
        console.log(target.attributes.titleforpage);
        var titleForPage = target.attributes.titleforpage;
        if(titleForPage !== undefined){
            title = titleForPage.value;
        }
        swal(
            {
                title: i18next.t('question.warning.save.title'),
                text: title,
                type: "warning",
                showCancelButton: true,
                cancelButtonText: i18next.t('question.warning.save.cancel'),
                confirmButtonText: i18next.t('question.warning.save.confirm'),
            },
            function (result) {
                if (result) {
                    if (typeof sanitizePhoneInputs === 'function') {
                        sanitizePhoneInputs();
                    }

                    form.submit();
                }
            }
        );
    };

    this.showCancelPaymentWarning = function (event) {
        event.preventDefault();
        let target                  = event.target;
        var index                   = target.getAttribute('index');
        var form                    = $("#cancelPayment"+index);
        var paymentField            = $(".value_cancel_"+index);
        var cancelBillValueField    = $(".value_bill_cancel_"+index);
        var paymentValue            = paymentField.val();
        var paymentMaxValue         = paymentField.attr('maxValue');
        paymentField.parent().removeClass('has-danger');
        if(paymentValue == "" || paymentValue <=0 || paymentValue > paymentMaxValue){
            paymentField.parent().addClass('has-danger');
            return false;
        }
        cancelBillValueField.val(paymentValue);
        swal(
            {
                title: i18next.t('question.warning.cancel_bill.title'),
                text: i18next.t('question.warning.cancel_bill.msg_01') + paymentValue + i18next.t('question.warning.cancel_bill.msg_02'),
                type: "info",
                showCancelButton: true,
                cancelButtonText: i18next.t('question.warning.cancel_bill.cancel'),
                confirmButtonText: i18next.t('question.warning.cancel_bill.confirm'),
            },
            function (result) {
                if (result) {
                    form.submit();
                }
            }
        );
    };

    this.showRegisterWarning = function (event) {
        event.preventDefault();
        let target = event.target;
        var form = target.tagName == 'I' ? $(target).parent()[0].form : target.form;

        swal(
            {
                title: i18next.t('question.warning.new_prov.title'),
                text: i18next.t('question.warning.new_prov.msg'),
                type: "info",
                showCancelButton: true,
                cancelButtonText: i18next.t('question.warning.new_prov.cancel'),
                confirmButtonText: i18next.t('question.warning.new_prov.confirm'),
            },
            function (result) {
                if (result) {
                    if (typeof sanitizePhoneInputs === 'function') {
                        sanitizePhoneInputs();
                    }

                    form.submit();
                }
            }
        );
    };

    this.savePut = function (event) {
        event.preventDefault();

        let target = event.target;
        let row = target.tagName == 'I' ? $(target).parent().parent().parent() : $(target).parent().parent();
        var form = row.find('input');
        var url = row.attr('data-href');
        var token = $('meta[name="csrf_token"]').attr('content');

        $(document.body).find('#additionalPutForm').remove();
        $(document.body).append('<form id="additionalPutForm" action="' + url + '/?redirect=back" method="post">');
        $(document.body).find('#additionalPutForm').empty();
        $(document.body).find('#additionalPutForm').append('<input name="_method" value="put">');
        $(document.body).find('#additionalPutForm').append('<input name="_token" value="' + token + '">');
        $(document.body).find('#additionalPutForm').append('<input name="price" value="' + form.closest('[data-name="price"]').val() + '">');
        $(document.body).find('#additionalPutForm').append('<input name="stock" value="' + form.closest('[data-name="stock"]').val() + '">');

        $(document.body).find('#additionalPutForm').submit();
    };

    /**
     * Show warning modal
     * @param  event
     * @return void
     */
    this.showWarning = function (event) {
        event.preventDefault();
        var element = $(this);
        var href = element.attr("href");

        swal(
            {
                title: i18next.t('question.warning.cancel_booking.title'),
                text: i18next.t('question.warning.cancel_booking.msg'),
                type: "warning",
                showCancelButton: true,
                cancelButtonText: i18next.t('question.warning.cancel_booking.cancel'),
                confirmButtonText: i18next.t('question.warning.cancel_booking.confirm'),
            },
            function (result) {
                if (result) {
                    window.location = href;
                }
            }
        );
    };

    /**
     * Toggle filter form exhibition
     *
     * return @void
     */
    this.toggleFilter = function () {
        let filterForm = $('#pageFilters');
        filterForm.children().toggle();
    };

  /**
   * Link a table row
   *
   * @return void
   */
    this.navigateFromRow = function () {
        var href = $(this)
            .parent()
            .data("href");

        if (typeof href === "undefined" || !href) return;

        window.location = href;
    };

  /**
   * Write the tab value in the hidden field for page reload
   *
   * @return void
   */
       this.markNavigation = function () {
        var href = $(this)
            .prop("hash");

        if (typeof href === "undefined" || !href) return;

        var inputNavigation  = $("input[name='navigation']");
        if(inputNavigation !== undefined){
            inputNavigation.val(href.replace("#",""));
        }
    };

    /**
     * Initialize comom app scripts
     *
     * @return void
     */
    this.init = function () {
        $(document).on("click", ".delete", App.showDeleteWarning);
        $(document).on("click", ".delete-line", App.showDeleteLineWarning);
        $(document).on("click", ".save", App.showSaveWarning);
        $(document).on("click", ".cancel-payment", App.showCancelPaymentWarning);
        $(document).on("click", ".register", App.showRegisterWarning);
        $(document).on("click", ".save-put", App.savePut);
        $(document).on("click", ".warn", App.showWarning);
        $(document).on("click", ".toggle-filter", App.toggleFilter);
        $('[data-inputmask]').inputmask();
        $(document).on(
            "click",
            ".table-linked-row tr td:not(.skip)",
            App.navigateFromRow
        );
        $(document).on(
            "click",
            ".nav-link",
            App.markNavigation
        );
        $('.select2').select2();
        $('[data-toggle="tooltip"]').tooltip();
        $('.multi-select').multiSelect({ selectableOptgroup: true });

        $('.input-money').inputmask({
            alias: 'numeric',
            groupSeparator: '.',
            radixPoint: ',',
            autoGroup: true,
            digits: 2,
            digitsOptional: false,
            placeholder: '0',
        });
    }

    /**
     * Request auth logout
     *
     * @param   {string}  route
     * @param   {string}  token
     *
     * @return  {void}
     */
    this.logout = function (route, token) {
        $.post(route, { _token: token }).always(() => {
            location.reload();
        });
    }
}

/**
 * Application instance
 * @type {Application}
 */
var App = new Application();
$(document).ready(App.init());
