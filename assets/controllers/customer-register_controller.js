import { Controller } from "@hotwired/stimulus"
import axios from 'axios';
export default class extends Controller {
    static targets = [ "modal"];

    professional() {
        axios.get('/register/company')
            .then(response => {
                this.modalTarget.innerHTML = response.data;
                console.log(response.data)
            })
            .catch(error => {
                console.error('Error fetching modal content:', error);
            });
    }
    private() {
        //this.modalTarget.textContent = "<div {{vue_component('PrivateCustomerNewFront_component')}}></div>";
        axios.get('/register/particular')
            .then(response => {
                this.modalTarget.innerHTML = response.data;
                console.log(response.data)
            })
            .catch(error => {
                console.error('Error fetching modal content:', error);
            });
    }
}