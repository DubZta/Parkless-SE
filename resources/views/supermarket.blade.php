<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Supermarket</title>
    <link rel="stylesheet" href="{{ asset('stylereview.css') }}">
</head>
<body>
    <div class="container">
        {{-- <a href="{{ route('map') }}" class="back-link">
            <span class="back-arrow">←</span> Back to Map
        </a> --}}

        <div class="wrapper">
            <h1 class="title">Detail Supermarket</h1>

            <div class="detail-card">
                <h2 class="subtitle">
                    {{ $supermarket->name }}
                    @if($supermarket->brand)
                    <span class="text-muted">| {{ $supermarket->brand }}</span>
                    @endif
                </h2>
                <div class="detail-item">
                    <span class="detail-label">ID Tempat:</span> {{ $supermarket->external_id }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Lokasi:</span> ({{ $supermarket->latitude }}, {{ $supermarket->longitude }})
                </div>
                <div class="detail-item">
                    <span class="detail-label">Cabang:</span> {{ $supermarket->branch ?? '-' }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Operator:</span> {{ $supermarket->operator ?? '-' }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Telepon:</span> {{ $supermarket->phone ?? '-' }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Parkir:</span>
                    <span class="badge {{ $supermarket->has_parking_attendant ? 'badge-secondary' : 'badge-success' }}">
                        {{ $supermarket->has_parking_attendant ? 'Ada tukang parkir' : 'Tidak ada tukang parkir' }}
                    </span>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @auth
            <div class="review-container">
                {{-- Formulir Tulis Review --}}
                <div class="review-form">
                    <div class="form-card">
                        <h2 class="subtitle">Tulis Review</h2>
                        <form action="/review" method="POST">
                            @csrf
                            <input type="hidden" name="supermarket_id" value="{{ $supermarket->id }}">
                            <input type="hidden" name="parent_id" id="parent_id">
                            <div class="field">
                                <textarea name="content" id="content" rows="4" placeholder="Tulis ulasan kamu..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim Review</button>
                        </form>
                    </div>
                </div>

                {{-- Daftar Review --}}
                <div class="review-list">
                    <div class="form-card" style="max-height: 500px; overflow-y: auto;">
                        <h2 class="subtitle">Review Pengguna</h2>
                        @foreach ($reviews as $review)
                            @if(!$review->parent_id)
                            @php $vote_parent = $votes[$review->id] ?? 0; @endphp
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="review-user">{{ $review->user->username }}</div>
                                    @if (auth()->user()->is_admin)
                                        <form action="/review/{{ $review->id }}" method="POST" onsubmit="return confirm('Yakin ingin hapus review ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-button">X</button>
                                        </form>
                                    @endif
                                </div>
                                <div class="review-content">{{ $review->content }}</div>
                                <div class="review-actions">
                                    <div class="review-meta">
                                        <span>{{$review->created_at->diffForHumans()}}</span>
                                        <button class="reply-btn" onclick="reply({{ $review->id }}, '{{$review->user->username}}')">Balas</button>
                                    </div>
                                    <form method="POST" action="/review/{{ $review->id }}/vote" class="vote-form">
                                        @csrf
                                        <button name="vote" value="1" class="vote-btn {{ $vote_parent === 1 ? 'vote-btn-active' : 'vote-btn-inactive' }}">
                                            👍 {{ $review->upvotesCount() }}
                                        </button>
                                        <button name="vote" value="-1" class="vote-btn {{ $vote_parent === -1 ? 'vote-btn-active' : 'vote-btn-inactive' }}">
                                            👎 {{ $review->downvotesCount() }}
                                        </button>
                                    </form>
                                </div>

                            @php
                                $r = $review->replies;
                                $replyCount = $r->count();
                            @endphp

                            @if($replyCount)
                                <button class="toggle-replies"
                                    onclick="toggleReplies({{ $review->id }})"
                                    id="toggle-btn-{{ $review->id }}">
                                    Lihat balasan ({{ $replyCount }})
                                </button>
                                <div class="replies-container d-none" id="replies-{{ $review->id }}">
                                    @foreach ($r as $reply)
                                        <div class="review-item">
                                            <div class="review-user">{{ $reply->user->username }}</div>
                                            <div class="review-content">{{ $reply->content }}</div>
                                            <form method="POST" action="/review/{{ $reply->id }}/vote" class="vote-form">
                                                @csrf
                                                @php $vote_reply = $votes[$reply->id] ?? 0; @endphp
                                                <button name="vote" value="1" class="vote-btn {{ $vote_reply === 1 ? 'vote-btn-active' : 'vote-btn-inactive' }}">
                                                    👍 {{ $reply->upvotesCount() }}
                                                </button>
                                                <button name="vote" value="-1" class="vote-btn {{ $vote_reply === -1 ? 'vote-btn-active' : 'vote-btn-inactive' }}">
                                                    👎 {{ $reply->downvotesCount() }}
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @else
                <div class="alert alert-success">
                    <a href="{{ route('parklessLogin') }}" class="login-link">Login</a>
                    untuk lihat dan tulis review
                </div>
            @endauth
        </div>
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
</body>
</html>
