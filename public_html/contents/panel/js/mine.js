jQuery(document).ready(function () {
/*    $("#example-vertical").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        stepsOrientation: "vertical",
        enableCancelButton: false,
        enableFinishButton: false,
        contentContainerTag: "div",
        labels: {
            cancel: "لغو",
            current: "current step:",
            pagination: "Pagination",
            finish: "پایان",
            next: "بعدی",
            previous: "قبلی",
            loading: "Loading ..."
        }
    });*/
    $(document).ready(function () {
        $('#category-selection').selectize({
            plugins: ['remove_button'],
            sortField: 'text',
            maxItems: 8
        });

    });
});