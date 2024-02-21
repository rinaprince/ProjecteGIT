import { Controller } from "@hotwired/stimulus";
import Swal from 'sweetalert2';

export default class extends Controller {
    static targets = ["button"];

    async addVehicle(event) {
        event.preventDefault();

        const url = event.currentTarget.href;
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                cache: "no-cache",
            });

            console.log('Código de estado de la respuesta:', response.status);

            if (response.ok) {
                const responseData = await response.json();
                if (responseData.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Vehículo añadido exitosamente',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    this.buttonTarget.textContent = 'Reservado';
                    this.buttonTarget.classList.remove('btn-primary');
                    this.buttonTarget.classList.add('btn-secondary');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Inicia Sessió',
                        text: responseData.error
                    });
                }
            } else if (response.status === 401) {
                // El usuario no está autenticado, mostrar un mensaje de inicio de sesión
                Swal.fire({
                    icon: 'warning',
                    title: 'Acceso denegado',
                    text: 'Debe iniciar sesión para agregar vehículos.'
                });
            } else if (response.status === 403) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Acceso denegado',
                        text: 'Debe iniciar sesión para agregar vehículos.'
                    });

            }else if (response.status === 500) {

                Swal.fire({
                    icon: 'error',
                    title: 'No permes',
                    text: 'Este tipus de usuari no pot realitzar esta acció'
                });
            }
            else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ha ocurrido un error al agregar el vehículo.'
                });
            }
        } catch (error) {
            console.error('Fetch error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Inicia Sessió',
                text: 'Has de iniciar sessió.'
            });
        }
    }

}
