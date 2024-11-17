// Function to handle quantity changes
document.addEventListener('DOMContentLoaded', function() {
    // Find all quantity control elements
    const quantityControls = document.querySelectorAll('.quantity-controls');
    
    quantityControls.forEach(control => {
        const minusBtn = control.querySelector('.quantity-btn:first-child');
        const plusBtn = control.querySelector('.quantity-btn:last-child');
        const quantitySpan = control.querySelector('.quantity');
        const checkbox = control.closest('.list-item').querySelector('.checkbox');
        
        // Initialize quantity
        let quantity = parseInt(quantitySpan.textContent);
        
        // Handle minus button click
        minusBtn.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent form submission
            if (quantity > 0) {
                quantity--;
                quantitySpan.textContent = quantity;
                updatePaymentList();
                
                // If quantity becomes 0, uncheck the checkbox
                if (quantity === 0) {
                    checkbox.checked = false;
                }
            }
        });
        
        // Handle plus button click
        plusBtn.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent form submission
            quantity++;
            quantitySpan.textContent = quantity;
            checkbox.checked = true; // Check the checkbox when quantity increases
            updatePaymentList();
        });
        
        // Handle checkbox change
        checkbox.addEventListener('change', function() {
            if (!this.checked) {
                quantity = 0;
                quantitySpan.textContent = quantity;
                updatePaymentList();
            } else if (quantity === 0) {
                quantity = 1;
                quantitySpan.textContent = quantity;
                updatePaymentList();
            }
        });
    });
    
    // Function to update payment list
    function updatePaymentList() {
        const paymentList = document.getElementById('paymentList');
        paymentList.innerHTML = ''; // Clear current payment list
        
        // Get all list items with quantity > 0
        document.querySelectorAll('.list-item').forEach(item => {
            const quantity = parseInt(item.querySelector('.quantity').textContent);
            if (quantity > 0) {
                const documentName = item.querySelector('.item-name h2').textContent;
                const price = item.querySelector('.price').textContent;
                
                // Create payment list item
                const listItem = document.createElement('li');
                listItem.classList.add('list-item-payment');
                
                const docPaymentDiv = document.createElement('div');
                docPaymentDiv.classList.add('list-doc-payment');
                
                docPaymentDiv.innerHTML = `
                    <p class="quantity">${quantity}x</p>
                    <p class="document-name">${documentName}</p>
                    <p class="document-price">${price}</p>
                `;
                
                listItem.appendChild(docPaymentDiv);
                paymentList.appendChild(listItem);
            }
        });
    }
});