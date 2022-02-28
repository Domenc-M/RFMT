const changingTitle = document.querySelectorAll('.changing-title');
var random;
var titleArray;

window.onload = function() {
    titleArray = [
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
    random = Math.trunc(Math.random() * titleArray.length);
    changingTitle.forEach(function(element) {
        element.innerHTML = titleArray[random];
    })
    
}