const cardNumberInput = document.getElementById('card-number');
const cardHolderInput = document.getElementById('card-holder');
const cardExpiryInput = document.getElementById('card-expiry');
const cardCvvInput = document.getElementById('card-cvv');
const paymentForm = document.getElementById('payment-form');

const cardNumberError = document.getElementById('card-number-error');
const cardHolderError = document.getElementById('card-holder-error');
const cardExpiryError = document.getElementById('card-expiry-error');
const cardCvvError = document.getElementById('card-cvv-error');

const isValidCardNumber = (number) => {
    // Suppression des espaces
    const sanitizedNumber = number.replace(/\s+/g, '');

    // Expression régulière pour Visa et MasterCard
    const visaMasterCardRegex = /^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|2(?:2[2-9][0-9]{2}|[3-6][0-9]{3}|7[01][0-9]{2}|720)[0-9]{12})$/;

    // Vérification du format
    if (!visaMasterCardRegex.test(sanitizedNumber)) {
        return false;
    }

    // Implémentation de l'algorithme de Luhn pour la vérification de la validité
    let sum = 0;
    let shouldDouble = false;

    // Parcourir le numéro de la fin au début
    for (let i = sanitizedNumber.length - 1; i >= 0; i--) {
        let digit = parseInt(sanitizedNumber[i], 10);

        if (shouldDouble) {
            digit *= 2;
            if (digit > 9) {
                digit -= 9;
            }
        }

        sum += digit;
        shouldDouble = !shouldDouble;
    }

    return sum % 10 === 0;
};

const isValidExpiry = (expiry) => {
    return /^(0[1-9]|1[0-2])\/\d{2}$/.test(expiry);
};

const isValidCvv = (cvv) => {
    return /^[0-9]{3}$/.test(cvv);
};

cardNumberInput.addEventListener('input', function() {
    const formattedNumber = this.value.replace(/\D/g, '').replace(/(.{4})/g, '$1 ').trim();
    this.value = formattedNumber;
    document.querySelector('.card-number').innerText = formattedNumber || '1234 5678 1234 5678';

    if (!isValidCardNumber(this.value)) {
        cardNumberError.style.display = 'block';
    } else {
        cardNumberError.style.display = 'none';
    }
});

cardHolderInput.addEventListener('input', function() {
    document.querySelector('.card-holder').innerText = this.value || 'John Doe';
});

cardExpiryInput.addEventListener('input', function() {
    const formattedExpiry = this.value.replace(/\D/g, '').replace(/(\d{2})(\d{2})/, '$1/$2').trim();
    this.value = formattedExpiry;
    document.querySelector('.card-expiry').innerText = formattedExpiry || '12/24';

    if (!isValidExpiry(this.value)) {
        cardExpiryError.style.display = 'block';
    } else {
        cardExpiryError.style.display = 'none';
    }
});

cardCvvInput.addEventListener('focus', function() {
    document.querySelector('.credit-card').classList.add('flipped');
});

cardCvvInput.addEventListener('blur', function() {
    document.querySelector('.credit-card').classList.remove('flipped');
});

cardCvvInput.addEventListener('input', function() {
    document.querySelector('.card-cvv').innerText = this.value || '123';

    if (!isValidCvv(this.value)) {
        cardCvvError.style.display = 'block';
    } else {
        cardCvvError.style.display = 'none';
    }
});

paymentForm.addEventListener('submit', function(event) {
    if (!isValidCardNumber(cardNumberInput.value) || !isValidExpiry(cardExpiryInput.value) || !isValidCvv(cardCvvInput.value)) {
        event.preventDefault();
        if (!isValidCardNumber(cardNumberInput.value)) {
            cardNumberError.style.display = 'block';
        }
        if (!isValidExpiry(cardExpiryInput.value)) {
            cardExpiryError.style.display = 'block';
        }
        if (!isValidCvv(cardCvvInput.value)) {
            cardCvvError.style.display = 'block';
        }
    }
});