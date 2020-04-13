// var istrue = true
// var validate = async function(inputs) {
//     $(inputs).each(function(i,e) {
//         if($(e).val()=='') {
//             $('#'+$(e).prop('id')+'sm').text('заполните поле');
//             return false;
//         }
//     });
//     return false
// }
function validate(fields) {
    var isValid=true;
    $(fields).each(function(i,e) {
        $(this).removeClass('is-invalid');        
        if($(this).val()=='') {
            $(this).addClass('is-invalid');
            $('#'+$(e).prop('id')+'sm').text('заполните поле');
            isValid =false;
        }
        else {
        }
        $(this).addClass('is-valid');
    });
    return isValid;
}