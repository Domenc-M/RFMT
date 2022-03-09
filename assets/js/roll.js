const rollButton = document.querySelector('#show-roll-button');
const resultContainer = document.querySelector('#roll-result-container');

window.onload = function() {
    rollButton.addEventListener("click", tableRoll);
}

function tableRoll()
{
    random = Math.trunc(Math.random() * array.length);

    randomDisplay = random+1;
    resultContainer.innerHTML = "<h3 class='rollResultTitle'>Résultat n°"+randomDisplay+" : </h3><div class='rollResultText'>"+array[random]+"</div>";
}