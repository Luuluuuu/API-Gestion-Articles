const iconLike = document.querySelectorAll(".btn-like .icon-like");
const labelNbLike = document.querySelectorAll(".btn-like .nb-like");

for (let i = 0; i < iconLike.length; i++) {
    let nbLike = labelNbLike[i].innerHTML;
    iconLike[i].addEventListener("click", () => {
    iconLike[i].classList.toggle("liked");
    if (iconLike[i].classList.contains("liked")){
        nbLike++;
        
    } else {
        nbLike--;
    }

    labelNbLike[i].innerHTML = nbLike;
})
}


function deconnexion() {
    $.ajax({
        url: "deconnexion.php",
        data: {},
        type: "get",
        success: function(msg){
                window.location.reload();
            }
        })
}