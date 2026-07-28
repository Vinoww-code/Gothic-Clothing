@extends('layouts.frontend')

@section('content')
<div class="container" style="min-height: 70vh; display: flex; align-items: center; justify-content: center; margin-top: 40px; margin-bottom: 40px;">
    <div style="background: #0a0a0a; padding: 40px; border-radius: 8px; border: 1px solid #222; max-width: 450px; width: 100%; box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
        
        <h2 style="color: #fff; margin-bottom: 5px; font-family: serif; text-align: center;">BERGABUNG</h2>
        <p style="color: #aaa; text-align: center; font-size: 14px; margin-bottom: 30px;">Buat akun untuk mulai menyewa koleksi kami.</p>

        <!-- Tampilkan Pesan Error Jika Ada -->
        @if ($errors->any())
            <div style="background: rgba(139, 0, 0, 0.2); border: 1px solid #8b0000; color: #ff4444; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 13px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <!-- Input Nama -->
            <div style="margin-bottom: 15px;">
                <label style="color: #bbb; font-size: 12px; font-weight: bold; letter-spacing: 1px; display: block; margin-bottom: 5px;">NAMA LENGKAP</label>
                <input type="text" name="name" value="{{ old('name') }}" required 
                       style="width: 100%; padding: 12px; background: #111; border: 1px solid #333; color: white; border-radius: 4px; outline: none; transition: 0.3s; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#8b0000'" onblur="this.style.borderColor='#333'">
            </div>

            <!-- Input Email -->
            <div style="margin-bottom: 15px;">
                <label style="color: #bbb; font-size: 12px; font-weight: bold; letter-spacing: 1px; display: block; margin-bottom: 5px;">EMAIL</label>
                <input type="email" name="email" value="{{ old('email') }}" required 
                       style="width: 100%; padding: 12px; background: #111; border: 1px solid #333; color: white; border-radius: 4px; outline: none; transition: 0.3s; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#8b0000'" onblur="this.style.borderColor='#333'">
            </div>

            <!-- Input Password -->
            <div style="margin-bottom: 15px;">
                <label style="color: #bbb; font-size: 12px; font-weight: bold; letter-spacing: 1px; display: block; margin-bottom: 5px;">PASSWORD</label>
                <input type="password" name="password" required 
                       style="width: 100%; padding: 12px; background: #111; border: 1px solid #333; color: white; border-radius: 4px; outline: none; transition: 0.3s; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#8b0000'" onblur="this.style.borderColor='#333'">
                <small style="color: #666; font-size: 11px;">Minimal 8 karakter.</small>
            </div>

            <!-- Konfirmasi Password -->
            <div style="margin-bottom: 25px;">
                <label style="color: #bbb; font-size: 12px; font-weight: bold; letter-spacing: 1px; display: block; margin-bottom: 5px;">KONFIRMASI PASSWORD</label>
                <!-- PERHATIKAN: Name harus 'password_confirmation' agar Laravel mengenali validasinya -->
                <input type="password" name="password_confirmation" required 
                       style="width: 100%; padding: 12px; background: #111; border: 1px solid #333; color: white; border-radius: 4px; outline: none; transition: 0.3s; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#8b0000'" onblur="this.style.borderColor='#333'">
            </div>

            <!-- Tombol Register -->
            <button type="submit" style="width: 100%; background: #8b0000; color: white; border: none; font-weight: bold; padding: 12px; border-radius: 4px; font-size: 14px; letter-spacing: 1px; cursor: pointer; transition: 0.3s;"
                    onmouseover="this.style.background='#a10000'" onmouseout="this.style.background='#8b0000'">
                DAFTAR SEKARANG
            </button>
            
            <p style="text-align: center; color: #888; font-size: 13px; margin-top: 20px;">
                Sudah punya akun? <a href="{{ route('login') }}" style="color: #ff4444; text-decoration: none; font-weight: bold;">Login di sini</a>
            </p>
        </form>
    </div>
</div>
@endsection