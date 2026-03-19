<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
    /* Import DM Sans font directly in CSS since it's not in the login head */
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap');

    /* ── Premium Light Theme Variables ── */
    :root {
        --primary:      #009ea3; 
        --primary-soft: #e0f2f1;
        --card-bg:      #ffffff;
        --text-main:    #1e293b;
        --text-muted:   #64748b;
        --border-color: rgba(226, 232, 240, 0.6);
        --input-bg:     #f1f5f9;
        --input-focus:  #ffffff;
        --shadow-md:    0 10px 30px rgba(0,0,0,0.04);
    }

    body {
        background: linear-gradient(135deg, #cceff1 0%, #efd6ea 100%);
        font-family: 'DM Sans', sans-serif;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
        color: var(--text-main);
    }

    /* ── Card Design ── */
    .login-card {
        width: 100%;
        max-width: 420px;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 40px;
        box-shadow: var(--shadow-md);
    }

    .login-card .brand {
        color: var(--text-main);
        font-size: 1.5rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 30px;
        font-family: 'DM Sans', sans-serif;
    }

    /* Make the brand hospital icon teal */
    .login-card .brand .text-primary {
        color: var(--primary) !important;
    }

    /* ── Labels ── */
    .form-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted);
        letter-spacing: 0.05em;
        text-transform: uppercase;
        margin-bottom: 8px;
        display: block;
    }

    /* Fix Remember Me Label (Taki wo uppercase na ho) */
    .mb-4.d-flex.align-items-center .form-label {
        margin-bottom: 0;
        text-transform: none;
        letter-spacing: normal;
        font-size: 0.9rem;
        color: var(--text-main);
        font-weight: 500;
        cursor: pointer;
    }

    /* ── Modern Inputs & Groups ── */
    .input-group {
        background: var(--input-bg);
        border: 1.5px solid transparent;
        border-radius: 12px;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.01);
        transition: all 0.3s ease;
        overflow: hidden; /* Important to keep border-radius clean with children */
    }
    
    .input-group:focus-within {
        border-color: var(--primary);
        background: var(--input-focus);
        box-shadow: 0 0 0 4px var(--primary-soft);
    }

    .form-control {
        background: transparent;
        border: none;
        color: var(--text-main);
        padding: 14px 18px 14px 0;
        font-size: 0.95rem;
        box-shadow: none !important; /* Overrides Bootstrap's default focus ring */
    }

    .form-control::placeholder {
        color: #94a3b8;
    }

    .form-control:focus {
        background: transparent;
        color: var(--text-main);
        box-shadow: none; 
    }

    .input-group-text {
        background: transparent;
        border: none;
        color: var(--text-muted);
        padding: 14px 18px;
    }

    /* ── Checkbox ── */
    input[type="checkbox"] {
        accent-color: var(--primary);
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    /* ── Submit Button ── */
    .btn-login {
        padding: 14px 36px;
        background: linear-gradient(to right, #009ea3 0%, #b3569f 100%);
        color: #ffffff;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.95rem;
        font-weight: 600;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        letter-spacing: 0.03em;
        transition: all 0.3s ease;
        box-shadow: 0 8px 16px rgba(179, 86, 159, 0.25);
        width: 100%;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 20px rgba(179, 86, 159, 0.35);
        color: #ffffff;
    }

    .btn-login:active { 
        transform: translateY(1px); 
    }
</style>
</head>
<body>

<div class="login-card">

    {{-- Brand --}}
    <div class="brand">
        <img src="{{ asset('images/logo.png') }}" alt="MedPanel Logo" style="height: 55px; display: block; margin: 0 auto 12px auto; object-fit: contain;">
        Admin Panel
    </div>

    {{-- Error Alert --}}
    @if($errors->any())
        <div class="alert alert-danger py-2 mb-3" style="font-size:0.875rem">
            <i class="fas fa-exclamation-circle me-1"></i>
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('admin.login.submit') }}" method="POST">
        @csrf

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-envelope"></i>
                </span>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="form-control"
                       placeholder="admin@admin.com"
                       required autofocus>
            </div>
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-lock"></i>
                </span>
                <input type="password"
                       name="password"
                       id="passwordField"
                       class="form-control"
                       placeholder="••••••••"
                       required>
                <span class="input-group-text"
                      style="cursor:pointer"
                      onclick="togglePassword()">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </span>
            </div>
        </div>

        {{-- Remember Me --}}
        <div class="mb-4 d-flex align-items-center">
            <input type="checkbox" name="remember" id="remember" class="me-2">
            <label for="remember" class="form-label mb-0">Remember me</label>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt me-2"></i> Login
        </button>

    </form>

</div>

<script>
    function togglePassword() {
        const field   = document.getElementById('passwordField');
        const eyeIcon = document.getElementById('eyeIcon');
        if (field.type === 'password') {
            field.type = 'text';
            eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            field.type = 'password';
            eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>

</body>
</html>
