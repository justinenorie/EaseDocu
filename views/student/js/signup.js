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