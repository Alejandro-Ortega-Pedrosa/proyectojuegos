function Mesa(id, width, height, y, x){
    this.id=id;
    this.width=width;
    this.height=height; 
    this.y=y; 
    this.x=x;
};

Mesa.prototype.pinta=function(almacen, sala, y){
    var mesa=$("<div>");

    mesa.height(this.height);
    mesa.width(this.width);
    mesa.css({"background-size":this.width});
    mesa.addClass("mesa");
    mesa.attr("id",this.id);
    
    if(this.y==0 && this.x==0){
        almacen.append(mesa);
    }else{
        mesa.css({position:"absolute",top:(this.y-y)+"px",left:this.x+"px"});
        sala.append(mesa);
    }

}

Mesa.prototype.pintaReserva=function(sala, y){
    
    if(this.y!=0 && this.x!=0){
        var mesa=$("<div>");

        mesa.height(this.height);
        mesa.width(this.width);
        mesa.css({"background-size":this.width});
        mesa.addClass("mesa");
        mesa.attr("id",this.id);

        mesa.css({position:"absolute",top:(this.y-y)+"px",left:this.x+"px"});
        sala.append(mesa);
    }

}

Mesa.prototype.solapa=function(mesas){

    var choque=[];
   
    //GUARDO MIS PROPIEDADES
    var miId=this.id;
    var miWidth=this.width;
    var miHeight=this.height;
    var miY=this.y;
    var miX=this.x;

    for(let i=0;i<mesas.length;i++){
        //GUARDO LAS PROPIEDADES CON LAS QUE VOY A COMPARAR
        var suId=mesas[i].attr("id");
        var suWidth=mesas[i].width();
        var suHeight=mesas[i].height();
        var suY=parseInt(mesas[i].offset().top);
        var suX=parseInt(mesas[i].offset().left);

        //CREO DOS ARRAY CON LA POSICION DE MI MESA Y CON LA POSICION CON LA QUE LA VOY A COMPARAR
        pos1=[miX,miX+miWidth,miY,miY+miHeight];
        pos2=[suX,suX+suWidth,suY,suY+suHeight];

        //COMPARO LAS POSICIONES SI NO SE CHOCA DEVUELVE TRUE
        if(((pos1[0]>pos2[0] && pos1[0]<pos2[1]) || 
            (pos1[1]>pos2[0] && pos1[1]<pos2[1]) || 
            (pos1[0]<=pos2[0] && pos1[1]>=pos2[1])) 
                        
            && 
                        
            ((pos1[2]>pos2[2] && pos1[2]<pos2[3]) || 
            (pos1[3]>pos2[2] && pos1[3]<pos2[3]) || 
            (pos1[2]<=pos2[2] && pos1[3]>=pos2[3]))
            
            &&
            
            (miId!=suId)){
                        
            choque.push("0")
        }
    }

    if(choque.length>1){
        return false;
    }else{
        return true;
    }
}