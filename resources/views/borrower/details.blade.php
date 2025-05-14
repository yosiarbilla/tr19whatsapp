@extends('layouts.app')
@section('admin_layout')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="fas fa-user-circle text-custom-yellow me-2"></i>Detail Peminjam
        </h4>
        <div>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Borrower Info Card -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-id-card text-custom-yellow me-2"></i>Informasi Peminjam
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 pb-3 border-bottom">
                        <label class="text-muted small d-block mb-1">Nama</label>
                        <div class="fw-medium">{{ $borrower->name }}</div>
                    </div>
                    
                    <div class="mb-3 pb-3 border-bottom">
                        <label class="text-muted small d-block mb-1">Email</label>
                        <div class="fw-medium">
                            <i class="fas fa-envelope text-muted me-1"></i>
                            <a href="mailto:{{ $borrower->email }}">{{ $borrower->email }}</a>
                        </div>
                    </div>
                    
                    <div class="mb-3 pb-3 border-bottom">
                        <label class="text-muted small d-block mb-1">Nomor Telepon</label>
                        <div class="fw-medium">
                            <i class="fas fa-phone text-muted me-1"></i>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $borrower->phone) }}" target="_blank">
                                {{ $borrower->phone }}
                            </a>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted small d-block mb-1">Terdaftar Pada</label>
                        <div class="fw-medium">
                            <i class="fas fa-calendar-alt text-muted me-1"></i>
                            {{ $borrower->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Books Card -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-book text-custom-yellow me-2"></i>Buku yang Dipinjam
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-3 py-2">Buku</th>
                                    <th class="border-0 px-3 py-2">Tanggal Jatuh Tempo</th>
                                    <th class="border-0 px-3 py-2">Status</th>
                                    <th class="border-0 px-3 py-2">Tanggal Pengembalian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($borrower->borrowedBooks as $book)
                                    @php
                                        $due_date = \Carbon\Carbon::parse($book->due_date);
                                        $now = \Carbon\Carbon::now();
                                        $is_overdue = $due_date->isPast() && !$book->is_returned;
                                        $is_upcoming = $due_date->diffInDays($now) <= 3 && !$is_overdue;
                                    @endphp
                                    <tr>
                                        <td class="px-3 py-2 border-bottom border-light align-middle">
                                            <span class="fw-medium">{{ $book->title }}</span>
                                        </td>
                                        <td class="px-3 py-2 border-bottom border-light align-middle">
                                            <span class="{{ $is_overdue ? 'badge bg-danger' : ($is_upcoming ? 'badge bg-warning text-dark' : 'badge bg-light text-dark') }} rounded-pill">
                                                <i class="{{ $is_overdue ? 'fas fa-exclamation-circle' : ($is_upcoming ? 'fas fa-clock' : 'far fa-calendar-alt') }} me-1"></i>
                                                {{ $due_date->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 border-bottom border-light align-middle">
                                            @if($book->is_returned)
                                                <span class="badge bg-success">Dikembalikan</span>
                                            @else
                                                <span class="badge bg-{{ $is_overdue ? 'danger' : 'primary' }}">
                                                    {{ $is_overdue ? 'Terlambat' : 'Dipinjam' }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 border-bottom border-light align-middle">
                                            @if($book->is_returned)
                                                <span class="text-success">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    {{ \Carbon\Carbon::parse($book->returned_at)->format('d/m/Y H:i') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                
                                @if(count($borrower->borrowedBooks) == 0)
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Tidak ada buku yang dipinjam
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Message Logs Card -->
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-history text-custom-yellow me-2"></i>Log Pesan WhatsApp
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-3 py-2">Waktu</th>
                                    <th class="border-0 px-3 py-2">Buku</th>
                                    <th class="border-0 px-3 py-2">Status</th>
                                    <th class="border-0 px-3 py-2">Pesan</th>
                                    <th class="border-0 px-3 py-2">Response</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($messageLogs as $log)
                                    <tr>
                                        <td class="px-3 py-2 border-bottom border-light align-middle" style="min-width: 120px;">
                                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                                        </td>
                                        <td class="px-3 py-2 border-bottom border-light align-middle">
                                            @if($log->book)
                                                <span class="fw-medium">{{ $log->book->title }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 border-bottom border-light align-middle">
                                            @php
                                                $statusBadge = match($log->status) {
                                                    'sent' => 'bg-success',
                                                    'pending' => 'bg-warning text-dark',
                                                    'retry' => 'bg-info',
                                                    'failed', 'failed_permanent' => 'bg-danger',
                                                    'error' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                                
                                                $statusText = match($log->status) {
                                                    'sent' => 'Terkirim',
                                                    'pending' => 'Menunggu',
                                                    'retry' => 'Mencoba Ulang',
                                                    'failed' => 'Gagal',
                                                    'failed_permanent' => 'Gagal Permanen',
                                                    'error' => 'Error',
                                                    default => ucfirst($log->status)
                                                };
                                            @endphp
                                            <span class="badge {{ $statusBadge }}">{{ $statusText }}</span>
                                        </td>
                                        <td class="px-3 py-2 border-bottom border-light align-middle">
                                            <button type="button" class="btn btn-sm btn-outline-secondary view-message-btn" data-message="{{ $log->message }}">
                                                <i class="fas fa-eye me-1"></i>Lihat Pesan
                                            </button>
                                        </td>
                                        <td class="px-3 py-2 border-bottom border-light align-middle">
                                            <div style="max-width: 350px; overflow: hidden; text-overflow: ellipsis;">
                                                {{ $log->response ?? '-' }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                
                                @if(count($messageLogs) == 0)
                                    <tr>
                                        <td colspan="5" class="text-center py-3 text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Tidak ada log pesan
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Message Preview Modal -->
<div class="modal fade" id="messagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    <i class="fab fa-whatsapp text-success me-2"></i>Preview Pesan WhatsApp
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="border rounded p-3 bg-light message-preview">
                    <pre class="mb-0" style="white-space: pre-wrap;"></pre>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize view message buttons
        const viewMessageBtns = document.querySelectorAll('.view-message-btn');
        const messagePreviewModal = new bootstrap.Modal(document.getElementById('messagePreviewModal'));
        const messagePreviewContent = document.querySelector('#messagePreviewModal pre');
        
        viewMessageBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const message = this.getAttribute('data-message');
                messagePreviewContent.textContent = message;
                messagePreviewModal.show();
            });
        });
    });
</script>

<style>
    .badge {
        font-weight: 500;
        padding: 0.4em 0.7em;
    }
    
    .text-custom-yellow {
        color: #d0cc4c !important;
    }
    
    .table tbody tr:hover {
        background-color: rgba(0,0,0,0.02);
    }
    
    .view-message-btn {
        font-size: 0.8rem;
        padding: 0.2rem 0.5rem;
    }
    
    .message-preview {
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
        font-size: 0.9rem;
        max-height: 400px;
        overflow-y: auto;
    }
</style>
@endsection 