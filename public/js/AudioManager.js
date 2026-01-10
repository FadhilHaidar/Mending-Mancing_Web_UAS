class AudioManager {
    constructor() {
        this.basePath = 'http://localhost/mending-mancing/public/assets/audio/'; // Sesuaikan jika path beda
        
        // Load Assets
        this.bgm = new Audio(this.basePath + 'bgm_ocean.mp3');
        this.sounds = {
            'cast': new Audio(this.basePath + 'sfx_cast.mp3'),
            'splash': new Audio(this.basePath + 'sfx_splash.mp3'),
            'ching': new Audio(this.basePath + 'sfx_ching.mp3'),
            'rare': new Audio(this.basePath + 'sfx_rare.mp3'),
            'legendary': new Audio(this.basePath + 'sfx_legendary.mp3'),
            'error': new Audio(this.basePath + 'sfx_error.mp3')
        };

        // Konfigurasi BGM
        this.bgm.loop = true;
        this.bgm.volume = 0.3; // Volume rendah biar ga berisik

        // Cek LocalStorage (Apakah user pernah mute?)
        this.isMuted = localStorage.getItem('mm_isMuted') === 'true';
        this.applyMuteSettings();

        // Auto Play Handler (Browser Policy)
        // Kita coba play, kalau gagal (diblokir browser), kita tunggu klik pertama user
        this.tryPlayBGM();
        
        document.addEventListener('click', () => {
            this.tryPlayBGM();
        }, { once: true }); // Hanya jalan sekali
    }

    tryPlayBGM() {
        if (!this.isMuted && this.bgm.paused) {
            this.bgm.play().catch(error => {
                console.log("Autoplay dicegah browser, menunggu interaksi user...");
            });
        }
    }

    playSFX(type) {
        if (this.isMuted) return;
        
        if (this.sounds[type]) {
            // Reset waktu agar bisa diputar cepat berulang kali
            this.sounds[type].currentTime = 0;
            this.sounds[type].play().catch(e => console.log(e));
        }
    }

    toggleMute() {
        this.isMuted = !this.isMuted;
        localStorage.setItem('mm_isMuted', this.isMuted);
        this.applyMuteSettings();
        return this.isMuted;
    }

    applyMuteSettings() {
        if (this.isMuted) {
            this.bgm.pause();
        } else {
            this.tryPlayBGM();
        }
        this.updateIcon();
    }

    updateIcon() {
        const icon = document.getElementById('muteIcon');
        const text = document.getElementById('muteText');
        
        if (icon) {
            if (this.isMuted) {
                icon.classList.remove('fa-volume-up');
                icon.classList.add('fa-volume-mute', 'text-danger');
                if(text) text.innerText = "Unmute";
            } else {
                icon.classList.remove('fa-volume-mute', 'text-danger');
                icon.classList.add('fa-volume-up');
                if(text) text.innerText = "Mute";
            }
        }
    }
}

// Inisialisasi Global
const audioManager = new AudioManager();