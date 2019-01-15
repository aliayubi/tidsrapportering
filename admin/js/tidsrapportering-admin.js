var $ = jQuery.noConflict();
$(document).ready(function () {
    $('.date_time_report .time').timepicker({
        'showDuration': true,
        'timeFormat': 'G:i'
    });

    $('.date_time_report .date').datepicker({
        'format': 'yyyy-m-d',
        'autoclose': true
    });

$('.add_report').submit(function (e) { 
    e.preventDefault();
    var datastring = $(this).serialize();
    $.ajax({
        type: "post",
        data: datastring,
        url: "/wp-admin/admin-ajax.php?action=submit_report",
        success: function (response) {
            console.log(response);
            $("<p style='font-weight:bold;'>Tidsrapportering har läggt till</p>").appendTo(".add_report .success");
        }
    }); 
});

// $('.get_reports').submit(function (e) { 
//     e.preventDefault();
//     $.ajax({
//         type: "get",
//         url: "/wp-admin/admin-ajax.php?action=get_reports",
//         success: function (response) {
//             console.log(response);
//         }
//     }); 
// });
});
