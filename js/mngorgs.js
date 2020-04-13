$(document).ready(function() {
    $(document).delegate('table#orgs .dels' , 'click' , function(e) {
        var dels = $('table#orgs .dels');
        $('#delorgs').hide();
        $(dels).each(function() {
            if($(this).prop('checked')){
                $('#delorgs').show();
            }
        });
    }) 
});

var addorg = function () {
    var name = $('#name').val();
    var tag =  $('#tag').val();

    if(validate($('#orgmodal .v'))) {
        $.ajax({
            type:'POST',
            url:'../ajax/addorg.php',
            dataType:'json',
            timeout:3000,
            data:{name:name,tag:tag},
            success:function(response) {
                var r =response;
                var html = "<tr data-id="+r.oid+">";
                    html+= "<td><input data-id="+r.oid+" class='dels' name='dels' type='checkbox'></td>";
                    html+= "<td class='editorg' data-id="+r.oid+">"+name+"</td>";
                    html+= "<td class='editorg' data-id="+r.oid+">"+tag+"</td>";
                    html+= "</tr>";
                $('table#orgs tbody').append(html);
                $('.modal').modal('hide');
                showAlert({
                    class:'success',
                    message:"Организация добавлена",
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

var delorgs = function() {
    var cb_dels = $('#orgs input[name=dels]');
    var ids =[];
    $(cb_dels).each(function(i,e){
        if($(e).prop('checked')){
            ids.push($(e).data('id'));
        }
    });
    if(confirm('Удалить выбранные записи'))
    $.ajax({
        type:"POST",
        url:'../ajax/delorg.php',
        data: {dels:ids},
        dataType:'json',timeout:3000,
        success:function(response) {
            console.log(ids);
            ids.forEach(el => {
                $('#orgs tr[data-id='+el+']').remove();
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
var gid;
var getdata = function(e) {
    gid = $(e.target).data('id');
    $.ajax({
        type:"POST",
        url:'../ajax/getdataorg.php',
        dataType:'json',
        data:{id:gid},timeout:3000,
        success:function(response) {
            var r  =response;
            $('#eorgmodal #ename').val(r.name);
            $('#eorgmodal #eparam').val(r.param);
            $('#eorgmodal').modal();
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

var saveorg = function() {
    var name  = $('#eorgmodal #ename').val();
    var param = $('#eorgmodal #eparam').val();
    if(validate($('#eorgmodal .v'))) {
        $.ajax({
            type:'POST',
            url:'../ajax/saveorg.php',
            dataType:'json',timeout:3000,
            data:{id:gid, name:name , param:param},
            success:function(r) {
                $('#orgs tr[data-id='+gid+'] .name').text(r.name);
                $('#orgs tr[data-id='+gid+'] .param').text(r.param);
                $('.modal').modal('hide');
                showAlert({
                    class:'success',
                    message:"Данные сохранены",
                    status:'OK'
                })
            },error:function() {
                $('.modal').modal('hide');
                showAlert({
                    class:'danger',
                    message:"Ошибка сервера",
                    status:'ERR'
                })
            }
        })
    }   
}

$(document).delegate('#esaveorg' , 'click' , saveorg)
$(document).delegate('#saveorg' , 'click' , addorg);
$(document).delegate('#delorgs' , 'click' , delorgs);
$(document).delegate('#orgs .editorg' , 'click' , getdata);