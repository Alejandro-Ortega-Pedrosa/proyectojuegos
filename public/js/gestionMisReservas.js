$(function(){

    $.ajax( "http://localhost:8000/api/reservasUser", 
    {
        method:"GET",
        dataType:"json",
    }).done(function(data){

        var tabla = $("<table></table>");
        tabla.attr({
        id:"idtabla"});

        tabla.addClass("table text-white text-center");

        var nuevoTr='<thead class"bg-secondary"><tr><th>MESA</th><th>FECHA</th><th>TRAMO HORARIO</th><th>JUEGO</th><th>CANCELACIÓN</th></thead>';
        tabla.append(nuevoTr);


        $("#container").append(tabla);

        var tbody=$("<tbody>");
        tbody.addClass("bg-white text-dark");

        $.each(data, function(i,v){

            let rYear = data[i].fecha.substring(0, 4);
            let rMonth = data[i].fecha.substring(5, 7);
            let rDay = data[i].fecha.substring(8, 10);

            fechaReserva=rDay+"/"+rMonth+"/"+rYear;
            
            //COJO LA FECHA DE HOY
            let fechaHoy = new Date()

            let day = fechaHoy.getDate()
            let month = fechaHoy.getMonth() + 1
            let year = fechaHoy.getFullYear()

            if(month < 10){
                fechaHoy=(`${year}-0${month}-${day}`)
            }else{
                fechaHoy=(`${year}-${month}-${day}`)
            }

            //CREO LAS DOS FECHAS PARA COMPARARLAS
            var f1 = new Date(rYear, rMonth, rDay);
            var f2 = new Date(year, month, day);

            //SI LA FECHA DE RESERVA YA HA PASADO EL BOTON DE CANCELAR SALE DESACTIVADO
            if(f2<=f1){
                var nuevoTr="<tr><td>"+data[i].mesa+"</td><td>"+fechaReserva+"</td><td>"+data[i].tramo+"</td><td>"+data[i].juego+'</td><td><button id='+data[i].id+' class="boton btn btn-primary rounded-0 py-2 px-lg-4 d-none d-lg-block">CANCELAR</button></td></tr>';
            }else{
                var nuevoTr="<tr><td>"+data[i].mesa+"</td><td>"+fechaReserva+"</td><td>"+data[i].tramo+"</td><td>"+data[i].juego+'</td><td><button class="btn btn-primary rounded-0 py-2 px-lg-4 d-none d-lg-block disabled">CANCELAR</button></td></tr>';
            }

            tbody.append(nuevoTr);
        })
        tabla.append(tbody);
        tabla.DataTable();

        $(".boton").click(function(){
            
            borraReserva($(this).attr("id"));
        })
    

        
    }).fail(function(){
            alert("ERROR")
    })

})

//FUNCION PARA BORRAR UNA DISTRIBUCION
function borraReserva(id){
    $.ajax( "http://localhost:8000/api/reserva/"+id, 
    {
        method:"DELETE"
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
        alert("ERROR");
    })

}

//FUNCION PARA LA PLANTILLA DEL FORMULARIO DEL MODAL
function pintaPlantillaAct(){
    
    var plantilla=`
    <form name="gesMesa">
        <div class="row g-3">
            <div class="col-12">
                <label>¡Se ha cancelado la reserva!</label>
            </div>
        </div>
    </form>`

    return plantilla;
}
