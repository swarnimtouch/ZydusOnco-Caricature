<!DOCTYPE html>
<html lang="en">
<head>
    <title>Doctor Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy:       #0f1e36;
            --teal:       #0a7c74;
            --teal-light: #0d9e93;
            --cream:      #f4f6f9;
            --white:      #ffffff;
            --text:       #1a2a40;
            --muted:      #6c7a8a;
            --border:     #d8dde4;
            --error:      #c0392b;
            --error-bg:   #fdf1f0;
            --success:    #1a7c5a;
        }

        body {
            background: var(--cream);
            font-family: 'DM Sans', sans-serif;
            color: var(--text);
            min-height: 100vh;
        }

        /* ── Top Navbar / Header Bar ── */
        .top-bar {
            background: linear-gradient(135deg, var(--navy) 0%, #1a3a5c 100%);
            padding: 0;
        }
        .top-bar-inner {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 18px 32px;
        }
        .logo-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 52px; height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--teal), var(--teal-light));
            box-shadow: 0 6px 18px rgba(10,124,116,0.35);
            flex-shrink: 0;
        }
        .logo-icon svg { width: 28px; height: 28px; }
        .top-bar h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.45rem;
            color: #fff;
            margin: 0;
            letter-spacing: -0.01em;
        }
        .top-bar p {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.5);
            margin: 0;
            font-weight: 300;
        }

        /* ── Page Content ── */
        .page-content {
            padding: 36px 32px 48px;
        }

        /* ── Card ── */
        .form-card {
            background: var(--white);
            border-radius: 16px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04), 0 12px 40px rgba(15,30,54,0.08);
            overflow: hidden;
            animation: fadeUp 0.45s cubic-bezier(0.22,1,0.36,1) both;
        }
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(18px); }
            to   { opacity:1; transform:translateY(0); }
        }

        .form-card-header {
            background: #f8fafc;
            border-bottom: 1.5px solid var(--border);
            padding: 20px 28px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-card-header .section-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: var(--teal);
        }
        .form-card-header h5 {
            font-family: 'Playfair Display', serif;
            font-size: 1.05rem;
            color: var(--navy);
            margin: 0;
        }

        .form-card-body { padding: 28px; }

        /* ── Labels ── */
        label.form-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--muted);
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        /* ── Inputs ── */
        .form-control, .form-control-file-styled {
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 10px 14px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            color: var(--text);
            background: #fafafa;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            width: 100%;
        }
        .form-control:focus {
            border-color: var(--teal);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(10,124,116,0.10);
            outline: none;
        }
        .form-control[readonly] {
            background: #eef3f3;
            color: var(--muted);
            cursor: not-allowed;
        }

        /* ── File input custom ── */
        .file-upload-wrap {
            border: 1.5px dashed var(--border);
            border-radius: 10px;
            background: #fafafa;
            padding: 0;
            transition: border-color 0.2s, background 0.2s;
            position: relative;
        }
        .file-upload-wrap:hover {
            border-color: var(--teal);
            background: #f0faf9;
        }
        .file-upload-wrap input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
            z-index: 2;
        }
        .file-upload-inner {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            pointer-events: none;
        }
        .file-upload-btn {
            background: var(--teal);
            color: #fff;
            border-radius: 7px;
            padding: 5px 14px;
            font-size: 0.8rem;
            font-weight: 500;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .file-upload-text {
            font-size: 0.85rem;
            color: var(--muted);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* ── Photo Preview ── */
        .photo-preview-wrap {
            display: none;
            margin-top: 12px;
            text-align: center;
        }
        .photo-preview-wrap img {
            width: 110px;
            height: 110px;
            object-fit: cover;
            border-radius: 12px;
            border: 2.5px solid var(--teal);
            box-shadow: 0 4px 16px rgba(10,124,116,0.18);
            animation: popIn 0.3s cubic-bezier(0.34,1.56,0.64,1) both;
        }
        @keyframes popIn {
            from { opacity:0; transform:scale(0.75); }
            to   { opacity:1; transform:scale(1); }
        }
        .photo-preview-label {
            display: block;
            margin-top: 6px;
            font-size: 0.72rem;
            color: var(--teal);
            font-weight: 500;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        /* ── Inline validation ── */
        .error-msg {
            display: none;
            align-items: center;
            gap: 5px;
            margin-top: 6px;
            font-size: 0.775rem;
            color: var(--error);
        }
        .error-msg svg { flex-shrink: 0; }

        .field-error .form-control,
        .field-error .file-upload-wrap {
            border-color: var(--error) !important;
            background: var(--error-bg) !important;
        }
        .field-error .error-msg { display: flex; }

        /* ── Success alert ── */
        .alert-success-custom {
            background: #edf7f2;
            border-left: 3px solid var(--success);
            border-radius: 10px;
            padding: 12px 16px;
            color: var(--success);
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }

        /* ── Submit Button ── */
        .btn-submit {
            padding: 13px 32px;
            background: linear-gradient(135deg, var(--navy) 0%, #1a3a5c 100%);
            color: var(--white);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            letter-spacing: 0.04em;
            transition: transform 0.15s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        }
        .btn-submit::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, var(--teal) 0%, var(--teal-light) 100%);
            opacity: 0;
            transition: opacity 0.25s;
        }
        .btn-submit:hover::after { opacity: 1; }
        .btn-submit span { position: relative; z-index: 1; }
        .btn-submit:active { transform: scale(0.98); }

        /* ── Responsive ── */
        @media (max-width: 576px) {
            .top-bar-inner { padding: 16px 20px; }
            .page-content  { padding: 20px 16px 40px; }
            .form-card-body { padding: 20px; }
            .form-card-header { padding: 16px 20px; }
        }
    </style>
</head>
<body>

<!-- Top Bar with Logo -->
<div class="top-bar">
    <div class="top-bar-inner">
        <div class="logo-icon">
            <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="20" y="6" width="8" height="22" rx="4" fill="white" opacity="0.95"/>
                <rect x="6" y="20" width="22" height="8" rx="4" fill="white" opacity="0.95"/>
                <circle cx="36" cy="36" r="7" stroke="white" stroke-width="2.5" fill="none" opacity="0.9"/>
                <path d="M28 30 Q24 24 28 20" stroke="white" stroke-width="2.5" fill="none" stroke-linecap="round" opacity="0.8"/>
            </svg>
        </div>
        <div>
            <h1>MedPanel</h1>
            <p>Doctor Registration System</p>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="page-content">
    <div class="container-fluid px-0">
        <div class="row">
            <div class="col-12">

                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert-success-custom mb-4">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Form Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="section-dot"></div>
                        <h5>New Doctor Details</h5>
                    </div>

                    <div class="form-card-body">
                        <form id="doctorForm" action="/store-doctor" method="POST" enctype="multipart/form-data" novalidate>
                            @csrf

                            <div class="row g-4">

                                <!-- Employee Code -->
                                <div class="col-12 col-md-6">
                                    <div class="form-field" id="field_employee_code">
                                        <label class="form-label" for="employee_code">Employee Code</label>
                                        <input type="text" class="form-control" name="employee_code" id="employee_code" placeholder="e.g. EMP001" autocomplete="off" value="{{ old('employee_code') }}">
                                        <span class="error-msg">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                            Employee Code is required
                                        </span>
                                    </div>
                                </div>

                                <!-- Employee Name -->
                                <div class="col-12 col-md-6">
                                    <div class="form-field" id="field_employee_name">
                                        <label class="form-label" for="employee_name">Employee Name</label>
                                        <input type="text" class="form-control" name="employee_name" id="employee_name" placeholder="Auto-filled from code" readonly value="{{ old('employee_name') }}">
                                        <span class="error-msg">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                            Employee Name is required
                                        </span>
                                    </div>
                                </div>

                                <!-- Doctor Name -->
                                <div class="col-12 col-md-6">
                                    <div class="form-field" id="field_doctor_name">
                                        <label class="form-label" for="doctor_name">Doctor Name</label>
                                        <input type="text" class="form-control" name="doctor_name" id="doctor_name" placeholder="Dr. Full Name" value="{{ old('doctor_name') }}">
                                        <span class="error-msg">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                            Doctor Name is required
                                        </span>
                                    </div>
                                </div>

                                <!-- Hospital Name -->
                                <div class="col-12 col-md-6">
                                    <div class="form-field" id="field_hospital_name">
                                        <label class="form-label" for="hospital_name">Hospital Name</label>
                                        <input type="text" class="form-control" name="hospital_name" id="hospital_name" placeholder="Hospital / Clinic" value="{{ old('hospital_name') }}">
                                        <span class="error-msg">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                            Hospital Name is required
                                        </span>
                                    </div>
                                </div>

                                <!-- City -->
                                <div class="col-12 col-md-6">
                                    <div class="form-field" id="field_city">
                                        <label class="form-label" for="city">City</label>
                                        <input type="text" class="form-control" name="city" id="city" placeholder="City" value="{{ old('city') }}">
                                        <span class="error-msg">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                            City is required
                                        </span>
                                    </div>
                                </div>

                                <!-- Photo Upload -->
                                <div class="col-12 col-md-6">
                                    <div class="form-field" id="field_photo">
                                        <label class="form-label" for="photo">Doctor Photo</label>
                                        <div class="file-upload-wrap">
                                            <input type="file" name="photo" id="photo" accept="image/*">
                                            <div class="file-upload-inner">
                                                <span class="file-upload-btn">Choose File</span>
                                                <span class="file-upload-text" id="file-label">No file chosen</span>
                                            </div>
                                        </div>
                                        <span class="error-msg">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                            Please upload a photo
                                        </span>

                                        <!-- Photo Preview -->
                                        <div class="photo-preview-wrap" id="photo-preview-wrap">
                                            <img id="photo-preview" src="" alt="Preview">
                                            <span class="photo-preview-label">Preview</span>
                                        </div>
                                    </div>
                                </div>

                            </div><!-- /row -->

                            <!-- Submit -->
                            <div class="d-flex justify-content-end mt-4 pt-2 border-top">
                                <button type="submit" class="btn-submit">
                                    <span>Submit Registration</span>
                                </button>
                            </div>

                        </form>
                    </div><!-- /form-card-body -->
                </div><!-- /form-card -->

            </div><!-- /col-12 -->
        </div><!-- /row -->
    </div><!-- /container -->
</div><!-- /page-content -->

<script>
    $(document).ready(function(){

        // ── Auto-fetch employee name ──
        $('#employee_code').on('keyup', function(){
            let code = $(this).val().trim();
            if(code.length > 0){
                $.ajax({
                    url: '/get-employee/' + code,
                    type: 'GET',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(res){
                        if(res.status){
                            $('#employee_name').val(res.name).prop('readonly', true);
                            $('#field_employee_name').removeClass('field-error');
                        } else {
                            $('#employee_name').val('').prop('readonly', false);
                        }
                    }
                });
            } else {
                $('#employee_name').val('').prop('readonly', false);
            }
        });

        // ── Photo preview ──
        $('#photo').on('change', function(){
            let file = this.files[0];
            if(file){
                $('#file-label').text(file.name);
                let reader = new FileReader();
                reader.onload = function(e){
                    $('#photo-preview').attr('src', e.target.result);
                    $('#photo-preview-wrap').fadeIn(250);
                };
                reader.readAsDataURL(file);
                $('#field_photo').removeClass('field-error');
            } else {
                $('#file-label').text('No file chosen');
                $('#photo-preview-wrap').hide();
            }
        });

        // ── Clear error on input ──
        $('.form-control').on('input change', function(){
            $(this).closest('.form-field').removeClass('field-error');
        });

        // ── Form Validation ──
        $('#doctorForm').on('submit', function(e){

            let valid = true;

            function showError(fieldId){
                $('#field_' + fieldId).addClass('field-error');
                valid = false;
            }

            $('.form-field').removeClass('field-error');

            if($('#employee_code').val().trim() === '')  showError('employee_code');
            if($('#employee_name').val().trim() === '')  showError('employee_name');
            if($('#doctor_name').val().trim() === '')    showError('doctor_name');
            if($('#hospital_name').val().trim() === '')  showError('hospital_name');
            if($('#city').val().trim() === '')           showError('city');
            if($('#photo').val() === '')                 showError('photo');

            if(!valid){
                e.preventDefault();
                let $first = $('.field-error').first();
                if($first.length){
                    $('html, body').animate({ scrollTop: $first.offset().top - 100 }, 300);
                }
            }
        });

    });
</script>

</body>
</html>
