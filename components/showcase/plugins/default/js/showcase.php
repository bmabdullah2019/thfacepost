//<script>
var gallery = {
  show : function(img){
  // show() : show selected image in light box

    var clone = img.cloneNode(),
        domain = clone.src.substr(0, clone.src.lastIndexOf("/",clone.src.lastIndexOf("/")-1)+1),
        image = clone.src.substr(clone.src.lastIndexOf("/")+1),
        front = document.getElementById("lfront"),
        back = document.getElementById("lback");

    clone.src = domain + "gallery/" + image;
    front.innerHTML = "";
    front.appendChild(clone);
    back.classList.add("show");
  },

  hide : function(){
  // hide() : hide the lightbox

    document.getElementById("lback").classList.remove("show");
  }
};