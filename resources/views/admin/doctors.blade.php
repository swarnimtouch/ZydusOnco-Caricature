@extends('layouts.admin')

@section('title', 'Doctors')
@section('page-title', 'Doctors')

@push('styles')
    <style>
        /* ══════════════════════════════════════
           DOCTORS INDEX — Unified Responsive Design
        ══════════════════════════════════════ */

        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 24px;
        }
        .page-title-group h4 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 3px;
            color: var(--text-primary);
        }
        .page-title-group p {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin: 0;
        }

        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: linear-gradient(135deg, var(--accent), #224abe);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 0.855rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s;
            white-space: nowrap;
        }
        .btn-add:hover { opacity: 0.88; color: #fff; transform: translateY(-1px); }
        .btn-export { background: linear-gradient(135deg, #1cc88a, #149968); }

        /* ── Filter Bar ── */
        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            padding: 14px 18px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            margin-bottom: 18px;
        }
        .search-wrap {
            position: relative;
            flex: 1;
            min-width: 180px;
        }
        .search-wrap i.search-icon {
            position: absolute;
            left: 12px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 13px;
            pointer-events: none;
            transition: color 0.2s;
        }
        /* Spinner inside search box */
        .search-spinner {
            display: none;
            position: absolute;
            right: 12px; top: 50%;
            transform: translateY(-50%);
            width: 14px; height: 14px;
            border: 2px solid var(--border);
            border-top-color: var(--accent);
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        @keyframes spin { to { transform: translateY(-50%) rotate(360deg); } }

        .filter-input {
            background: rgba(255,255,255,0.06);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 0.855rem;
            padding: 9px 36px 9px 36px;
            width: 100%;
            outline: none;
            font-family: 'Outfit', sans-serif;
            transition: border-color 0.2s, background 0.2s;
        }
        .filter-input:focus { border-color: var(--accent); background: rgba(255,255,255,0.09); }
        .filter-input::placeholder { color: var(--text-muted); }

        /* ── Alert ── */
        .alert-success-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            background: rgba(28,200,138,0.1);
            border: 1px solid rgba(28,200,138,0.25);
            border-radius: 10px;
            color: #1cc88a;
            font-size: 0.875rem;
            margin-bottom: 18px;
        }

        /* ══════════════════════════════════════
           DESKTOP TABLE (≥ 768px)
        ══════════════════════════════════════ */
        .desktop-view { display: block; }
        .mobile-view  { display: none; }

        @media (max-width: 767px) {
            .desktop-view { display: none !important; }
            .mobile-view  { display: block; }
        }

        .table-wrap { overflow-x: auto; border-radius: 14px 14px 0 0; }

        .doc-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.835rem;
            min-width: 1150px;
        }
        .doc-table thead tr { background: rgba(255,255,255,0.03); }
        .doc-table th {
            padding: 13px 14px;
            font-size: 0.66rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        .doc-table th.th-doctor   { color: #7b9ff5; }
        .doc-table th.th-employee { color: #5bc0aa; }

        .doc-table td {
            padding: 13px 14px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            vertical-align: middle;
            color: var(--text-primary);
        }
        .doc-table tbody tr:last-child td { border-bottom: none; }
        .doc-table tbody tr { transition: background 0.15s; }
        .doc-table tbody tr:hover td { background: var(--bg-card-h); }

        .serial-cell {
            color: var(--text-muted);
            font-family: 'Space Mono', monospace;
            font-size: 0.72rem;
        }

        .doc-name-cell { display: flex; align-items: center; gap: 10px; }
        .doc-av {
            width: 36px; height: 36px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }
        .c1 { background: linear-gradient(135deg,#4e73df,#224abe); }
        .c2 { background: linear-gradient(135deg,#36b9cc,#1a8a9a); }
        .c3 { background: linear-gradient(135deg,#1cc88a,#149968); }
        .c4 { background: linear-gradient(135deg,#f6c23e,#d4a017); }
        .c5 { background: linear-gradient(135deg,#e74a3b,#b53029); }

        .doc-name-text { font-weight: 600; color: var(--text-primary); }

        .badge-mono {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 20px;
            background: rgba(78,115,223,0.12);
            color: #7b9ff5;
            border: 1px solid rgba(78,115,223,0.2);
            font-family: 'Space Mono', monospace;
            white-space: nowrap;
        }
        .badge-mono.emp {
            background: rgba(91,192,170,0.12);
            color: #5bc0aa;
            border-color: rgba(91,192,170,0.25);
        }
        .text-muted-sm { font-size: 0.8rem; color: var(--text-muted); }
        .col-sep { border-left: 2px solid rgba(255,255,255,0.06) !important; }

        .photo-thumb {
            width: 40px; height: 40px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid var(--border);
            transition: transform 0.2s, border-color 0.2s;
        }
        .photo-thumb:hover { transform: scale(1.14); border-color: var(--accent); }

        .action-btns { display: flex; gap: 6px; align-items: center; }
        .act-btn {
            width: 30px; height: 30px;
            border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid transparent;
            cursor: pointer;
            background: none;
        }
        .act-btn.del {
            color: #e74a3b;
            border-color: rgba(231,74,59,0.2);
            background: rgba(231,74,59,0.08);
        }
        .act-btn.del:hover {
            background: rgba(231,74,59,0.22);
            border-color: rgba(231,74,59,0.4);
        }

        /* Pagination */
        .pagination-wrap {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            padding: 14px 18px;
            border-top: 1px solid var(--border);
        }
        .page-info { font-size: 0.78rem; color: var(--text-muted); }
        .custom-pagination { display: flex; gap: 4px; flex-wrap: wrap; }
        .page-btn {
            width: 32px; height: 32px;
            border-radius: 7px;
            border: 1px solid var(--border);
            background: var(--bg-card);
            color: var(--text-muted);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }
        .page-btn:hover,
        .page-btn.active { background: var(--accent); border-color: var(--accent); color: #fff; }

        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 3.5rem; color: var(--text-muted); opacity: 0.25; margin-bottom: 16px; display: block; }
        .empty-state h5 { font-size: 1rem; color: var(--text-muted); margin-bottom: 8px; }
        .empty-state p  { font-size: 0.82rem; color: var(--text-muted); opacity: 0.7; margin: 0; }

        /* ══════════════════════════════════════
           MOBILE CARDS (< 768px)
        ══════════════════════════════════════ */
        .m-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            margin-bottom: 14px;
            overflow: hidden;
            animation: fadeUp 0.3s ease both;
            transition: border-color 0.2s;
        }
        .m-card:hover { border-color: rgba(78,115,223,0.35); }

        @keyframes fadeUp {
            from { opacity:0; transform:translateY(10px); }
            to   { opacity:1; transform:translateY(0); }
        }

        .m-card-header {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 16px 14px;
            border-bottom: 1px solid var(--border);
            background: rgba(255,255,255,0.02);
        }
        .m-card-photo {
            width: 54px; height: 54px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--border);
            flex-shrink: 0;
            cursor: pointer;
            transition: border-color 0.2s, transform 0.2s;
        }
        .m-card-photo:hover { border-color: var(--accent); transform: scale(1.06); }
        .m-card-av {
            width: 54px; height: 54px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }
        .m-card-title { flex: 1; min-width: 0; }
        .m-card-name {
            font-size: 1rem; font-weight: 700;
            color: var(--text-primary);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .m-card-sub {
            font-size: 0.72rem;
            color: var(--text-muted);
            font-family: 'Space Mono', monospace;
            margin-top: 3px;
        }
        .m-card-serial-badge {
            font-size: 0.65rem; font-weight: 700;
            padding: 3px 8px; border-radius: 20px;
            background: rgba(78,115,223,0.12);
            color: #7b9ff5;
            border: 1px solid rgba(78,115,223,0.2);
            white-space: nowrap; flex-shrink: 0;
        }

        .m-section-label {
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 8px 16px 6px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .m-section-label.doctor   { color: #7b9ff5; background: rgba(78,115,223,0.05); }
        .m-section-label.employee { color: #5bc0aa; background: rgba(91,192,170,0.05); }

        .m-card-body { padding: 12px 16px; }

        .m-fields-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 4px;
        }
        .m-field {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border);
            border-radius: 9px;
            padding: 9px 11px;
        }
        .m-field.full { grid-column: 1 / -1; }
        .m-field-label {
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .m-field-value {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-primary);
            word-break: break-word;
            line-height: 1.35;
        }
        .m-field-value.mono     { font-family: 'Space Mono', monospace; font-size: 0.73rem; color: #7b9ff5; }
        .m-field-value.mono-emp { font-family: 'Space Mono', monospace; font-size: 0.73rem; color: #5bc0aa; }
        .m-field-value.muted    { color: var(--text-muted); font-weight: 400; font-style: italic; }
        .m-field-value.email-val { font-size: 0.76rem; word-break: break-all; }

        .m-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 11px 16px;
            border-top: 1px solid var(--border);
            background: rgba(255,255,255,0.015);
            gap: 10px;
        }
        .m-card-date {
            font-size: 0.7rem;
            color: var(--text-muted);
            font-family: 'Space Mono', monospace;
        }
        .btn-del-mobile {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            background: rgba(231,74,59,0.08);
            border: 1px solid rgba(231,74,59,0.25);
            border-radius: 8px;
            color: #e74a3b;
            font-size: 0.78rem; font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-del-mobile:hover { background: rgba(231,74,59,0.2); }

        /* ── Photo Modal ── */
        .photo-modal-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.82);
            backdrop-filter: blur(8px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .photo-modal-overlay.open { display: flex; }
        .photo-modal-box {
            position: relative;
            background: rgba(15,20,38,0.98);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 18px;
            padding: 20px;
            max-width: 400px;
            width: 100%;
            animation: popIn 0.28s cubic-bezier(.34,1.56,.64,1);
        }
        @keyframes popIn {
            from { opacity:0; transform:scale(0.82); }
            to   { opacity:1; transform:scale(1); }
        }
        .photo-modal-box img {
            width: 100%; border-radius: 12px;
            object-fit: cover; display: block; max-height: 420px;
        }
        .photo-modal-name  { text-align: center; margin-top: 14px; font-size: 1rem; font-weight: 700; color: var(--text-primary); }
        .photo-modal-empid { text-align: center; font-size: 0.75rem; color: var(--text-muted); font-family: 'Space Mono', monospace; margin-top: 4px; }
        .photo-modal-close {
            position: absolute; top: -13px; right: -13px;
            width: 32px; height: 32px; border-radius: 50%;
            background: #e74a3b; border: 2px solid var(--bg-deep);
            color: #fff; font-size: 13px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.4);
            transition: transform 0.2s; z-index: 10;
        }
        .photo-modal-close:hover { transform: scale(1.15); }

        @media (max-width: 400px) {
            .m-fields-grid { grid-template-columns: 1fr; }
            .m-field.full  { grid-column: 1; }
        }
    </style>
@endpush

@section('content')

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div class="page-title-group">
            <h4>Doctors</h4>
            <p>Manage all registered doctors in the system</p>
        </div>
        <a href="{{ route('admin.doctors.export') }}{{ request('search') ? '?search='.request('search') : '' }}"
           class="btn-add btn-export">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
    </div>

    {{-- ── Success Alert ── --}}
    @if(session('success'))
        <div class="alert-success-bar">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── Live Search Bar — no submit button ── --}}
    <div class="filter-bar">
        <div class="search-wrap">
            <i class="fas fa-search search-icon"></i>
            <input type="text"
                   id="liveSearch"
                   value="{{ request('search') }}"
                   class="filter-input"
                   placeholder="Type to search by name or Employee ID..."
                   autocomplete="off">
            <span class="search-spinner" id="searchSpinner"></span>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         DESKTOP TABLE (≥ 768px)
    ════════════════════════════════════════ --}}
    <div class="glass-card desktop-view">
        <div class="table-wrap">
            <table class="doc-table">
                <thead>
                <tr>
                    <th>SR NO.</th>
                    <th>Photo</th>
                    <th class="th-doctor">Doctor Name</th>
                    <th class="th-doctor">Hospital Name</th>
                    <th class="th-doctor">Doctor City</th>
                    <th class="th-doctor">Doctor Gender</th>
                    <th class="th-employee col-sep">Employee Name</th>
                    <th class="th-employee">Employee Code</th>
                    <th class="th-employee">Employee City</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($doctors as $index => $doctor)
                    @php $colors = ['c1','c2','c3','c4','c5']; $c = $colors[$index % 5]; @endphp
                    <tr>
                        <td class="serial-cell">{{ $doctors->firstItem() + $index }}</td>

                        <td>
                            @if($doctor->photo)
                                <img src="{{ $doctor->photo }}"
                                     class="photo-thumb"
                                     onclick="openPhotoModal('{{ $doctor->photo }}', '{{ $doctor->name }}', '{{ $doctor->emp_id }}')"
                                     alt="{{ $doctor->name }}">
                            @else
                                <span class="text-muted-sm">—</span>
                            @endif
                        </td>

                        <td>
                            <div class="doc-name-cell">
                                <span class="doc-name-text">{{ $doctor->name }}</span>
                            </div>
                        </td>

                        <td><span class="badge-mono">{{ $doctor->hospital_name }}</span></td>
                        <td class="text-muted-sm">{{ $doctor->city ?? '—' }}</td>
                        <td class="text-muted-sm">{{ $doctor->gender ?? '—' }}</td>
                        <td class="col-sep" style="font-weight:500;">{{ $doctor->employee->name ?? '—' }}</td>
                        <td><span class="badge-mono emp">{{ $doctor->employee->employee_code ?? '—' }}</span></td>
                        <td class="text-muted-sm">{{ $doctor->employee->city ?? '—' }}</td>

                        <td class="text-muted-sm" style="font-size:0.75rem; white-space:nowrap;">
                            {{ $doctor->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                        </td>

                        <td>
                            <div class="action-btns">
                                <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST"
                                      onsubmit="return confirm('Delete {{ $doctor->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="act-btn del" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10">
                            <div class="empty-state">
                                <i class="fas fa-user-md"></i>
                                <h5>No records found</h5>
                                <p>No doctors have been added yet.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($doctors->hasPages())
            <div class="pagination-wrap">
                <div class="page-info">
                    Showing {{ $doctors->firstItem() }}–{{ $doctors->lastItem() }} of {{ $doctors->total() }}
                </div>
                <div class="custom-pagination">
                    @if($doctors->onFirstPage())
                        <span class="page-btn" style="opacity:0.4;cursor:not-allowed;"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $doctors->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
                    @endif
                    @foreach($doctors->getUrlRange(1, $doctors->lastPage()) as $page => $url)
                        <a href="{{ $url }}" class="page-btn {{ $page == $doctors->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                    @endforeach
                    @if($doctors->hasMorePages())
                        <a href="{{ $doctors->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="page-btn" style="opacity:0.4;cursor:not-allowed;"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- ════════════════════════════════════════
         MOBILE CARDS (< 768px)
    ════════════════════════════════════════ --}}
    <div class="mobile-view">

        @forelse($doctors as $index => $doctor)
            @php $colors = ['c1','c2','c3','c4','c5']; $c = $colors[$index % 5]; @endphp

            <div class="m-card" style="animation-delay:{{ $index * 0.04 }}s;">

                <div class="m-card-header">
                    @if($doctor->photo)
                        <img src="{{ $doctor->photo }}"
                             class="m-card-photo"
                             onclick="openPhotoModal('{{ $doctor->photo }}', '{{ $doctor->name }}', '{{ $doctor->emp_id }}')"
                             alt="{{ $doctor->name }}">
                    @else
                        <div class="m-card-av {{ $c }}">{{ strtoupper(substr($doctor->name, 0, 1)) }}</div>
                    @endif
                    <div class="m-card-title">
                        <div class="m-card-name">{{ $doctor->name }}</div>
                        <div class="m-card-sub">{{ $doctor->hospital_name }}</div>
                    </div>
                    <span class="m-card-serial-badge">#{{ $doctors->firstItem() + $index }}</span>
                </div>

                <div class="m-section-label doctor">
                    <i class="fas fa-user-md"></i> Doctor Details
                </div>
                <div class="m-card-body">
                    <div class="m-fields-grid">
                        <div class="m-field">
                            <div class="m-field-label"><i class="fas fa-city"></i> Doctor City</div>
                            <div class="m-field-value {{ $doctor->city ? '' : 'muted' }}">{{ $doctor->city ?? 'Not set' }}</div>
                        </div>
                        <div class="m-field">
                            <div class="m-field-label"><i class="fas fa-hospital"></i> Hospital Name</div>
                            <div class="m-field-value {{ $doctor->hospital_name ? '' : 'muted' }}">{{ $doctor->hospital_name ?? 'Not set' }}</div>
                        </div>
                        <div class="m-field">
                            <div class="m-field-label"><i class="fas fa-hospital"></i> Gender</div>
                            <div class="m-field-value {{ $doctor->gender ? '' : 'muted' }}">{{ $doctor->gender ?? 'Not set' }}</div>
                        </div>
                    </div>
                </div>

                <div class="m-section-label employee">
                    <i class="fas fa-id-card"></i> Employee Details
                </div>
                <div class="m-card-body">
                    <div class="m-fields-grid">
                        <div class="m-field">
                            <div class="m-field-label"><i class="fas fa-user"></i> Employee Name</div>
                            <div class="m-field-value">{{ $doctor->employee->name ?? '—' }}</div>
                        </div>
                        <div class="m-field">
                            <div class="m-field-label"><i class="fas fa-id-badge"></i> Employee Code</div>
                            <div class="m-field-value mono-emp">{{ $doctor->employee->employee_code ?? '—' }}</div>
                        </div>
                        <div class="m-field">
                            <div class="m-field-label"><i class="fas fa-map-marker-alt"></i> Employee City</div>
                            <div class="m-field-value {{ $doctor->employee->city ? '' : 'muted' }}">{{ $doctor->employee->city ?? 'Not set' }}</div>
                        </div>
                    </div>
                </div>

                <div class="m-card-footer">
                    <div class="m-card-date">
                        <i class="fas fa-calendar me-1"></i>
                        {{ $doctor->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                    </div>
                    <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST"
                          onsubmit="return confirm('Delete {{ $doctor->name }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-del-mobile">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </form>
                </div>

            </div>

        @empty
            <div class="glass-card">
                <div class="empty-state">
                    <i class="fas fa-user-md"></i>
                    <h5>No records found</h5>
                    <p>No doctors have been added yet.</p>
                </div>
            </div>
        @endforelse

        @if($doctors->hasPages())
            <div class="pagination-wrap" style="border:none; padding:4px 0 16px;">
                <div class="page-info">{{ $doctors->firstItem() }}–{{ $doctors->lastItem() }} of {{ $doctors->total() }}</div>
                <div class="custom-pagination">
                    @if($doctors->onFirstPage())
                        <span class="page-btn" style="opacity:0.4;cursor:not-allowed;"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $doctors->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
                    @endif
                    @foreach($doctors->getUrlRange(1, $doctors->lastPage()) as $page => $url)
                        <a href="{{ $url }}" class="page-btn {{ $page == $doctors->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                    @endforeach
                    @if($doctors->hasMorePages())
                        <a href="{{ $doctors->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="page-btn" style="opacity:0.4;cursor:not-allowed;"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        @endif

    </div>

    {{-- ── Photo Preview Modal ── --}}
    <div class="photo-modal-overlay" id="photoModal" onclick="closePhotoModal(event)">
        <div class="photo-modal-box">
            <button class="photo-modal-close"
                    onclick="document.getElementById('photoModal').classList.remove('open')">
                <i class="fas fa-times"></i>
            </button>
            <div id="modalImgWrap"></div>
            <div class="photo-modal-name"  id="modalName"></div>
            <div class="photo-modal-empid" id="modalEmpId"></div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // ══════════════════════════════════════
        // LIVE SEARCH — keyup pe auto redirect
        // ══════════════════════════════════════
        (function () {
            const input   = document.getElementById('liveSearch');
            const spinner = document.getElementById('searchSpinner');
            let   timer   = null;

            input.addEventListener('keyup', function () {
                clearTimeout(timer);

                const query = this.value.trim();

                // Spinner dikhao
                spinner.style.display = 'block';

                // 400ms debounce — user type karna band kare tab navigate karo
                timer = setTimeout(function () {
                    const baseUrl = '{{ route('admin.doctors.index') }}';
                    const url     = query.length > 0
                        ? baseUrl + '?search=' + encodeURIComponent(query)
                        : baseUrl;

                    window.location.href = url;
                }, 400);
            });
        })();

        // ══════════════════════════════════════
        // PHOTO MODAL
        // ══════════════════════════════════════
        function openPhotoModal(src, name, empId) {
            const wrap = document.getElementById('modalImgWrap');
            if (src && src !== '' && src !== 'null') {
                wrap.innerHTML = `<img src="${src}" alt="${name}">`;
            } else {
                wrap.innerHTML = `
            <div style="width:100%;height:180px;border-radius:12px;
                 background:rgba(255,255,255,0.03);
                 border:2px dashed rgba(255,255,255,0.08);
                 display:flex;flex-direction:column;align-items:center;
                 justify-content:center;color:rgba(255,255,255,0.3);gap:10px;">
                <i class="fas fa-user-md" style="font-size:2.5rem;opacity:0.25;"></i>
                <span style="font-size:0.82rem;">No photo uploaded</span>
            </div>`;
            }
            document.getElementById('modalName').textContent  = name  || '';
            document.getElementById('modalEmpId').textContent = empId ? 'Employee ID: ' + empId : '';
            document.getElementById('photoModal').classList.add('open');
        }

        function closePhotoModal(e) {
            if (e.target.id === 'photoModal') {
                document.getElementById('photoModal').classList.remove('open');
            }
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                document.getElementById('photoModal').classList.remove('open');
            }
        });
    </script>
@endpush
