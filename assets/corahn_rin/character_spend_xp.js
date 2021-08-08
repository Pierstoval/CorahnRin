import './spend_xp.scss';

(function (window, document) {
    "use strict";
    var i, l;

    const speedInput = document.getElementById('character_spend_xp_speed');
    const defenseInput = document.getElementById('character_spend_xp_defense');
    const xpElement = document.getElementById('xp');
    const baseXp = getIntOrDie(xpElement.innerText, 'Invalid base XP value.');
    const domainsInputs = document.querySelectorAll('[id^="character_spend_xp_domains_"]');
    const disciplinesInputs = document.querySelectorAll('input[type="text"][id^="character_spend_xp_disciplines_"]');
    const oghamInputs = document.querySelectorAll('input[data-ogham]');
    const miraclesInputs = document.querySelectorAll('input[data-miracle]');

    if (!speedInput || !defenseInput || domainsInputs.length !== 16 || !disciplinesInputs.length) {
        throw 'Invalid inputs. Cannot handle JS for current form.';
    }

    const baseSpeed = getIntOrDie(speedInput.getAttribute('data-base'), 'Base speed has an invalid value.');
    const baseDefense = getIntOrDie(defenseInput.getAttribute('data-base'), 'Base defense has an invalid value.');
    const baseDomainsValues = [];
    const baseDisciplinesValues = [];

    const dataRangeButtons = document.querySelectorAll('[data-range-button]');
    for (i = 0, l = dataRangeButtons.length; i < l; i++) {
        var button = dataRangeButtons[i];

        button.addEventListener('click', function() {
            recalculate();
        });
    }

    const btnCheckboxInitializer = function(input) {
        button = input.parentElement; // Must be a label.btn
        button.addEventListener('click', function (e) {
            e.preventDefault();
            if (this.classList.contains('selected')) {
                this.classList.remove('selected');
                this.classList.add('grey');
            } else {
                this.classList.add('selected');
                this.classList.remove('grey');
            }
            recalculate();
        });
    }

    oghamInputs.forEach(btnCheckboxInitializer);
    miraclesInputs.forEach(btnCheckboxInitializer);

    for (i = 0, l = domainsInputs.length; i < l; i++) {
        var baseDomainValue = getIntOrDie(domainsInputs[i].getAttribute('data-base'), 'Base domain value is invalid for domain '+domainsInputs[i].id);
        if (baseDomainValue < 0 || baseDomainValue > 5) {
            throw 'Base domain value is out of range for domain '+domainsInputs[i].id;
        }
        baseDomainsValues[domainsInputs[i].id] = baseDomainValue;

        domainsInputs[i].addEventListener('change', function () {
            recalculate();
        });
    }
    for (i = 0, l = disciplinesInputs.length; i < l; i++) {
        var baseDisciplineValue = getIntOrDie(disciplinesInputs[i].getAttribute('data-base'), 'Base discipline value is invalid for discipline '+disciplinesInputs[i].id);
        if (baseDisciplineValue < 0 || baseDisciplineValue > 10) {
            throw 'Base discipline value is out of range for discipline '+disciplinesInputs[i].id;
        }
        baseDisciplinesValues[disciplinesInputs[i].id] = baseDisciplineValue;

        disciplinesInputs[i].addEventListener('change', function () {
            recalculate();
        });
    }

    function recalculate() {
        var spentXp = 0, currentlySpent;

        currentlySpent = spendDefense(spentXp);
        if (false === currentlySpent) {
            return;
        }
        spentXp += currentlySpent;

        currentlySpent = spendSpeed(spentXp);
        if (false === currentlySpent) {
            return;
        }
        spentXp += currentlySpent;

        currentlySpent = spendDomains(spentXp);
        if (false === currentlySpent) {
            return;
        }
        spentXp += currentlySpent;

        currentlySpent = spendDisciplines(spentXp);
        if (false === currentlySpent) {
            return;
        }
        spentXp += currentlySpent;

        currentlySpent = spendOgham(spentXp);
        if (false === currentlySpent) {
            return;
        }
        spentXp += currentlySpent;

        currentlySpent = spendMiracles(spentXp);
        if (false === currentlySpent) {
            return;
        }
        spentXp += currentlySpent;

        if (spentXp === 0) {
            xpElement.innerText = baseXp.toString();
        } else {
            xpElement.innerHTML =
                baseXp.toString() +
                ' - <span class="teal-text text-lighten-2">' +
                spentXp.toString() +
                '</span> = ' +
                (baseXp - spentXp).toString()
            ;
        }
    }

    function spendDefense(totalCurrentlySpent) {
        const currentDefense = getIntOrDie(defenseInput.value, 'Invalid defense value.');

        highlightInput(currentDefense, baseDefense, defenseInput);

        if (baseDefense === 5 || baseDefense === currentDefense) {
            // Can't spent XP then
            return 0;
        }

        if (currentDefense < baseDefense || currentDefense > 10) {
            throw 'Defense score is out of range.';
        }

        const spentForDefense = spendSpeedOrDefenseXP(baseDefense, currentDefense);

        if (hasSpentToMuchXp(totalCurrentlySpent, spentForDefense, defenseInput)) {
            return false;
        }

        return spentForDefense;
    }

    function spendSpeed(totalCurrentlySpent) {
        const currentSpeed = getIntOrDie(speedInput.value, 'Invalid speed value.');

        highlightInput(currentSpeed, baseSpeed, speedInput);

        if (baseSpeed === 5 || baseSpeed === currentSpeed) {
            // Can't spent XP then
            return 0;
        }

        if (currentSpeed < baseSpeed || currentSpeed > 5) {
            throw 'Speed score is out of range.';
        }

        const spentForSpeed = spendSpeedOrDefenseXP(baseSpeed, currentSpeed);

        if (hasSpentToMuchXp(totalCurrentlySpent, spentForSpeed, speedInput)) {
            return false;
        }

        return spentForSpeed;
    }

    function spendOgham(totalCurrentlySpent) {
        var i, l;

        var spentXp = 0;

        for (i = 0, l = oghamInputs.length; i < l; i++) {
            var input = oghamInputs[i];
            var button = input.parentElement;

            if (button.classList.contains('disabled')) {
                continue;
            }

            if (
                !button.classList.contains('selected')
                || (totalCurrentlySpent + spentXp + 5) > baseXp
            ) {
                button.classList.remove('selected');
                button.classList.add('grey');
                input.checked = false;
                continue;
            }

            spentXp += 5;
            input.checked = true;
        }

        return spentXp;
    }

    function spendMiracles(totalCurrentlySpent) {
        var i, l;

        var spentXp = 0;

        for (i = 0, l = miraclesInputs.length; i < l; i++) {
            var input = miraclesInputs[i];
            var button = input.parentElement;

            if (button.classList.contains('disabled')) {
                continue;
            }

            if (
                !button.classList.contains('selected')
                || (totalCurrentlySpent + spentXp + 5) > baseXp
            ) {
                button.classList.remove('selected');
                button.classList.add('grey');
                input.checked = false;
                continue;
            }

            spentXp += 5;
            input.checked = true;
        }

        return spentXp;
    }

    function spendDomains(totalCurrentlySpent) {
        var i, l;

        var spentXp = 0;

        for (i = 0, l = domainsInputs.length; i < l; i++) {
            var input = domainsInputs[i];
            var currentDomainValue = getIntOrDie(input.value, 'Current domain value is invalid for domain '+input.id);

            highlightInput(currentDomainValue, baseDomainsValues[input.id], input);

            if (currentDomainValue === 0) {
                continue;
            }
            const spentForDomain = spendDomain(input.id, currentDomainValue);
            if (hasSpentToMuchXp(totalCurrentlySpent, spentForDomain, input)) {
                return false;
            }
            totalCurrentlySpent += spentForDomain;
            spentXp += spentForDomain;

            const disciplineContainer = document.querySelector('.disciplines-container[data-domain="'+input.getAttribute('data-domain')+'"]');

            if (!disciplineContainer) {
                throw 'Discipline container is invalid. Perhaps the "data-domain" property was removed?';
            }

            disciplineContainer.style.display =
                currentDomainValue === 5
                ? 'block'
                : 'none'
            ;
        }

        return spentXp;
    }

    function spendDomain(domainId, currentDomainValue) {
        var baseValue = baseDomainsValues[domainId];

        if (baseValue === 5) {
            // Don't spent XP if base value is already 5
            return 0;
        }

        if (currentDomainValue < baseValue || currentDomainValue > 5) {
            throw 'Current domain value is out of range for domain '+domainId;
        }

        // Book 1 p229: domain bonus = 10 XP per point
        return (currentDomainValue - baseValue) * 10;
    }

    function spendDisciplines(totalCurrentlySpent) {
        var i, l;

        var spentXp = 0;

        for (i = 0, l = disciplinesInputs.length; i < l; i++) {
            var input = disciplinesInputs[i];
            var currentDisciplineValue = getIntOrDie(input.value, 'Current discipline value is invalid for discipline '+input.id);

            highlightInput(currentDisciplineValue, baseDisciplinesValues[input.id], input);

            if (currentDisciplineValue === 0) {
                continue;
            }
            const spentForDiscipline = spendDiscipline(input.id, currentDisciplineValue);
            if (hasSpentToMuchXp(totalCurrentlySpent, spentForDiscipline, input)) {
                return false;
            }
            totalCurrentlySpent += spentForDiscipline;
            spentXp += spentForDiscipline;
        }

        return spentXp;
    }

    function spendDiscipline(disciplineId, currentDisciplineValue) {
        var baseValue = baseDisciplinesValues[disciplineId];

        if (baseValue === 10) {
            // Don't spent XP if base value is already 10
            return 0;
        }

        if (currentDisciplineValue < baseValue || currentDisciplineValue > 10) {
            throw 'Current discipline value is out of range for discipline '+disciplineId;
        }

        var cost = 0;
        for (var i = (baseValue+1); i <= currentDisciplineValue; i++) {
            // Book 1 p229:
            // Disciplines from  6 to 10 = 25 XP (20 with mentor, but not supported yet)
            // Disciplines from 11 to 15 = 40 XP (30 with mentor, but not supported yet)
            if (i <= 5) {
                cost += 25;
            } else if (i > 5 && i <= 10) {
                cost += 40;
            } else {
                throw 'Discipline score is '+i+' and this is not supposed to happen.';
            }
        }

        return cost;
    }

    function spendSpeedOrDefenseXP(base, current) {
        var spentXp = 0;

        for (i = base; i <= current; i++) {
            if (i === 0) {
                continue;
            }
            if (i <= 5) {
                spentXp += (i * 5) + 5;
            } else {
                spentXp += 30;
            }
        }

        return spentXp;
    }

    function highlightInput(currentValue, baseValue, input) {
        if (currentValue > baseValue) {
            input.parentElement.parentElement.classList.add('active');
        } else {
            input.parentElement.parentElement.classList.remove('active');
        }
    }

    function hasSpentToMuchXp(total, justSpent, input) {
        if ((total + justSpent) > baseXp) {
            input.value--;

            return true;
        }

        return false;
    }

    function getIntOrDie(value, message) {
        value = parseInt(value, 10);

        if (isNaN(value)) {
            throw message;
        }

        return value;
    }

    //
    // Execute it at least once at page load,
    // this will make sure inaccessible disciplines are not displayed.
    recalculate();
})(window, document);
