const toggleBtn = document.querySelector('.password-toggle a');
const toggleImg = document.querySelector('.password-toggle img');
const passwordInput = document.querySelector('#password');

const ConfirmtoggleBtn = document.querySelector('.confirm-password-toggle a');
const ConfirmtoggleImg = document.querySelector('.confirm-password-toggle img');
const confirmPasswordInput = document.querySelector('#confirmPassword');


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

//Confirm Password
if (ConfirmtoggleBtn && ConfirmtoggleImg && confirmPasswordInput) {  
    ConfirmtoggleBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (confirmPasswordInput.type === 'password') {
            confirmPasswordInput.type = 'text';
            ConfirmtoggleImg.src = '../../public/images/icons/pw-toggle-show.png';
        } else {
            confirmPasswordInput.type = 'password';
            ConfirmtoggleImg.src = '../../public/images/icons/pw-toggle-hide.png';
        }
    });
}

//Login Success Popup
function alertLoginSuccess() {
    event.preventDefault();
    Swal.fire({
        position: "center",
        icon: "success",
        background: "#F5F5F5",
        title: "Successfull",
        text: "Successfuly logged in!",
        showConfirmButton: false,
        timer: 2500
    });
}

//Failed Admin Login Popup
function alertFailAdminLogin() {
    event.preventDefault();
    Swal.fire({
        position: "center",
        icon: "error",
        background: "#F5F5F5",
        title: "Login Failed",
        text: "The Username Admin and Password you entered is incorrect! Please try again.....",
        showConfirmButton: false,
        timer: 2500
    });
}

//Failed Student Login Popup
function alertFailStudentLogin() {
    event.preventDefault();
    Swal.fire({
        position: "center",
        icon: "error",
        background: "#F5F5F5",
        title: "Login Failed",
        text: "The StudentID and Password you entered is incorrect! Please try again.....",
        showConfirmButton: false,
        timer: 2500
    });
}

