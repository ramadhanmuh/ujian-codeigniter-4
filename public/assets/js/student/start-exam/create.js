$(document).ready(function(){
    var endTime = new Date($('#timer').data('endtime')).getTime();

    var x = setInterval(function() {

        var now = new Date().getTime();
        var distance = endTime - now;

        if (distance < 0) {
            clearInterval(x);
            $('#timer').html('Waktu Habis');
            return;
        }
        
        var hours   = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        $('#timer').html(
            'Sisa Waktu : ' + hours + ' jam ' +
            minutes + ' menit ' + seconds + ' detik'
        );

    }, 1000);

});