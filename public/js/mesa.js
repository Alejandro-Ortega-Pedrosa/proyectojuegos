function Mesa(id, width, height, y, x){
    this.id=id;
    this.width=width;
    this.height=height; 
    this.y=y; 
    this.x=x;
};

Mesa.prototype.pinta=function(almacen, sala){
    var mesa=$("<div>");

    mesa.height(this.height);
    mesa.width(this.width);
    mesa.css({background:"blue"});
    mesa.addClass("mesa");
    mesa.attr("id",this.id);
    
    if(this.y==0 && this.x==0){
        almacen.append(mesa);
    }else{
        mesa.css({position:"absolute",top:this.y+"px",left:this.x+"px"});
        sala.append(mesa);
    }

}

Mesa.prototype.solapa=function(mesa){

    //GUARDO MIS PROPIEDADES
    var miWidth=this.width;
    var miHeight=this.height;
    var miY=this.y;
    var miX=this.x;

    //GUARDO LAS PROPIEDADES CON LAS QUE VOY A COMPARAR
    var suWidth=mesa.width;
    var suHeight=mesa.height;
    var suY=mesa.y;
    var suX=mesa.x;

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
        (pos1[2]<=pos2[2] && pos1[3]>=pos2[3]))){
                    
        return true;
    }else{
        return false;
    }

}