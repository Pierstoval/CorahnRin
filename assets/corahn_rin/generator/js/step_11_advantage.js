(function ($, d) {

    /**
     * Advantage immutable object type.
     *
     * @param object
     * @constructor
     * @property {Number} id
     * @property {Number} xp
     * @property {Number} baseValue
     * @property {Number} currentValue
     * @property {Number} bonus
     * @property {boolean} isAdvantage
     * @property {Element} input
     * @property {Element} label
     * @property {Element} indicationInput
     */
    var Advantage = function (object) {
        this.id = !isNaN(parseInt(object.id)) ? parseInt(object.id, 10) : null;
        this.xp = !isNaN(parseInt(object.xp)) ? parseInt(object.xp, 10) : null;
        this.baseValue = !isNaN(parseInt(object.baseValue)) ? parseInt(object.baseValue, 10) : null;
        this.currentValue = !isNaN(parseInt(object.currentValue)) ? parseInt(object.currentValue, 10) : null;
        this.bonus = !isNaN(parseInt(object.bonus)) ? parseInt(object.bonus, 10) : null;
        this.isAdvantage = !!object.isAdvantage;
        this.input = object.input || null;
        this.label = object.label || null;
        this.indicationInput = object.indicationInput || null;
    };

    Advantage.new = function (object) {
        return Object.freeze(new Advantage(object));
    };


    /**
     * Get a plain object representing the advantage, based on an HTML input.
     *
     * @param {Element} input
     * @param {Element} label
     *
     * @returns {Advantage}
     */
    function getAdvantageFromInput(input, label) {
        var id = parseInt(input.getAttribute('data-element-id'));
        var xp = parseInt(input.getAttribute('data-element-cost'));
        var currentValue = parseInt(input.value);
        var bonus = parseInt(input.getAttribute('data-bonus'));
        var isAdvantage = null;
        var indicationInput = document.getElementById('advantages_indications_' + id);

        // If these elements are not numbers,
        // it means someone attempted to override DOM values.
        if (isNaN(xp) || isNaN(currentValue) || isNaN(bonus) || isNaN(id)) {
            throw 'Wrong values';
        }

        // Determine whether it's an advantage or a disadvantage.
        if (label.classList.contains('change_disadvtg')) {
            isAdvantage = false;
        } else if (label.classList.contains('change_avtg')) {
            isAdvantage = true;
        } else {
            throw 'Wrong element class, missing change_avtg or change_disadvtg.';
        }

        return Advantage.new({
            'id': id,
            'xp': xp,
            'baseValue': currentValue,
            'currentValue': currentValue,
            'bonus': bonus,
            'isAdvantage': isAdvantage,
            'input': input,
            'label': label,
            'indicationInput': indicationInput
        });
    }

    /**
     * Calculate exp from advantage.
     * If "virtualValue" is specified, will calculate as if the advantage had specific value.
     *
     * @param {Advantage} advantage
     * @param {Number}    [virtualValue]
     *
     * @returns {number}
     */
    function calculateXpFromAdvantage(advantage, virtualValue) {
        var value, xp;

        // Virtual value is used to calculate experience only "for information", manually.
        // If there is no virtual value, it means we process calculation based on the input value directly.
        if (null !== virtualValue && typeof virtualValue !== 'undefined') {
            value = virtualValue;
        } else {
            value = advantage.input.value;
        }

        value = parseInt(value, 10);

        if (isNaN(value)) {
            throw 'Incorrect value for input.';
        }

        if (advantage.id === 50) {
            // Case of the "Trauma" disadvantage.
            // Trauma XP calculation is simpler than other disadvantages.
            return value * advantage.xp;
        }

        /**
         * If advantage can be chosen twice, the total experience cost is
         * the base price + half the base price, truncated.
         */

        if (value === 0) {
            return 0;
        } else if (value === 1) {
            xp = advantage.xp;
        } else if (value === 2 && advantage.bonus > 0) {
            xp = Math.floor(advantage.xp * 1.5);
        } else if (value > 2) {
            throw 'Incorrect value for advantage / disadvantage';
        }

        // Negative exp for advantages.
        if (advantage.isAdvantage) {
            xp *= (-1);
        }

        return xp;
    }

    /**
     * @param {Advantage} advantage
     * @param {Number}    [forceValue]
     */
    function updateAdvantageValue(advantage, forceValue) {
        var value = parseInt(advantage.input.value, 10);
        var classList;

        forceValue = typeof forceValue !== 'undefined' ? parseInt(forceValue, 10) : null;

        if (isNaN(value)) {
            throw 'Incorrect value for input. Check your code.';
        }
        if (isNaN(forceValue) || forceValue < 0 || forceValue > 3) {
            throw 'Incorrect force value, must be a number or undefined.';
        }

        if (null !== forceValue) {
            value = forceValue;
        } else {
            value++;
        }

        // Reset value to 0 if it exceeds a specific value.
        // For Trauma, max is 3,
        //  else max is 2.
        if (
            (advantage.id === 50 && value > 3)
            || (advantage.id !== 50 && value > 2)
            || (advantage.bonus === 0 && value >= 2)
        ) {
            value = 0;
        }

        // Edge case possible with bad code.
        if (value < 0) {
            throw 'Incorrect negative value.';
        }

        // Update input value.
        advantage.input.value = value;

        // Update label classes.
        classList = advantage.label.className.replace(/btn-state-[0-9]+/gi, '').trim();

        if (value) {
            classList += ' btn-state-' + value;
        }

        advantage.label.className = classList;
    }

    /**
     * @param {Number}    currentAdvantageId
     * @param {Number}    experience
     * @param {{}}        advantagesList
     * @param {Number}    [deep]
     *
     * @returns {Number}
     */
    function gainOrSpendExperience(currentAdvantageId, experience, advantagesList, deep) {
        var experienceGainedWithDisadvantages = 0;
        var numberOfAdvantages = 0;

        if (typeof deep === 'undefined') {
            deep = 0;
        }

        if (deep > 10) {
            throw 'More than 10 loops when calculating disadvantages, something is going on.';
        }

        for (var elementId in advantagesList) {
            if (!advantagesList.hasOwnProperty(elementId)) {
                continue;
            }

            // Do not process current advantage
            if (elementId.toString() === currentAdvantageId.toString()) {
                continue;
            }

            var advantageOrDisadvantage = advantagesList[elementId];

            // Get xp from function.
            var xpGained = calculateXpFromAdvantage(advantageOrDisadvantage);

            if (0 === xpGained) {
                continue;
            }

            numberOfAdvantages++;

            if (
                (advantageOrDisadvantage.isAdvantage && (numberOfAdvantages >= 4 || experience + xpGained < 0))
                ||
                (!advantageOrDisadvantage.isAdvantage && (numberOfAdvantages >= 4 || experienceGainedWithDisadvantages + xpGained > 80))
            ) {
                updateAdvantageValue(advantageOrDisadvantage, advantageOrDisadvantage.input.value - 1);

                return gainOrSpendExperience(currentAdvantageId, experience, advantagesList, deep + 1);
            }

            experience += xpGained;
        }

        return experience;
    }

    function updateIndicationsDisplay() {
        var indicationList = document.getElementsByClassName('indication');

        Array.from(indicationList).forEach(function (indicationDiv) {
            var advantageId = parseInt(indicationDiv.getAttribute('data-indication-for'), 10);
            var indicationInput, advantageInput;

            if (isNaN(advantageId)) {
                throw 'Invalid HTML attribute for indication.';
            }

            indicationInput = document.getElementById('advantages_indications_' + advantageId);
            advantageInput = document.querySelector('input[data-element-id="' + advantageId + '"]');

            if (!indicationInput) {
                throw 'No indication input found for advantage ' + advantageId;
            }
            if (!advantageInput) {
                throw 'No value input found for advantage ' + advantageId;
            }

            // Enable/disable potentially necessary instructions
            if (parseInt(advantageInput.value, 10) === 0) {
                indicationDiv.classList.remove('active');
                indicationInput.removeAttribute('required');
                indicationInput.value = '';
            } else {
                indicationDiv.classList.add('active');
                indicationInput.setAttribute('required', 'required');
            }
        });
    }

    // Depends on the "Advantage" class.
    // Depends on all functions for this specific step.

    if (d.getElementById('generator_11_advantages')) {

        // Vars
        var $labelsCollection = $('.change_char_advantage');
        var xpElement = d.getElementById('xp');
        var advantagesList = {};
        var disadvantagesList = {};
        var numberOfAdvantages = 0;
        var numberOfDisadvantages = 0;

        // Initialize the two arrays.
        // Allows us to only work with memory and not always with loops through DOM...
        for (var i = 0, l = $labelsCollection.length; i < l; i++) {
            var input = $labelsCollection[i].querySelector('input');
            var element = getAdvantageFromInput(input, $labelsCollection[i]);
            var elementId = parseInt(element.id, 10);
            // Push these changes into memory array.
            if (element.isAdvantage) {
                advantagesList[elementId] = element;
                ++numberOfAdvantages;
            } else {
                disadvantagesList[elementId] = element;
                ++numberOfDisadvantages;
            }
        }
        // End initialize

        // Process event listeners.
        // If we're here, input DOM attributes are already validated, so no more checks.
        $labelsCollection.on('click', function () {
            var currentInput = this.querySelector('input');
            var currentAdvantage = getAdvantageFromInput(currentInput, this);
            var currentAdvantageId = parseInt(currentAdvantage.id, 10);
            var currentValue = parseInt(currentInput.value, 10);
            var experience = 100;
            var i, currentXp, virtualValueToTest, gainedWithDisadvantages;

            if (isNaN(currentValue)) {
                throw 'Incorrect value for input.';
            }

            // Handle groups of advantages that cannot be selected together

            // Case "Allies"
            if ([1, 2, 3].indexOf(currentAdvantageId) >= 0) {
                for (i = 1; i <= 3; i++) {
                    if (currentAdvantageId !== i) {
                        updateAdvantageValue(advantagesList[i], 0);
                    }
                }
            }

            // Case "Financial ease"
            if ([4, 5, 6, 7, 8].indexOf(currentAdvantageId) >= 0) {
                for (i = 4; i <= 8; i++) {
                    if (currentAdvantageId !== i) {
                        updateAdvantageValue(advantagesList[i], 0);
                    }
                }
            }

            // Calculate from disadvantages.
            experience = gainOrSpendExperience(currentAdvantageId, experience, disadvantagesList);

            // Keep the "gain" in memory to check later if it's superior to 80.
            gainedWithDisadvantages = experience - 100;

            // Calculate from advantages.
            experience = gainOrSpendExperience(currentAdvantageId, experience, advantagesList);

            virtualValueToTest = currentValue + 1;
            if (
                (currentAdvantageId === 50 && virtualValueToTest > 3)
                || (currentAdvantageId !== 50 && virtualValueToTest > 2)
                || (currentAdvantage.bonus === 0 && virtualValueToTest >= 2)
            ) {
                virtualValueToTest = 0;
            }

            currentXp = calculateXpFromAdvantage(currentAdvantage, virtualValueToTest);

            if (experience + currentXp < 0 || (!currentAdvantage.isAdvantage && gainedWithDisadvantages + currentXp > 80)) {
                return;
            }

            updateAdvantageValue(currentAdvantage);
            updateIndicationsDisplay();

            experience += currentXp;

            xpElement.innerHTML = experience;
        });
    }
})(jQuery, document);
