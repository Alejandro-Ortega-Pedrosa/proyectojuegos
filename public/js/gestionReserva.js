$(function(){

    $.datepicker.setDefaults($.datepicker.regional['es']);

    //RELLENO EL SELECT CON LOS TRAMOS 
    $.ajax( "http://localhost:8000/api/tramo", 
    {
        method:"GET",
        dataType:"json"
    }).done(function(data){

        //RECORRO LA DISPOSICION SEGUN EL DIA SELECCIONADO Y PINTA LAS MESAS EN SU NUEVA POSICION
        $.each(data, function(i,v){
            
            $("#tramo").append('<option value='+data[i].id+'>'+data[i].hora+'</option>');
        })

    }).fail(function(){
        alert("ERROR")
    })

    //RELLENO EL SELECT DE LOS JUEGOS
    $.ajax( "http://localhost:8000/api/juego", 
    {
        method:"GET",
        dataType:"json"
    }).done(function(data){
                    
        //RECORRO EL JSON Y RELLENO EL SELECT
        $.each(data, function(i,v){
                
            $("#juego").append('<option value='+data[i].id+'>'+data[i].nombre+'</option>');
                
        })
    })


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

            $("#sala").empty();

            $.ajax( "http://localhost:8000/api/distribucion/"+fechaSeleccionada, 
            {
                method:"GET",
                dataType:"json"
            }).done(function(data){

                //ME CREO UNA VARIABLE PARA SABER SI HAY DISTRIBUCION EN ESA FECHA
                let encontrada=false

                $.each(data, function(i,v){
                    //SI LA FECHA QUE ESTA SELECCIONADA TIENE DISTRIBUCION PINTA ESA DISTRIBUCION
                    if(fechaSeleccionada===data[i].fecha){
                        var id=data[i].mesa;

                        let difY=$("#sala").offset().top;

                        var mesa=$(`<div id=${id}>`);
                        //ASIGNO LAS PROPIEDADES A LA MESA
                        mesa.height(data[i].height);
                        mesa.width(data[i].width);
                        mesa.css({position:"absolute",top:(data[i].y-difY)+"px",left:(data[i].x)+"px", 
                        'background': 'maroon', "background-size":this.width});
                        mesa.addClass("mesa");

                        //AÑADO LA MESA A LA SALA
                        $("#sala").append(mesa);

                        //CUANDO ENCUENTRA LA DISTRIBUCION SE CAMBIA LA VARIABLE ENCONTRADA
                        encontrada=true;
                    }
                })


                //SI NO HAY DISTRIBUCION PINTA LA BASE
                if(!encontrada){
                    $("#sala").empty();
                    ///PINTO LA DISTRIBUCION BASE
                    $.ajax( "http://localhost:8000/api/mesas", 
                    {
                        method:"GET",
                        dataType:"json"
                    }).done(function(data){

                        let difY=$("#sala").offset().top;
                    
                        //RECORRO EL ARRAY DE MESAS Y LAS PINTO SEGUN SU POSICION GUARDADA BASE
                        $.each(data, function(i,v){

                            var mesa=new Mesa(data[i].id,data[i].width,data[i].height,data[i].y,data[i].x);
                                
                            mesa.pintaReserva($("#sala"), difY);
                        })

                        reserva(fechaSeleccionada);
                    })
                }else{
                    reserva(fechaSeleccionada);
                }

               

            }).fail(function(){
                alert("ERROR")
            })
            
        }
    })


})

function reserva(fechaSeleccionada){

    reservada(fechaSeleccionada);

    //CUANDO SE SELECCIONA LA MESA
    $(".mesa").click(function(){

        var idMesa=$(this).attr("id");
        var heightMesa=$(this).height();
        var widthMesa=$(this).width();

        var mesa=$(".mesa#"+idMesa);
        mesa.css({"border": "5px solid green"});

        $("#mimesa").text("HAS SELECCIONADO LA MESA "+idMesa).css({"color":"green"});

        $.ajax( "http://localhost:8000/api/juego", 
        {
            method:"GET",
            dataType:"json"
        }).done(function(data){
                      
            //VACIO EL SELECT PARA RELLENARLO SOLO CON LOS DE ESA MESA
            $("#juego").empty();
            //RECORRO EL JSON Y RELLENO EL SELECT DE LOS JUEGOS QUE SE PUEDEN JUGAR EN ESA MESA
            $.each(data, function(i,v){
                if(heightMesa>data[i].height && widthMesa>data[i].width){
                    $("#juego").append('<option value='+data[i].id+'>'+data[i].nombre+'</option>');
                }

            })


            //CUANDO LE DE A RESERVAR SE LLAMA A LA API PARA HACER UNA RESERVA
            $("#reservar").click(function(ev){

                ev.preventDefault();

                //VALIDAR
                $.ajax( "http://localhost:8000/api/reserva", 
                {
                    method:"POST",
                    dataType:"json",
                    data:{
                        fecha: fechaSeleccionada,
                        tramo: $("#tramo").val(),
                        mesa: idMesa,
                        juego: $("#juego").val(),
                    }
                }).done(function(data){
            
                     //PLANTILLA DE BORRADO
                    var plantilla=`
                        <form name="gesMesa">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label>La fecha seleccionada es: `+fechaSeleccionada+`</label>
                                </div>
                                <div class="col-12">
                                    <label>El tramo horario es: `+ $("#tramo").val()+`</label>
                                </div>
                                <div class="col-12">
                                    <label>La mesa es: `+fechaSeleccionada+`</label>
                                </div>
                                <div class="col-12">
                                    <label>El juego: `+ $("#juego").val()+`</label>
                                </div>
                            </div>
                        </form>`
                    
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

            })
         
        })
    })

    //CUANDO SE HACE DOBLE CLICK SOBRE LA MESA SE DESSELECCIONA
    $(".mesa").dblclick(function(){
        var idMesa=$(this).attr("id");
        var mesa=$(".mesa#"+idMesa);
        mesa.css({"border": "none"});
        $("#mimesa").text("NO HAY NINGUNA MESA SELECCIONADA");
    })
}

function reservada(fechaSeleccionada){
    $.ajax( "http://localhost:8000/api/reserva", 
    {
        method:"GET",
        dataType:"json"
    }).done(function(data){
                
        //COMPRUEBO SI LA MESA ESTA RESERVADA O NO SI ESTA RESERVADA SE PONE CLARITA Y NO DEJA PINCHARLA
        $.each(data, function(i,v){
            
            for(let j=0;j<$(".mesa").length;j++){
                if(data[i].fecha==fechaSeleccionada && data[i].mesa==$(".mesa").eq(j).attr("id") && data[i].tramo==$("#tramo").val()){
                    var mesaReservada=$(".mesa#"+data[i].mesa)
                    mesaReservada.css("opacity",".4");
                    mesaReservada.css("pointer-events","none");
                }
            }
        })
 
    })
}

