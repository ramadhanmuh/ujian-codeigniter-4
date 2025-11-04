$(document).ready(function () {
    var listURL = $('#question-list-column').data('url');

    var orderBy = $('#order').find(':selected').data('order');

    var direction = $('#order').find(':selected').data('direction')

    var examId = $('#exam_id').find(':selected').val();
    
    var keyword = $('#keyword').val();
    
    function getData() {
        $.ajax({
            url: listURL,
            type: 'GET',
            data: {
                orderBy: orderBy,
                direction: direction,
                exam_id: examId,
                keyword: keyword
            },
            dataType: 'json',
            success: function (response) {
                $('#loader').addClass('d-none')
                $('#loader').removeClass('d-flex')

                if (response.records.length === 0) {
                    $('#question-list-column').html('<div class="text-center">Data tidak ditemukan.<div>')
                }

                setURLToCreateButton()
            },
            error: function (xhr, status, error) {
                console.log(error)
            }
        })   
    }

    function setURLToCreateButton() {
        $('#createButton').attr(
            'href',
            $('#createButton').data('link') + '?exam_id=' + examId
        )
    }

    getData()
})