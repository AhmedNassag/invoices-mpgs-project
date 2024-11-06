if (jQuery.fn.datepicker) {
    jQuery(".datepicker").datepicker({
        format: 'dd-mm-yyyy', autoclose: true
    });
}


if (jQuery.fn.select2) {
    jQuery('.select2').select2();
}

function printDiv() {
    var oldPage = document.body.innerHTML;
    var printDiv = document.getElementById('printDiv').innerHTML;
    document.body.innerHTML = '<html><head><title>' + document.title + '</title></head><body>' + printDiv + '</body></html>';
    window.print();
    document.body.innerHTML = oldPage;
    window.location.reload();
}