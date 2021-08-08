(function($, w){
    var i, addButton, existingInputs, l,
        d = w.document,
        equipmentPanel = d.getElementById('equipment_panel')
    ;

    if  (!equipmentPanel) {
        throw 'Could not find equipment panel.';
    }

    addButton = equipmentPanel.querySelector('.equipment_add');

    existingInputs = equipmentPanel.querySelectorAll('input[name="equipment[]"]');

    l = existingInputs.length;

    if (l) {
        for (i = 0; i < l; i++) {
            addEventListenersToInput(existingInputs[i]);
        }
    }

    function addEventListenersToInput(input) {
        if (input.type !== 'text' && input.name !== 'equipment[]') {
            throw 'Invalid input to add event listeners to.';
        }

        input.addEventListener('keypress', function(event) {
            if (event.keyCode === 13) {
                if (event.target.value.trim()) {
                    // Click on the "Add" button if current input has a value
                    addButton.click();
                }

                // Stop default behavior when pressing "Enter"
                event.preventDefault();
            }
        });
    }

    equipmentPanel.addEventListener('click', function(event){
        var action, target, button, input, container, newElement, index, label, newButton, icon;

        target = event.target;

        if (target.classList.contains('equipment_remove') || target.parentElement.classList.contains('equipment_remove')) {
            action = 'remove';
            button = target.classList.contains('equipment_remove') ? target : target.parentElement;
        } else if (target.classList.contains('equipment_add') || target.parentElement.classList.contains('equipment_add')) {
            action = 'add';
            button = target.classList.contains('equipment_add') ? target : target.parentElement;
        }

        if (action !== 'add' && action !== 'remove') {
            return;
        }

        container = button.parentElement;

        if (!container.classList.contains('equipment')) {
            throw 'Invalid container for button';
        }

        if ('remove' === action) {
            if (equipmentPanel.querySelectorAll('div.equipment').length > 1) {
                container.parentElement.removeChild(container);
            }
            return;
        }

        newElement = container.cloneNode(true);

        // This will fix issues with materialize animations
        newButton = newElement.querySelector('.equipment_add');
        icon = newButton.querySelector('i.fa');
        newButton.innerHTML = '';
        newButton.appendChild(icon);

        input = newElement.querySelector('input[name="equipment[]"]');
        try {
            index = 1 + parseInt(input.id.replace(/\D+/gi, ''));
        } catch (e) {
            throw 'Invalid input inside container';
        }

        input.id = 'equipment_'+index;
        input.value = '';
        label = newElement.querySelector('label[for="equipment_'+(index-1)+'"]');
        if (!label) {
            throw 'Input label is invalid';
        }
        label.htmlFor = 'equipment_'+index;

        equipmentPanel.appendChild(newElement);

        addEventListenersToInput(input);
        input.focus();
    });
})(jQuery, window);
