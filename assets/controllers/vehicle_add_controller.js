import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["button"]; // Define un target llamado "button"

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
                console.log('Vehículo  exitosamente');

                // Cambiar el texto y el estilo del botón a "Reservado"
                this.buttonTarget.textContent = 'Reservado';
                this.buttonTarget.classList.remove('btn-primary');
                this.buttonTarget.classList.add('btn-secondary');
            } else {
                console.error('Error al añadir el vehículo:', response.statusText);
                // Puedes manejar el error de alguna manera, por ejemplo, mostrando un mensaje de error al usuario
            }
        } catch (error) {
            console.error('Fetch error:', error);
        }
    }
}
