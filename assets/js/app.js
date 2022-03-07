$('document').ready(function () {
    //toastr
    toastr.options = {
        closeButton: !0,
        newestOnTop: !0,
        progressBar: !0,
        positionClass: "toast-top-right",
        preventDuplicates: !1,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        timeOut: "5000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "swing",
        showMethod: "slideDown",
        hideMethod: "slideUp"
    };

    //select2
    $('.select2').each(function () {
        $(this).select2({
            dropdownParent: $($(this).parent()).parent()
        });
    })

//click to send
    $("body").on("click", ".send-to-server-click", function (e) {
        e.preventDefault();
        var t = $(this);
        var i = t.attr("data"),
            n = t.attr("url"),
            s = !0,
            r = i.split("|"),
            i = {};
        if (r.forEach(function (e) {
            var t = e.split(":");
            i[t[0]] = t[1]
        }), "true" === t.attr("loader") ? s = !0 : "false" === t.attr("loader") && (s = !1), void 0 === t.attr("warning-title"))
        {
            console.log(i);
            server(
                {
                    url: n,
                    data: i,
                    loader: s
                })
        } else {
            var o = t.attr("warning-title"),
                a = "Continue";
            a = void 0 === t.attr("warning-button") ? "Continue" : t.attr("warning-button"), void 0 === t.attr("warning-message") ? message = "" : message = t.attr("warning-message"), swal(
                {
                    title: o,
                    text: message,
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonColor: "#007bff",
                    confirmButtonText: a,
                    closeOnConfirm: !1
                }).then((result) => {
                    if (result.value) {
                        server(
                            {
                                url: n,
                                data: i,
                                loader: s
                            })
                    }
                })
        }
    });

//submit
    $("body").on("submit", ".ajaxForm", function (e) {
        e.preventDefault();
        $('.modal').modal('hide');
        var t = !1;
        "true" === $(this).attr("loader") && (t = !0), $(this).parsley().validate(), $(this).parsley().isValid() && (t && showLoader(), $.ajax(
            {
                url: $(this).attr("action"),
                type: $(this).attr("method"),
                data: new FormData(this),
                contentType: !1,
                processData: !1,
                success: function (e) {
                    t && hideLoader(), serverResponse(e)
                },
                error: function (e, i, n) {
                    t && hideLoader(), toastr.error(n, "Oops!")
                }
            }))
    });
});

// server
function server(e) {
    var t = !0;
    (void 0 === e ? t = !1 : (void 0 === e.url && (console.error("URL is missing!"), t = !1), void 0 === e.data && (console.error("Data is missing!"), t = !1), void 0 === e.loader && (e.loader = !0)), t) && (e.loader && showLoader(), $.post(e.url, e.data).done(function (t) {
        e.loader && hideLoader(), serverResponse(t)
    }).fail(function (e) {
        toast('Error', e.responseText, 'error');
        hideLoader();
    }))
}
function ajaxRequest(e, callback) {
    var t = !0;
    (void 0 === e ? t = !1 : (void 0 === e.url && (console.error("URL is missing!"), t = !1), void 0 === e.data && (console.error("Data is missing!"), t = !1), void 0 === e.loader && (e.loader = !0)), t) && (e.loader && showLoader(), $.post(e.url, e.data).done(function (t) {
        e.loader && hideLoader(), callback(t)
    }).fail(function (e) {
        toast('Error', e.responseText, 'error');
        hideLoader();
    }))
}

//notify
function notify(title, message, type, button, advanced) {
    void 0 === type && (type = "info"), void 0 === button && (button = "Okay!"), void 0 === advanced ? swal(
    {
        title: title,
        text: message,
        type: type,
        showCancelButton: !1,
        confirmButtonColor: "#007bff",
        confirmButtonText: button,
        closeOnConfirm: !0
    }) : (void 0 === advanced.color && (advanced.color = "#007bff"), void 0 === advanced.showCancelButton && (advanced.showCancelButton = !1), void 0 === advanced.closeOnConfirm && (advanced.closeOnConfirm = !0), void 0 === advanced.callback ) ? swal(
    {
        title: title,
        text: message,
        type: type,
        showCancelButton: advanced.showCancelButton,
        confirmButtonColor: advanced.color,
        confirmButtonText: button,
        closeOnConfirm: advanced.closeOnConfirm
    }) : swal(
    {
        title: title,
        text: message,
        type: type,
        showCancelButton: advanced.showCancelButton,
        confirmButtonColor: advanced.color,
        confirmButtonText: button,
        closeOnConfirm: advanced.closeOnConfirm
    }).then(function () {
        eval(advanced.callback)
    })
}

function toast(title, message, status) {
    true && (swal.close(), "success" === status ? toastr.success(message, title) : "error" === status ? toastr.error(message, title) : "info" === status ? toastr.info(message, title) : "warning" === status && toastr.warning(message, title));
    swal.close();
}

//server response
function serverResponse(response) {
    void 0 === response.notify && (response.notify = !0), void 0 === response.notifyType && (response.notifyType = "swal"), void 0 === response.status && (response.status = "info"), void 0 === response.title && (response.title = "Hello!"), void 0 === response.buttonText && (response.buttonText = "Okay"), void 0 !== response.callback && void 0 === response.callbackTime && (response.notify && "toastr" !== response.notifyType ? response.notify && "swal" === response.notifyType && (response.callbackTime = "onconfirm") : response.callbackTime = "instant"), void 0 === response.showCancelButton && (response.showCancelButton = !1), void 0 === response.message && (response.message = ""), "instant" === response.callbackTime && void 0 !== response.callback && eval(response.callback), response.notify ? "swal" === response.notifyType ? "onconfirm" === response.callbackTime && void 0 !== response.callback ? swal(
        {
            title: response.title,
            text: response.message,
            type: response.status,
            showCancelButton: response.showCancelButton,
            confirmButtonColor: "#007bff",
            confirmButtonText: response.buttonText,
            closeOnConfirm: !0
        }).then(function () {
            eval(response.callback)
        }) : swal(
        {
            title: response.title,
            text: response.message,
            type: response.status,
            showCancelButton: response.showCancelButton,
            confirmButtonColor: "#007bff",
            confirmButtonText: response.buttonText,
            closeOnConfirm: !0
        }) : "toastr" === response.notifyType && (swal.close(), "success" === response.status ? toastr.success(response.message, response.title) : "error" === response.status ? toastr.error(response.message, response.title) : "info" === response.status ? toastr.info(response.message, response.title) : "warning" === response.status && toastr.warning(response.message, response.title)) : swal.close()
}

function printNode(element, name) {
    var doc = new jsPDF('p', 'pt', 'letter');
    var html = $(element).html();
    var canvas = doc.canvas;
    canvas.height = 72 * 11;
    canvas.width= 72 * 8.5;


    //doc.save(name+".pdf");
    html2pdf(html, doc, function(doc) {
        //doc.output('dataurlnewwindow');
        doc.save(name+".pdf");
    });
}

//loader
function showLoader(e) {
    hideLoader(), void 0 === e ? e = {
        color: "#007bff",
        background: "rgba(0,129,255,0.31)"
    } : (void 0 === e.background && (e.background = "rgba(0,129,255,0.31)"), void 0 === e.color && (e.color = "#007bff")), $("body").append('<div class="loading-overlay" style="background-color:' + e.background + ';"><div class="loader-box"><div class="circle-loader"></div></div></div>'), $("body").append('<style class="notify-styling">.circle-loader:before { border-top-color: ' + e.color + "; }</style>")
}

function hideLoader() {
    $(".loading-overlay, notify-styling").remove()
}