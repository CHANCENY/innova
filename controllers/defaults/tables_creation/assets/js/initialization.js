

function startTableCreation(evt)
{
 const image = document.getElementById("loading");
 image.src = image.getAttribute("data");
 const xhr = new XMLHttpRequest();
 const host = evt.getAttribute("data");
 xhr.open("GET", host +"/database/tables/initialization/3", true);
 xhr.setRequestHeader("Content-Type","application/json");
 xhr.onload = function (){
  if(this.status === 200)
  {
     window.location.replace(host+ "/users/register");
  }
 }
 xhr.send();
}