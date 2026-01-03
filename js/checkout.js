// --- Script Asli Anda ---
const metode = document.getElementById('metode_pembayaran');
const transferInfo = document.getElementById('transfer-info');
const ewalletInfo = document.getElementById('ewallet-info');
const bankSelect = document.getElementById('bank-select');
const ewalletSelect = document.getElementById('ewallet-select');

metode.addEventListener('change', () => {
    transferInfo.style.display = 'none';
    ewalletInfo.style.display = 'none';
    document.querySelectorAll('.payment-option').forEach(e => e.style.display = 'none');

    if (metode.value === 'Transfer Bank') transferInfo.style.display = 'block';
    else if (metode.value === 'E-Wallet') ewalletInfo.style.display = 'block';
});

bankSelect.addEventListener('change', () => {
    document.querySelectorAll('#transfer-info .payment-option').forEach(e => e.style.display = 'none');
    if (bankSelect.value) document.getElementById(bankSelect.value).style.display = 'block';
});

ewalletSelect.addEventListener('change', () => {
    document.querySelectorAll('#ewallet-info .payment-option').forEach(e => e.style.display = 'none');
    if (ewalletSelect.value) document.getElementById(ewalletSelect.value).style.display = 'block';
});

// --- Script Tombol Animasi Baru ---
const orderButton = document.querySelector(".order");
const checkoutForm = orderButton ? orderButton.closest('form') : null;

if (orderButton) { 
    orderButton.addEventListener("click", function (e) {
        e.preventDefault();

        let button = this;
        
        if (checkoutForm && typeof checkoutForm.checkValidity === 'function' && checkoutForm.checkValidity()) {
            if (!button.classList.contains("animate")) {
                button.classList.add("animate");

                // Set CSS variables for button width and elements initial X position
                const buttonWidth = button.offsetWidth;
                button.style.setProperty('--button-width', `${buttonWidth}px`);
                
                const runnerInitialX = orderButton.querySelector('.runner').offsetLeft;
                const dogInitialX = orderButton.querySelector('.dog').offsetLeft;
                button.style.setProperty('--runner-start-pos-x', `${runnerInitialX}px`);
                button.style.setProperty('--dog-start-pos-x', `${dogInitialX}px`); // New variable for dog's start position


                // Tunda pengiriman form selama 7.5 detik (sesuai total durasi animasi)
                setTimeout(() => {
                    checkoutForm.submit();
                }, 7500); // 7.5 seconds to allow full animation to play out
            }
        } else if (checkoutForm && typeof checkoutForm.reportValidity === 'function') {
            checkoutForm.reportValidity();
        }
    });
}
