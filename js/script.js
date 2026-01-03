document.addEventListener('DOMContentLoaded', () => {
    
    // 1. STICKY NAVBAR EFFECT
    // Memberikan efek background saat discroll ke bawah
    const header = document.querySelector('header');
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // 2. SCROLL REVEAL ANIMATION (Intersection Observer)
    // Membuat elemen muncul perlahan saat masuk ke layar
    const observerOptions = {
        threshold: 0.15, // Muncul saat 15% elemen terlihat
        rootMargin: "0px 0px -50px 0px"
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target); // Hanya animasi sekali
            }
        });
    }, observerOptions);

    // Target elemen yang akan dianimasikan
    const animatedElements = document.querySelectorAll('.feature-item, .product-card, .section-header, .hero-text, .hero-image');
    animatedElements.forEach(el => {
        el.classList.add('hidden-element'); // Tambah class awal via JS
        observer.observe(el);
    });

    // 3. 3D TILT EFFECT FOR PRODUCT CARDS
    // Efek kartu bergerak mengikuti mouse
    const cards = document.querySelectorAll('.product-card');

    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            // Hitung rotasi berdasarkan posisi mouse
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = ((y - centerY) / centerY) * -10; // Max rotasi 10deg
            const rotateY = ((x - centerX) / centerX) * 10;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.05)`;
        });

        // Reset saat mouse keluar
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
        });
    });

    // 4. HERO PARALLAX EFFECT
    // Gambar hero bergerak sedikit saat mouse digerakkan di area hero
    const heroSection = document.querySelector('.hero');
    const heroImg = document.querySelector('.hero-image img');

    if (heroSection && heroImg) {
        heroSection.addEventListener('mousemove', (e) => {
            const x = (window.innerWidth - e.pageX * 2) / 90;
            const y = (window.innerHeight - e.pageY * 2) / 90;
            
            heroImg.style.transform = `translateX(${x}px) translateY(${y}px)`;
        });
        
        // Reset saat mouse keluar
        heroSection.addEventListener('mouseleave', () => {
             heroImg.style.transform = `translateX(0) translateY(0)`;
        });
    }

    // 5. SMOOTH SCROLLING UNTUK LINK NAVIGASI
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // 6. NOTIFIKASI TOAST (Opsional: Saat klik beli/detail)
    const buttons = document.querySelectorAll('.btn-card, .btn-primary');
    buttons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            // Hapus baris ini jika ingin link berfungsi normal (pindah halaman)
            // e.preventDefault(); 
            
            // Efek ripple sederhana saat klik
            let x = e.clientX - e.target.offsetLeft;
            let y = e.clientY - e.target.offsetTop;
            
            let ripples = document.createElement('span');
            ripples.style.left = x + 'px';
            ripples.style.top = y + 'px';
            ripples.classList.add('ripple');
            this.appendChild(ripples);

            setTimeout(() => {
                ripples.remove();
            }, 1000);
        });
    });
});
//Produk//
document.addEventListener('DOMContentLoaded', () => {
    
    /* =========================================
       1. STICKY GLASSMORPHISM NAVBAR
       ========================================= */
    const header = document.querySelector('header');
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 20) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    /* =========================================
       2. SCROLL REVEAL ANIMATION (Intersection Observer)
       ========================================= */
    // Opsi: Elemen muncul ketika 15% bagiannya masuk layar
    const observerOptions = {
        threshold: 0.15,
        rootMargin: "0px 0px -50px 0px"
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target); // Animasi hanya sekali
            }
        });
    }, observerOptions);

    // Target elemen yang akan dianimasikan
    const animatedElements = document.querySelectorAll(
        '.hero-text, .hero-image, .feature-item, .product-card, .section-header, .search-container, .page-header'
    );

    animatedElements.forEach((el, index) => {
        el.classList.add('hidden-element'); // Set state awal tersembunyi
        // Tambahkan delay bertingkat untuk item dalam grid yang sama
        if(el.classList.contains('product-card') || el.classList.contains('feature-item')) {
            el.style.transitionDelay = `${(index % 4) * 0.1}s`; 
        }
        observer.observe(el);
    });

    /* =========================================
       3. 3D TILT EFFECT (Untuk Product Card)
       ========================================= */
    const cards = document.querySelectorAll('.product-card, .feature-item');

    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            // Hitung titik tengah
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            // Kalkulasi rotasi (Max 15 derajat)
            const rotateX = ((y - centerY) / centerY) * -10;
            const rotateY = ((x - centerX) / centerX) * 10;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
        });

        // Reset posisi saat mouse keluar
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
        });
    });

    /* =========================================
       4. HERO PARALLAX EFFECT (Hanya di Halaman Home)
       ========================================= */
    const heroSection = document.querySelector('.hero');
    const heroImg = document.querySelector('.hero-image img');

    if (heroSection && heroImg) {
        heroSection.addEventListener('mousemove', (e) => {
            // Gerakan berlawanan arah mouse (Parallax)
            const x = (window.innerWidth - e.pageX * 2) / 90;
            const y = (window.innerHeight - e.pageY * 2) / 90;
            
            heroImg.style.transform = `translate(${x}px, ${y}px) scale(1.05)`;
        });

        heroSection.addEventListener('mouseleave', () => {
            heroImg.style.transform = `translate(0, 0) scale(1)`;
        });
    }

    /* =========================================
       5. SEARCH BAR FOCUS EFFECT (Halaman Produk)
       ========================================= */
    const searchInput = document.querySelector('.search-container input');
    const searchContainer = document.querySelector('.search-container');

    if (searchInput && searchContainer) {
        searchInput.addEventListener('focus', () => {
            searchContainer.classList.add('focused');
        });
        searchInput.addEventListener('blur', () => {
            searchContainer.classList.remove('focused');
        });
    }
});

//pesanan//
document.addEventListener('DOMContentLoaded', () => {

    // =========================================
    // 1. STICKY GLASSMORPHISM NAVBAR
    // =========================================
    const header = document.querySelector('header');
    
    // Cek posisi scroll saat halaman dimuat (untuk refresh di tengah halaman)
    if (window.scrollY > 20) header.classList.add('scrolled');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 20) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // =========================================
    // 2. SCROLL REVEAL ANIMATION (Intersection Observer)
    // =========================================
    const observerOptions = {
        threshold: 0.1, // Elemen muncul saat 10% terlihat
        rootMargin: "0px 0px -50px 0px"
    };

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target); // Stop observe setelah muncul
            }
        });
    }, observerOptions);

    // Daftar elemen yang ingin dianimasikan
    const elementsToAnimate = document.querySelectorAll(
        '.hero-text, .hero-image, .feature-item, .product-card, .section-header, .search-container, .page-header, .order-history-container, .footer-column'
    );

    elementsToAnimate.forEach((el, index) => {
        el.classList.add('hidden-element');
        
        // Memberikan delay bertingkat (stagger) jika elemen bersebelahan (seperti grid produk)
        // Ini membuat efek munculnya berurutan (wave effect)
        if (el.classList.contains('product-card') || el.classList.contains('feature-item')) {
            // Mengambil sisa bagi index untuk membuat pola delay (0.1s, 0.2s, 0.3s...)
            const delay = (index % 4) * 100; 
            el.style.transitionDelay = `${delay}ms`;
        }
        
        revealObserver.observe(el);
    });

    // =========================================
    // 3. 3D TILT EFFECT (Untuk Card Produk & Fitur)
    // =========================================
    const tiltCards = document.querySelectorAll('.product-card, .feature-item, .vision-mission-card');

    tiltCards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left; // Posisi X kursor di dalam elemen
            const y = e.clientY - rect.top;  // Posisi Y kursor di dalam elemen

            // Menghitung titik tengah elemen
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            // Kalkulasi rotasi (Maksimal 12 derajat agar tidak pusing)
            // Y cursor mempengaruhi RotateX, X cursor mempengaruhi RotateY
            const rotateX = ((y - centerY) / centerY) * -12;
            const rotateY = ((x - centerX) / centerX) * 12;

            // Terapkan transformasi
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
        });

        // Reset posisi saat mouse keluar
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
        });
    });

    // =========================================
    // 4. HERO PARALLAX EFFECT (Khusus Halaman Home)
    // =========================================
    const heroSection = document.querySelector('.hero');
    const heroImg = document.querySelector('.hero-image img');

    if (heroSection && heroImg) {
        heroSection.addEventListener('mousemove', (e) => {
            // Menggerakkan gambar sedikit berlawanan arah mouse
            const x = (window.innerWidth - e.pageX * 2) / 90;
            const y = (window.innerHeight - e.pageY * 2) / 90;
            
            heroImg.style.transform = `translate(${x}px, ${y}px) scale(1.05)`;
        });

        heroSection.addEventListener('mouseleave', () => {
            heroImg.style.transform = `translate(0, 0) scale(1)`;
        });
    }

    // =========================================
    // 5. BUTTON RIPPLE EFFECT
    // =========================================
    const buttons = document.querySelectorAll('.btn-primary, .btn-see-all, button[type="submit"]');

    buttons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            // Jangan preventDefault agar form tetap submit / link tetap jalan
            
            let x = e.clientX - e.target.getBoundingClientRect().left;
            let y = e.clientY - e.target.getBoundingClientRect().top;
            
            let ripples = document.createElement('span');
            ripples.style.left = x + 'px';
            ripples.style.top = y + 'px';
            ripples.classList.add('ripple');
            
            this.appendChild(ripples);

            // Hapus elemen span setelah animasi selesai (600ms)
            setTimeout(() => {
                ripples.remove();
            }, 600);
        });
    });
});
//Chat//
document.addEventListener('DOMContentLoaded', () => {

    /* =========================================
       1. STICKY & GLASSMORPHISM NAVBAR
       ========================================= */
    const header = document.querySelector('header');
    
    const handleScroll = () => {
        if (window.scrollY > 20) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    };

    window.addEventListener('scroll', handleScroll);
    // Jalankan sekali saat load agar jika di-refresh di tengah halaman tetap sticky
    handleScroll();


    /* =========================================
       2. SCROLL REVEAL ANIMATION (Intersection Observer)
       ========================================= */
    const observerOptions = {
        threshold: 0.15, // Elemen mulai muncul saat 15% terlihat
        rootMargin: "0px 0px -50px 0px"
    };

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target); // Stop animasi setelah muncul
            }
        });
    }, observerOptions);

    // Target elemen yang akan dianimasikan
    const animatedElements = document.querySelectorAll(
        '.hero-text, .hero-image, .feature-item, .product-card, .chat-container, .page-header, .search-container'
    );

    animatedElements.forEach((el, index) => {
        el.classList.add('hidden-element');

        // Staggered Delay (Efek muncul berurutan untuk item grid)
        if (el.classList.contains('product-card') || el.classList.contains('feature-item')) {
            const delay = (index % 4) * 100; // 0ms, 100ms, 200ms, 300ms
            el.style.transitionDelay = `${delay}ms`;
        }

        revealObserver.observe(el);
    });


    /* =========================================
       3. 3D TILT EFFECT (Card & Chat Box)
       ========================================= */
    const tiltElements = document.querySelectorAll('.product-card, .feature-item, .chat-container');

    tiltElements.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left; 
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            // Kalkulasi rotasi (Max 8 derajat agar elegan)
            const rotateX = ((y - centerY) / centerY) * -8;
            const rotateY = ((x - centerX) / centerX) * 8;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
        });
    });


    /* =========================================
       4. HERO PARALLAX (Gambar Bergerak Berlawanan Mouse)
       ========================================= */
    const heroSection = document.querySelector('.hero');
    const heroImg = document.querySelector('.hero-image img');

    if (heroSection && heroImg) {
        heroSection.addEventListener('mousemove', (e) => {
            const x = (window.innerWidth - e.pageX * 2) / 90;
            const y = (window.innerHeight - e.pageY * 2) / 90;
            
            heroImg.style.transform = `translate(${x}px, ${y}px) scale(1.05)`;
        });

        heroSection.addEventListener('mouseleave', () => {
            heroImg.style.transform = `translate(0, 0) scale(1)`;
        });
    }


    /* =========================================
       5. BUTTON RIPPLE EFFECT
       ========================================= */
    const buttons = document.querySelectorAll('.btn-primary, .btn-card, .btn-see-all, button[type="submit"]');

    buttons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            // e.preventDefault(); // Hapus ini agar link/form tetap jalan

            let x = e.clientX - e.target.getBoundingClientRect().left;
            let y = e.clientY - e.target.getBoundingClientRect().top;

            let ripples = document.createElement('span');
            ripples.style.left = x + 'px';
            ripples.style.top = y + 'px';
            ripples.classList.add('ripple');

            this.appendChild(ripples);

            setTimeout(() => {
                ripples.remove();
            }, 600);
        });
    });

    /* =========================================
       6. CHAT AUTO SCROLL (Helper)
       ========================================= */
    const chatBox = document.getElementById('chat-messages');
    if (chatBox) {
        // Scroll ke bawah saat halaman dimuat
        chatBox.scrollTop = chatBox.scrollHeight;
        
        // Observer untuk mendeteksi perubahan konten (pesan baru masuk)
        const mutationObserver = new MutationObserver(() => {
            chatBox.scrollTop = chatBox.scrollHeight;
        });
        
        mutationObserver.observe(chatBox, { childList: true });
    }
});

//About//
document.addEventListener('DOMContentLoaded', () => {

    /* =========================================
       1. SMART GLASSMORPHISM NAVBAR
       ========================================= */
    const header = document.querySelector('header');
    
    const updateHeader = () => {
        if (window.scrollY > 20) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    };

    window.addEventListener('scroll', updateHeader);
    updateHeader(); // Jalankan saat load


    /* =========================================
       2. STAGGERED SCROLL REVEAL (Animasi Muncul Berurutan)
       ========================================= */
    const observerOptions = {
        threshold: 0.1, 
        rootMargin: "0px 0px -50px 0px" // Muncul sedikit sebelum masuk viewport penuh
    };

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Target semua elemen penting di semua halaman
    const revealTargets = document.querySelectorAll(
        '.hero-text, .hero-image, .feature-item, .product-card, .section-header, ' +
        '.search-container, .page-header, .about-image, .about-content, ' +
        '.vision-mission-card, .team-member, .chat-container, .order-history-container, .order-detail-container'
    );

    revealTargets.forEach((el, index) => {
        el.classList.add('hidden-element');

        // Logika Stagger: Jika elemen adalah bagian dari grid/list, beri delay bertingkat
        if (el.matches('.product-card, .feature-item, .team-member, .vision-mission-card')) {
            // Delay: 100ms, 200ms, 300ms, 400ms... reset setiap 4 item
            const delay = (index % 4) * 100; 
            el.style.transitionDelay = `${delay}ms`;
        }

        revealObserver.observe(el);
    });


    /* =========================================
       3. HOLOGRAPHIC 3D TILT EFFECT
       ========================================= */
    const tiltElements = document.querySelectorAll(
        '.product-card, .feature-item, .vision-mission-card, .team-member'
    );

    tiltElements.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            // Rotasi X (atas/bawah) dipengaruhi posisi Y mouse
            // Rotasi Y (kiri/kanan) dipengaruhi posisi X mouse
            // Multiplier 10 menentukan seberapa miring (derajat)
            const rotateX = ((y - centerY) / centerY) * -10;
            const rotateY = ((x - centerX) / centerX) * 10;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.03)`;
        });

        // Reset saat mouse keluar
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
        });
    });


    /* =========================================
       4. HERO PARALLAX (Khusus Homepage)
       ========================================= */
    const heroSection = document.querySelector('.hero');
    const heroImg = document.querySelector('.hero-image img');

    if (heroSection && heroImg) {
        heroSection.addEventListener('mousemove', (e) => {
            const x = (window.innerWidth - e.pageX * 2) / 90;
            const y = (window.innerHeight - e.pageY * 2) / 90;
            
            heroImg.style.transform = `translate(${x}px, ${y}px) scale(1.05)`;
        });

        heroSection.addEventListener('mouseleave', () => {
            heroImg.style.transform = `translate(0, 0) scale(1)`;
        });
    }


    /* =========================================
       5. BUTTON RIPPLE EFFECT
       ========================================= */
    const buttons = document.querySelectorAll('.btn-primary, .btn-card, .btn-see-all, button[type="submit"]');

    buttons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            // Posisi klik relatif terhadap tombol
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const ripple = document.createElement('span');
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;
            ripple.classList.add('ripple');

            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });


    /* =========================================
       6. CHAT AUTO-SCROLL (Khusus Halaman Chat)
       ========================================= */
    const chatBox = document.getElementById('chat-messages');
    
    if (chatBox) {
        // Fungsi scroll ke bawah
        const scrollToBottom = () => {
            chatBox.scrollTop = chatBox.scrollHeight;
        };

        // Scroll saat load pertama
        scrollToBottom();

        // Pantau perubahan konten (pesan baru masuk)
        const observer = new MutationObserver(scrollToBottom);
        observer.observe(chatBox, { childList: true, subtree: true });
    }
});

//Manage_pesanan//
document.addEventListener('DOMContentLoaded', () => {

    /* =========================================
       1. INJECT CUSTOM CSS (Agar tidak perlu edit style.css)
       ========================================= */
    const style = document.createElement('style');
    style.innerHTML = `
        /* Animasi Baris Tabel */
        .data-table tbody tr {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .data-table tbody tr.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Search Bar Wrapper */
        .order-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 15px;
            flex-wrap: wrap;
        }

        /* Input Pencarian Keren */
        .dynamic-search {
            flex-grow: 1;
            padding: 12px 20px;
            border-radius: 8px;
            border: 1px solid rgba(160, 196, 255, 0.2);
            background: #0f1123;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='8'%3E%3C/circle%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'%3E%3C/line%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 98% center;
            max-width: 400px;
        }
        .dynamic-search:focus {
            border-color: #a0c4ff;
            box-shadow: 0 0 10px rgba(160, 196, 255, 0.2);
            outline: none;
            background-position: 96% center;
        }

        /* Status Badge Styling (Visualisasi Dropdown) */
        select.status-select {
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        /* Highlight baris saat status diubah tapi belum disubmit */
        tr.pending-change {
            background: rgba(160, 196, 255, 0.1) !important;
            border-left: 3px solid #a0c4ff;
        }
    `;
    document.head.appendChild(style);


    /* =========================================
       2. REAL-TIME SEARCH (Filter ID / Nama)
       ========================================= */
    const contentContainer = document.querySelector('.content-container');
    const table = document.querySelector('.data-table');

    // Buat Wrapper Controls
    const controls = document.createElement('div');
    controls.className = 'order-controls';
    
    // Buat Input Search
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.className = 'dynamic-search';
    searchInput.placeholder = 'Cari ID Pesanan atau Nama Pelanggan...';
    
    controls.appendChild(searchInput);
    
    // Sisipkan sebelum tabel
    if (contentContainer && table) {
        contentContainer.insertBefore(controls, document.querySelector('.table-responsive'));
    }

    // Logika Pencarian
    searchInput.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('.data-table tbody tr');

        rows.forEach(row => {
            // Kolom 0: ID, Kolom 1: Nama
            const idText = row.cells[0].textContent.toLowerCase();
            const nameText = row.cells[1].textContent.toLowerCase();

            if (idText.includes(filter) || nameText.includes(filter)) {
                row.style.display = '';
                setTimeout(() => row.style.opacity = '1', 50);
            } else {
                row.style.display = 'none';
                row.style.opacity = '0';
            }
        });
    });


    /* =========================================
       3. SMART STATUS COLORING (Dropdown)
       ========================================= */
    const statusSelects = document.querySelectorAll('.status-select');

    // Fungsi untuk update warna dropdown berdasarkan value
    const updateColor = (select) => {
        const val = select.value;
        let bgColor, textColor, borderColor;

        // Skema Warna Dark Mode
        switch (val) {
            case 'Menunggu Pembayaran':
                bgColor = 'rgba(243, 156, 18, 0.15)'; textColor = '#f39c12'; borderColor = '#f39c12';
                break;
            case 'Diproses':
                bgColor = 'rgba(52, 152, 219, 0.15)'; textColor = '#3498db'; borderColor = '#3498db';
                break;
            case 'Dikirim':
                bgColor = 'rgba(46, 204, 113, 0.15)'; textColor = '#2ecc71'; borderColor = '#2ecc71';
                break;
            case 'Selesai':
                bgColor = 'rgba(26, 188, 156, 0.15)'; textColor = '#1abc9c'; borderColor = '#1abc9c';
                break;
            case 'Dibatalkan':
                bgColor = 'rgba(231, 76, 60, 0.15)'; textColor = '#e74c3c'; borderColor = '#e74c3c';
                break;
            default:
                bgColor = '#131629'; textColor = '#fff'; borderColor = '#ccc';
        }

        select.style.backgroundColor = bgColor;
        select.style.color = textColor;
        select.style.borderColor = borderColor;
    };

    statusSelects.forEach(select => {
        // Set warna awal saat load
        updateColor(select);

        // Update warna saat diganti user
        select.addEventListener('change', function() {
            updateColor(this);
            // Tambah visual feedback pada baris tabel
            const row = this.closest('tr');
            row.classList.add('pending-change');
        });
    });


    /* =========================================
       4. ANIMASI BARIS TABEL (Waterfall Effect)
       ========================================= */
    const rows = document.querySelectorAll('.data-table tbody tr');
    
    if (rows.length > 0) {
        rows.forEach((row, index) => {
            setTimeout(() => {
                row.classList.add('visible');
            }, index * 80); // Delay 80ms per baris
        });
    }


    /* =========================================
       5. AUTO-DISMISS NOTIFIKASI
       ========================================= */
    const alertBox = document.querySelector('.alert');
    if (alertBox) {
        setTimeout(() => {
            alertBox.style.transition = 'opacity 0.5s, transform 0.5s';
            alertBox.style.opacity = '0';
            alertBox.style.transform = 'translateY(-10px)';
            setTimeout(() => alertBox.remove(), 500);
        }, 3000);
    }


    /* =========================================
       6. KONFIRMASI UPDATE STATUS (Opsional)
       ========================================= */
    // Mencegah submit tidak sengaja
    const updateButtons = document.querySelectorAll('.btn-update');
    
    updateButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const form = this.closest('form');
            const select = form.querySelector('select');
            
            // Animasi tombol saat diklik
            this.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';
            
            // Biarkan form submit (atau tambahkan confirm() jika ingin pop-up)
            // e.preventDefault();
            // if(confirm('Ubah status pesanan menjadi ' + select.value + '?')) form.submit();
        });
    });

});
//Manage_User//
document.addEventListener('DOMContentLoaded', () => {

    /* =========================================
       1. INJECT CUSTOM CSS (Styling Tambahan)
       ========================================= */
    const style = document.createElement('style');
    style.innerHTML = `
        /* Animasi Baris Tabel */
        .data-table tbody tr {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .data-table tbody tr.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Avatar Inisial (Auto-generated) */
        .user-avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #344e89, #253560);
            color: #fff;
            border-radius: 50%;
            font-weight: 600;
            font-size: 0.85rem;
            margin-right: 12px;
            border: 2px solid rgba(160, 196, 255, 0.2);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        /* Toolbar (Search + Export) */
        .user-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 15px;
            flex-wrap: wrap;
        }

        .search-wrapper {
            flex-grow: 1;
            position: relative;
        }

        .dynamic-search {
            width: 100%;
            padding: 12px 40px 12px 20px;
            border-radius: 8px;
            border: 1px solid rgba(160, 196, 255, 0.2);
            background: #0f1123;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
        }
        .dynamic-search:focus {
            border-color: #a0c4ff;
            box-shadow: 0 0 10px rgba(160, 196, 255, 0.2);
            outline: none;
        }
        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        /* Tombol Export CSV */
        .btn-export {
            padding: 12px 20px;
            background: #1c2540;
            color: #a0c4ff;
            border: 1px solid #344e89;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        .btn-export:hover {
            background: #344e89;
            color: #fff;
            transform: translateY(-2px);
        }
    `;
    document.head.appendChild(style);


    /* =========================================
       2. GENERATE USER AVATAR & INITIALS
       ========================================= */
    const rows = document.querySelectorAll('.data-table tbody tr');
    
    // Helper: Buat inisial dari nama (Misal: "Rudi Hartono" -> "RH")
    const getInitials = (name) => {
        const parts = name.trim().split(' ');
        if (parts.length === 1) return parts[0].substring(0, 2).toUpperCase();
        return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
    };

    rows.forEach(row => {
        // Skip jika row adalah pesan "Tidak ada pengguna"
        if (row.cells.length < 2) return;

        // Kolom Nama ada di index 1
        const nameCell = row.cells[1];
        const fullName = nameCell.textContent;
        const initials = getInitials(fullName);

        // Bungkus teks nama lama dengan avatar baru
        nameCell.innerHTML = `
            <div style="display:flex; align-items:center;">
                <span class="user-avatar">${initials}</span>
                <span>${fullName}</span>
            </div>
        `;
    });


    /* =========================================
       3. TOOLBAR (Search & Export)
       ========================================= */
    const contentContainer = document.querySelector('.content-container');
    const tableDiv = document.querySelector('.table-responsive');

    // Buat Toolbar Container
    const toolbar = document.createElement('div');
    toolbar.className = 'user-toolbar';

    // 3a. Search Bar
    const searchDiv = document.createElement('div');
    searchDiv.className = 'search-wrapper';
    searchDiv.innerHTML = `
        <input type="text" class="dynamic-search" placeholder="Cari Nama, Email, atau No. HP...">
        <i class="fa-solid fa-magnifying-glass search-icon"></i>
    `;
    
    // 3b. Export Button
    const exportBtn = document.createElement('button');
    exportBtn.className = 'btn-export';
    exportBtn.innerHTML = '<i class="fa-solid fa-file-csv"></i> Export Data';
    
    // Gabungkan
    toolbar.appendChild(searchDiv);
    toolbar.appendChild(exportBtn);

    // Insert sebelum tabel
    if (contentContainer && tableDiv) {
        contentContainer.insertBefore(toolbar, tableDiv);
    }


    /* =========================================
       4. LOGIKA PENCARIAN (Live Filter)
       ========================================= */
    const searchInput = document.querySelector('.dynamic-search');
    
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            
            rows.forEach(row => {
                if(row.cells.length < 2) return;

                // Ambil data dari kolom Nama(1), Email(2), HP(3)
                const textData = row.innerText.toLowerCase();
                
                if (textData.includes(filter)) {
                    row.style.display = '';
                    setTimeout(() => row.style.opacity = '1', 50);
                } else {
                    row.style.display = 'none';
                    row.style.opacity = '0';
                }
            });
        });
    }


    /* =========================================
       5. LOGIKA EXPORT KE CSV
       ========================================= */
    exportBtn.addEventListener('click', () => {
        const table = document.querySelector('.data-table');
        let csv = [];
        const rows = table.querySelectorAll('tr');
        
        for (let i = 0; i < rows.length; i++) {
            const row = [], cols = rows[i].querySelectorAll('td, th');
            
            for (let j = 0; j < cols.length - 1; j++) { // Skip kolom aksi
                // Bersihkan teks (hapus enter/spasi berlebih)
                let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, "").trim();
                // Jika data nama (ada avatar), ambil teksnya saja
                if(j === 1 && i > 0) {
                     data = cols[j].textContent.replace(/(\r\n|\n|\r)/gm, "").trim().substring(2); // Skip 2 char inisial
                     // Perbaikan logic simple: ambil text content murni
                     data = cols[j].textContent.trim().slice(-data.length); // Fallback
                     data = cols[j].innerText.trim().split('\n').pop(); // Ambil bagian nama saja
                }
                
                row.push('"' + data + '"');
            }
            csv.push(row.join(','));
        }

        const csvFile = new Blob([csv.join('\n')], {type: 'text/csv'});
        const downloadLink = document.createElement('a');
        downloadLink.download = 'Data_Pengguna_Ce3sPart.csv';
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = 'none';
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    });


    /* =========================================
       6. STAGGERED ANIMATION & RIPPLE
       ========================================= */
    // Animasi Masuk
    rows.forEach((row, index) => {
        setTimeout(() => {
            row.classList.add('visible');
        }, index * 80);
    });

    // Ripple Effect pada tombol delete
    const deleteBtns = document.querySelectorAll('.btn-delete');
    deleteBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            // Animasi ripple kecil sebelum confirm dialog muncul
            let x = e.clientX - e.target.getBoundingClientRect().left;
            let y = e.clientY - e.target.getBoundingClientRect().top;
            let ripple = document.createElement('span');
            // ... (Kode ripple sama seperti file js admin lain) ...
        });
    });

    // Auto-dismiss Alert
    const alertBox = document.querySelector('.alert');
    if (alertBox) {
        setTimeout(() => {
            alertBox.style.transition = 'opacity 0.5s, transform 0.5s';
            alertBox.style.opacity = '0';
            alertBox.style.transform = 'translateY(-10px)';
            setTimeout(() => alertBox.remove(), 500);
        }, 3000);
    }
});

//Admmin_chat//
document.addEventListener('DOMContentLoaded', () => {

    /* =========================================
       1. INJECT CUSTOM CSS (Search Bar & Animasi)
       ========================================= */
    const style = document.createElement('style');
    style.innerHTML = `
        /* Search Bar Wrapper */
        .chat-controls {
            margin-bottom: 20px;
            position: relative;
            animation: fadeIn 0.5s ease-out;
        }

        .chat-search {
            width: 100%;
            padding: 15px 45px 15px 20px;
            border-radius: 12px;
            border: 1px solid rgba(160, 196, 255, 0.15);
            background: #0f1123;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .chat-search:focus {
            border-color: #a0c4ff;
            box-shadow: 0 0 20px rgba(52, 78, 137, 0.3);
            background: #131629;
            outline: none;
            transform: translateY(-2px);
        }

        .search-icon-overlay {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
            transition: color 0.3s;
        }

        .chat-search:focus + .search-icon-overlay {
            color: #a0c4ff;
        }

        /* Modifikasi Item Percakapan untuk Animasi */
        .convo-item {
            opacity: 0;
            transform: translateX(-20px);
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden; /* Untuk Ripple */
        }

        .convo-item.visible {
            opacity: 1;
            transform: translateX(0);
        }

        /* Hover Effect yang Lebih Kuat */
        .convo-item:hover {
            background: rgba(52, 78, 137, 0.15); /* #344e89 opacity */
            border-left: 3px solid #a0c4ff;
            padding-left: 18px; /* Geser sedikit */
        }

        /* Empty State Animation */
        .empty-search {
            text-align: center;
            padding: 30px;
            color: #94a3b8;
            display: none;
            animation: fadeIn 0.3s;
        }
    `;
    document.head.appendChild(style);


    /* =========================================
       2. FITUR PENCARIAN (LIVE FILTER)
       ========================================= */
    const contentContainer = document.querySelector('.content-container');
    const convoList = document.querySelector('.conversation-list');

    if (contentContainer && convoList) {
        // Buat Search Bar
        const controls = document.createElement('div');
        controls.className = 'chat-controls';
        controls.innerHTML = `
            <input type="text" class="chat-search" placeholder="Cari nama pelanggan...">
            <i class="fa-solid fa-search search-icon-overlay"></i>
        `;
        
        // Buat Pesan "Tidak Ditemukan"
        const emptyMsg = document.createElement('div');
        emptyMsg.className = 'empty-search';
        emptyMsg.innerHTML = '<i class="fa-regular fa-face-frown"></i> Percakapan tidak ditemukan.';
        convoList.appendChild(emptyMsg);

        // Sisipkan sebelum daftar chat
        contentContainer.insertBefore(controls, convoList);

        // Logika Filter
        const searchInput = controls.querySelector('.chat-search');
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const items = document.querySelectorAll('.convo-item');
            let hasResult = false;

            items.forEach(item => {
                const name = item.querySelector('.convo-name').textContent.toLowerCase();
                const msg = item.querySelector('.convo-last-message').textContent.toLowerCase();

                if (name.includes(filter) || msg.includes(filter)) {
                    item.style.display = 'flex';
                    // Re-trigger animasi masuk
                    item.classList.remove('visible');
                    setTimeout(() => item.classList.add('visible'), 50);
                    hasResult = true;
                } else {
                    item.style.display = 'none';
                }
            });

            emptyMsg.style.display = hasResult ? 'none' : 'block';
        });
    }


    /* =========================================
       3. STAGGERED ANIMATION (Masuk Bertahap)
       ========================================= */
    const convoItems = document.querySelectorAll('.convo-item');
    
    if (convoItems.length > 0) {
        convoItems.forEach((item, index) => {
            // Delay bertingkat (50ms, 100ms, 150ms...)
            setTimeout(() => {
                item.classList.add('visible');
            }, index * 80);
        });
    }


    /* =========================================
       4. INTERACTIVE RIPPLE CLICK
       ========================================= */
    convoItems.forEach(item => {
        item.addEventListener('click', function(e) {
            // Jangan preventDefault karena ini link <a>
            
            let x = e.clientX - e.target.getBoundingClientRect().left;
            let y = e.clientY - e.target.getBoundingClientRect().top;
            
            let ripple = document.createElement('span');
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.style.position = 'absolute';
            ripple.style.background = 'rgba(160, 196, 255, 0.2)'; // #a0c4ff transparant
            ripple.style.borderRadius = '50%';
            ripple.style.transform = 'translate(-50%, -50%)';
            ripple.style.pointerEvents = 'none';
            ripple.style.animation = 'ripple 0.6s linear';
            ripple.style.width = '0px';
            ripple.style.height = '0px';
            
            // Inject Keyframe jika belum ada
            if(!document.getElementById('ripple-kf')){
                const kf = document.createElement('style');
                kf.id = 'ripple-kf';
                kf.innerHTML = `@keyframes ripple { 0% { width: 0px; height: 0px; opacity: 0.5; } 100% { width: 400px; height: 400px; opacity: 0; } }`;
                document.head.appendChild(kf);
            }

            this.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
        });
    });

});

/* =========================================
   TAMBAHAN: SCRIPT HAMBURGER MENU MOBILE
   ========================================= */
document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.getElementById("hamburger");
    const navMenu = document.getElementById("nav-menu");

    if (hamburger && navMenu) {
        hamburger.addEventListener("click", () => {
            // Toggle class 'active' untuk animasi X dan slide menu
            hamburger.classList.toggle("active");
            navMenu.classList.toggle("active");
        });

        // Fitur Tambahan: Tutup menu otomatis saat salah satu link diklik
        document.querySelectorAll(".nav-menu a").forEach(n => n.addEventListener("click", () => {
            hamburger.classList.remove("active");
            navMenu.classList.remove("active");
        }));
    }
});