document.addEventListener('DOMContentLoaded', function () {
    // Client-side validation for customer form
    const customerForm = document.querySelector('form[action*="/customers"]');
    if (customerForm) {
        customerForm.addEventListener('submit', function (e) {
            const name = document.getElementById('name').value;
            const phone = document.getElementById('phone').value;
            const address = document.getElementById('address').value;

            if (!name || !phone || !address) {
                e.preventDefault();
                alert('Please fill in all fields.');
            }
        });
    }

    // Client-side validation for order form
    const orderForm = document.querySelector('form[action*="/orders"]');
    if (orderForm) {
        orderForm.addEventListener('submit', function (e) {
            const customer = document.getElementById('customer_id').value;
            const service = document.getElementById('service_id').value;
            const weight = document.getElementById('weight').value;
            const pickup = document.getElementById('pickup_date').value;
            const delivery = document.getElementById('delivery_date').value;

            if (!customer || !service || !weight || !pickup || !delivery) {
                e.preventDefault();
                alert('Please fill in all fields.');
            } else if (new Date(delivery) < new Date(pickup)) {
                e.preventDefault();
                alert('Delivery date must be after or equal to pickup date.');
            }
        });
    }
});