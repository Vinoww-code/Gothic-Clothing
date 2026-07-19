@extends('layouts.frontend')

@section('content')
    <!-- Hero Contact -->
    <div class="contact-hero" style="background-image: url('{{ asset('images/bg-contact.png') }}');">
        <div class="container">
            <div class="contact-subtitle">CONTACT</div>
            <h1 class="contact-title">MEET OUR TEAM</h1>
            <div style="color: var(--text-muted); margin-bottom: 20px;"><i class="fa-solid fa-fan"></i></div>
            <p class="contact-desc">
                Kami adalah tim yang berdedikasi untuk menghadirkan pengalaman terbaik dalam penyewaan kostum gothic. 
                Website ini dibuat sebagai proyek pembelajaran dengan tujuan mengasah kemampuan, kreativitas, dan kolaborasi kami dalam membangun aplikasi web yang bermanfaat.
            </p>
        </div>
    </div>

    <!-- Team Section -->
    <div class="team-section">
        <div class="container">
            <div class="contact-subtitle" style="justify-content: center;">TIM PENGEMBANG</div>
            <h2 class="contact-title" style="text-align: center; font-size: 36px;">KENALI KAMI</h2>
            
            <div class="team-grid">
                <!-- Asih -->
                <div class="team-card">
                    <div class="team-img-wrap">
                        <img src="{{ asset('images/Asih.jpg') }}" alt="Asih">
                    </div>
                    <h3 class="team-name">Asih Agustina</h3>
                    <div class="team-class">XII RPL B</div>
                    <div class="team-divider"><i class="fa-solid fa-diamond"></i></div>
                    <div class="team-role">
                        <i class="fa-solid fa-pen-nib"></i> UI/UX Designer
                    </div>
                    <p class="team-job-desc">Merancang desain antarmuka, pengalaman pengguna, dan memastikan tampilan website tetap elegan dan modern.</p>
                    <a href="https://instagram.com/asihtna_" target="_blank" class="btn-ig">
                        <i class="fa-brands fa-instagram" style="font-size: 18px;"></i> @asihtna_
                    </a>
                </div>

                <!-- Kelvin -->
                <div class="team-card">
                    <div class="team-img-wrap">
                        <img src="{{ asset('images/Kelvin.jpg') }}" alt="Kelvin">
                    </div>
                    <h3 class="team-name">Kelvin Allvino Azza</h3>
                    <div class="team-class">XII RPL B</div>
                    <div class="team-divider"><i class="fa-solid fa-diamond"></i></div>
                    <div class="team-role">
                        <i class="fa-solid fa-code"></i> Web Developer
                    </div>
                    <p class="team-job-desc">Bertanggung jawab dalam pengembangan backend, database, dan integrasi sistem menggunakan Laravel.</p>
                    <a href="https://instagram.com/klvn.allvno" target="_blank" class="btn-ig">
                        <i class="fa-brands fa-instagram" style="font-size: 18px;"></i> @klvn.allvno
                    </a>
                </div>

                <!-- Ineke -->
                <div class="team-card">
                    <div class="team-img-wrap">
                        <img src="{{ asset('images/Ineke.jpg') }}" alt="Ineke">
                    </div>
                    <h3 class="team-name">Ineke Nurlizanazem</h3>
                    <div class="team-class">XII RPL B</div>
                    <div class="team-divider"><i class="fa-solid fa-diamond"></i></div>
                    <div class="team-role">
                        <i class="fa-regular fa-file-lines"></i> Content & Tester
                    </div>
                    <p class="team-job-desc">Menyusun konten, menguji fitur website, dan memastikan semua berjalan dengan baik sebelum dirilis.</p>
                    <a href="https://instagram.com/ineke_nrlzaa" target="_blank" class="btn-ig">
                        <i class="fa-brands fa-instagram" style="font-size: 18px;"></i> @ineke_nrlzaa
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection