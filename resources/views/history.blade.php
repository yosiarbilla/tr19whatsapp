@extends('layouts.app')
@section('admin_layout')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif
    
    <!-- Data Riwayat Peminjaman Section -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center">
                <i class="fas fa-history me-2 text-custom-yellow"></i>
                <h5 class="mb-0">Riwayat Peminjaman</h5>
            </div>
            <div class="d-flex gap-2">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" id="searchTable" class="form-control border-start-0" placeholder="Cari...">
                </div>
                <button type="button" id="bulk-delete-btn" class="btn btn-danger btn-sm d-none">
                    <i class="fas fa-trash-alt me-1"></i>Hapus Terpilih
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <form id="bulk-delete-form" action="{{ route('bulk.delete.books') }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="borrowerTable">
                        <thead>
                            <tr class="bg-light">
                                <th class="px-2 py-3 border-bottom" width="30px">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="select-all">
                                    </div>
                                </th>
                                <th class="px-2 py-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        Nama
                                        <div class="sort-icons ms-1">
                                            <a href="{{ route('history', ['sort' => 'name', 'direction' => 'asc']) }}" class="{{ ($sortColumn == 'name' && $sortDirection == 'asc') ? 'active' : '' }}">
                                                <i class="fas fa-sort-up"></i>
                                            </a>
                                            <a href="{{ route('history', ['sort' => 'name', 'direction' => 'desc']) }}" class="{{ ($sortColumn == 'name' && $sortDirection == 'desc') ? 'active' : '' }}">
                                                <i class="fas fa-sort-down"></i>
                                            </a>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-2 py-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        Email
                                        <div class="sort-icons ms-1">
                                            <a href="{{ route('history', ['sort' => 'email', 'direction' => 'asc']) }}" class="{{ ($sortColumn == 'email' && $sortDirection == 'asc') ? 'active' : '' }}">
                                                <i class="fas fa-sort-up"></i>
                                            </a>
                                            <a href="{{ route('history', ['sort' => 'email', 'direction' => 'desc']) }}" class="{{ ($sortColumn == 'email' && $sortDirection == 'desc') ? 'active' : '' }}">
                                                <i class="fas fa-sort-down"></i>
                                            </a>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-2 py-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        Phone
                                        <div class="sort-icons ms-1">
                                            <a href="{{ route('history', ['sort' => 'phone', 'direction' => 'asc']) }}" class="{{ ($sortColumn == 'phone' && $sortDirection == 'asc') ? 'active' : '' }}">
                                                <i class="fas fa-sort-up"></i>
                                            </a>
                                            <a href="{{ route('history', ['sort' => 'phone', 'direction' => 'desc']) }}" class="{{ ($sortColumn == 'phone' && $sortDirection == 'desc') ? 'active' : '' }}">
                                                <i class="fas fa-sort-down"></i>
                                            </a>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-2 py-3 border-bottom">Buku yang Dipinjam</th>
                                <th class="px-2 py-3 border-bottom">Due Date</th>
                                <th class="px-2 py-3 border-bottom">Tanggal Pengembalian</th>
                                <th class="px-2 py-3 border-bottom">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($borrowers as $index => $borrower)
                                <tr>
                                    <td class="px-2 py-3 border-bottom border-light align-middle">
                                        <ul class="list-unstyled m-0">
                                            @foreach($borrower->borrowedBooks as $book)
                                                <li class="mb-1">
                                                    <div class="form-check">
                                                        <input class="form-check-input book-checkbox" type="checkbox" name="book_ids[]" value="{{ $book->id }}">
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
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
                                                <li class="mb-1">
                                                    <span class="badge bg-light text-dark rounded-pill">
                                                        <i class="far fa-calendar-alt me-1"></i>
                                                        {{ \Carbon\Carbon::parse($book->due_date)->format('d/m/Y') }}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="px-2 py-3 border-bottom border-light">
                                        <ul class="list-unstyled m-0">
                                            @foreach($borrower->borrowedBooks as $book)
                                                <li class="mb-1">
                                                    <span class="badge bg-success rounded-pill">
                                                        <i class="fas fa-check-circle me-1"></i>
                                                        {{ \Carbon\Carbon::parse($book->returned_at)->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="px-2 py-3 border-bottom border-light">
                                        <ul class="list-unstyled m-0">
                                            @foreach($borrower->borrowedBooks as $book)
                                                <li class="mb-1 d-flex gap-1 align-items-center">
                                                    <button type="button" class="btn btn-danger btn-sm delete-book" 
                                                        data-id="{{ $book->id }}" 
                                                        data-title="{{ $book->title }}" 
                                                        data-borrower="{{ $borrower->name }}"
                                                        style="font-size: 0.75rem; padding: 0.25rem 0.5rem; white-space: nowrap;">
                                                        <i class="fas fa-trash-alt me-1"></i>Hapus
                                                    </button>
                                                    <a href="{{ route('borrower.details', $borrower->id) }}" class="btn btn-info btn-sm" 
                                                       style="font-size: 0.75rem; padding: 0.25rem 0.5rem; white-space: nowrap;">
                                                        <i class="fas fa-info-circle me-1"></i>Detail
                                                    </a>
                                                    <form id="delete-form-{{ $book->id }}" action="{{ route('delete.book', $book->id) }}" method="POST" class="d-none">
                                                        @csrf
                                                        @method('POST')
                                                    </form>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                            
                            @if(count($borrowers) == 0)
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle me-2"></i>Tidak ada riwayat peminjaman
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
            </form>
        </div>
    </div>
</div>

<!-- Sweet Alert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Additional Styles -->
<style>
    .text-custom-yellow {
        color: #d0cc4c !important;
    }
    
    .card {
        border-radius: 8px;
        overflow: hidden;
    }
    
    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(208, 204, 76, 0.1);
        border-color: #d8d48f;
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
    
    /* Fix untuk tampilan buku */
    .book-title-item {
        display: block;
        white-space: normal;
        word-break: break-word;
        text-overflow: initial;
        overflow: visible;
        margin-bottom: 0.5rem !important;
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
    
    /* Button styles */
    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        transition: all 0.2s ease;
    }
    
    .btn-danger:hover {
        background-color: #bb2d3b;
        border-color: #b02a37;
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
    
    /* Responsive column widths at different breakpoints */
    @media (min-width: 768px) {
        .table th:nth-child(1), .table td:nth-child(1) { width: 4%; }
        .table th:nth-child(2), .table td:nth-child(2) { width: 14%; }
        .table th:nth-child(3), .table td:nth-child(3) { width: 16%; }
        .table th:nth-child(4), .table td:nth-child(4) { width: 10%; }
        .table th:nth-child(5), .table td:nth-child(5) { width: 18%; }
        .table th:nth-child(6), .table td:nth-child(6) { width: 12%; }
        .table th:nth-child(7), .table td:nth-child(7) { width: 14%; }
        .table th:nth-child(8), .table td:nth-child(8) { width: 12%; }
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
    
    /* Form check styling */
    .form-check {
        margin-bottom: 0;
    }
    
    .form-check-input {
        cursor: pointer;
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
</style>

<!-- Additional Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
        
        // Delete confirmation for delete buttons
        const deleteButtons = document.querySelectorAll('.delete-book');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const title = this.getAttribute('data-title');
                const borrower = this.getAttribute('data-borrower');
                
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    html: `Apakah Anda yakin ingin menghapus catatan peminjaman buku <strong>"${title}"</strong> oleh <strong>${borrower}</strong>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${id}`).submit();
                    }
                });
            });
        });
        
        // Bulk delete functionality
        const selectAllCheckbox = document.getElementById('select-all');
        const bookCheckboxes = document.querySelectorAll('.book-checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
        const bulkDeleteForm = document.getElementById('bulk-delete-form');
        
        // Select all functionality
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            bookCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            updateBulkDeleteButton();
        });
        
        // Individual checkbox change
        bookCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateBulkDeleteButton();
                
                // Update select all checkbox
                const allChecked = Array.from(bookCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(bookCheckboxes).some(cb => cb.checked);
                
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked && !allChecked;
            });
        });
        
        // Update bulk delete button visibility
        function updateBulkDeleteButton() {
            const anyChecked = Array.from(bookCheckboxes).some(cb => cb.checked);
            if (anyChecked) {
                bulkDeleteBtn.classList.remove('d-none');
            } else {
                bulkDeleteBtn.classList.add('d-none');
            }
        }
        
        // Bulk delete button click
        bulkDeleteBtn.addEventListener('click', function() {
            const checkedCount = Array.from(bookCheckboxes).filter(cb => cb.checked).length;
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                html: `Apakah Anda yakin ingin menghapus <strong>${checkedCount}</strong> catatan peminjaman yang dipilih?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus semua!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Ensure the form is using POST method
                    bulkDeleteForm.setAttribute('method', 'POST');
                    bulkDeleteForm.submit();
                }
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
                    <td colspan="8" class="text-center py-4 text-muted">
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