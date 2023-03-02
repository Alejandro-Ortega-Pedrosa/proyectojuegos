function accionPlay()
{
  if(!medio.paused && !medio.ended) 
  {
    medio.pause();
    play.value='\u25BA'; //\u25BA
  }
  else
  {
    medio.play();
    play.value='||';
  }
}
function accionReiniciar()
{
    medio.load();
    play.value='\u25BA';
}
function accionRetrasar()
{
    medio.currentTime -= 5
}
function accionAdelantar()
{
  medio.currentTime += 5
}

function iniciar() 
{
  
  medio=document.getElementById('medio');
  medio2=document.getElementById('medio2');
  play=document.getElementById('play');
  reiniciar=document.getElementById('reiniciar');
  retrasar=document.getElementById('retrasar');
  adelantar=document.getElementById('adelantar');
  silenciar=document.getElementById('silenciar');

  play.addEventListener('click', accionPlay);
  reiniciar.addEventListener('click', accionReiniciar);
  retrasar.addEventListener('click', accionRetrasar);
  adelantar.addEventListener('click', accionAdelantar);

  play.addEventListener('click', accionPlay2);
  reiniciar.addEventListener('click', accionReiniciar2);
  retrasar.addEventListener('click', accionRetrasar2);
  adelantar.addEventListener('click', accionAdelantar2);
  silenciar.addEventListener('click', accionSilenciar);
  menosVolumen.addEventListener('click', accionMenosVolumen);
  masVolumen.addEventListener('click', accionMasVolumen);

  
}

function accionPlay2()
{
  if(!medio2.paused && !medio2.ended) 
  {
    medio2.pause();
    play.value='\u25BA'; //\u25BA
  }
  else
  {
    medio2.play();
    play.value='||';
  }
  }
function accionReiniciar2()
{
  medio2.load();
  play.value='\u25BA';
}
function accionRetrasar2()
{
  medio2.currentTime -= 5
}
function accionAdelantar2()
{
medio2.currentTime += 5
}

function accionSilenciar()
{
  if(medio2.muted==false){
      this.value="Activar sonido"
      medio2.muted = true;
  }else{
      this.value="Silenciar"
      medio2.muted = false;

  }

}
function accionMasVolumen()
{
  if(medio2.volume<1){
      medio2.volume += 0.1
    }
}
function accionMenosVolumen()
{
  if(medio2.volume>=0.1){
      medio2.volume -= 0.1
  }
}

window.addEventListener('load', iniciar, false);