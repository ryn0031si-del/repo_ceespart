// ===== keranjang.js - Interaktif & Animatif =====
document.addEventListener('DOMContentLoaded', () => {

    const cartTable = document.querySelector('.cart-table tbody');
    const totalElem = document.querySelector('.cart-total-harga');

    // ===== Fungsi Update Subtotal & Total =====
    function updateTotals() {
        let total = 0;
        const rows = cartTable.querySelectorAll('tr');
        rows.forEach(row => {
            const harga = parseInt(row.querySelector('.cart-harga').dataset.harga);
            const jumlah = parseInt(row.querySelector('.quantity-input-cart').value);
            const subtotalElem = row.querySelector('.cart-subtotal');
            const subtotal = harga * jumlah;
            subtotalElem.textContent = formatRupiah(subtotal);
            total += subtotal;
        });
        totalElem.textContent = formatRupiah(total);
    }

    // ===== Fungsi Format Rupiah =====
    function formatRupiah(angka) {
        return 'Rp. ' + angka.toLocaleString('id-ID');
    }

    // ===== Update Jumlah (Live) =====
    const quantityInputs = document.querySelectorAll('.quantity-input-cart');

    quantityInputs.forEach(input => {
        input.addEventListener('input', (e) => {
            const row = e.target.closest('tr');
            const jumlahBaru = parseInt(e.target.value);
            const idProduk = row.querySelector('input[name="id_produk_update"]').value;

            if (jumlahBaru <= 0) {
                e.target.value = 1;
                return;
            }

            // Animasi highlight
            row.style.transition = 'background 0.3s';
            row.style.backgroundColor = '#d1f7d1';
            setTimeout(() => row.style.backgroundColor = '', 300);

            // Update subtotal & total secara langsung
            updateTotals();

            // Kirim request update ke server via fetch
            fetch('keranjang.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `update_jumlah=1&id_produk_update=${idProduk}&jumlah_baru=${jumlahBaru}`
            })
            .then(res => res.text())
            .then(data => {
                // Bisa gunakan untuk feedback sukses/error
                console.log('Update sukses');
            })
            .catch(err => console.error(err));
        });
    });

    // ===== Hapus Item dengan Animasi =====
    const btnHapusList = document.querySelectorAll('.btn-hapus');

    btnHapusList.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const row = btn.closest('tr');
            const idProduk = row.querySelector('input[name="id_produk_hapus"]').value;

            if (confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
                // Animasi fade-out
                row.style.transition = 'all 0.5s ease';
                row.style.opacity = 0;
                row.style.transform = 'translateX(-50px)';
                
                setTimeout(() => {
                    row.remove();
                    updateTotals();

                    // Kirim request hapus ke server via fetch
                    fetch('keranjang.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `hapus_item=1&id_produk_hapus=${idProduk}`
                    })
                    .then(res => res.text())
                    .then(data => console.log('Item dihapus'))
                    .catch(err => console.error(err));
                }, 500);
            }
        });
    });

});
