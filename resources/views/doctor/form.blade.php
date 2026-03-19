<!DOCTYPE html>
<html lang="en">
<head>
    <title>ZydusOnco-Caricature</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        /* ── Premium Light Theme Variables ── */
        :root {
            --primary:      #009688; /* Zydus Teal Theme */
            --primary-soft: #e0f2f1;
            --primary-hover:#00796b;
            --bg-light:     #f8fafc;
            --card-bg:      #ffffff;
            --text-main:    #1e293b;
            --text-muted:   #64748b;
            --border-color: #e2e8f0;
            --input-bg:     #f1f5f9;
            --input-focus:  #ffffff;
            --error:        #ef4444;
            --error-bg:     #fef2f2;
            --success:      #10b981;
            
            --shadow-sm:    0 2px 4px rgba(0,0,0,0.02);
            --shadow-md:    0 10px 30px rgba(0,0,0,0.04);
            --shadow-lg:    0 20px 40px rgba(0,0,0,0.06);
        }

        body {
            background: linear-gradient(135deg, #cceff1 0%, #efd6ea 100%);
            font-family: 'DM Sans', sans-serif;
            color: var(--text-main);
            min-height: 100vh;
        }

        /* ── Top Navbar / Header Bar ── */
        .top-bar {
            background: var(--card-bg);
            box-shadow: 0 4px 25px rgba(0,0,0,0.04);
            position: relative;
            z-index: 10;
        }
        .top-bar-inner {
            display: flex;
            align-items: center;
            padding: 16px 32px;
            width: 100%;
        }
        .logo-wrapper {
            transition: transform 0.3s ease;
        }
        .logo-wrapper:hover {
            transform: scale(1.02);
        }

        /* ── Page Content ── */
        .page-content {
            padding: 40px 32px 60px;
            max-width: 1100px;
            margin: 0 auto;
        }

        /* ── Card Design ── */
        .form-card {
            background: var(--card-bg);
            border-radius: 20px;
            border: 1px solid rgba(226, 232, 240, 0.6);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            animation: fadeUp 0.5s cubic-bezier(0.22,1,0.36,1) both;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .form-card-header {
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding: 24px 32px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .form-card-header .section-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-soft);
        }
        .form-card-header h5 {
            font-size: 1.25rem;
            color: var(--text-main);
            margin: 0;
            font-weight: 600;
        }

        .form-card-body { 
            padding: 32px; 
        }

        /* ── Labels ── */
        label.form-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 8px;
            display: block;
        }

        /* ── Modern Inputs ── */
        .form-control {
            border: 1.5px solid transparent;
            border-radius: 12px;
            padding: 14px 18px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            color: var(--text-main);
            background: var(--input-bg);
            transition: all 0.3s ease;
            width: 100%;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.01);
        }
        .form-control::placeholder {
            color: #94a3b8;
        }
        .form-control:focus {
            border-color: var(--primary);
            background: var(--input-focus);
            box-shadow: 0 0 0 4px var(--primary-soft);
            outline: none;
        }
        .form-control[readonly] {
            background: #e2e8f0;
            color: var(--text-muted);
            cursor: not-allowed;
            opacity: 0.8;
        }

        /* ── File Upload Custom (Overriding inline styles safely) ── */
        .file-upload-wrap {
            background: var(--primary-soft) !important;
            border: 2px dashed var(--primary) !important;
            border-radius: 14px !important;
            padding: 35px 20px !important;
            transition: all 0.3s ease !important;
        }
        .file-upload-wrap:hover {
            background: #ccfbf1 !important;
            transform: translateY(-2px);
        }
        
        /* ── Inline validation ── */
        .error-msg {
            display: none;
            align-items: center;
            gap: 6px;
            margin-top: 8px;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--error);
            animation: slideDown 0.2s ease-out;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .error-msg svg { flex-shrink: 0; }

        .field-error .form-control,
        .field-error .file-upload-wrap {
            border-color: var(--error) !important;
            background: var(--error-bg) !important;
            box-shadow: none !important;
        }

        /* ── Success alert ── */
        .alert-success-custom {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-left: 4px solid var(--success);
            border-radius: 12px;
            padding: 16px 20px;
            color: #065f46;
            font-size: 0.95rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            box-shadow: var(--shadow-sm);
        }

        /* ── Submit Button ── */
        .btn-submit {
            padding: 14px 36px;
            /* Left se Right ka mast gradient */
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
            /* Shadow ko thoda premium touch diya naye color ke sath */
            box-shadow: 0 8px 16px rgba(179, 86, 159, 0.25);
            position: relative;
            overflow: hidden;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px rgba(179, 86, 159, 0.35);
        }
        .btn-submit:active { 
            transform: translateY(1px); 
        }

        /* Discard Button Styling */
        #btn-discard {
            border-radius: 8px;
            font-weight: 500;
            padding: 6px 16px;
            border-width: 1.5px;
            transition: all 0.2s;
        }
        #btn-discard:hover {
            background-color: var(--error);
            color: #fff;
        }

        /* ── Responsive ── */
        @media (max-width: 576px) {
            .top-bar-inner { padding: 16px 20px; }
            .page-content  { padding: 20px 16px 40px; }
            .form-card-body { padding: 24px 20px; }
            .form-card-header { padding: 20px; }
        }
    </style>
</head>
<body>

<div class="top-bar">
    <div class="top-bar-inner">
        <div class="logo-wrapper">
            <img src="{{ asset('images/logo.png') }}" alt="MedPanel Logo" style="height: 55px; width: auto; object-fit: contain;">
        </div>
    </div>
</div>

<div class="page-content">
    <div class="container-fluid px-0">
        <div class="row">
            <div class="col-12">

                @if(session('success'))
                    <div class="alert-success-custom mb-4">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="form-card">
                    <div class="form-card-header">
                        <div class="section-dot"></div>
                        <h5>New Doctor Details</h5>
                    </div>

                    <div class="form-card-body">
                        <form id="doctorForm" action="/store-doctor" method="POST" enctype="multipart/form-data" novalidate>
                            @csrf

                            <div class="row g-4">

                                <div class="col-12 col-md-6">
                                    <div class="form-field" id="field_employee_code">
                                        <label class="form-label" for="employee_code">Employee Code</label>
                                        <input type="text" class="form-control" name="employee_code" id="employee_code" placeholder="Enter Employee code" autocomplete="off" value="{{ old('employee_code') }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-field" id="field_employee_name">
                                        <label class="form-label" for="employee_name">Employee Name</label>
                                        <input type="text" class="form-control" name="employee_name" id="employee_name" placeholder="Auto-filled from code" readonly value="{{ old('employee_name') }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-field" id="field_doctor_name">
                                        <label class="form-label" for="doctor_name">Doctor Name</label>
                                        <input type="text" class="form-control" name="doctor_name" id="doctor_name" placeholder="Enter Doctor name" value="{{ old('doctor_name') }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-field" id="field_hospital_name">
                                        <label class="form-label" for="hospital_name">Hospital Name</label>
                                        <input type="text" class="form-control" name="hospital_name" id="hospital_name" placeholder="Enter Hospital / Clinic" value="{{ old('hospital_name') }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-field" id="field_city">
                                        <label class="form-label" for="city">City</label>
                                        <input type="text" class="form-control" name="city" id="city" placeholder="Enter City" value="{{ old('city') }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-field" id="field_photo">
                                        <label class="form-label" for="photo">Doctor Photo</label>
                                        <div class="upload-container text-center">
                                            <div class="file-upload-wrap" id="upload-box" style="position: relative; cursor: pointer;">
                                                <input type="file" name="photo" id="photo" accept=".jpg, .jpeg, .png" style="position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; z-index: 2;">
                                                
                                                <div id="upload-ui">
                                                    <svg width="46" height="46" viewBox="0 0 24 24" fill="var(--primary)" xmlns="http://www.w3.org/2000/svg" style="opacity: 0.9;">
                                                        <path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z"/>
                                                    </svg>
                                                    <div style="color: var(--primary); font-weight: 600; font-size: 1.05rem; margin-top: 12px;">Tap to Upload Photo</div>
                                                    <div style="color: var(--text-muted); font-size: 0.85rem; margin-top: 6px;">Supports: JPG, PNG</div>
                                                </div>

                                                <div id="preview-ui" style="display: none; position: relative; z-index: 3;">
                                                    <img id="photo-preview" src="" alt="Preview" style="max-height: 140px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: 3px solid #fff;">
                                                </div>
                                            </div>

                                            <button type="button" id="btn-discard" class="btn btn-sm btn-outline-danger mt-3" style="display: none; width: auto; margin: 0 auto;">
                                                Discard Image
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div><div class="d-flex justify-content-center mt-5 pt-4 border-top">
                                        <button type="submit" class="btn-submit">
                                            <span>Submit Registration</span>
                                        </button>
                                    </div>

                        </form>
                    </div></div></div></div></div></div><script>
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

        // ── Photo Upload & Discard Logic ──
        $('#photo').on('change', function(){
            let file = this.files[0];
            if(file){
                let reader = new FileReader();
                reader.onload = function(e){
                    $('#photo-preview').attr('src', e.target.result); 
                    $('#upload-ui').hide(); 
                    $('#preview-ui').fadeIn(300); 
                    $('#btn-discard').fadeIn(300); 
                    $('#photo').css('pointer-events', 'none'); 
                };
                reader.readAsDataURL(file);
                $('#field_photo').removeClass('field-error');
            }
        });

        // Discard Button Click
        $('#btn-discard').on('click', function() {
            $('#photo').val(''); 
            $('#photo-preview').attr('src', ''); 
            $('#preview-ui').hide(); 
            $('#btn-discard').hide(); 
            $('#upload-ui').fadeIn(300); 
            $('#photo').css('pointer-events', 'auto'); 
        });

        // ── Clear error on input ──
        $('.form-control').on('input change', function(){
            $(this).closest('.form-field').removeClass('field-error');
        });

        // ── jQuery Plugin Validation ──
        $('#doctorForm').validate({
            errorElement: 'span',
            errorClass: 'error-msg',
            highlight: function(element, errorClass, validClass) {
                $(element).closest('.form-field').addClass('field-error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).closest('.form-field').removeClass('field-error');
            },
            errorPlacement: function(error, element) {
                error.css('display', 'flex'); 
                error.prepend('<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> ');
                
                if (element.attr("name") == "photo") {
                    error.insertAfter(element.closest('.upload-container'));
                } else {
                    error.insertAfter(element);
                }
            },
            rules: {
                employee_code: { required: true },
                employee_name: { required: true },
                doctor_name: { required: true },
                hospital_name: { required: true },
                city: { required: true },
                photo: { required: true }
            },
            messages: {
                employee_code: "Employee Code is required",
                employee_name: "Employee Name is required",
                doctor_name: "Doctor Name is required",
                hospital_name: "Hospital Name is required",
                city: "City is required",
                photo: "Please upload a doctor photo"
            }
        });
    });
</script>

</body>
</html>