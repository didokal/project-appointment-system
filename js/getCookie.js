function getCookie(nombreCookie){
    cName = "";
    pCOOKIES = new Array();
    pCOOKIES = document.cookie.split('; ');
    for(bb = 0; bb < pCOOKIES.length; bb++){
        NmeVal  = new Array();
        NmeVal  = pCOOKIES[bb].split('=');
        if(NmeVal[0] == nombreCookie){
            cName = unescape(NmeVal[1]);
        }
    }
    return cName;
}