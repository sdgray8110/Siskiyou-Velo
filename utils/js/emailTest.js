$('#resetForm').click(function(e) {
    e.preventDefault();

    $('#htmlContent').replaceWith('<textarea id="htmlContent" name="htmlContent"></textarea>');
    $('#emailAddresses, #subject').attr('value','');
});