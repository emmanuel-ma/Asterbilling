function doClientPrint(formId) {

    var printerSettings = $("#" + formId).serialize();

    //alert( printerSettings );

    // store user printer settings & commands in server cache
    $.post('PrintCommandsProcess.php',
        printerSettings,
        function() {
            //alert('data=' + data);
             // Launch WCPP at the client side for printing...
             var sessionId = $("#sid").val();
             jsWebClientPrint.print('sid=' + sessionId);
        }
    );

}