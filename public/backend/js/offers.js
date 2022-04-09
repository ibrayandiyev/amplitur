function Offers() {
    var self = this;

  /**
   * Show save warning modal
   *
   * @param  event
   * @return void
   */
    this.showSaveAccommodationWarning = function (event) {
        event.preventDefault();
        var element = $(this);
        var href = element.attr("href-longtrip-accommodation");
        var token = element.attr("token");
        var longtrip_accommodation_type_id = $("#longtrip_accommodation_type_id :selected").val();
        if(token === undefined){
            token = $("input[name=_token").val();
        }
        swal(
            {
                title: "Você tem certeza?",
                text: "A conclusão desta operação é irreversível. Você tem certeza que deseja excluir este registro?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancelar",
                confirmButtonText: "Sim, continuar"
            },
            function (result) {
                if (result) {
                    $('<form>', {
                        "id": "showSaveAccommodationWarning",
                        "html": '<input type="hidden" name="longtrip_accommodation_type_id" value="'+longtrip_accommodation_type_id+'" /><input type="hidden" name="_token" value="'+token+'">',
                        "action": href,
                        "method": "POST"
                    }).appendTo(document.body).submit();
                }
                return  false;
            }
        );
        return false;
    };

    /**
     * Initialize comom app scripts
     *
     * @return void
     */
    this.init = function () {
        $(document).on("click", ".save-accommodation", OfferApp.showSaveAccommodationWarning);
    }

}

/**
 * Offers instance
 * @type {Offers}
 */
var OfferApp = new Offers();
$(document).ready(OfferApp.init());
