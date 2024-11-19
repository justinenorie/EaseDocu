document.addEventListener('DOMContentLoaded', function() {
    // Find all quantity control elements
    const quantityControls = document.querySelectorAll('.quantity-controls');
    
    // Track number of unique selected documents
    let uniqueDocumentsCount = 0;
    
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
                    uniqueDocumentsCount--;
                }
            }
        });
        
        // Handle plus button click
        plusBtn.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent form submission
            if (checkbox.checked && quantity < 3) {
                quantity++;
                quantitySpan.textContent = quantity;
                updatePaymentList();
                
                if (quantity === 3) {
                    alert('Maximum limit of 3 copies for this document reached!');
                }
            }
        });
        
        // Handle checkbox change
        checkbox.addEventListener('change', function() {
            if (!this.checked) {
                if (quantity > 0) {
                    uniqueDocumentsCount--;
                }
                quantity = 0;
                quantitySpan.textContent = quantity;
                updatePaymentList();
            } else {
                if (uniqueDocumentsCount >= 5) {
                    alert('Maximum limit of 5 different documents reached! You can add copies to already selected documents.');
                    this.checked = false;
                    return;
                }
                quantity = 1;
                uniqueDocumentsCount++;
                quantitySpan.textContent = quantity;
                updatePaymentList();
            }
        });
    });
    
    // Function to update payment list
    function updatePaymentList() {
        const paymentList = document.getElementById('paymentList');
        paymentList.innerHTML = ''; // Clear current payment list
        
        let totalPrice = 0;
        
        // Get all list items with quantity > 0
        document.querySelectorAll('.list-item').forEach(item => {
            const quantity = parseInt(item.querySelector('.quantity').textContent);
            if (quantity > 0) {
                const documentName = item.querySelector('.item-name h2').textContent;
                // Extract the numeric price value (removing the ₱ symbol)
                const priceText = item.querySelector('.price').textContent;
                const price = parseFloat(priceText.replace(/[₱,]/g, ''));
                const itemTotal = price * quantity;
                totalPrice += itemTotal;
                
                // Create payment list item
                const listItem = document.createElement('li');
                listItem.classList.add('list-item-payment');
                
                const docPaymentDiv = document.createElement('div');
                docPaymentDiv.classList.add('list-doc-payment');
                
                docPaymentDiv.innerHTML = `
                    <p class="quantity">${quantity}x</p>
                    <p class="document-name">${documentName}</p>
                    <p class="document-price">₱${itemTotal.toFixed(2)}</p>
                `;
                
                listItem.appendChild(docPaymentDiv);
                paymentList.appendChild(listItem);
            }
        });
        
        // Add total price if there are items in the list
        if (totalPrice > 0) {
            const totalDiv = document.createElement('div');
            totalDiv.classList.add('total-price');
            totalDiv.innerHTML = `<p><strong>Total: ₱${totalPrice.toFixed(2)}</strong></p>`;
            paymentList.appendChild(totalDiv);
        }
    }
});