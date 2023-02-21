$(function(){

    var fechaSeleccionada=null;
    var difY=$("#sala").offset().top;
    var difX=$("#sala").offset().left;

    var diasFestivos=["27/02/2023","28/02/2023","01/03/2023"];
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '< Ant',
        nextText: 'Sig >',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };

    $.datepicker.setDefaults($.datepicker.regional['es']);
    
    $("#desde").datepicker({
        minDate:"D",
        maxDate:"3M+1D",

        onSelect:function(text, obj){
        
            //UNA VEZ SELECCIONADO
            fechaSeleccionada = new Date()

            let day = obj.currentDay
            let month = obj.currentMonth + 1
            let year = obj.currentYear

            if(month < 10){
                fechaSeleccionada=(year+'-0'+month+'-'+day);
            }else{
                fechaSeleccionada=(year+'-0'+month+'-'+day);
            }

            $.ajax( "http://localhost:8000/api/distribucion", 
            {
                method:"GET",
                dataType:"json"
            }).done(function(data){

                //COJO TODAS LAS MESAS Y LAS AÑADO AL ALMACEN
                for(let i=0;i<$(".mesa").length;i++){
                    var mesa=$(".mesa")[i];
                    $(mesa).css({position:"relative",top:0+"px",left:0+"px"});
                    $("#almacen").append(mesa);
                }

                //RECORRO LA DISPOSICION SEGUN EL DIA SELECCIONADO Y PINTA LAS MESAS EN SU NUEVA POSICION
                $.each(data, function(i,v){
                    if(fechaSeleccionada===data[i].fecha){
                        var id=data[i].mesa;

                        mesa=$('#'+id);
                        mesa.attr('name', data[i].id);
                        mesa.css({top:(data[i].y-difY)+"px",left:(data[i].x-difX)+"px"});
                        $("#sala").append(mesa);
                    }
                })

            })
        }
    })
        

    //AJAX PARA TRAER LAS MESAS DE LA BASE DE DATOS
    $.ajax( "http://localhost:8000/api/mesas", 
    {
        method:"GET",
        dataType:"json"
    }).done(function(data){


        //RECORRO EL ARRAY DE MESAS Y LAS PINTO SEGUN SU POSICION GUARDADA BASE
        $.each(data, function(i,v){
            var mesa=new Mesa(data[i].id,data[i].width,data[i].height,data[i].y,data[i].x);
            mesa.pinta($("#almacen"),$("#sala"), difY);
        })

        //MESA DRAGABLE
        $(".mesa").draggable({
            start: function(ev, ui){
                $(this).attr("data-x",ui.offset.left);
                $(this).attr("data-y",ui.offset.top);
            },
            revert: true,
            helper:'clone',
            accept: '#almacen, #sala',
            revertDuration: 0,
        });
            
        
        //SALA
        $("#sala").droppable({
        
            drop: function(ev, ui) {

                //COJO EL ELEMENTO QUE ARRASTRO
                var mesa=ui.draggable;
                var top=parseInt(ui.offset.top);
                var left=parseInt(ui.offset.left);

                //CALCULO LA DIFERENCIA DE ALTURA ENTRE LA SALA Y LA MESA
                var difY=$(this).offset().top;
                var difX=$(this).offset().left;
                difMY=top-difY;
                difMX=left-difX;

                //CALCULO EL TOP Y EL LEFT DE LA MESA
                var mesaObj=new Mesa(mesa.attr("id"),mesa.width(),mesa.height(),parseInt(ui.offset.top),parseInt(ui.offset.left));

                //RELLENO EL ARRAY MESAS CON LAS MESAS QUE HAY 
                var mesas=[];
                if($(".mesa").length>0){
                    //RELLENO EL ARRAY CON LAS MESAS DE LA SALA
                    for(let i=0;i<$(".mesa").length;i++){
                        mesas.push($(".mesa").eq(i));
                    }
                }
    
                //SI SE SOLAPA LA MESA DEVUELVE FALSE Y POR LO TANTO NO CAMBIA DE POSICION
                if(mesaObj.solapa(mesas)){
                    //CAMBIO LAS PROPIEDADES DE LA MESA QUE MUEVO PARA QUE CAMBIE EL TOP Y EL LEFT Y LA AÑADO A LA SALA
                    $(this).append(mesa);
                    mesa.css({position:"absolute",top:difMY+"px",left:left+"px"});
                   
                    //SI ESTA SELECCIONADA LA FECHA GUARDA LA DISTRIBUCION
                    if(fechaSeleccionada!=null){
                        
                        //COMPRUEBO SI YA TIENE DISTRIBUCION Y SI TIENE HAGO UN PUT SI NO UN POST 
                        if(mesa.attr("name")!=null){

                            guardaMesaDistribucion(fechaSeleccionada, mesa.offset().top, mesa.offset().left, mesa);
                        }else{
                            guardaMesaNuevaDistribucion(fechaSeleccionada, mesa.offset().top, mesa.offset().left, mesa);
                        }
                        
                    }else{
                        guardaMesa(mesa, mesa.offset().top, mesa.offset().left);
                    }

                };

                
            }
        });

        //ALMACEN
        $("#almacen").droppable({
            drop: function(ev, ui){
                //AÑADE LA MESA AL ALMACEN Y LA GUARDA CON TOP Y LEFT A 0
                var mesa=ui.draggable;
                mesa.css({position:"relative",top:0,left:0});
                $(this).append(mesa);
                if(fechaSeleccionada!=null){
                    borraDistribucion(mesa);
                }else{
                    guardaMesa(mesa, 0, 0);
                }

            }
        })

        //MODAL DEL FORMULARIO
        $("#almacen .mesa").dblclick(function(ev){

            var id=$(this).attr("id");

            $.ajax( "http://localhost:8000/api/mesa/"+id, 
            {
                method:"GET",
                dataType:"json",
            }).done(function(data){

                ev.preventDefault();

                var plantilla=pintaPlantilla();

                var jqPlantilla=$(plantilla);
                jqPlantilla.find("#height").val(data.height);
                jqPlantilla.find("#width").val(data.width);
                jqPlantilla.find("button").text("Guardar");

                jqPlantilla.dialog({
                    height: 300,
                    width: 350,
                    modal: true,
                });

                //FORMULARIO DE CREACION DE UNA NUEVA MESA
                $("form[name='gesMesa']").submit(function(ev){
                    
                    var form=$(this);
                    var height=form.find("#height").val();
                    var width=form.find("#width").val();
                    var y=0;
                    var x=0;

                    $.ajax( "http://localhost:8000/api/mesa/"+id, 
                    {
                        method:"PUT",
                        dataType:"json",
                        data:{
                            width: width,
                            height: height,
                            y: y,
                            x: x
                        }
                    }).done(function(data){
                        "SE HAN ENVIADO LOS DATOS";
                    })
                });

            }).fail(function(){
                alert("ERROR")
            })

            
        })

                
    }).fail(function(){
        alert("ERROR")
    })

    

    //MODAL DEL FORMULARIO PARA CREAR UNA MESA
    $("button").click(function(ev){

        ev.preventDefault();

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
        $("form[name='gesMesa']").submit(function(ev){
            var form=$(this);
            var height=form.find("#height").val();
            var width=form.find("#width").val();

            nuevaMesa(height, width);
        });
    })

})

//FUNCION PARA GUARDAR UNA MESA
function guardaMesa(mesa, y, x){
    $.ajax( "http://localhost:8000/api/mesa/"+mesa.attr("id"), 
    {
        method:"PUT",
        dataType:"json",
        data:{
            width: parseInt(mesa.width()),
            height: parseInt(mesa.height()),
            y: y,
            x: x
        }
    }).done(function(data){

        console.log("SE HAN ENVIADO LOS DATOS");

    }).fail(function(){
        alert("ERROR")
    })

}

//FUNCION PARA GUARDAR UNA MESA
function guardaMesaDistribucion(fechaSeleccionada, y, x, mesa){
    $.ajax( "http://localhost:8000/api/distribucion/"+mesa.attr("name"), 
    {
        method:"PUT",
        dataType:"json",
        data:{
            fecha: fechaSeleccionada,
            y: y,
            x: x,
            idmesa: mesa.attr("id")
        }
    }).done(function(data){

        console.log("SE HAN ENVIADO LOS DATOS");

    }).fail(function(){
        alert("ERROR")
    })

}

//FUNCION PARA GUARDAR UNA MESA
function guardaMesaNuevaDistribucion(fechaSeleccionada, y, x, mesa){
    $.ajax( "http://localhost:8000/api/distribucion", 
    {
        method:"POST",
        dataType:"json",
        data:{
            fecha: fechaSeleccionada,
            y: y,
            x: x,
            idmesa: mesa.attr("id")
        }
    }).done(function(data){

        console.log("SE HAN ENVIADO LOS DATOS");

    }).fail(function(){
        alert("ERROR")
    })

}

function borraDistribucion(mesa){
    $.ajax( "http://localhost:8000/api/distribucion/"+mesa.attr('name'), 
    {
        method:"DELETE"
    }).done(function(data){

        console.log("SE HAN ENVIADO LOS DATOS");

    }).fail(function(){
        alert("ERROR")
    })

}

//FUNCION PARA CREAR UNA NUEVA MESA
function nuevaMesa(width, height){
    $.ajax( "http://localhost:8000/api/mesa", 
    {
        method:"POST",
        dataType:"json",
        data:{
            width: width,
            height: height,
            y: 0,
            x: 0
        }
    }).done(function(data){

        console.log("SE HA GUARDADO LA MESA");

    }).fail(function(){
        alert("ERROR")
    })
}

//FUNCION PARA LA PLANTILLA DEL FORMULARIO DEL MODAL
function pintaPlantilla(){
    
    var plantilla=`
        <form name="gesMesa">
            <div class="row g-3">
                <div class="col-12">
                    <div class="form-floating">
                        <input class="form-control bg-transparent" name="height" id="height" type="text">
                        <label>Height:</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                        <input class="form-control bg-transparent" name="width" id="width" type="text">
                        <label>Width:</label>
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100 py-3">Crear</button>
                </div>
            </div>
        </form>`

    return plantilla;
}

