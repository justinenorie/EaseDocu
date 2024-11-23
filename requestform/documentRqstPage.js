const certificatePrice = 50;


function updateTotal() {
    const doc1 = document.getElementById('doc1');
    const doc2 = document.getElementById('doc2');
    const doc3 = document.getElementById('doc3');
    
  
    const quantity1 = parseInt(document.getElementById('quantity1').value);
    const quantity2 = parseInt(document.getElementById('quantity2').value);
    const quantity3 = parseInt(document.getElementById('quantity3').value);
    
    let totalPayment = 0;

  
    if (doc1.checked) {
        const total1 = certificatePrice * quantity1;
        document.getElementById('certificateTotal1').innerText = `P${total1.toFixed(2)}`;
        totalPayment += total1;
    } else {
        document.getElementById('certificateTotal1').innerText = 'P0.00';
    }

 
    if (doc2.checked) {
        const total2 = certificatePrice * quantity2;
        document.getElementById('certificateTotal2').innerText = `P${total2.toFixed(2)}`;
        totalPayment += total2;
    } else {
        document.getElementById('certificateTotal2').innerText = 'P0.00';
    }


    if (doc3.checked) {
        const total3 = certificatePrice * quantity3;
        document.getElementById('certificateTotal3').innerText = `P${total3.toFixed(2)}`;
        totalPayment += total3;
    } else {
        document.getElementById('certificateTotal3').innerText = 'P0.00';
    }


    document.getElementById('totalPayment').innerText = `P${totalPayment.toFixed(2)}`;
}

// Increment quantity
function incrementQuantity(quantityId) {
    const quantityInput = document.getElementById(quantityId);
    quantityInput.value = parseInt(quantityInput.value) + 1;
    updateTotal();
}

// Decrement quantity
function decrementQuantity(quantityId) {
    const quantityInput = document.getElementById(quantityId);
    if (quantityInput.value > 1) {
        quantityInput.value = parseInt(quantityInput.value) - 1;
        updateTotal();
    }
}
