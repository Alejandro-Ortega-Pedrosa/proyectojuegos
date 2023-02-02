$(function(){

    //AJAX PARA TRAER LAS MESAS DE LA BASE DE DATOS
    $.ajax( "http://localhost:8000/api/mesas", 
    {
        method:"GET",
        dataType:"json"
    }).done(function(data){

        //RELLENO EL SEGUNDO SELECT
        $.each(data, function(i,v){

            var mesa=new Mesa(data[i].id,data[i].width,data[i].height,data[i].y,data[i].x);
            mesa.pinta($("#almacen"),$("#sala"));
        })

        //MESA
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

                console.log(mesa.attr("id"));

                //CALCULO EL TOP Y EL LEFT DE LA MESA
                var top=parseInt(ui.offset.top);
                var left=parseInt(ui.offset.left);
                var width=mesa.width();
                var height=mesa.height();

                const pos1=[left,left+width,top,top+height];

                //COMPRUEBO SI SE CHOCA 
                for(let i=0;i<$("#sala .mesa").length;i++){
                    
                    var mesaYa=$("#sala .mesa").eq(i);

                }

                var mesaYa=$("#sala .mesa").eq(0);
                
                if(mesaYa.length>0){
                    var posY=parseInt(mesaYa.offset().top);
                    var posX=parseInt(mesaYa.offset().left);
                    var anchura=mesaYa.width();
                    var altura=mesaYa.height();
        
                    const pos2=[posX,posX+anchura,posY,posY+altura];
                
                    if(((pos1[0]>pos2[0] && pos1[0]<pos2[1]) || 
                        (pos1[1]>pos2[0] && pos1[1]<pos2[1]) || 
                        (pos1[0]<=pos2[0] && pos1[1]>=pos2[1])) 
                        
                        && 
                        
                        ((pos1[2]>pos2[2] && pos1[2]<pos2[3]) || 
                        (pos1[3]>pos2[2] && pos1[3]<pos2[3]) || 
                        (pos1[2]<=pos2[2] && pos1[3]>=pos2[3]))){
                        
                            console.log("CHOQUE");
                        }else{
                            //CAMBIO LAS PROPIEDADES DE LA MESA QUE MUEVO PARA QUE CAMBIE EL TOP Y EL LEFT Y LA AÑADO A LA SALA
                            $(this).append(mesa);
                            mesa.css({position:"absolute",top:top+"px",left:left+"px"});
                            guardaMesaSala(mesa);
                        }
                }else{
                    //CAMBIO LAS PROPIEDADES DE LA MESA QUE MUEVO PARA QUE CAMBIE EL TOP Y EL LEFT Y LA AÑADO A LA SALA
                    $(this).append(mesa);
                    mesa.css({position:"absolute",top:top+"px",left:left+"px"});
                    guardaMesaSala(mesa);
                }
                
            }
        });

        //ALMACEN
        $("#almacen").droppable({
            drop: function(ev, ui){
                var mesa=ui.draggable;
                mesa.css({position:"relative",top:0,left:0});
                $(this).append(mesa);
                guardaMesaAlmacen(mesa);
            }
        })

                
    }).fail(function(){
        alert("ERROR")
    })


})

function guardaMesaSala(mesa){
    $.ajax( "http://localhost:8000/api/mesa/"+mesa.attr("id"), 
    {
        method:"PUT",
        dataType:"json",
        data:{
            width: parseInt(mesa.width()),
            height: parseInt(mesa.height()),
            y: mesa.offset().top,
            x: mesa.offset().left
        }
    }).done(function(data){

        console.log("SE HAN ENVIADO LOS DATOS");

    }).fail(function(){
        alert("ERROR")
    })

}

function guardaMesaAlmacen(mesa){
    $.ajax( "http://localhost:8000/api/mesa/"+mesa.attr("id"), 
    {
        method:"PUT",
        dataType:"json",
        data:{
            width: parseInt(mesa.width()),
            height: parseInt(mesa.height()),
            y: 0,
            x: 0
        }
    }).done(function(data){

        console.log("SE HAN ENVIADO LOS DATOS");

    }).fail(function(){
        alert("ERROR")
    })

}