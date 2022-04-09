
function refreshDatesService(object, objectTargetClass){
    let objectTargetInput   = $("."+objectTargetClass);
    objectTargetInput
    .find('option')
    .remove()
    .end();
    let dates = object.find(":selected").attr("data-dates");
    let dateSelected = object.find(":selected").attr("date-selected");
    if(dates !== undefined && dates != ""){
        $(JSON.parse(dates)).each(function (i){
            let selected = "";
            let object = {
                value: this,
                text:this
            };
            if(dateSelected == this){
                object.selected = "selected";
            }
            objectTargetInput.append($('<option>', object));
        });
    }
}

$(document).ready(() => {
    $(".selectService").each(function(i, e){
        refreshDatesService($(this), $(this).attr("data-objectClass"));
    });
    $(".selectService").on("change", function(){
        $(this).find(":selected").attr("date-selected", "");
        refreshDatesService($(this), $(this).attr("data-objectClass"));
    });
});