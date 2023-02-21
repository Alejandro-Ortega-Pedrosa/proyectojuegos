$(function(){

    var difY=$("#sala").offset().top;
    var difX=$("#sala").offset().left;

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

                //RECORRO LA DISPOSICION SEGUN EL DIA SELECCIONADO Y PINTA LAS MESAS EN SU NUEVA POSICION
                $.each(data, function(i,v){
                    if(fechaSeleccionada===data[i].fecha){
                        var id=data[i].mesa;

                        var mesa=$("<div>");
                        mesa.addClass("mesa");
                        mesa=$('#'+id);
                        mesa.attr('name', data[i].id);
                        mesa.css({top:(data[i].y-difY)+"px",left:(data[i].x-difX)+"px"});
                        $("#sala").append(mesa);
                    }else{
                        //PINTO LA DISTRIBUCION BASE
                        $.ajax( "http://localhost:8000/api/mesas", 
                        {
                            method:"GET",
                            dataType:"json"
                        }).done(function(data){
                    
                    
                            //RECORRO EL ARRAY DE MESAS Y LAS PINTO SEGUN SU POSICION GUARDADA BASE
                            $.each(data, function(i,v){
                                var mesa=new Mesa(data[i].id,data[i].width,data[i].height,data[i].y,data[i].x);
                                
                                mesa.pintaReserva($("#sala"), difY);
                            })

                            reserva(fechaSeleccionada);

                        })
                    
                    }

                })

            }).fail(function(){
                alert("ERROR")
            })
        }
    })


})

function reserva(fechaSeleccionada){
    //PINCHO LA MESA
    $(".mesa").click(function(){
        
        var mesa=($(this).attr("id"));
        var heightMesa=$(this).height();
        var widthMesa=$(this).width();

        $.ajax( "http://localhost:8000/api/juego", 
        {
            method:"GET",
            dataType:"json"
        }).done(function(data){
                       
            //RECORRO EL ARRAY DE MESAS Y LAS PINTO SEGUN SU POSICION GUARDADA BASE
            $.each(data, function(i,v){
                if(heightMesa>data[i].height && widthMesa>data[i].width){
                    
                    $("select").append('<option value='+data[i].id+'>'+data[i].nombre+'</option>');
                }

            })

            //CUANDO LE DE A SELECCIONAR RESERVA
            $("#reservar").click(function(ev){
                ev.preventDefault();
                console.log(fechaSeleccionada);
                console.log(mesa);
                console.log($("#juego").val());
                
                
            })
         
        })
    })
}