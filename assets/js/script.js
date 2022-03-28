
window.addEventListener('DOMContentLoaded', event => {

    // Navbar shrink function
    var navbarShrink = function () {
        const navbarCollapsible = document.body.querySelector('#mainNav');
        if (!navbarCollapsible) {
            return;
        }
        if (window.scrollY === 0) {
            navbarCollapsible.classList.remove('navbar-shrink')
        } else {
            navbarCollapsible.classList.add('navbar-shrink')
        }

    };

    // Shrink the navbar 
    navbarShrink();

    // Shrink the navbar when page is scrolled
    document.addEventListener('scroll', navbarShrink);

    // Activate Bootstrap scrollspy on the main nav element
    const mainNav = document.body.querySelector('#mainNav');
    if (mainNav) {
        new bootstrap.ScrollSpy(document.body, {
            target: '#mainNav',
            offset: 74,
        });
    };

    // Collapse responsive navbar when toggler is visible
    const navbarToggler = document.body.querySelector('.navbar-toggler');
    const responsiveNavItems = [].slice.call(
        document.querySelectorAll('#navbarResponsive .nav-link')
    );
    responsiveNavItems.map(function (responsiveNavItem) {
        responsiveNavItem.addEventListener('click', () => {
            if (window.getComputedStyle(navbarToggler).display !== 'none') {
                navbarToggler.click();
            }
        });
    });

});



// confirmation de la suppression article (page gestion_salles.php)
let listBouton = document.getElementsByClassName('confirm_delete');

if(listBouton.length) {
    for(let i = 0; i < listBouton.length; i++) {
        listBouton[i].addEventListener('click', function (e) {
            let choix = confirm('Etes-vous sûr ?');
            // console.log(choix);

            if(choix == false) {
                e.preventDefault();
            }
        });
    }
}

// équivalent en html <a href="" onclick="return(confirm('Etes-vous sûr ?'))"></a>

// jQuery gestion des dates
$( function() {
    $( "#date_arrivee" ).datepicker();
    $( "#date_depart" ).datepicker();
    $( "#filtre_arrivee" ).datepicker();
    $( "#filtre_depart" ).datepicker();
});

$.datepicker.regional['fr'] = {clearText: 'Effacer', clearStatus: '',
closeText: 'Fermer', closeStatus: 'Fermer sans modifier',
prevText: '&lt;Préc', prevStatus: 'Voir le mois précédent',
nextText: 'Suiv&gt;', nextStatus: 'Voir le mois suivant',
currentText: 'Courant', currentStatus: 'Voir le mois courant',
monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin',
'Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
monthNamesShort: ['Jan','Fév','Mar','Avr','Mai','Jun',
'Jul','Aoû','Sep','Oct','Nov','Déc'],
monthStatus: 'Voir un autre mois', yearStatus: 'Voir un autre année',
weekHeader: 'Sm', weekStatus: '',
dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
dayNamesShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
dayNamesMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
dayStatus: 'Utiliser DD comme premier jour de la semaine', dateStatus: 'Choisir le DD, MM d',
dateFormat: 'yy-mm-dd', firstDay: 0, 
initStatus: 'Choisir la date', isRTL: false};
$.datepicker.setDefaults($.datepicker.regional['fr']);
