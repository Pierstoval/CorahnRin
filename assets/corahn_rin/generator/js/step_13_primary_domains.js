(function ($, d, w) {
    var i, l, btn, data;
    var buttons = d.querySelectorAll('.domain button.domain-change');
    var $buttons = $(buttons);

    /**
     * Update a specific button with passed value if set.
     *
     * @param {Element} button
     * @param {Number} value
     */
    function updateDomainValue(button, value) {
        button._data.input.value = value;

        for (var i = 0, list = button._data.btnsList, l = list.length; i < l; i++) {
            if (!list[i]._data) {
                // This may be an input without data, in this case it can be input with 5.
                continue;
            }
            if (list[i]._data.changeValue === value) {
                list[i].classList.add('active');
            } else {
                list[i].classList.remove('active');
            }
        }
    }

    /**
     * Refreshes all buttons depending on all current scores.
     */
    function refreshButtonsAndMessages() {
        var numberOf;
        var numberOfCompleted;
        var numberToComplete;
        var ostElement = d.getElementById('ost');

        $buttons.removeClass('not_selectable');

        // Save the number of active buttons depending on their "data-change" value
        numberOf = {
            1: $buttons.filter('[data-change=1].active').length,
            2: $buttons.filter('[data-change=2].active').length,
            3: $buttons.filter('[data-change=3].active').length
        };

        d.getElementById('numberOf1').innerHTML = numberOf[1];
        d.getElementById('numberOf2').innerHTML = numberOf[2];
        d.getElementById('numberOf3').innerHTML = numberOf[3];

        numberOfCompleted = 0;

        // By default, user needs to select the 3 domain categories and the Ost service, which makes 4 inputs to fill
        numberToComplete = 4;

        if (numberOf[1] >= 2) {
            $buttons.filter('[data-change=1]:not(.active)').addClass('not_selectable');
            numberOfCompleted++;
        }
        if (numberOf[2] >= 2) {
            $buttons.filter('[data-change=2]:not(.active)').addClass('not_selectable');
            numberOfCompleted++;
        }
        if (numberOf[3] >= 1) {
            $buttons.filter('[data-change=3]:not(.active)').addClass('not_selectable');
            numberOfCompleted++;
        }

        if (ostElement.value) {
            numberOfCompleted++;
        }

        if (numberOfCompleted === numberToComplete) {
            $('#numberOfCompleted').slideDown(400);
        } else {
            $('#numberOfCompleted').slideUp(400);
        }
    }

    /**
     * @param {string}  cssClass
     * @returns Element
     */
    Element.prototype.findParentByClass = function (cssClass) {
        var element = this;
        while ((element = element.parentElement) && !element.classList.contains(cssClass)) {
            // Do nothing, element var will change automatically. That's the trick.
        }
        return element;
    };

    // At first, we cache DOM elements in the buttons themselves.
    // It avoids looking into the DOM on each click.
    // This loop proceeds domain inputs.
    for (i = 0, l = buttons.length; i < l; i++) {
        btn = buttons[i];

        // Initialize the property we'll use for "cache".
        data = {};

        data.changeValue = parseInt(btn.getAttribute('data-change'), 10);
        data.btnGroup = btn.findParentByClass('domain_button_group');

        if (!data.btnGroup) {
            throw 'Button group is inaccessible.';
        }

        if (isNaN(data.changeValue)) {
            throw 'Invalid "change" value. Tried to change it?';
        }

        // Initialize data that depend on other data.
        data.btnsOfSameType = d.querySelectorAll('button[data-change="' + data.changeValue + '"]');
        data.btnsList = data.btnGroup.querySelectorAll('button[data-change]');
        data.domainElement = data.btnGroup.findParentByClass('domain');
        if (!data.domainElement) {
            throw 'Domain element is inaccessible.';
        }

        data.domainId = parseInt(data.domainElement.getAttribute('data-domain-id'), 10);
        if (isNaN(data.changeValue)) {
            throw 'Invalid domain id.';
        }

        data.input = data.domainElement.querySelector('input[type="hidden"][name^="domain"]');
        if (!data.input) {
            throw 'Data input is inaccessible.';
        }

        btn._data = Object.freeze(data);

        //-----------------------------------------------------------------------------

        // Add the listener to each button.
        btn.addEventListener('click', function () {
            var data = this._data;
            var value = parseInt(data.input.value, 10);
            var activeBtns;
            var btnsOfSameType;
            var btnsToDisable;

            // Do nothing if input is the same or if value equals 5.
            // Because 5 cannot be changed, so it's useless to execute more useless JS.
            if (data.changeValue === value || data.changeValue === 5) {
                return false;
            }

            if (isNaN(value)) {
                throw 'Incorrect input value. Must be an integer.';
            }

            btnsOfSameType = $(data.btnsOfSameType);

            activeBtns = btnsOfSameType.filter('.active');

            if (data.changeValue === 3 && activeBtns.length > 0) {
                btnsToDisable = activeBtns;
            } else if (
                   data.changeValue === 2 && activeBtns.length > 1
                || data.changeValue === 1 && activeBtns.length > 1
            ) {
                btnsToDisable = activeBtns.filter(':gt(0)')
            }

            // Disable when "too much" buttons.
            if (btnsToDisable && btnsToDisable.length > 0) {
                btnsToDisable.each(function(){
                    updateDomainValue(this, 0);
                });
            }

            updateDomainValue(this, data.changeValue);

            refreshButtonsAndMessages();
        });
    } // End for loop on each button

    var ostButtons = d.querySelectorAll('.domain button[data-type="ost"]');
    var ostInput = d.getElementById('ost');

    // Proceed listeners for Ost buttons, because we're almost sure we'll have them.
    for (i = 0, l = ostButtons.length; i < l; i++) {
        btn = ostButtons[i];

        // Add the listener to each button.
        btn.addEventListener('click', function () {

            // Disable all buttons
            for (var j = 0, l = ostButtons.length; j < l; j++) {
                ostButtons[j].classList.remove('active');
            }

            this.classList.add('active');
            ostInput.value = this.getAttribute('data-domain-id');

            refreshButtonsAndMessages();
        });
    }

    refreshButtonsAndMessages();

})(jQuery, document, window);
