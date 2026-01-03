// Konfigurasi Animasi Hujan
const canvas = document.getElementById('rainCanvas');
const ctx = canvas.getContext('2d');

canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

let raindrops = [];

class RainDrop {
    constructor() {
        this.reset();
    }

    reset() {
        this.x = Math.random() * canvas.width;
        this.y = Math.random() * -canvas.height;
        this.speed = Math.random() * 5 + 2;
        this.length = Math.random() * 15 + 5;
        this.opacity = Math.random() * 0.3;
    }

    update() {
        this.y += this.speed;
        if (this.y > canvas.height) {
            this.reset();
        }
    }

    draw() {
        ctx.beginPath();
        ctx.strokeStyle = `rgba(241, 196, 15, ${this.opacity})`;
        ctx.lineWidth = 1;
        ctx.moveTo(this.x, this.y);
        ctx.lineTo(this.x, this.y + this.length);
        ctx.stroke();
    }
}

function initRain() {
    for (let i = 0; i < 150; i++) {
        raindrops.push(new RainDrop());
    }
}

function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    raindrops.forEach(drop => {
        drop.update();
        drop.draw();
    });
    requestAnimationFrame(animate);
}

window.addEventListener('resize', () => {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
});

// Fitur Intip Password
const togglePassword = document.querySelector('#togglePassword');
const passwordField = document.querySelector('#password');

togglePassword.addEventListener('click', function() {
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);
    this.classList.toggle('fa-eye-slash');
});

// Jalankan Animasi
initRain();
animate();
// Hapus kode toggle sebelumnya dan ganti dengan ini:
document.addEventListener('click', function(e) {
    // Cari elemen yang memiliki class 'fa-eye' atau ID 'togglePassword'
    if (e.target.classList.contains('fa-eye') || e.target.id === 'togglePassword') {
        
        // Cari input password di dalam pembungkus yang sama (parent)
        const wrapper = e.target.closest('.password-wrapper');
        const input = wrapper ? wrapper.querySelector('input') : document.getElementById('password');
        
        if (input) {
            // Tukar tipe input
            const isPassword = input.getAttribute('type') === 'password';
            input.setAttribute('type', isPassword ? 'text' : 'password');
            
            // Tukar ikon mata
            e.target.classList.toggle('fa-eye');
            e.target.classList.toggle('fa-eye-slash');
            
            console.log("Password toggle clicked: " + (isPassword ? "Show" : "Hide")); // Untuk cek di Inspect Element
        }
    }
});
