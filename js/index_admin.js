document.addEventListener('DOMContentLoaded', () => {

    /* =========================================
       1. DYNAMIC GREETING (Sapaan Waktu)
       ========================================= */
    const updateGreeting = () => {
        const adminInfo = document.querySelector('.admin-info');
        if (adminInfo) {
            const hour = new Date().getHours();
            let greeting = 'Selamat Datang';
            let icon = '👋';

            if (hour >= 4 && hour < 11) {
                greeting = 'Selamat Pagi';
                icon = '☀️';
            } else if (hour >= 11 && hour < 15) {
                greeting = 'Selamat Siang';
                icon = '🌤️';
            } else if (hour >= 15 && hour < 18) {
                greeting = 'Selamat Sore';
                icon = '🌇';
            } else {
                greeting = 'Selamat Malam';
                icon = '🌙';
            }

            // Ganti teks "Selamat Datang" dengan sapaan waktu tanpa menghapus nama
            const originalText = adminInfo.innerHTML;
            if(originalText.includes('Selamat Datang')) {
                 adminInfo.innerHTML = originalText.replace('Selamat Datang', `${icon} ${greeting}`);
            }
        }
    };
    updateGreeting();


    /* =========================================
       2. ANIMATED STATS COUNTER (Hitung Angka)
       ========================================= */
    const counters = document.querySelectorAll('.stat-info p');
    
    counters.forEach(counter => {
        const originalText = counter.innerText;
        // Ambil hanya angka dari teks (misal: "Rp. 5.000.000" jadi 5000000)
        const target = +originalText.replace(/\D/g, ''); 
        
        // Jika tidak ada angka (0), skip
        if(target === 0) return;

        const duration = 2000; // Durasi animasi 2 detik
        const increment = target / (duration / 16); // 60fps
        
        let current = 0;
        
        const updateCounter = () => {
            current += increment;
            
            if (current < target) {
                // Format ulang angka saat animasi berjalan
                // Cek apakah format uang (Rp) atau angka biasa
                if(originalText.includes('Rp')) {
                    counter.innerText = 'Rp. ' + Math.ceil(current).toLocaleString('id-ID');
                } else {
                    counter.innerText = Math.ceil(current).toLocaleString('id-ID');
                }
                requestAnimationFrame(updateCounter);
            } else {
                // Pastikan angka akhir sesuai data asli
                counter.innerText = originalText;
            }
        };
        
        updateCounter();
    });


    /* =========================================
       3. 3D TILT EFFECT (Stat Cards)
       ========================================= */
    const cards = document.querySelectorAll('.stat-card');

    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            // Rotasi halus maksimal 10 derajat
            const rotateX = ((y - centerY) / centerY) * -10;
            const rotateY = ((x - centerX) / centerX) * 10;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
        });
    });


    /* =========================================
       4. MOBILE SIDEBAR TOGGLE
       ========================================= */
    // Inject tombol toggle jika belum ada (karena di PHP tidak terlihat tombolnya)
    const mainHeader = document.querySelector('.main-header');
    if (mainHeader && !document.querySelector('.menu-toggle')) {
        const toggleBtn = document.createElement('button');
        toggleBtn.className = 'menu-toggle';
        toggleBtn.innerHTML = '<i class="fa-solid fa-bars"></i>';
        toggleBtn.style.marginRight = '15px';
        
        // Masukkan sebelum judul Dashboard
        mainHeader.insertBefore(toggleBtn, mainHeader.firstChild);
        
        // Event Listener
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            // Geser konten jika perlu (opsional)
        });

        // Tutup sidebar saat klik di luar (Mobile)
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 992) {
                if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
    }


    /* =========================================
       5. RIPPLE EFFECT ON SIDEBAR LINKS
       ========================================= */
    const links = document.querySelectorAll('.sidebar-nav ul li a');

    links.forEach(link => {
        link.addEventListener('click', function(e) {
            let x = e.clientX - e.target.getBoundingClientRect().left;
            let y = e.clientY - e.target.getBoundingClientRect().top;

            let ripple = document.createElement('span');
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.style.position = 'absolute';
            ripple.style.background = 'rgba(160, 196, 255, 0.3)'; // Warna aksen transparan
            ripple.style.borderRadius = '50%';
            ripple.style.transform = 'translate(-50%, -50%)';
            ripple.style.pointerEvents = 'none';
            ripple.style.animation = 'ripple 0.6s linear';
            ripple.style.width = '0px';
            ripple.style.height = '0px';

            // Tambahkan CSS keyframe dinamis jika belum ada
            if (!document.getElementById('ripple-style')) {
                const style = document.createElement('style');
                style.id = 'ripple-style';
                style.innerHTML = `
                    @keyframes ripple {
                        0% { width: 0px; height: 0px; opacity: 0.5; }
                        100% { width: 300px; height: 300px; opacity: 0; }
                    }
                    .sidebar-nav ul li a { position: relative; overflow: hidden; }
                `;
                document.head.appendChild(style);
            }

            this.appendChild(ripple);
            setTimeout(() => { ripple.remove(); }, 600);
        });
    });
    
    /* =========================================
       6. STAGGERED FADE IN (Animasi Masuk)
       ========================================= */
    const elementsToAnimate = document.querySelectorAll('.stat-card, .recent-activities');
    elementsToAnimate.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'all 0.5s ease-out';
        
        setTimeout(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, index * 100); // Delay bertingkat
    });

});