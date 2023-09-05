$(() => {
    forms()
    main()
    chat()
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
                    document.querySelector(`[data-form=${form}]`).reset()
                }
                if (r.message) {
                    alert(r.message)
                }
                if (r.modal) {
                    //open modal r.modal
                }
                if (r.redirect) {
                    window.location.href = r.redirect
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

    $(document).on('click', '[data-append-button]', function () {
        const
            obj = $(this),
            selector = obj.data('append-button'),
            container = $(`[data-append-container="${selector}"]`),
            elements = container.find(`[data-append-element="${selector}"]`);
        if (typeof elements[0] !== undefined) {
            const element = $(elements[0]).clone()
            element.attr('id',  selector + (elements.length + 1))
            element.val('')
            element.appendTo(container)
        }
    })
}

function chat() {
    $(document).on('click', '[data-lobby-id]', function () {
        const
            obj = $(this),
            buttons = $('[data-lobby-id]'),
            id = obj.data('lobby-id');

        $.ajax({
            url: window.location.pathname,
            method: 'get',
            dataType: 'html',
            data: {lobby_id: id},
            success: function(r){
                replace(r)
                buttons.each((index, button) => {
                    $(button).removeClass('active')
                })
                obj.addClass('active')
            }
        })

    })

    $('textarea#message')
        .on('input', function(){
            const
                defaultHeight = 42,
                defaultScroll = 51,
                maxHeight = 144,
                scrollHeight = this.scrollHeight

            if (scrollHeight < maxHeight) {
                this.style.overflowY = 'hidden'
                if (defaultHeight < scrollHeight && defaultScroll !== scrollHeight) {
                    this.style.height = this.scrollHeight + 'px'
                } else {
                    this.style.height = defaultHeight + 'px'
                }
            } else {
                this.style.height = maxHeight + 'px'
                this.style.overflowY = 'auto'
            }
        })
        .keydown(function (key) {
            const
                form = $(this).closest('form'),
                ENTER = 13,
                defaultHeight = 42
            let keyCode = key.keyCode || key.which
            if (keyCode !== ENTER) return;
            if (!key.shiftKey) {
                key.preventDefault()
                form.submit()
                form.trigger('reset')
                this.style.height = defaultHeight + 'px'
                this.style.overflowY = 'hidden'
            }
        })
}

function replace(r) {
    const replace = $('[data-replace]');

    replace.each((index, element) => {
        const data = $(element).data('replace');
        $(element).replaceWith($(r).find(`[data-replace="${data}"]`))
    })
}