(function ($, d) {
    var i, l;
    var buttons  = d.querySelectorAll('.domain button.domain-change:not(.disabled)');
    var domainsButtons = {};
    var domainsInputs = {};
    var remainingPointsDiv = d.getElementById('remaining_bonus_points');

    var maxBonusPoints = parseInt(d.getElementById('max_bonus_points').innerHTML, 10);
    if (isNaN(maxBonusPoints)) {
        throw 'Bonus points are not valid.';
    }

    /**
     * Calculates number of spent points, update UI to show the value, and return it.
     */
    function calculateTotalBonusPoints() {
        var i, l;
        var remainingPoints = maxBonusPoints;

        for (i = 0, l = buttons.length; i < l; i++) {
            var button = buttons[i];
            var isActive = button.classList.contains('active');
            var domainId = button.getAttribute('data-domain-id');

            if (domainsInputs[domainId].value === '1') {
                if (isActive && button._is_domain_base === false) {
                    remainingPoints--;
                }
                domainsButtons[domainId].bonus.parentElement.parentElement.classList.add('active');
                domainsButtons[domainId].bonus.classList.add('active');
                domainsButtons[domainId].base.classList.remove('active');
            } else {
                domainsButtons[domainId].bonus.parentElement.parentElement.classList.remove('active');
                domainsButtons[domainId].bonus.classList.remove('active');
                domainsButtons[domainId].base.classList.add('active');
            }
        }

        remainingPointsDiv.innerHTML = remainingPoints.toString();
    }

    for (i = 0, l = buttons.length; i < l; i++) {
        var button = buttons[i];
        var id = button.getAttribute('data-domain-id');

        if (domainsButtons[id]) {
            domainsButtons[id].bonus = button;
            button._is_domain_base = false;
        } else {
            domainsButtons[id] = {
                base: button,
                bonus: null
            };
            button._is_domain_base = true;
        }

        domainsInputs[id] = d.querySelector('input[type="hidden"][id="domains_'+id+'"]');

        button.addEventListener('click', function(){
            var isActive = this.classList.contains('active');
            var domainId = this.getAttribute('data-domain-id');
            var remainingPoints = parseInt(remainingPointsDiv.innerHTML, 10);

            if (isNaN(remainingPoints)) {
                throw 'Invalid remaining points value. Please refresh the page.';
            }

            if (isActive) {
                return false;
            }

            if (true === this._is_domain_base) {
                // Set "0" for base value
                domainsInputs[domainId].value = '0';
            } else if (false === this._is_domain_base) {
                // And set "1" for bonus value, but only if we have remaining points.
                if (remainingPoints > 0) {
                    domainsInputs[domainId].value = '1';
                }
            } else {
                throw 'Invalid domain base value for button. Please refresh the page.';
            }

            if (remainingPoints > 0 || true === this._is_domain_base) {
                this.classList.add('active');
            }

            calculateTotalBonusPoints();
        });
    }

    calculateTotalBonusPoints();

})(jQuery, document, window);
