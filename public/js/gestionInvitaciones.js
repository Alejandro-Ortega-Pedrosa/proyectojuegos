$(function(){

    $.ajax( "http://localhost:8000/api/user", 
    {
        method:"GET",
        dataType:"json",
    }).done(function(data){

        var tabla = $("<table></table>");
        tabla.attr({
        id:"idtabla"});

        tabla.addClass("table text-white text-center");

        var nuevoTr='<thead class"bg-secondary"><tr><th>EMAIL</th><th>NOMBRE</th><th>APELLIDOS</th><th>INVITAR</th></tr></thead>';
        tabla.append(nuevoTr);


        $("#container").append(tabla);

        var tbody=$("<tbody>");
        tbody.addClass("bg-white text-dark");

        $.each(data, function(i,v){
            var nuevoTr="<tr><td>"+data[i].email+"</td><td>"+data[i].nombre+"</td><td>"+data[i].apellidos+"</td><td><input type='checkbox' id="+data[i].id+"> </td></tr>";
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

        //PLANTILLA DE BORRADO
        var plantilla=pintaPlantillaAct();
    
        //CARACTERISTICAS DEL MODAL
        var jqPlantilla=$(plantilla);

        jqPlantilla.dialog({
            resizable: false,
            height: "auto",
            width: 400,
            draggable: false,
            modal: true,
            buttons: {
                Aceptar: function() {
                  $( this ).dialog( "close" );
                }
            }
        });

    }).fail(function(){
            alert("ERROR")
    })

}

//FUNCION PARA LA PLANTILLA DEL FORMULARIO DEL MODAL
function pintaPlantillaAct(){
    
    var plantilla=`
    <form name="gesMesa">
        <div class="row g-3">
            <div class="col-12">
                <label>¡Se han enviado las notificaciones a los usuarios!</label>
            </div>
        </div>
    </form>`

    return plantilla;
}

