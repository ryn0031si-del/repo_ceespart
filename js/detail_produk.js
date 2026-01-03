// Menunggu semua konten HTML dimuat sebelum menjalankan script
document.addEventListener("DOMContentLoaded", () => {

    // 🛒 Variabel untuk elemen-elemen di halaman
    const minusBtn = document.querySelector('.quantity-btn.minus');
    const plusBtn = document.querySelector('.quantity-btn.plus');
    const quantityInput = document.querySelector('.quantity-input');
    const pesanBtn = document.querySelector('.btn-order');
    const addToCartBtn = document.getElementById('btn-add-to-cart'); // Tombol baru
    
    // Pastikan semua elemen ada sebelum menambahkan event listener
    if (minusBtn && plusBtn && quantityInput) {
        
        const maxStock = parseInt(quantityInput.max) || 1; // Ambil stok maks

        minusBtn.addEventListener('click', () => {
            let value = parseInt(quantityInput.value);
            if (value > 1) {
                quantityInput.value = value - 1;
                updateLink(); // Update link "Pesan Sekarang"
            }
        });

        plusBtn.addEventListener('click', () => {
            let value = parseInt(quantityInput.value);
            if (value < maxStock) {
                quantityInput.value = value + 1;
                updateLink(); // Update link "Pesan Sekarang"
            }
        });
        
        // Jaga agar user tidak input manual melebihi stok
        quantityInput.addEventListener('change', () => {
            let value = parseInt(quantityInput.value);
            if (value > maxStock) quantityInput.value = maxStock;
            if (value < 1) quantityInput.value = 1;
            updateLink();
        });
    }

    // 🔄 Update link "Pesan Sekarang" sesuai quantity (Script Asli Anda)
    function updateLink() {
        if (pesanBtn && quantityInput) {
            const qty = quantityInput.value;
            // Ambil URL dasar dari href dan hapus query string lama
            const baseUrl = pesanBtn.href.split('?')[0]; 
            const productId = pesanBtn.href.match(/id=(\d+)/)[1]; // Ambil ID dari link
            
            const newUrl = `${baseUrl}?id=${productId}&qty=${qty}`;
            pesanBtn.setAttribute('href', newUrl);
        }
    }
    
    // --- 🚀 FUNGSI BARU: Tambah ke Keranjang (AJAX) ---
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', (e) => {
            const productId = e.currentTarget.dataset.id;
            const quantity = quantityInput.value;
            
            // Siapkan data untuk dikirim
            const formData = new FormData();
            formData.append('id_produk', productId);
            formData.append('quantity', quantity);
            
            // Kirim data ke server menggunakan fetch
            fetch('tambah_ke_keranjang.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // Ubah respons server ke JSON
            .then(data => {
                // Tampilkan pesan dari server
                if (data.status === 'success') {
                    alert(data.message); // "Produk ditambahkan ke keranjang!"
                    // Opsional: update ikon keranjang di header
                    // updateCartIcon(data.cartCount); 
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menambahkan ke keranjang.');
            });
        });
    }
    
}); // Akhir dari DOMContentLoaded