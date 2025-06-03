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
        {{-- <a href="/map" class="btn btn-info mb-4">Back to Map</a> --}}
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

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @auth
        <div class="row mt-4">
            {{-- Formulir Tulis Review --}}
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Tulis Review</h5>
                        <form action="/review" method="POST">
                            @csrf
                            <input type="hidden" name="supermarket_id" value="{{ $supermarket->id }}">
                            <input type="hidden" name="parent_id" id="parent_id">
                            <div class="mb-2">
                                <textarea name="content" id="content" rows="4" class="form-control" placeholder="Tulis ulasan kamu..."></textarea>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Kirim Review</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Daftar Review --}}
            <div class="col-md-8">
                <div class="card" style="max-height: 350px; overflow-y: auto;">
                    <div class="card-body">
                        <h5 class="card-title">Review Pengguna</h5>
                        @foreach ($reviews as $review)
                            @if(!$review->parent_id)
                            @php $vote_parent = $votes[$review->id] ?? 0; @endphp
                            <div class="border-bottom p-2 mb-2">
                                <strong>{{ $review->user->username }}</strong>
                                <p class="mb-0">{{ $review->content }}</p>
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div class="d-flex gap-4 fs-6 text-secondary align-items-center">
                                        <span>{{$review->created_at->diffForHumans()}}</span>
                                        <button class="btn btn-sm fw-medium fs-6" onclick="reply({{ $review->id }}, '{{$review->user->username}}')">Balas</button>
                                    </div>
                                    <form method="POST" action="/review/{{ $review->id }}/vote" class="d-flex gap-2">
                                        @csrf
                                        <button name="vote" value="1" class="btn btn-sm {{ $vote_parent === 1 ? 'btn-success' : 'btn-secondary' }}">
                                            👍 {{ $review->upvotesCount() }}
                                        </button>
                                        <button name="vote" value="-1" class="btn btn-sm {{ $vote_parent === -1 ? 'btn-danger' : 'btn-secondary' }}">
                                            👎 {{ $review->downvotesCount() }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endif
                        @php
                            $r = $review->replies;
                            $replyCount = $r->count();
                        @endphp

                        @if($replyCount)
                            <button class="btn btn-sm text-primary mb-2"
                                onclick="toggleReplies({{ $review->id }})"
                                id="toggle-btn-{{ $review->id }}">
                                Lihat balasan ({{ $replyCount }})
                            </button>
                            <div class="ms-4 border-start ps-2 mt-1 d-none" id="replies-{{ $review->id }}">
                                @foreach ($r as $reply)
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between">
                                            <strong>{{ $reply->user->username }}</strong>
                                            <div class="d-flex">
                                                <form method="POST" action="/review/{{ $reply->id }}/vote" class="d-flex gap-2">
                                                    @csrf
                                                    @php $vote_reply = $votes[$reply->id] @endphp
                                                    <button name="vote" value="1" class="btn btn-sm {{ $vote_reply === 1 ? 'btn-success' : 'btn-secondary' }}">
                                                        👍 {{ $reply->upvotesCount() }}
                                                    </button>
                                                    <button name="vote" value="-1" class="btn btn-sm {{ $vote_reply === -1 ? 'btn-danger' : 'btn-secondary' }}">
                                                        👎 {{ $reply->downvotesCount() }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <p class="mb-1">{{ $reply->content }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @else
            <p class="alert alert-success">Login untuk lihat dan tulis review</p>
        @endauth

        {{-- <div class="w-75 mx-auto my-5">
        @forelse($reviews as $review)
            <div class="card mb-3">
                <div class="card-body w-100">
                    <h5 class="card-subtitle mb-1 text-muted">{{ $review->user->username }}</h5>
                    <p class="card-text">{{ $review->content }}</p>
                    <div class="d-flex gap-3">
                        <form action="/review/{{$review->id}}/vote" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="vote" value="1">
                            <button type="submit" class="btn btn-sm {{$votes[$review->id] == 1 ? 'btn-success' : 'btn-outline-success'}}">👍 {{$review->upvotesCount()}}</button>
                        </form>

                        <form action="/review/{{$review->id}}/vote" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="vote" value="-1">
                            <button type="submit" class="btn btn-sm {{$votes[$review->id] == -1 ? 'btn-danger' : 'btn-outline-danger'}}">👎 {{$review->downvotesCount()}}</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">Belum ada review.</div>
        @endforelse
        </div> --}}

        {{-- @auth --}}
            {{-- <div class="card mt-4 w-75 mx-auto">
                <div class="card-body w-100">
                    <h5 class="card-title">Tulis Review</h5>
                    <form method="POST" action="/review">
                        @csrf
                        <input type="hidden" name="supermarket_id" value="{{ $supermarket->id }}">
                        <div class="mb-3">
                            <textarea name="content" class="form-control" rows="3" placeholder="Tulis review..." required></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </div>
                    </form>
                </div>
            </div> --}}
        {{-- @else
            <p class="mt-3"><i>Login untuk menulis review.</i></p>
        @endauth --}}
    </div>

    <script>
         function toggleReplies(reviewId) {
            const repliesDiv = document.getElementById('replies-' + reviewId);
            const toggleBtn = document.getElementById('toggle-btn-' + reviewId);

            if (repliesDiv.classList.contains('d-none')) {
                repliesDiv.classList.remove('d-none');
                toggleBtn.textContent = 'Sembunyikan balasan';
            } else {
                repliesDiv.classList.add('d-none');
                toggleBtn.textContent = 'Lihat balasan (' + repliesDiv.children.length + ')';
            }
        }
        function reply(reviewId, username) {
            const input = document.getElementById('parent_id');
            input.value = parseInt(reviewId);

            const textarea = document.getElementById('content');
            textarea.placeholder = 'Membalas @' + username;

            textarea.focus();
        }
    </script>
    <!-- Bootstrap JS (optional if you need dropdowns, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
