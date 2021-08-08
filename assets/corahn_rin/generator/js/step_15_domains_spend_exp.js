(function ($, d) {
    var i, j, k, l, input, id, value, btns, baseValue;
    var buttons = d.querySelectorAll('.domain button.domain-change:not(.disabled)');
    var domainsInputs = {};
    var remainingPointsDiv = d.getElementById('remaining_exp_points');

    var maxExpPoints = parseInt(d.getElementById('max_exp_points').innerHTML, 10);
    if (isNaN(maxExpPoints)) {
        throw 'Exp points are not valid.';
    }
    var currentExp = maxExpPoints;

    var globalCurrentExp = currentExp;

    var inputs = d.querySelectorAll('.domain input[name^=domains_spend_exp]');

    /**
     * First, let's populate the domainsInputs property.
     * It will contain ALL inputs based on their IDs.
     * It will also contain all buttons related to the input, for dynamic activation.
     */
    for (i = 0, l = inputs.length; i < l; i++) {
        input = inputs[i];
        id = input.id.replace('domains_', '');
        value = parseInt(input.value, 10);
        if (isNaN(value)) {
            throw 'Input value for id '+id+' is not valid';
        }
        baseValue = parseInt(input.getAttribute('data-base-value'), 10);
        if (isNaN(baseValue)) {
            throw 'Input base value for id '+id+' is not valid';
        }
        currentExp -= (value * 10);
        domainsInputs[id] = {
            input: input,
            value: value, // Stored in memory so updating input manually will be useless<
            baseValue: baseValue,
            buttons: {}
        };

        // Get list of all buttons for this id
        btns = d.querySelectorAll('button[data-domain-id="'+id.toString()+'"]');

        // Store them in the buttons property for this domainInput
        for (j = 0, k = btns.length; j < k; j++) {
            value = btns[j].getAttribute('data-change');
            domainsInputs[id].buttons[value] = btns[j];
        }
    } // End for inputs

    if (currentExp < 0) {
        throw 'Experience is invalid. Please reset step.';
    }

    /**
     * Calculates number of spent points, update UI to show the value, and return it.
     */
    function recalculateTotalExpPoints() {
        var id, domainData, value;
        var currentExp = maxExpPoints;

        for (id in domainsInputs) {
            if (!domainsInputs.hasOwnProperty(id)) { continue; }
            domainData = domainsInputs[id];
            value = parseInt(domainData.value, 10);

            if (isNaN(value)) {
                throw 'Input value for id '+id+' is not valid';
            }

            if (currentExp - (value * 10) < 0) {
                continue;
            }

            currentExp -= (value * 10);
        }

        globalCurrentExp = currentExp;

        remainingPointsDiv.innerHTML = currentExp.toString();
    }

    for (i = 0, l = buttons.length; i < l; i++) {
        var button = buttons[i];
        id = button.getAttribute('data-domain-id');

        // Don't apply listener on disabled inputs
        if (button.classList.contains('disabled')) {
            continue;
        }

        button.addEventListener('click', function(){
            var isActive = this.classList.contains('active');
            var domainId = this.getAttribute('data-domain-id');
            var domainData, baseValue, newValue, buttonValue, computedValue, key, button;

            if (isActive || globalCurrentExp < 10) {
                return false;
            }

            if (!domainsInputs[domainId]) {
                throw 'Invalid input domain '+domainId;
            }

            newValue = parseInt(this.getAttribute('data-change'), 10);

            if (isNaN(newValue)) {
                throw 'Invalid button value';
            }

            domainData = domainsInputs[domainId];
            baseValue = domainData.baseValue;
            value = domainData.value;
            buttonValue = parseInt(this.getAttribute('data-change'));

            if (isNaN(buttonValue) || buttonValue > 5 || buttonValue < baseValue) {
                throw 'Invalid button value';
            }

            computedValue = buttonValue - baseValue;

            domainData.value = computedValue;
            domainData.input.value = computedValue;

            for (key in domainData.buttons) {
                if (!domainData.buttons.hasOwnProperty(key)) { continue; }

                button = domainData.buttons[key];

                if (button.getAttribute('data-change').toString() === buttonValue.toString()) {
                    button.classList.add('active');
                } else {
                    button.classList.remove('active');
                }
            }

            recalculateTotalExpPoints();
        });
    }

    recalculateTotalExpPoints();

})(jQuery, document, window);
