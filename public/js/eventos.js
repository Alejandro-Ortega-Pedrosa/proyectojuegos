$(function(){

    for(let i=0;i<$(".evento").length;i++){
        
        let rYear = $(".fecha").eq(i).text().substring(7, 11);
        let rMonth = $(".fecha").eq(i).text().substring(12, 14);
        let rDay = $(".fecha").eq(i).text().substring(15, 17);

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

        var f1 = new Date(rYear, rMonth, rDay);
        var f2 = new Date(year, month, day);

        //SI LA FECHA DE RESERVA YA HA PASADO EL BOTON DE CANCELAR SALE DESACTIVADO
        if(f2<=f1){
            $(".evento").eq(i).addClass("activo");
        }else{
            $(".evento").eq(i).addClass("pasado");
        }

    }

})

    

        
  



