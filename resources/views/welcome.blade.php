@extends('layouts.app')
@section('admin_layout')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif
    
    <!-- Upload CSV Section -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="fas fa-file-upload me-2 text-custom-yellow"></i>
                <h5 class="mb-0">Upload File CSV</h5>
            </div>
            <button type="button" class="btn btn-primary manual-input-btn" data-bs-toggle="modal" data-bs-target="#manualInputModal" data-bs-placement="bottom" title="Tambah data peminjam secara manual">
                <i class="fas fa-plus-circle me-1"></i>Manual Input
            </button>
        </div>
        <div class="card-body">
            <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-wrap align-items-center gap-3">
                @csrf
                <div class="file-upload-wrapper">
                    <input type="file" name="file" id="csvFile" accept=".csv" class="d-none" required>
                    <label for="csvFile" class="btn btn-outline-primary">
                        <i class="fas fa-file-csv me-2"></i>Pilih File CSV
                    </label>
                    <span id="file-chosen" class="ms-2 text-muted">No file chosen</span>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-upload me-2"></i>Upload
                </button>
            </form>
        </div>
    </div>

    <!-- Data Peminjaman Section -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center">
                <i class="fas fa-book-reader me-2 text-custom-yellow"></i>
                <h5 class="mb-0">Data Peminjaman</h5>
            </div>
            <div class="d-flex">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" id="searchTable" class="form-control border-start-0" placeholder="Cari...">
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="borrowerTable">
                    <thead>
                        <tr class="bg-light">
                            <th class="px-2 py-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    Nama
                                    <div class="sort-icons ms-1">
                                        <a href="{{ route('data.peminjam', ['sort' => 'name', 'direction' => 'asc']) }}" class="{{ ($sortColumn == 'name' && $sortDirection == 'asc') ? 'active' : '' }}">
                                            <i class="fas fa-sort-up"></i>
                                        </a>
                                        <a href="{{ route('data.peminjam', ['sort' => 'name', 'direction' => 'desc']) }}" class="{{ ($sortColumn == 'name' && $sortDirection == 'desc') ? 'active' : '' }}">
                                            <i class="fas fa-sort-down"></i>
                                        </a>
                                    </div>
                                </div>
                            </th>
                            <th class="px-2 py-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    Email
                                    <div class="sort-icons ms-1">
                                        <a href="{{ route('data.peminjam', ['sort' => 'email', 'direction' => 'asc']) }}" class="{{ ($sortColumn == 'email' && $sortDirection == 'asc') ? 'active' : '' }}">
                                            <i class="fas fa-sort-up"></i>
                                        </a>
                                        <a href="{{ route('data.peminjam', ['sort' => 'email', 'direction' => 'desc']) }}" class="{{ ($sortColumn == 'email' && $sortDirection == 'desc') ? 'active' : '' }}">
                                            <i class="fas fa-sort-down"></i>
                                        </a>
                                    </div>
                                </div>
                            </th>
                            <th class="px-2 py-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    Phone
                                    <div class="sort-icons ms-1">
                                        <a href="{{ route('data.peminjam', ['sort' => 'phone', 'direction' => 'asc']) }}" class="{{ ($sortColumn == 'phone' && $sortDirection == 'asc') ? 'active' : '' }}">
                                            <i class="fas fa-sort-up"></i>
                                        </a>
                                        <a href="{{ route('data.peminjam', ['sort' => 'phone', 'direction' => 'desc']) }}" class="{{ ($sortColumn == 'phone' && $sortDirection == 'desc') ? 'active' : '' }}">
                                            <i class="fas fa-sort-down"></i>
                                        </a>
                                    </div>
                                </div>
                            </th>
                            <th class="px-2 py-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    Buku yang Dipinjam
                                    <div class="sort-icons ms-1">
                                        <a href="{{ route('data.peminjam', ['sort' => 'phone', 'direction' => 'asc']) }}" class="{{ ($sortColumn == 'phone' && $sortDirection == 'asc') ? 'active' : '' }}">
                                            <i class="fas fa-sort-up"></i>
                                        </a>
                                        <a href="{{ route('data.peminjam', ['sort' => 'phone', 'direction' => 'desc']) }}" class="{{ ($sortColumn == 'phone' && $sortDirection == 'desc') ? 'active' : '' }}">
                                            <i class="fas fa-sort-down"></i>
                                        </a>
                                    </div>
                                </div>
                            </th>
                            <th class="px-2 py-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    Due Date
                                    <div class="sort-icons ms-1">
                                        <a href="{{ route('data.peminjam', ['sort' => 'phone', 'direction' => 'asc']) }}" class="{{ ($sortColumn == 'phone' && $sortDirection == 'asc') ? 'active' : '' }}">
                                            <i class="fas fa-sort-up"></i>
                                        </a>
                                        <a href="{{ route('data.peminjam', ['sort' => 'phone', 'direction' => 'desc']) }}" class="{{ ($sortColumn == 'phone' && $sortDirection == 'desc') ? 'active' : '' }}">
                                            <i class="fas fa-sort-down"></i>
                                        </a>
                                    </div>
                                </div>
                            </th>
                            <th class="px-2 py-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    Status Reminder
                                    <div class="sort-icons ms-1">
                                        <a href="{{ route('data.peminjam', ['sort' => 'phone', 'direction' => 'asc']) }}" class="{{ ($sortColumn == 'phone' && $sortDirection == 'asc') ? 'active' : '' }}">
                                            <i class="fas fa-sort-up"></i>
                                        </a>
                                        <a href="{{ route('data.peminjam', ['sort' => 'phone', 'direction' => 'desc']) }}" class="{{ ($sortColumn == 'phone' && $sortDirection == 'desc') ? 'active' : '' }}">
                                            <i class="fas fa-sort-down"></i>
                                        </a>
                                    </div>
                                </div>
                            </th>
                            <th class="px-2 py-3 border-bottom">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($borrowers as $index => $borrower)
                            <tr>
                                <td class="px-2 py-3 border-bottom border-light align-middle">
                                    <div class="d-flex align-items-center justify-content-start">
                                        <span class="fw-medium">{{ $borrower->name }}</span>
                                    </div>
                                </td>
                                <td class="px-2 py-3 border-bottom border-light align-middle">
                                    <div class="d-flex align-items-center">
                                    <i class="fas fa-envelope text-muted me-1"></i>
                                    <span>{{ $borrower->email }}</span>
                                    </div>
                                </td>
                                <td class="px-2 py-3 border-bottom border-light align-middle">
                                    <div class="d-flex align-items-center">
                                    <i class="fas fa-phone text-muted me-1"></i>
                                    <span>{{ $borrower->phone }}</span>
                                    </div>
                                </td>
                                <td class="px-2 py-3 border-bottom border-light">
                                    <ul class="list-unstyled m-0">
                                        @foreach($borrower->borrowedBooks as $book)
                                            <li class="mb-1 book-title-item">
                                                <i class="fas fa-book text-custom-yellow me-1"></i>
                                                <span>{{ $book->title }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-2 py-3 border-bottom border-light">
                                    <ul class="list-unstyled m-0">
                                        @foreach($borrower->borrowedBooks as $book)
                                            @php
                                                $due_date = \Carbon\Carbon::parse($book->due_date);
                                                $now = \Carbon\Carbon::now();
                                                $is_overdue = $due_date->isPast();
                                                $is_upcoming = $due_date->diffInDays($now) <= 3 && !$is_overdue;
                                                
                                                // Menghitung selisih hari
                                                $diff_days = $now->diffInDays($due_date, false);
                                                
                                                // Pilih warna badge berdasarkan status
                                                $badge_class = $is_overdue ? 'badge bg-danger rounded-pill' : 
                                                              ($is_upcoming ? 'badge bg-warning text-dark rounded-pill' : 
                                                              'badge bg-light text-dark rounded-pill');
                                                $icon_class = $is_overdue ? 'fas fa-exclamation-circle' : 
                                                            ($is_upcoming ? 'fas fa-clock' : 'far fa-calendar-alt');
                                                            
                                                // Teks yang lebih simpel tanpa angka di belakang koma           
                                                if ($is_overdue) {
                                                    $time_text = (int)abs($diff_days) . " hari terlewat";
                                                } else if ($diff_days == 0) {
                                                    $time_text = "Hari ini";
                                                } else {
                                                    $time_text = (int)$diff_days . " hari lagi";
                                                }
                                            @endphp
                                            <li class="mb-1">
                                                <div class="date-hover-container">
                                                    <span class="{{ $badge_class }} date-badge">
                                                    <i class="{{ $icon_class }} me-1"></i>
                                                        {{ $due_date->format('d/m/Y') }}
                                                </span>
                                                    <div class="date-hover-info {{ $is_overdue ? 'text-danger' : ($is_upcoming ? 'text-warning' : 'text-muted') }}">
                                                        <i class="fas fa-hourglass-half me-1"></i>
                                                        {{ $time_text }}
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-2 py-3 border-bottom border-light">
    @php
        // Retrieve the latest message log for the current book
        $latestLog = $book->messageLogs()->latest()->first();

        $statusBadge = match($latestLog->status ?? '') {
            'sent' => 'bg-success',
            'pending' => 'bg-warning text-dark',
            'retry' => 'bg-info',
            'failed', 'failed_permanent' => 'bg-danger',
            'error' => 'bg-danger',
            default => 'bg-secondary'
        };

        $statusText = match($latestLog->status ?? '') {
            'sent' => 'Terkirim',
            'pending' => 'Menunggu',
            'retry' => 'Mencoba Ulang',
            'failed' => 'Gagal',
            'failed_permanent' => 'Gagal Permanen',
            'error' => 'Error',
            default => 'Belum Terkirim '
        };
    @endphp

    <span class="badge {{ $statusBadge }}">{{ $statusText }}</span>
</td>

                                <td class="px-2 py-3 border-bottom border-light">
                                    <ul class="list-unstyled m-0">
                                        @foreach($borrower->borrowedBooks as $book)
                                            <li class="mb-1 d-flex gap-1 align-items-center">
                                                <button type="button" class="btn btn-success btn-sm return-book" 
                                                       data-id="{{ $book->id }}" 
                                                       data-title="{{ $book->title }}" 
                                                       data-borrower="{{ $borrower->name }}"
                                                       style="font-size: 0.75rem; padding: 0.25rem 0.5rem; white-space: nowrap;">
                                                    <i class="fas fa-check-circle me-1"></i>Sudah Dikembalikan
                                                </button>
                                                <a href="{{ route('borrower.details', $borrower->id) }}" class="btn btn-info btn-sm" 
                                                   style="font-size: 0.75rem; padding: 0.25rem 0.5rem; white-space: nowrap;">
                                                    <i class="fas fa-info-circle me-1"></i>Detail
                                                </a>
                                                <form id="return-form-{{ $book->id }}" action="{{ route('return.book', $book->id) }}" method="POST" class="d-none">
                                                    @csrf
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                        
                        @if(count($borrowers) == 0)
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle me-2"></i>Tidak ada data peminjaman
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center py-4">
                <nav aria-label="Page navigation" class="pagination-nav">
                    <ul class="pagination pagination-custom">
                        @if($borrowers->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link border-0 bg-light" aria-hidden="true">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link border-0" href="{{ $borrowers->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        @endif
                        
                        @for($i = 1; $i <= $borrowers->lastPage(); $i++)
                            <li class="page-item {{ $borrowers->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link border-0" href="{{ $borrowers->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                        
                        @if($borrowers->hasMorePages())
                            <li class="page-item">
                                <a class="page-link border-0" href="{{ $borrowers->nextPageUrl() }}" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link border-0 bg-light" aria-hidden="true">&raquo;</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Sweet Alert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Manual Input Modal -->
<div class="modal fade" id="manualInputModal" tabindex="-1" aria-labelledby="manualInputModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header d-flex align-items-center bg-primary bg-gradient text-white">
                <h5 class="modal-title" id="manualInputModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>
                    Tambah Peminjam Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('borrower.store') }}" method="POST" id="manualInputForm">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="name" class="form-label fw-medium">Nama Peminjam <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-user text-custom-yellow"></i>
                                </span>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama lengkap" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-envelope text-custom-yellow"></i>
                                </span>
                                <input type="email" class="form-control" id="email" name="email" placeholder="contoh@email.com" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="phone" class="form-label fw-medium">Nomor Telepon <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-phone text-custom-yellow"></i>
                                </span>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="08xxxxxxxxxx" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="due_date" class="form-label fw-medium">Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-calendar text-custom-yellow"></i>
                                </span>
                                <input type="date" class="form-control" id="due_date" name="due_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="book_title" class="form-label fw-medium">Judul Buku <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-book text-custom-yellow"></i>
                            </span>
                            <input type="text" class="form-control" id="book_title" name="book_title" placeholder="Masukkan judul buku" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4 pt-2">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i>Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Additional Styles -->
<style>
    .text-custom-yellow {
        color: #d0cc4c !important;
    }
    
    .avatar-initial {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    
    .card {
        border-radius: 8px;
        overflow: hidden;
    }
    
    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(208, 204, 76, 0.1);
        border-color: #d8d48f;
    }
    
    .btn-outline-primary {
        color: #d0cc4c !important;
        border-color: #d0cc4c !important;
    }
    
    .btn-outline-primary:hover {
        background-color: #d0cc4c;
        color: white !important;
    }
    
    .btn-primary {
        background-color: #d0cc4c !important;
        border-color: #d0cc4c !important;
        transition: all 0.2s ease-in-out;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(208, 204, 76, 0.2);
    }
    
    .table tbody tr:hover {
        transition: background-color 0.15s ease-in-out;
    }
    
    .badge {
        font-weight: normal;
        padding: 0.35em 0.65em;
    }
    
    .alert {
        border-radius: 8px;
    }
    
    /* Button styles */
    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
        transition: all 0.2s ease;
    }
    
    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    /* Info button styling */
    .btn-info {
        background-color: #17a2b8;
        border-color: #17a2b8;
        color: #fff;
        transition: all 0.2s ease;
    }
    
    .btn-info:hover {
        background-color: #138496;
        border-color: #117a8b;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    /* Outline danger button styling */
    .btn-outline-danger {
        color: #dc3545;
        border-color: #dc3545;
        transition: all 0.2s ease;
    }
    
    .btn-outline-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    /* Date indicator hover styles */
    .date-hover-container {
        position: relative;
        display: inline-block;
    }
    
    .date-hover-info {
        position: absolute;
        top: 100%;
        left: 0;
        margin-top: 5px;
        background-color: #fff;
        border: 1px solid rgba(0,0,0,0.1);
        padding: 4px 8px;
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        z-index: 100;
        white-space: nowrap;
        font-size: 0.85rem;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s ease, visibility 0.2s ease;
    }
    
    .date-hover-container:hover .date-hover-info {
        opacity: 1;
        visibility: visible;
    }
    
    .date-badge {
        cursor: help;
    }
    
    /* Fix table responsive issues */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .table {
        width: 100%;
        margin-bottom: 0;
    }
    
    /* Responsive text handling */
    .text-truncate {
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: inline-block;
    }
    
    /* Fix untuk tampilan buku */
    .book-title-item {
        display: block;
        white-space: normal;
        word-break: break-word;
        text-overflow: initial;
        overflow: visible;
        margin-bottom: 0.5rem !important;
    }
    
    /* Responsive column widths at different breakpoints */
    @media (min-width: 768px) {
        .table th:nth-child(1), .table td:nth-child(1) { width: 16%; }
        .table th:nth-child(2), .table td:nth-child(2) { width: 22%; }
        .table th:nth-child(3), .table td:nth-child(3) { width: 12%; }
        .table th:nth-child(4), .table td:nth-child(4) { width: 20%; }
        .table th:nth-child(5), .table td:nth-child(5) { width: 15%; }
        .table th:nth-child(6), .table td:nth-child(6) { width: 15%; }
    }
    
    @media (max-width: 767.98px) {
        .table th, .table td {
            white-space: nowrap;
            max-width: 200px;
        }
        
        .badge {
            white-space: nowrap;
        }
    }
    
    /* Animation for new rows */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .table tbody tr {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    /* Custom pagination styling */
    .pagination {
        margin-bottom: 0;
    }
    
    .pagination-custom {
        display: flex;
        padding-left: 0;
        list-style: none;
        border-radius: 0.25rem;
        gap: 6px;
    }
    
    .pagination-custom .page-item {
        margin: 0;
    }
    
    .pagination-custom .page-link {
        padding: 0.5rem 0.75rem;
        font-size: 0.95rem;
        line-height: 1.5;
        color: #6c757d;
        background-color: #f8f9fa;
        border-radius: 0.25rem !important;
        transition: all 0.2s ease;
    }
    
    .pagination-custom .page-link:hover {
        z-index: 2;
        color: #fff;
        text-decoration: none;
        background-color: #b8b543;
        border-color: #b8b543;
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .pagination-custom .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background-color: #d0cc4c;
        border-color: #d0cc4c;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(208, 204, 76, 0.2);
    }
    
    .pagination-custom .page-item.disabled .page-link {
        color: #adb5bd;
        pointer-events: none;
        background-color: #f3f3f3;
        border-color: #f3f3f3;
    }
    
    .pagination-nav {
        display: inline-block;
        background-color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    /* Container full width on smaller screens */
    @media (max-width: 991.98px) {
        .container-fluid {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
    }
    
    /* Perbaikan tampilan kolom */
    .table th {
        font-weight: 600;
        vertical-align: middle;
    }
    
    /* Konsistensi padding untuk kolom tabel */
    .table td, .table th {
        padding: 0.75rem 0.5rem;
    }
    
    .btn-primary.manual-input-btn {
        border-radius: 6px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        letter-spacing: 0.3px;
    }
    
    .btn-primary.manual-input-btn:hover {
        background-color: #b8b543 !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(208, 204, 76, 0.2);
    }
    
    /* Modal styling improvements */
    .modal-header {
        padding: 1rem 1.5rem;
        border-bottom: none;
    }
    
    .modal-header.bg-primary {
        background-color: #d0cc4c !important;
    }
    
    .modal-header .modal-title {
        font-weight: 600;
        font-size: 1.25rem;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    /* Modal positioning fixes */
    .modal-dialog {
        display: flex;
        align-items: center;
        min-height: calc(100% - 1rem);
    }
    
    .modal-dialog-centered {
        justify-content: center;
    }
    
    .modal-content {
        width: 100%;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 600px;
            margin: 1.75rem auto;
        }
        
        .modal-dialog.modal-lg {
            max-width: 800px;
        }
    }
    
    @media (max-width: 575.98px) {
        .modal-dialog {
            margin: 0.5rem;
        }
    }
    
    /* Dialog animation */
    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
        transform: translateY(-20px);
    }
    
    .modal.show .modal-dialog {
        transform: translateY(0);
    }
    
    .form-label {
        margin-bottom: 0.5rem;
        color: #495057;
    }
    
    .input-group {
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        border-radius: 6px;
        overflow: hidden;
    }
    
    .input-group-text {
        border: none;
        background-color: #f8f9fa;
    }
    
    .form-control {
        border: none;
        border-left: 1px solid #f0f0f0;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
    }
    
    .form-control:focus {
        box-shadow: none;
        border-color: #dee2e6;
    }
    
    .btn-primary, .btn-outline-secondary {
        padding: 0.5rem 1.5rem;
        font-weight: 500;
        border-radius: 5px;
        transition: all 0.2s;
    }
    
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
    }
    
    /* Sort icons styling */
    .sort-icons {
        display: flex;
        flex-direction: column;
        height: 16px;
        line-height: 0;
        margin-left: 5px;
    }
    
    .sort-icons a {
        color: #ccc;
        height: 8px;
        display: flex;
        align-items: center;
        text-decoration: none;
    }
    
    .sort-icons a.active {
        color: #d0cc4c;
    }
    
    .sort-icons a i {
        font-size: 10px;
        line-height: 0;
    }
    
    .sort-icons .fa-sort-up {
        position: relative;
        top: 2px;
    }
    
    .sort-icons .fa-sort-down {
        position: relative;
        bottom: 2px;
    }
    
    th {
        position: relative;
    }
</style>

<!-- Additional Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if Bootstrap is loaded
        if (typeof bootstrap !== 'undefined') {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"], [title]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Make sure modals are properly initialized
            var manualInputModal = document.getElementById('manualInputModal');
            if (manualInputModal) {
                var modal = new bootstrap.Modal(manualInputModal);
                
                // Debug - add click handler directly to the button
                var manualInputBtn = document.querySelector('.manual-input-btn');
                if (manualInputBtn) {
                    manualInputBtn.addEventListener('click', function() {
                        modal.show();
                    });
                }
            }
        } else {
            console.error('Bootstrap is not loaded. Modals and tooltips will not work.');
        }
        
        // Check for success message
        @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#28a745',
                confirmButtonText: 'OK'
            });
        @endif
        
        // File input display logic
        const fileInput = document.getElementById('csvFile');
        const fileChosen = document.getElementById('file-chosen');
        
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                if (fileInput.files.length > 0) {
                    fileChosen.textContent = fileInput.files[0].name;
                } else {
                    fileChosen.textContent = 'No file chosen';
                }
            });
        }
        
        // Set default due date to 1 week from now for manual input form
        const dueDateInput = document.getElementById('due_date');
        if (dueDateInput) {
            const today = new Date();
            const nextWeek = new Date(today.getTime() + 7 * 24 * 60 * 60 * 1000);
            const formattedDate = nextWeek.toISOString().split('T')[0];
            dueDateInput.value = formattedDate;
        }
        
        // Form validation
        const manualInputForm = document.getElementById('manualInputForm');
        if (manualInputForm) {
            manualInputForm.addEventListener('submit', function(e) {
                const phoneInput = document.getElementById('phone');
                // Format nomor telepon untuk memastikan format yang benar
                if (phoneInput && phoneInput.value) {
                    let phone = phoneInput.value.replace(/\D/g, '');
                    if (phone.startsWith('62')) {
                        phone = '0' + phone.substring(2);
                    } else if (phone.startsWith('0') === false) {
                        phone = '0' + phone;
                    }
                    phoneInput.value = phone;
                }
            });
        }
        
        // Check for error message
        @if(session('error'))
            Swal.fire({
                title: 'Error!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'OK'
            });
        @endif
        
        // Return book confirmation
        const returnButtons = document.querySelectorAll('.return-book');
        returnButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const title = this.getAttribute('data-title');
                const borrower = this.getAttribute('data-borrower');
                
                Swal.fire({
                    title: 'Konfirmasi Pengembalian',
                    html: `Apakah buku <strong>"${title}"</strong> yang dipinjam oleh <strong>${borrower}</strong> sudah dikembalikan?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, sudah dikembalikan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`return-form-${id}`).submit();
                    }
                });
            });
        });
        
        // Enhanced search functionality
        const searchInput = document.getElementById('searchTable');
        const table = document.getElementById('borrowerTable');
        
        searchInput.addEventListener('keyup', function() {
            const searchTerm = searchInput.value.toLowerCase();
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Check if no results found
            const visibleRows = table.querySelectorAll('tbody tr:not([style*="display: none"])');
            const tbody = table.querySelector('tbody');
            const noResultsRow = table.querySelector('.no-results-row');
            
            if (visibleRows.length === 0 && !noResultsRow) {
                const newRow = document.createElement('tr');
                newRow.className = 'no-results-row';
                newRow.innerHTML = `
                    <td colspan="6" class="text-center py-4 text-muted">
                        <i class="fas fa-search me-2"></i>Tidak ada hasil pencarian untuk "${searchInput.value}"
                    </td>
                `;
                tbody.appendChild(newRow);
            } else if (visibleRows.length > 0 && noResultsRow) {
                noResultsRow.remove();
            }
        });
        
        // Handle window resize for better responsiveness
        function handleResize() {
            const tableContainer = document.querySelector('.table-responsive');
            if (window.innerWidth < 768) {
                tableContainer.classList.add('overflow-auto');
            } else {
                tableContainer.classList.remove('overflow-auto');
            }
        }
        
        // Initial call and event listener
        handleResize();
        window.addEventListener('resize', handleResize);
    });
</script>
@endsection