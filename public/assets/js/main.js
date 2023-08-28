$(() => {
    forms()
})

function forms() {
    $(document).on('submit', '[data-type=form]', (e)=> {
        e.preventDefault()

        const
            obj = $(e.target),
            form = obj.data('form'),
            inputs = obj.find('input, textearea, select, [data-input]');
        let data = {}
        if (typeof form == 'undefined') return;

        inputs.each((index, element) => {
            console.log($(element).attr('id'))
            let id = typeof $(element).attr('id') == 'undefined' ? $(element).attr('name') : $(element).attr('id')
            if (typeof id == 'undefined') id = index
            data[id] = $(element).val()
        })

        //отправляем ajax (написать функцию для обработки форм)

        console.log(data);

    })
}