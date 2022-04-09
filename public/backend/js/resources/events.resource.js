$(document).ready(function () {
    var selectCategoryType = $('select[name="type"]');

    handleOnlyEvents(selectCategoryType.val());

    selectCategoryType.change(function () {
        handleOnlyEvents($(this).val());
    });

    function handleOnlyEvents(categoryType) {
        var selectDuration = $('[data-event-only]');

        if (categoryType == '{{ \App\Enums\CategoryType::EVENT }}') {
            selectDuration.show();
        } else {
            selectDuration.hide();
        }
    }
});