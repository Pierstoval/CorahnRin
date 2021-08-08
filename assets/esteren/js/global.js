(function($, d, Materialize){
    "use strict";
    var i, l, button, input;

    function enableJsComponents(context) {
        Materialize.AutoInit(context);
    }

    // Manage the "disable tags" cookie CNIL requirement
    button = d.querySelector('button.disable_tags');
    if (button) {
        button.addEventListener('click', function(e){
            if (e.target.tagName.toLowerCase() === 'button' && e.target.className.match('disable_tags')) {
                d.cookie = "disable_tags=1";
                e.target.innerHTML = 'OK';
            }
        });
    }

    // Handle "input range buttons" javascript
    var dataRangeMinusButtons = d.querySelectorAll('[data-range-button]');
    for (i = 0, l = dataRangeMinusButtons.length; i < l; i++) {
        button = dataRangeMinusButtons[i];
        input = d.getElementById(button.getAttribute('data-range-button'));

        if (!input) {
            console.error('Cannot set up listeners for range inputs buttons, selector is invalid.');

            continue;
        }

        const recalculateInputValue = function (input, increment) {
            var min = parseInt(input.getAttribute('data-base'), 10);
            var max = parseInt(input.getAttribute('max'), 10);

            var value = parseInt(input.value, 10);

            value += increment;

            if (isNaN(value)) {
                value = min;
            }

            if (value < min) {
                value = min;
            } else if (value > max) {
                value = max;
            }

            input.value = value;
        };

        input.addEventListener('change', function () {
            recalculateInputValue(this, 0)
        });

        button.addEventListener('click', function (e) {
            e.preventDefault();

            recalculateInputValue(
                d.getElementById(this.getAttribute('data-range-button')),
                parseInt(this.getAttribute('data-increment'), 10)
            )
        });
    }

    enableJsComponents(d.body);

    window._enableJsComponents = enableJsComponents;
})(jQuery, document, M);
