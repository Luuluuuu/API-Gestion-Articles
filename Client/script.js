// function ajaxEnregistrer() {
//     // Récupération des paramètres
//     let pseudo = document.getElementById("pseudo").value;
//     let phrase = document.getElementById("phrase").value;
//     if (phrase !== "" && pseudo !== "") {
//         $.ajax({
//             url: "enregistrer.php",
//             data: {"pseudo":pseudo, "phrase":phrase},
//             type: "get",
//             success: function(msg){
//                 // Si le 
//                 if (document.getElementById("pseudo").hidden === false){
//                     document.getElementById("pseudo").hidden = true;

//                 }
//                 document.getElementById("phrase").value = null;
//                 document.getElementById("erreur").innerText = "";
//             }
//         })
//     } else {
//         document.getElementById("erreur").innerText = "Veuillez compléter tous les champs de saisie.";
//     }
// }

// b$("#envoyer").click(function(){
//     ajaxEnregistrer();
// });