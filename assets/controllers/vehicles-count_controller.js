import { Controller } from "@hotwired/stimulus"
export default class extends Controller {
    static targets = [ "counter"];

    connect(){
        this.load();
    }
    async load() {
        try {
            const response = await fetch('/api/v1/count');
            const data = await response.json();

            if (data.count_number === 0){
                this.counterTarget.style = 'display: none';
            }

            if (data.count_number) {
                this.counterTarget.textContent = data.count_number;
            }
        } catch (error) {
            console.error('Fetch error:', error)
        }
    }
}