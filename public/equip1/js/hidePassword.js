function main(){
    const password = document.getElementById('password');
    const eyeDiv = document.getElementById('hideEye');
    const eyeIcon = document.querySelector('#hideEye>svg');

    eyeDiv.addEventListener('click',function (){
        if (password.getAttribute('type') === 'password'){
            password.removeAttribute('type')
            password.setAttribute('type','text')
            eyeDiv.innerHTML = "<i class='bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 small'></i>"
        }
        else {
            password.removeAttribute('type')
            password.setAttribute('type','password')
            eyeDiv.innerHTML = "<i class='bi bi-eye position-absolute top-50 end-0 translate-middle-y me-3 small'></i>"
        }
    })
    console.log(eyeDiv.textContent)
}

document.addEventListener('DOMContentLoaded',main);