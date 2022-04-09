function CopyPaste() {
    var self = this;

    this.copyBuffer = [];
    var ctrlDown = false,
        ctrlKey = 17,
        cmdKey = 91,
        vKey = 86,
        cKey = 67;
    var sequencialCode = 0;
    /**
    * Show save warning modal
    *
    * @param  event
    * @return void
    */
    this.copySelectOptions = function (event) {
        var object      = $(event.target);
        var objectCopy  = $("." + object.attr("target"));
        if (!$(objectCopy).data('select2'))
        {
            objectCopy  = $(object).closest(".select2-container--focus").prev("select");
            if (!$(objectCopy).data('select2'))
            {
                // Multi-select options
                objectCopy = $(object).closest(".ms-container").prev("select");
                if(!objectCopy){
                    return false;
                }
            }
        }
        var textarea = $('<textarea class="copytextarea" />');
        var tempBuffer = [];
        objectCopy.find(':selected').each(function (i, e){
            console.log(e.value);
            tempBuffer.push(e.value);
        });
        CopyPasteApp.copyBuffer = tempBuffer;
        textarea.text(JSON.stringify(tempBuffer));
        $("body").append(textarea);
        $(".copytextarea").select();
        try {
            var successful = document.execCommand('copy');
        } catch (err) {
            console.log("Cannot copy text");
        }
        textarea.remove();
    };

    this.pasteSelectOptions = function (event) {
        try{
            CopyPasteApp.copyBuffer = JSON.parse(event.originalEvent.clipboardData.getData('text'));
        }catch(err){
            CopyPasteApp.copyBuffer = "";
        }
        //if((this.sequencialCode | event.keyCode) == (ctrlKey | vKey)){
        var object = $(event.target);
        var selectItem = $('.select2-container--open').prev();
        if(selectItem.length >0){
            // Select 2
            var index   = selectItem.index();
            var id      = selectItem.attr('id');
            $("#"+id).val(null).trigger("change");
            object.val("");
            $("#"+id).val(CopyPasteApp.copyBuffer).trigger("change");
        }else{
            selectItem = $(object).closest(".ms-container").prev("select");
            if(selectItem.length >0){
                selectItem.multiSelect('deselect');
                selectItem.multiSelect('select', CopyPasteApp.copyBuffer);
            }
        }
        
        
        //}
        if (event.keyCode == ctrlKey || event.keyCode == vKey){
            this.sequencialCode = event.keyCode;
        }
        return false;
    };

    /**
     * Initialize comom app scripts
     *
     * @return void
     */
    this.init = function () {
        $(".copyButton").on("click", this.copySelectOptions);
        $(".copyButton").on("copy", this.copySelectOptions);
        $(".ms-container").on("keyup", this.copySelectOptions);
        $(".select2-search").on("copy", this.copySelectOptions);
        
        //$(document).on('keyup', '.select2-search__field', this.pasteSelectOptions);
        $(document).on('paste', '.select2-search__field', this.pasteSelectOptions);
        $(document).on('paste', '.ms-container', this.pasteSelectOptions);
    }

}

/**
 * CopyPaste instance
 * @type {CopyPaste}
 */
var CopyPasteApp = new CopyPaste();
$(document).ready(CopyPasteApp.init());
