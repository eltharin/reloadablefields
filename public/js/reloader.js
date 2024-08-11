JR.events.add( 'click','.reload_button', function() {
    JR.events.dispatch('reload', '#' + this.dataset.target);
});

JR.events.add( 'reload','.reloadable', function(e) {
    (async function(){
        const html = await reloadForm(document.querySelector('form'));
        e.target.innerHTML = html.getElementById(e.target.id).innerHTML
        if(e.detail.onReload !== undefined)
        {
            e.detail.onReload.call(e, e.target , html);
        }
    })();
});

JR.events.add('onFormSubmitSuccess','.addAndReload',  function(event)
{
    var textToSearch = "";
    if(this.dataset.formfield !== undefined)
    {
        textToSearch = event.detail.formData.get(this.dataset.formfield) || "";
    }

    JR.events.dispatch('reload', '#' + this.dataset.target, {
        "detail": {
            onReload : function (select,html) {
                if(textToSearch != "")
                {
                    const optionToSelect = Array.from(select.options).find(item => item.text === textToSearch);
                    optionToSelect.selected = true;
                }
            }
        }
    });
});


const reloadForm = async (form) =>
{
    const data = new URLSearchParams();
    for (const pair of new FormData(form)) {
        data.append(pair[0], pair[1]);
    }


    const req = await fetch(form.getAttribute('action') || document.location, {
        method: form.getAttribute('method') || 'GET',
        body: data,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'charset': 'utf-8',
            'X-reload-form': '1'
        }
    });

    const text = await req.text();
    const parser = new DOMParser();
    return parser.parseFromString(text, 'text/html');
}