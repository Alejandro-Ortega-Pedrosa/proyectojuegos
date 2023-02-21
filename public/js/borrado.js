$(function(){

    $("button").click(function(ev){

        ev.preventDefault();

        var id=this.id;

        //PLANTILLA DEL FORMULARIO
        var plantilla=pintaPlantilla();
    
        //CARACTERISTICAS DEL MODAL
        var jqPlantilla=$(plantilla);
        jqPlantilla.dialog({
            height: 300,
            width: 350,
            modal: true,
        });

        //FORMULARIO DE CREACION DE UNA NUEVA MESA
        $("form[name='borrar']").submit(function(ev){
            borrar(id);
        });
    })

})

function borrar(id){
    $.ajax( "http://localhost:8000/gestionJuegosDelete/"+id, 
    {
        method:"DELETE",
        
    }).done(function(data){

        console.log("SE HA BORRADO");

    }).fail(function(){
        alert("ERROR")
    })

}

//FUNCION PARA LA PLANTILLA DEL FORMULARIO DEL MODAL
function pintaPlantilla(id){
    
    var plantilla=`
        <form name="borrar">
            <div class="col-12">
                <p>Se va a Eliminar, ¿Estás seguro?</p>
            </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100 py-3">Borrar</button>
                </div>
            </div>
        </form>`

    return plantilla;
}

