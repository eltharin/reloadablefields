//-- create function for add event as $(document).on(eventName, elementSelector, handler) in jQuery
if(typeof addEvent != 'function'){
   window.addEvent = function(eventName, elementSelector, handler)
{
    document.addEventListener(eventName, function(e) {
        for (var target = e.target; target && target != this; target = target.parentNode) {
            if (target.matches(elementSelector)) {
                handler.call(target, e);
                break;
            }
        }
    }, false);
};
}

addEvent('reload', '.reloadable', function() {
    let request = new XMLHttpRequest();
    let item = this;
    request.open('GET', item.dataset.reloadUrl, true);

    request.onload = function() {
        if (this.status >= 200 && this.status < 400) {
            // Success!
            let resp = this.response;
            item.innerHTML = resp;
        }
    };

    request.send();
});

addEvent('click', '.reloader', function() {
    let target = document.querySelector('#' + this.dataset.target);
    let event = new Event("reload", {"bubbles":true, "cancelable":false})
    target.dispatchEvent(event);
});