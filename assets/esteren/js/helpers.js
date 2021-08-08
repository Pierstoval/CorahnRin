// Here only some polyfills and helpers useful for customization

/**
 * Re-implementation of toggleClass method inspired by jQuery.
 * Toggles one single class at a time.
 *
 * @param toggledClass
 * @returns {Element}
 */
Element.prototype.toggleClass = function (toggledClass) {
    var classes = this.className.toString().trim();

    toggledClass = toggledClass.trim();

    if (classes.match(toggledClass)) {
        this.className = classes.replace(toggledClass, '');
    } else {
        this.className = classes + toggledClass;
    }

    return this;
};

if (!String.prototype.trim) {
    // .trim() for all string objects
    console.warn('Applied .trim() polyfill.');
    String.prototype.trim = function(){
        return this.replace(/^\s+|\s+$/g, '');
    };
}
