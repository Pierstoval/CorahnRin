"use strict";

(function($, d){
    var i, l;

    /**
     * Once an element has "data-onchangesubmit" attribute, its "change" event triggers submit in parent form.
     */
    if (d.querySelector('[data-onchangesubmit]')) {
        var changesubmitNodesList = d.querySelectorAll('[data-onchangesubmit]');
        for (i = 0, l = changesubmitNodesList.length; i < l; i++) {
            changesubmitNodesList[i].addEventListener('change', function(){
                var a = this.getAttribute('data-onchangesubmit'),
                    form;
                if (a !== 'false' && a !== '0') {
                    form = this.form;
                    if (form) {
                        form.submit();
                    } else {
                        console.warn('Tried to set onchangesubmit on an element that do not have a form as a parent.', this);
                    }
                }
                return false;
            });
        }
    }

    /**
     * Each "toggle increment" element is able to increment a value in an input or in an HTML tag.
     * "ti" stands for "toggle increment".
     *
     * @param data-ti-target-node    Node that will receive the output value.
     * @param data-ti-increment      Allows specifying the increment value (default: +1). Careful: we use "parseInt" for it.
     * @param data-ti-increment-max  Max value for this node.
     * @param data-ti-increment-min  Min value for this node.
     * @param data-ti-use-html       Use .innerHTML rather than .value when outputting the incremented value.
     * @param data-ti-sum-max        Get the sum of all elements in "data-ti-sum-selector", and checks that it does not reach the "sum-max".
     * @param data-ti-sum-selector   Used conjointedly with "data-ti-sum-max".
     */
    if (d.querySelector('[data-toggle-increment]')) {
        var incrementNodesList = d.querySelectorAll('[data-toggle-increment]');
        for (i = 0, l = incrementNodesList.length; i < l; i++) {
            incrementNodesList[i].addEventListener('click', function(e){
                var target      = this.getAttribute('data-ti-target-node'),
                    max         = this.getAttribute('data-ti-increment-max'),
                    min         = this.getAttribute('data-ti-increment-min'),
                    sumMax      = parseInt(this.getAttribute('data-ti-sum-max')),
                    sumSelector = this.getAttribute('data-ti-sum-selector'),
                    sumOutput   = this.getAttribute('data-ti-sum-output'),
                    sumOutHtml  = this.hasAttribute('data-ti-sum-output-html'),
                    sum         = 0,
                    useHtml     = this.hasAttribute('data-ti-use-html'),
                    increment   = parseInt(this.getAttribute('data-ti-increment')),
                    value       = parseInt(useHtml ? d.getElementById(target).innerHTML : d.getElementById(target).value),
                    sumOutputElement,
                    i, l, c, j // Only loop vars
                ;

                // Fix parameters NaN values
                if (isNaN(sumMax))    { sumMax = null; }
                if (isNaN(max))       { max = null; }
                if (isNaN(min))       { min = null; }
                if (isNaN(value))     { value = null !== min ? min : 0; }
                if (isNaN(increment)) { increment = 1; }

                if (null === sumMax) {
                    // Increment normally
                    value += increment;
                } else {
                    // If we have sumMax specified, there's another behavior
                    if (!sumSelector) {
                        console.error('When using data-ti-sum-max, a data-ti-sum-selector must be provided.');
                        return false;
                    }

                    // We loop through all nodes in the sumSelector and increment the sum value,
                    //   based on what we have in the node itself.
                    // We check according to the "useHtml" parameter.
                    // Any erroneous value is converted to zero.
                    for (i = 0, l = d.querySelectorAll(sumSelector), c = l.length; i < c; i++) {
                        if (useHtml) {
                            j = parseInt(l[i].innerHTML);
                        } else {
                            j = parseInt(l[i].value);
                        }
                        if (isNaN(j)) {
                            console.warn('When calculating the sum, a node has a wrong value (but we converted it to zero in case of).', l[i]);
                            j = 0;
                        }
                        sum += j;
                    }

                    // Increment only when sure it does not reach the sumMax requirement.
                    if (sum + increment <= sumMax) {
                        value += increment;
                    }
                }

                // Force value to be max or min if it is out of range.
                if (null !== max && value > max) { value = max; }
                if (null !== min && value < min) { value = min; }

                if (useHtml) {
                    d.getElementById(target).innerHTML = value.toString();
                } else {
                    d.getElementById(target).value = value;
                }

                if (sumOutput && sumSelector) {
                    // Recalculate sum at the end of all calculation for display purpose
                    sum = 0;

                    for (i = 0, l = d.querySelectorAll(sumSelector), c = l.length; i < c; i++) {
                        if (useHtml) {
                            j = parseInt(l[i].innerHTML);
                        } else {
                            j = parseInt(l[i].value);
                        }
                        if (isNaN(j)) {
                            console.warn('When calculating the sum, a node has a wrong value (but we converted it to zero in case of).', l[i]);
                            j = 0;
                        }
                        sum += j;
                    }

                    sumOutputElement = d.getElementById(sumOutput);

                    if (sumOutHtml) {
                        sumOutputElement.innerHTML = sum.toString();
                    } else {
                        sumOutputElement.value = sum;
                    }

                    if (sum === sumMax) {
                        sumOutputElement.classList.remove('red');
                        sumOutputElement.classList.add('green');
                    } else {
                        sumOutputElement.classList.remove('green');
                        sumOutputElement.classList.add('red');
                    }
                }

                e.preventDefault();
                return false;
            });
        }
    }

    /**
     * Activate generator divchoice component (div as "checkboxes")
     */
    if (d.querySelector('.gen-div-choice')) {
        var divChoiceNodesList = d.querySelectorAll('.gen-div-choice');
        for (i = 0, l = divChoiceNodesList.length; i < l; i++) {
            divChoiceNodesList[i].addEventListener('click', function () {
                var nodes = d.getElementsByClassName('gen-div-choice'),
                    count = nodes.length,
                    node
                ;

                if (this.classList.contains('selected')) {
                    return false;
                }

                if (this.classList.contains('divchoice-button')) {
                    return false;
                }

                for (var i = 0; i < count; i++) {
                    nodes[i].classList.remove('selected');
                    if (nodes[i].getAttribute('data-divchoice-inside') === 'true') {
                        $(nodes[i].querySelector('.divchoice-inside')).slideUp(400);
                        for (var l = nodes[i].querySelectorAll('.divchoice-inside .btn'), c = l.length, j = 0; j < c; j++) {
                            l[j].classList.remove('active');
                            l[j].firstElementChild.checked = false;
                        }
                    }
                }

                this.classList.add('selected');

                if (this.getAttribute('data-target-node')) {
                    node = d.querySelector(this.getAttribute('data-target-node'));
                } else {
                    node = d.getElementById('gen-div-choice');
                }

                if (this.getAttribute('data-divchoice-inside') === 'true') {
                    $(this.querySelector('.divchoice-inside')).slideDown(400);
                }

                if (node) {
                    node.value = this.getAttribute('data-divchoice-value');
                }

                return false;
            });
        }
    }

    /**
     * Used in social classes:
     * Allows to select a specific number of checkboxes in a group of multiple inputs.
     * Processed with bootstrap's radio buttons.
     */
    if (d.querySelector('[data-max-buttons] label.btn')) {
        $('[data-max-buttons] label.btn')
            .each(function() {
                // Set JS data to avoid reprocessing all parameters
                var $this = $(this);

                var parent = $this.data('jq_parent');
                if (!parent) {
                    parent = $this.parents('[data-max-buttons]')[0];
                    $this.data('jq_parent', parent);
                }

                var max = $this.data('jq_max_number');
                if (!max) {
                    max = parseInt(parent.getAttribute('data-max-buttons'));
                    if (isNaN(max)) { max = 1; }
                    $this.data('jq_max_number', max);
                }
            })
            .on('click', function(e){
                // Remove other active labels for this group of inputs
                var $this = $(this);
                var max = $this.data('jq_max_number');

                // Disable activated elements if count is superior to maximum value
                var elements = $this.data('jq_parent').querySelectorAll('label.active');
                if (elements.length >= (max-1)) {
                    for (var i = max, c = elements.length; i < c ; i++) {
                        elements[i].classList.remove('active');
                        elements[i].querySelector('input').checked = false;
                    }
                }

                if (this.classList.contains('active')) {
                    this.classList.remove('active');
                    this.querySelector('input').checked = false;
                } else if (elements.length < max) {
                    // Activate current label
                    this.classList.add('active');
                    this.querySelector('input').checked = true;
                }

                e.stopPropagation();

                return false;
            })
        ;
    }

    /**
     * Activates generator choice button with potential info modal (used un jobs, traits...).
     */
    if (d.querySelector('[data-toggle="btn-gen-choice"]')) {
        var btnGenChoiceList = d.querySelectorAll('[data-toggle="btn-gen-choice"]');
        for (i = 0, l = btnGenChoiceList.length; i < l; i++) {
            btnGenChoiceList[i].addEventListener('click', function (event) {
                var tagName = event.target.nodeName.toLowerCase();

                // Do nothing if we clicked on the image or its link.
                // This behavior allows clicking on any part, and not only on the span link
                if (tagName === 'img' || tagName === 'a') {
                    return true;
                }

                var node,
                    count,
                    i,
                    e = this,
                    selectorForActiveGenChoices = '[data-toggle="btn-gen-choice"].active'
                ;

                if (e.getAttribute('data-group')) {
                    selectorForActiveGenChoices += '[data-group="' + e.getAttribute('data-group') + '"]';
                }
                node = d.querySelectorAll(selectorForActiveGenChoices);
                count = node.length;
                for (i = 0; i < count; i++) {
                    node[i].classList.remove('active');
                }
                this.classList.add('active');
                d.getElementById(e.getAttribute('data-target-node')).value = e.getAttribute('data-input-value');
            });
        }
    }

    /**
     * Clic distant sur un élément
     */
    $('[data-dist-click]').on('click', function(){
        $('#'+this.getAttribute('data-target-node')).click();
    });
})(jQuery, document, window);
