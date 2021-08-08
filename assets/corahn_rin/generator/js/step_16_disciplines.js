(function ($, d) {
    var i, l, input, id, domainId;
    var disciplinesInputs = {};
    var inputs = d.querySelectorAll('input[type="checkbox"][name^="disciplines_spend_exp"]');
    var remainingBonusPointsDiv = d.getElementById('remaining_bonus_points');
    var remainingExpPointsDiv = d.getElementById('remaining_exp_points');

    var maxExpPoints = parseInt(d.getElementById('max_exp_points').innerHTML, 10);
    if (isNaN(maxExpPoints)) { throw 'Exp points are not valid.'; }
    var currentExp = maxExpPoints;

    var maxBonusPoints = parseInt(d.getElementById('max_bonus_points').innerHTML, 10);
    if (isNaN(maxBonusPoints)) { throw 'Bonus points are not valid.'; }
    var currentBonus = maxBonusPoints;

    var globalCurrentExp = currentExp;
    var globalCurrentBonus = currentBonus;

    /**
     * First, let's populate the disciplinesInputs property.
     * It will contain ALL inputs based on their IDs with domains ids too.
     */
    for (i = 0, l = inputs.length; i < l; i++) {
        input = inputs[i];
        id = parseInt(input.getAttribute('data-discipline-id'), 10);
        domainId = parseInt(input.getAttribute('data-domain-id'), 10);

        if (input.checked) {
            if (currentBonus > 0) {
                currentBonus--;
            } else {
                currentExp -= 25;
            }
        }

        if (!disciplinesInputs[domainId]) {
            disciplinesInputs[domainId] = {};
        }

        disciplinesInputs[domainId][id] = input;

        input.addEventListener('change', function() {
            if (this.checked && (globalCurrentExp - 25 < 0 && globalCurrentBonus <= 0)) {
                this.checked = false;
                return false;
            }

            recalculateTotalExpPoints();
        })
    } // End for inputs

    if (currentExp < 0 || currentBonus < 0) {
        throw 'Experience is invalid. Please reset step.';
    }

    /**
     * Calculates number of spent points, update UI to show the value, and return it.
     */
    function recalculateTotalExpPoints() {
        var id, domainId, input, disciplines;
        var currentExp = maxExpPoints;
        var currentBonus = maxBonusPoints;

        for (domainId in disciplinesInputs) {
            if (!disciplinesInputs.hasOwnProperty(domainId)) { continue; }
            disciplines = disciplinesInputs[domainId];

            for (id in disciplines) {
                if (!disciplines.hasOwnProperty(id)) { continue; }
                input = disciplines[id];

                if (input.checked) {
                    if (currentBonus > 0) {
                        currentBonus--;
                    } else {
                        if (currentExp - 25 < 0) {
                            input.checked = false;
                            continue;
                        }
                        currentExp -= 25;
                    }
                }
            }
        }

        globalCurrentExp = currentExp;
        globalCurrentBonus = currentBonus;

        remainingBonusPointsDiv.innerHTML = currentBonus.toString();
        remainingExpPointsDiv.innerHTML = currentExp.toString();
    }

    recalculateTotalExpPoints();

})(jQuery, document, window);
