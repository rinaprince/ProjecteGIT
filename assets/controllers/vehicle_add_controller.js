import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    async addVehicle(event) {
        event.preventDefault(); // Prevenir el comportamiento predeterminado del enlace

        const url = event.currentTarget.href;
        try {
            const response = await fetch(url, {
                method: 'POST', // Utilizamos el método POST para enviar la solicitud
                headers: {
                    'Content-Type': 'application/json', // Especificamos el tipo de contenido de la solicitud
                },
                cache: "no-cache",
            });

            if (response.ok) {
                // Manejar la respuesta según sea necesario
                console.log('Vehículo añadido exitosamente');
                // Puedes realizar alguna acción adicional si es necesario, como actualizar la página o mostrar un mensaje de éxito
            } else {
                console.error('Error al añadir el vehículo:', response.statusText);
                // Puedes manejar el error de alguna manera, por ejemplo, mostrando un mensaje de error al usuario
            }
        } catch (error) {
            console.error('Fetch error:', error);
        }
    }
}

