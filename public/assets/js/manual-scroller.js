//Manual Scroller- &copy; Dynamic Drive 2001
//For full source code, visit http://www.dynamicdrive.com

//specify speed of scroll (greater=faster)
var speed=2

iens6=document.all||document.getElementById
ns4=document.layers

if (iens6){
document.write('<div id="container" style="position:relative;width:300px;height:625px;overflow:hidden; margin:15px 0px 0px 0px;">')
document.write('<div id="content" style="position:absolute;width:300px;left:0px;top:0px">')
}
//<!--
//</script><div style="position:relative;width:300px;height:625px;overflow:hidden; margin:15px 0px 0px 0px;" id="container"><div style="position:absolute;width:300px;left:0px;top:0px" id="content">
//<script language="JavaScript1.2">
//-->
if (iens6){
document.write('</div></div>')
var crossobj=document.getElementById? document.getElementById("content") : document.all.content
var contentheight=crossobj.offsetHeight
}
else if (ns4){
var crossobj=document.nscontainer.document.nscontent
var contentheight=crossobj.clip.height
}

function movedown(){
if (window.moveupvar) clearTimeout(moveupvar)
if (iens6&&parseInt(crossobj.style.top)>=(contentheight*(-1)+100))
crossobj.style.top=parseInt(crossobj.style.top)-speed+"px"
else if (ns4&&crossobj.top>=(contentheight*(-1)+100))
crossobj.top-=speed
movedownvar=setTimeout("movedown()",20)
}

function moveup(){
if (window.movedownvar) clearTimeout(movedownvar)
if (iens6&&parseInt(crossobj.style.top)<=0)
crossobj.style.top=parseInt(crossobj.style.top)+speed+"px"
else if (ns4&&crossobj.top<=0)
crossobj.top+=speed
moveupvar=setTimeout("moveup()",20)
}

function stopscroll(){
if (window.moveupvar) clearTimeout(moveupvar)
if (window.movedownvar) clearTimeout(movedownvar)
}

function movetop(){
stopscroll()
if (iens6)
crossobj.style.top=0+"px"
else if (ns4)
crossobj.top=0
}

function getcontent_height(){
if (iens6)
contentheight=crossobj.offsetHeight
else if (ns4)
document.nscontainer.document.nscontent.visibility="show"
}
window.onload=getcontent_height
//eof