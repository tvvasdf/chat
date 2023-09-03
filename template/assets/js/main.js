$(() => {
    forms()
    main()
})

function forms() {
    $(document).on('submit', '[data-type=form]', function (e) {
        const
            obj = $(this),
            form = obj.data('form'),
            inputs = obj.find('input, textearea, select, [data-input]');
        let data = {form_name: form}
        e.preventDefault()
        if (typeof form == 'undefined') return
        inputs.each((index, element) => {
            let id = typeof $(element).attr('id') == 'undefined' ? $(element).attr('name') : $(element).attr('id')
            if (typeof id == 'undefined') id = index
            data[id] = $(element).val()
        })

        $.ajax({
            url: obj.attr('action'),
            method: 'post',
            dataType: 'json',
            data: data,
            success: function(r){
                if (r.success) {

                } else {
                    alert(r.message)
                }
            }
        })

    })
}

function main() {
    $(document).on('click', '[data-show-button]', function() {
        const
            obj = $(this),
            selector = obj.data('show-button'),
            show = $(`[data-show-container="${selector}"]`),
            buttons = $('[data-show-button]'),
            containers = $('[data-show-container]');

        containers.each(function (index, element) {
            if ($(element).data('show-container') !== show) {
                $(element).attr('hidden', 'hidden')
            }
        })

        show.removeAttr('hidden')
        buttons.removeClass('active')
        obj.addClass('active')
    })

    $(document).on('click', '[data-lobby-id]', function () {
        const
            obj = $(this),
            id = obj.data('lobby-id');

        $.ajax({
            url: window.location.pathname,
            method: 'get',
            dataType: 'html',
            data: {lobby_id: id},
            success: function(r){
                replace(r)
            }
        })

    })
}

function replace(r) {
    const replace = $('[data-replace]');

    replace.each((index, element) => {
        const data = $(element).data('replace');
        $(element).replaceWith($(r).find(`[data-replace="${data}"]`))
    })
}