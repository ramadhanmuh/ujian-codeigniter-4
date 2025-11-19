function getInput() {
    return {
        orderBy: $('#order').find(':selected').data('order'),
        direction: $('#order').find(':selected').data('direction'),
        examId: $('#exam_id').find(':selected').val(),
        keyword: $('#keyword').val()
    }
}

function setInputToURL(url, input) {
    url += '?orderBy=' + input.orderBy + '&direction=' + input.direction

    if (input.examId !== '') {
        url += '&exam_id=' + input.examId
    }

    if (input.keyword !== '') {
        url += '&keyword=' + input.keyword
    }

    return url
}

$('#order').change(function () {
    document.location.href = setInputToURL($(this).data('url'), getInput())
})

$('#exam_id').change(function () {
    document.location.href = setInputToURL($(this).data('url'), getInput())
})