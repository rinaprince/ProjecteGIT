


    window.openMenuModal = function () {
        var menuModal = document.getElementById('menu-modal');
        menuModal.style.display = 'block';
    }



    window.closeMenuModal = function() {
        var menuModal = document.getElementById('menu-modal');
        menuModal.style.display = 'none';

        var administrationMenu = document.getElementById('administration-menu');
        administrationMenu.style.display = 'none'; // Ocultar el menú de administración
        console.log('Hola');
    }

// Agregar un controlador de eventos al icono de usuario
//var usuarioIcon = document.getElementById('usuari');
//usuarioIcon.addEventListener('click', openMenuModal);

// Modificar el controlador de eventos para el botón de administración
    const administrationMenuBtn = document.getElementById('administration-menu-btn');

    window.openAdmin = function () {
        var administrationMenu = document.getElementById('administration-menu');

        if (administrationMenu.style.display === 'block') {
            administrationMenu.style.display = 'none';
        } else {
            administrationMenu.style.display = 'block';
        }
    }





// Agregar controladores de eventos al hacer clic en "Catàleg" y "Administració"
/*
document.getElementById('catalog-menu-btn').addEventListener('click', function (event) {
    event.preventDefault(); // Prevenir el comportamiento predeterminado del enlace
    var catalogMenu = document.getElementById('catalog-menu');
    catalogMenu.style.display = (catalogMenu.style.display === 'block') ? 'none' : 'block';
});
*/

