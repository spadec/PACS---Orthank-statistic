function showAlert(msg) {
    $('#blockmsg').html('<div class="col-sm-12">'+
        '<div class="alert  alert-'+msg.class+' alert-dismissible fade show" role="alert">'+
            '<span class="badge badge-pill badge-'+msg.class+'">'+msg.status+'</span>' +
            ''+msg.message+''+
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                '<span aria-hidden="true">&times;</span>'+
            '</button>'+
        '</div>'+
    '</div>');
}



$(document).ready(function() {
    $('.modal').on('hidden.bs.modal', () => {
        console.log('hide'); 
        var input = $('input');
        $(input).each((i,e) => {
            $(e).val('').removeClass('is-invalid').removeClass('is-valid');
            $('small').text('');
        });
    });
    $('.modal').on('show.bs.modal', () => {
        console.log('show'); 
        var input = $('.modal input');
        setTimeout(()=>{
            $(input[0]).prop('autofocus','true');
        },1000);
        
    });



    $('#org').chosen({width:"100%"});
    $('#org').val([1]);
    $('#org').trigger('chosen:updated');
    $('#eorg').chosen({width:"100%"});
    $('#eorg').trigger('chosen:updated');
    $('#del').hide();

    $(document).delegate('[name=dels]' , 'click' , function(e) {
        var dels = $('[name=dels]');
        $('#del').hide();
        $(dels).each(function() {
            if($(this).prop('checked')){
                $('#del').show();
            }
        });
    }) 

});
var gid;
var sels;
var adduser = function() {
    var login = $('#login').val();
    var password = $('#password').val();
    var org = $('#org').val();
    sels = org;
    if(validate($('#modal .v'))) {
        $.ajax({
            type:'POST',
            url:'../ajax/adduser.php',
            data: {
                login:login , password:password , org:org
            },
            dataType:'json',timeout:3000,
            success:function(response) {
                console.log(response);
                var r= response;
                var html = "<tr data-id="+r.uid+">";
                html+="<td><input data-id='"+r.uid+"' class='dels' name='dels' type='checkbox'></td>";
                html+="<td class='edit' data-id="+r.uid+">"+login+"</td>";
                html+="<td class='edit' data-id="+r.uid+">";
                sels.forEach(el => {
                    html+=$('#org option[value='+el+']').text() + ", ";
                });
                html+="</td>";
                // html+= "<td></td>";
                
                html+="</tr>";
                $('table#users tbody').append(html);
                $('.modal').modal('hide');
                showAlert({
                    class:'success',
                    message:"Пользователь добавлен",
                    status:'OK'
                })
            },
            error:function() {
                $('.modal').modal('hide');
                showAlert({
                    class:'danger',
                    message:"Ошибка сервера",
                    status:'ERR'
                })
            }
        })
    } else {

    }
    
}


var deluser = function() {
    var cb_dels = $('#users input[name=dels]');
    var ids =[];
    $(cb_dels).each(function(i,e){
        if($(e).prop('checked')){
            ids.push($(e).data('id'));
        }
    });
    if(confirm('Удалить выбранные записи'))
    $.ajax({
        type:"POST",timeout:3000,
        url:'../ajax/deluser.php',
        data: {dels:ids},
        dataType:'json',
        success:function(response) {
            console.log(ids);
            ids.forEach(el => {
                $('tr[data-id='+el+']').remove();
            });
            showAlert({
                class:'info',
                message:"Данные удалены",
                status:'OK'
            })
        },
        error:function() {
            $('.modal').modal('hide');
            showAlert({
                class:'danger',
                message:"Ошибка сервера",
                status:'ERR'
            })
        }
    });
};

 function onedel(e) {
    var ids= [];
    console.log($(e.target).data());
    
    ids.push($(e.target).data('id'));

    $.ajax({
        type:"POST",timeout:3000,
        url:'../ajax/deluser.php',
        data: {dels:ids},
        dataType:'json',
        success:function(response) {
            console.log(ids);
            ids.forEach(el => {
                $('tr[data-id='+el+']').remove();
            });
        },
        error:function() {
            $('.modal').modal('hide');
            showAlert({
                class:'danger',
                message:"Ошибка сервера",
                status:'ERR'
            })
        }
    });
}

function edit(e) {
    gid=$(e.target).data('id');
    
    $.ajax({
        type:'POST',
        url:'../ajax/get.php',
        data:{id:$(e.target).data('id')},
        dataType:'json',
        timeout:3000,
        success:function(response) {
            console.log(response);
            var orgs = response.orgs;
            var login = response.login;
            var password = response.password;
            var id = response.id;
            
            $('#elogin').val(login);
            $('#epassword').val();

            var selOrgsId =[];
            var selOrgsName =[];
            response.orgs.forEach(el => {
                selOrgsId.push(el.id);
            });
            $('#eorg').val(selOrgsId).trigger('chosen:updated');
            $('#emodal').modal();
        },
        error:function() {
            $('.modal').modal('hide');
            showAlert({
                class:'danger',
                message:"Ошибка сервера",
                status:'ERR'
            })
        }
    })
}

var saveuser =  function() {
    var login = $('#elogin').val();
    var password = $('#epassword').val();
    var orgs = $('#eorg').val();
    console.log(orgs);
    if(validate($('#emodal .v'))) {
        $.ajax({
            type:'POST',
            url:'../ajax/saveuser.php',
            dataType:'json',
            data:{id:gid,login:login,password:password,orgs:orgs},
            success:function(response) {
                var orgs = response.org;
                $('#emodal').modal('hide')
                $('tr[data-id='+gid+'] td.login').val(response.login);
                $('tr[data-id='+gid+'] td.orgs').empty();
                var str = "";
                response.forEach(el=> {
                    str += el.name + ", ";
                });
                console.log(str);
                $('tr[data-id='+gid+'] td.orgs').append(str); 
                $('#emodal input').val('');
                showAlert({
                    class:'success',
                    message:"Данные сохранены",
                    status:'OK'
                })
            },
            error:function() {
                $('.modal').modal('hide');
                showAlert({
                    class:'danger',
                    message:"Ошибка сервера",
                    status:'ERR'
                })
            }
        });
    }
    
}


$(document).delegate("#del" , 'click' , deluser);
$(document).delegate('#modal #saveuser' , 'click' , adduser);
// $(document).delegate('.onedel' ,'click' , onedel);
$(document).delegate('#users .edit' , 'click' , edit);
$(document).delegate('#emodal #esaveuser', 'click'  , saveuser);