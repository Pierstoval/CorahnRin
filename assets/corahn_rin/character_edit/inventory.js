(function($, d){
    var i, l, itemsButtons, treasuresButtons, itemsInputs, treasuresInputs;

    itemsButtons = d.querySelectorAll('#items_container a.equipment_add,#items_container a.equipment_remove');
    treasuresButtons = d.querySelectorAll('#treasures_container a.equipment_add,#treasures_container a.equipment_remove');

    itemsInputs = d.querySelectorAll('#items_container input[name^="character_edit[inventory][items]"]');
    treasuresInputs = d.querySelectorAll('#treasures_container input[name^="character_edit[inventory][preciousObjects]"]');

    for (i = 0, l = itemsInputs.length; i < l; i++) {
        addEventListenersToInput(itemsInputs[i], 'items_container');
    }
    for (i = 0, l = treasuresInputs.length; i < l; i++) {
        addEventListenersToInput(treasuresInputs[i], 'treasures_container');
    }
    for (i = 0, l = itemsButtons.length; i < l; i++) {
        addEventListenersToButton(itemsButtons[i], 'items_container');
    }
    for (i = 0, l = treasuresButtons.length; i < l; i++) {
        addEventListenersToButton(treasuresButtons[i], 'treasures_container');
    }

    function addEventListenersToInput(input, containerId) {
        var addButton = d.getElementById(containerId).querySelector('.equipment_add');

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

        input.addEventListener('blur', function(event) {
            if (!event.target.value.trim()) {
                // 3 parentElement calls because we have something like
                // div.row > div.input-field > input
                input.parentElement.parentElement.parentElement.removeChild(input.parentElement.parentElement);
            }
        });
    }

    function addEventListenersToButton(button, containerId) {
        var container = d.getElementById(containerId);

        button.addEventListener('click', function(){
            var input, inputField, newElement, rowsContainer;

            newElement = d.createElement('div');
            newElement.className = 'row';

            inputField = d.createElement('div');
            inputField.className = 'input-field col s12';

            input = d.createElement('input');
            input.type = 'text';

            if (containerId === 'items_container') {
                input.name = 'character_edit[inventory][items][]';
                rowsContainer = d.getElementById('character_edit_inventory_items');
            } else if (containerId === 'treasures_container') {
                input.name = 'character_edit[inventory][preciousObjects][]';
                rowsContainer = d.getElementById('character_edit_inventory_preciousObjects');
            }

            rowsContainer.appendChild(newElement);
            newElement.appendChild(inputField);
            inputField.appendChild(input);

            addEventListenersToInput(input, containerId);
            input.focus();
        });
    }
})(jQuery, window.document);
