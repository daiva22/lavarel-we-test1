document.addEventListener('DOMContentLoaded', function () {
    console.log('cart.js loaded');

    document.addEventListener('click', function (e) {
        const button = e.target.closest('.ajax-add-to-cart-btn');
        if (!button) return;

        e.preventDefault();
        e.stopPropagation();

        console.log('AJAX click triggered');

        const url = button.dataset.url;
        const type = button.dataset.type;
        const id = button.dataset.id;
        const token = button.dataset.token;

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                type: type,
                id: id
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);

            if (data.success) {
                const cartCount = document.getElementById('cart-count');
                const cartMessage = document.getElementById('cart-message');

                if (cartCount) {
                    cartCount.textContent = data.cart_count;
                }

                if (cartMessage) {
                    cartMessage.textContent = data.message;
                    cartMessage.style.display = 'block';

                    setTimeout(() => {
                        cartMessage.style.display = 'none';
                    }, 2000);
                }
            }
        })
        .catch(error => {
            console.log(error);
            alert('Error adding to cart');
        });
    });
});