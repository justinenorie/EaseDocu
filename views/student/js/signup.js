const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');

const toggleBtn = document.querySelector('.password-toggle a');
const toggleImg = document.querySelector('.password-toggle img');
const passwordInput = document.querySelector('#confirmPassword');

// Animation
window.onload = function() {
    const textElement = document.getElementById('docuText');
    const textContent = textElement.textContent;
    textElement.innerHTML = '';

    textContent.split('').forEach((char, index) => {
      const span = document.createElement('span');
      span.textContent = char;
      
      span.style.animationDelay = `${index * 0.1}s`; 
      textElement.appendChild(span);
    });
};

inputs.forEach(input => {
    input.addEventListener('click', function(e) {

        inputs.forEach(input => {
            input.classList.remove('active-input');
        });
        
        e.target.classList.add('active-input');
    });
});

document.addEventListener('click', function(e) {
    if (!e.target.matches('input[type="text"], input[type="email"], input[type="password"]')) {
        inputs.forEach(input => {
            input.classList.remove('active-input');
        });
    }
});

if (toggleBtn && toggleImg && passwordInput) {  
    toggleBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleImg.src = '../../public/images/icons/pw-toggle-show.png';
        } else {
            passwordInput.type = 'password';
            toggleImg.src = '../../public/images/icons/pw-toggle-hide.png';
        }
    });
}

// TODO: Not final need to change based on the database
document.querySelector('#signup-form').addEventListener('submit', function(event){
    event.preventDefault();
    alertSignupSuccess();
});

//Signup Success Popup
function alertSignupSuccess() {
    event.preventDefault();
    Swal.fire({
        position: "center",
        icon: "success",
        background: "#F5F5F5",
        title: "Successfull",
        text: "Successfully Registered! You can now Login to your account....",
        showConfirmButton: false,
        timer: 3000
    });
    setTimeout(() => {
        window.location.href = "login.php";
    }, 1500);
}