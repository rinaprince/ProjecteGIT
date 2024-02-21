import { Controller } from "@hotwired/stimulus"
import {isDisabled} from "bootstrap/js/src/util";
export default class extends Controller {
    static targets = [ "password", "password2","message"];
    confirm() {
        if (this.passwordTarget.value === this.password2Target.value){
            this.password2Target.classList.remove('border-danger');
            this.password2Target.classList.remove('border-black');
            this.password2Target.classList.add('border-success');
            this.password2Target.classList.add('border-2');
            this.password2Target.classList.add('border-opacity-75');
            this.messageTarget.textContent = "";
         }else {
            this.password2Target.classList.remove('border-success');
            this.password2Target.classList.remove('border-black');
            this.password2Target.classList.add('border-danger');
            this.password2Target.classList.add('border-2');
            this.password2Target.classList.add('border-opacity-75');
            this.messageTarget.textContent = "La contrasenya no coincideix";
            this.messageTarget.classList.add('text-danger');
         }
    }


}