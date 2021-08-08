(function ($, d) {
    var i, l, input, id;
    var combatArtsInputs = {};
    var inputs = d.querySelectorAll('input[type="checkbox"][name^="combat_arts_spend_exp"]');
    var remainingExpPointsDiv = d.getElementById('remaining_exp_points');

    var maxExpPoints = parseInt(d.getElementById('max_exp_points').innerHTML, 10);
    if (isNaN(maxExpPoints)) { throw 'Exp points are not valid.'; }
    var currentExp = maxExpPoints;

    var globalCurrentExp = currentExp;

    /**
     * First, let's populate the combatArtsInputs property.
     * It will contain ALL inputs based on their IDs with domains ids too.
     */
    for (i = 0, l = inputs.length; i < l; i++) {
        input = inputs[i];
        id = parseInt(input.getAttribute('data-combat-art-id'), 10);
        if (isNaN(id)) {
            throw 'Inputs are not valid';
        }

        if (input.checked) {
            currentExp -= 20;
        }

        combatArtsInputs[id] = input;

        input.addEventListener('change', function() {
            if (this.checked && globalCurrentExp - 20 < 0) {
                this.checked = false;
                return false;
            }

            recalculateTotalExpPoints();
        })
    } // End for inputs

    if (currentExp < 0) {
        throw 'Experience is invalid. Please reset step.';
    }

    /**
     * Calculates number of spent points, update UI to show the value, and return it.
     */
    function recalculateTotalExpPoints() {
        var id, input;
        var currentExp = maxExpPoints;

        for (id in combatArtsInputs) {
            if (!combatArtsInputs.hasOwnProperty(id)) { continue; }
            input = combatArtsInputs[id];

            if (input.checked) {
                if (currentExp - 20 < 0) {
                    input.checked = false;
                    continue;
                }
                currentExp -= 20;
            }
        }

        globalCurrentExp = currentExp;
        remainingExpPointsDiv.innerHTML = currentExp.toString();
    }

    recalculateTotalExpPoints();

})(jQuery, document, window);
