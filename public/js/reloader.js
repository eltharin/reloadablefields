JR.events.add( 'reload','.reloadable', function(e) {
    let request = new XMLHttpRequest();
    let item = this;
    request.open('GET', item.dataset.reloadUrl, true);

    request.onload = function() {
        if (this.status >= 200 && this.status < 400) {
            // Success!
            let resp = this.response;
            item.innerHTML = resp;
            if(e.detail.onReload !== undefined)
            {
                e.detail.onReload.call(item , item, this);
            }
        }
    };

    request.send();
});

JR.events.add( 'click','.reloader', function() {
    JR.events.dispatch('reload', '#' + this.dataset.target);
});

JR.events.add('onFormSubmitSuccess','.addAndReload',  function(event)
{
    var textToSearch = "";
    if(this.dataset.formfield !== undefined)
    {
        textToSearch = event.detail.formData.get(this.dataset.formfield) || "";
    }

    JR.events.dispatch('reload', '#' + this.dataset.target, { "detail": {onReload : function (select,httprequest) {
        if(textToSearch != "")
        {
            const optionToSelect = Array.from(select.options).find(item => item.text === textToSearch);
            optionToSelect.selected = true;
        }
    }}});
});