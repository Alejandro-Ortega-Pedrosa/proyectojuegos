$(function(){

    $.ajax( "http://localhost:8000/api/user", 
    {
        method:"GET",
        dataType:"json",
    }).done(function(data){

        var tabla = $("<table></table>");
        tabla.attr({
        id:"idtabla"});

        tabla.addClass("table table-dark");

        var nuevoTr="<thead><tr><th>EMAIL</th><th>NOMBRE</th><th>APELLIDOS</th><th>ROLES</th><th>INVITAR</th></tr></thead>";
        tabla.append(nuevoTr);


        $("#container").append(tabla);

        var tbody=$("<tbody>");

        $.each(data, function(i,v){
            var nuevoTr="<tr><td>"+data[i].email+"</td><td>"+data[i].nombre+"</td><td>"+data[i].apellidos+"</td><td>"+data[i].roles+"</td><td><input type='checkbox' id="+data[i].id+"> </td></tr>";
            tbody.append(nuevoTr);
        })
        tabla.append(tbody);
        tabla.DataTable();

        //CREO EL ARRAY DE USUARIOS Y COJO EL ID DEL EVENTO
        var users=[];
        var idEvento=$(".boton").attr("id");
    
        //CUANDO SE ELIGE UN USUARIO SE METE EN EL ARRAY DE USUARIOS SELECCIONADOS
        $('input[type=checkbox]').on('change', function() {
            if ($(this).is(':checked') ) {
                users.push($(this).prop("id"));
            }else {
                users=users.filter(user => user != $(this).prop("id"));
            }
        });

        //MANDA LAS INVITACIONES A LOS USUARIOS SELECCIONADOS
        $(".boton").click(function(){
            for(let i=0;i<users.length;i++){
                invitar(users[i], idEvento);
            }
        })

        
    }).fail(function(){
            alert("ERROR")
    })

})

function invitar(idUser, idEvento){
    $.ajax( "http://localhost:8000/api/invitacion", 
    {
        method:"POST",
        dataType:"json",
        data:{
            user: idUser,
            evento: idEvento
        }
    }).done(function(data){

        console.log("SE HAN ENVIADO LOS DATOS")

    }).fail(function(){
            alert("ERROR")
    })

}

