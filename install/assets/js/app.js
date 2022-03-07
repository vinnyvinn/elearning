/*!
* App.js
* Version 1.0 - built Fri, Feb 1st 2019, 05:05 pm
* https://simcycreative.com
* Simcy Creative - <hello@simcycreative.com>
* Private License
*/

/*
 * Start jQuery
 */
$(document).ready(function() {

    /*
     * Tooltip
     */
    $('[data-toggle="tooltip"]').tooltip();


    /*
     * humbager
     */
    $(".humbager, .close-menu").click(function(event) {
        event.preventDefault();
        var menu = $("header nav");
        if (menu.hasClass("open")) {
            menu.removeClass("open");
        } else {
            menu.addClass("open");
        }
    });

});



/*
 * auth page switch pages
 */
$(".auth-switch").click(function(event){
	event.preventDefault();
	$(".register, .forgot, .reset, .login").hide();
	$($(this).attr("show")).show();
});

/**
 * Active Links
 */
$('nav.navigation a[href="'+window.location+'"]').parent().addClass('active');

/**
 * Disable values
 */
$('option[value="0"]').attr('disabled',true);

$(document).ready(function() {
    $('#datatable').DataTable({
        dom: 'Bfrtip',
        buttons: [{
            extend: 'copyHtml5',
            exportOptions: {
                columns: [ 0, 1, 2, 3, 4, 5, 6 ]
            }
        },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                }
            }
        ]
    });
    $('#b2c').DataTable({
        dom: 'Bfrtip',
        buttons: [{
            extend: 'copyHtml5',
            exportOptions: {
                columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
            }
        },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                }
            }
        ]
    });
    $('#customers').DataTable({
        dom: 'Bfrtip',
        buttons: [{
            extend: 'copyHtml5',
            exportOptions: {
                columns: [ 0, 1, 2, 3 ]
            }
        },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3 ]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3 ]
                }
            }
        ]
    });
});

$('#datatable').on('change', function(e){
    $('#datatable').DataTable({
        dom: 'Bfrtip',
        buttons: [{
            extend: 'copyHtml5',
            exportOptions: {
                columns: [ 0, 1, 2, 3, 4, 5, 6 ]
            }
        },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                }
            }
        ]
    });
})
$('#b2c').on('change', function(e){
    $('#b2c').DataTable({
        dom: 'Bfrtip',
        buttons: [{
            extend: 'copyHtml5',
            exportOptions: {
                columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
            }
        },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                }
            }
        ]
    });
})