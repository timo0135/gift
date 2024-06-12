document.querySelectorAll('.prestation-quantity input').forEach(function(input) {
    input.addEventListener('change', function() {
        let quantity = parseInt(input.value);
        let prestation_id = input.getAttribute('data-prestation-id');
        let box_id = input.getAttribute('data-box-id');
        let prestation_price = input.getAttribute('data-prestation-price');
        fetch(`/box/${box_id}/prestation/${prestation_id}/update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ quantity: quantity })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`price-${prestation_id}`).textContent = 'Prix Total : '+ (quantity * prestation_price)+'€';
                    document.getElementById('total-price').textContent ='Prix Total : '+ data.total_price.toFixed(2) + '€';
                }
            })
    });
});

const quantityInputs = document.querySelectorAll('input[type="number"]');

quantityInputs.forEach(input => {
    // Handle both input and change events
    input.addEventListener('input', validateQuantity);
    input.addEventListener('change', validateQuantity);
});

function validateQuantity(event) {
    if (event.target.value < 1) {
        event.target.value = 1;
    }
    updateTotalPrice(event.target);
}

function updateTotalPrice(input) {
    const prestationId = input.getAttribute('data-prestation-id');
    const prestationPrice = parseFloat(input.getAttribute('data-prestation-price'));
    const quantity = parseInt(input.value);

    const totalPriceElement = document.getElementById(`price-${prestationId}`);
    totalPriceElement.textContent = `Prix Total : ${quantity * prestationPrice}€`;
}