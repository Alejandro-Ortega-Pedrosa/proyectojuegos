$(function(){

    $(".boton").click(function(ev){
       
        ev.preventDefault();

        var id=this.id;

        //PLANTILLA DEL FORMULARIO
        var plantilla=pintaPlantilla();
    
        //CARACTERISTICAS DEL MODAL
        var jqPlantilla=$(plantilla);
        jqPlantilla.dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            draggable: false,
            buttons: {
                Cancelar: function() {
                  $( this ).dialog( "close" );
                }
            }
        });

        //FORMULARIO DE CREACION DE UNA NUEVA MESA
        $("form[name='borrar']").submit(function(ev){

            ev.preventDefault();

            borrar(id);
        });
    })

})

function borrar(id){
    $.ajax( "http://localhost:8000/gestionEventosDelete/"+id, 
    {
        method:"DELETE",
        
    }).done(function(data){

        console.log("SE HA BORRADO");

    }).fail(function(){

        //PLANTILLA DE ERROR 
        var plantilla=pintaPlantillaDelete();
    
        //CARACTERISTICAS DEL MODAL
        var jqPlantilla=$(plantilla);

        jqPlantilla.dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            draggable: false,
        });
    })

}

//FUNCION PARA LA PLANTILLA DEL FORMULARIO DEL MODAL
function pintaPlantilla(){
    
    var plantilla=`
        <form name="borrar">
            <div class="col-12">
                <p>Se va a Eliminar, ¿Estás seguro?</p>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary w-100 py-3">Borrar</button>
            </div>
        </form>`

    return plantilla;
}

function pintaPlantillaDelete(){
    
    var plantilla=`
        <form name="gesMesa">
            <div class="row g-3">
                <div class="col-12">
                    <label>Lo sentimos, esta mesa solo se puede eliminar con Easy Admin</label>
                </div>
            </div>
        </form>`

    return plantilla;
}