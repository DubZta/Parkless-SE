<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Supermarket</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-4">
        <h2 class="mb-3">Detail Supermarket</h2>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    {{ $supermarket->name }}
                    @if($supermarket->brand)
                    <span class="text-muted">| {{ $supermarket->brand }}</span>
                    @endif
                </h5>
                <p class="card-text">
                    <strong>ID Tempat:</strong> {{ $supermarket->external_id }}<br>
                    <strong>Lokasi:</strong> ({{ $supermarket->latitude }}, {{ $supermarket->longitude }})<br>
                    <strong>Cabang:</strong> {{ $supermarket->branch ?? '-' }}<br>
                    <strong>Operator:</strong> {{ $supermarket->operator ?? '-' }}<br>
                    <strong>Telepon:</strong> {{ $supermarket->phone ?? '-' }}<br>
                    <strong>Parkir:</strong>
                    <span class="badge bg-{{ $supermarket->has_parking_attendant ? 'secondary' : 'success' }}">
                        {{ $supermarket->has_parking_attendant ? 'Ada tukang parkir' : 'Tidak ada tukang parkir' }}
                    </span>
                </p>
            </div>
        </div>

        <h3>Review</h3>
        @forelse($reviews as $review)
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="card-subtitle mb-1 text-muted">{{ $review->user->username }}</h6>
                    <p class="card-text">{{ $review->content }}</p>
                    <div class="d-flex gap-3">
                        <span class="badge bg-success">👍 {{ $review->upvotes }}</span>
                        <span class="badge bg-danger">👎 {{ $review->downvotes }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">Belum ada review.</div>
        @endforelse

        {{-- @auth --}}
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Tulis Review</h5>
                    <form method="POST" action="">
                        @csrf
                        <input type="hidden" name="place_id" value="{{ $supermarket->id }}">
                        <div class="mb-3">
                            <textarea name="review" class="form-control" rows="3" placeholder="Tulis review..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </form>
                </div>
            </div>
        {{-- @else
            <p class="mt-3"><i>Login untuk menulis review.</i></p>
        @endauth --}}
    </div>

    <!-- Bootstrap JS (optional if you need dropdowns, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
