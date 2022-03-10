const rollButton = document.querySelector('#show-roll-button');
const resultContainer = document.querySelector('#roll-result-container');
const changingTitle = document.querySelectorAll('.changing-title');
var random;
const titleArray = [
    'Quand vos joueurs fouillent le 378ème gobelin',
    'Quand on vous demande pour la 18ème fois comment s\'appelle un figurant',
    'Quand vous avez oublié dans quelle ville était le donneur de quête',
    'Quand vos joueurs débarquent dans 5 minutes',
    'Quand vous en avez marre que toutes vos rencontres contiennent des orcs',
    'Quand il vous faut un artéfact pour soudoyer vos joueurs',
    'Quand les joueurs connaissent tous vos pièges',
    'Quand c\'est la cinquième fois que vous rejouez le même module',
    'Quand vous aimez beaucoup trop les tables aléatoires',
    'Quand les joueurs préfèrent les quêtes secondaires',
    'Quand vos idées n\'ont plus d\'idées',
];

window.onload = function() {
    if(rollButton != null)
    {
        rollButton.addEventListener("click", tableRoll);
    }

    random = Math.trunc(Math.random() * titleArray.length);
    changingTitle.forEach(function(element) {
        element.innerHTML = titleArray[random];
    })
}

function tableRoll()
{
    random = Math.trunc(Math.random() * array.length);

    randomDisplay = random+1;
    resultContainer.innerHTML = "<h3 class='rollResultTitle'>Résultat n°"+randomDisplay+" : </h3><div class='rollResultText'>"+array[random]+"</div>";
}