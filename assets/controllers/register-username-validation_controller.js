import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["username"];

    connect() {
        this.load();
    }

    async load() {
        this.usernameTarget.addEventListener('input', async () => {
            const username = this.usernameTarget.value;
            try {
                const response = await fetch('/api/v1/username-validation', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username: username })
                });
                const data = await response.json();
                if (data.username_exists) {
                    this.usernameTarget.classList.remove('border-black');
                    this.usernameTarget.classList.remove('border-success');
                    this.usernameTarget.classList.add('border-3');
                    this.usernameTarget.classList.add('border-danger');
                } else {
                    this.usernameTarget.classList.remove('border-black');
                    this.usernameTarget.classList.remove('border-danger');
                    this.usernameTarget.classList.add('border-3');
                    this.usernameTarget.classList.add('border-success');
                }
            } catch (error) {
                console.error('Fetch error:', error);
            }
        });
    }
}
