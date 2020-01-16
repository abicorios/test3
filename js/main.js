// let today=new Date();
// let str_today=today.toISOString().split('T')[0];
$(window).on("load resize ", function() {
    var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
    $('.tbl-header').css({'padding-right':scrollWidth});
}).resize();

$.ajax({
    url: 'api/list',
    dataType: 'json',
    success: function (data) {
        if(date.error)
            console.log(date.error);
        else {
            $.each(data.result, function (id, item) {
                $('tbody').append(`<tr><td>${item.name}</td><td>${item.charCode}</td><td id="${id}"></td></tr>`)
            });
        }
    }
});
function rates(this_date) {
    $.ajax({
        url: 'api/list',
        dataType: 'json',
        success: function (data) {
            if(data.error)
                console.log(data.error)
            else {
                $.each(data.result, function (id, item) {
                    $.ajax({
                        url: `api/valueID/${id}/date/${this_date}`,
                        dataType: 'json',
                        success: function (result) {
                            if(result.error)
                                console.log(result.error);
                            else
                                $(`#${result.result.valueID}`).text(result.result.rates[this_date]);
                        }
                    })
                })
            }
        }
    });
}
$.ajax({
    url:'api/min_date',
    dataType:'json',
    success: function (data) {
        console.log(data);
        if(data.error)
            console.log(data.error)
        else
            $('#date').attr('min',data.result.min_date)
    }
});
$.ajax({
    url:'api/max_date',
    dataType:'json',
    success: function (data) {
        console.log(data);
        if(data.error)
            console.log(data.error)
        else {
            $('#date').attr('max',data.result.max_date);
            $('#date').val(data.result.max_date);
            rates(data.result.max_date);
        }
    }
});
$('#date').change(function () {
    rates($('#date').val());
});
$.ajax({
    url:'api',
    dataType: 'json',
    success: function (data) {
        console.log(data);
    }
});