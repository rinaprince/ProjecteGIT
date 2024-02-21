import { Controller } from "@hotwired/stimulus"
export default class extends Controller {
    static targets = [ "password","eye"];

    ocultPassword(){
        if (this.eyeTarget.classList.contains('bi-eye')){
            this.eyeTarget.classList.remove('bi-eye');
            this.eyeTarget.classList.add('bi-eye-slash');
            this.passwordTarget.removeAttribute('type');
            this.passwordTarget.setAttribute('type','text');
        }
        else if (this.eyeTarget.classList.contains('bi-eye-slash')){
            this.eyeTarget.classList.remove('bi-eye-slash');
            this.eyeTarget.classList.add('bi-eye');
            this.passwordTarget.removeAttribute('type');
            this.passwordTarget.setAttribute('type','password');
        }
    }
}