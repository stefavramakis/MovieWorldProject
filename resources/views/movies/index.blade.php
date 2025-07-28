@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center mt-4">
        <div class="w-75">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex flex-column">
                    <h1 class="mb-4 text-center">Movie World</h1>
                    <div class="mb-2">
                        Found {{ $movies->count() }} movies
                    </div>
                </div>

                @guest
                    <div class="mb-3 text-center">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                    </div>
                @endguest
                @auth
                    <div class="d-flex flex-column align-items-center mb-3">
                        <span class="text-center mb-2" style="font-size: 0.8rem!important">
                        Welcome Back
                        <a href="{{ route('movies.index', ['user' => auth()->id()]) }}" style="color: #00acee; text-decoration: none;">
                            {{ auth()->user()->name }}
                        </a>
                        </span>
                        <div class="mb-3 d-flex justify-content-end w-100">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary me-2">Logout</button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
            <div class="flex justify-content-between mb-3">
                <div class="w-75" style="height: 700px; overflow-y: scroll;">
                    @foreach ($movies as $movie)
                        @php
                            $userVote = $movie->votes->firstWhere('user_id', auth()->id());
                            $hasLiked = $userVote && $userVote->type === 'like';
                            $hasHated = $userVote && $userVote->type === 'hate';
                        @endphp
                        <div class="border border-2 rounded-4 p-3 mb-4" style="max-width: 600px; font-family: Arial, sans-serif;">
                            <!-- Top bar -->
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <strong>{{ $movie->title }}</strong>
                                <span style="font-size: 0.9em;">
                                    Posted {{ $movie->created_at->format('d/m/Y') }}
                                </span>
                            </div>

                            <!-- Description -->
                            <div style="margin-bottom: 15px;" class="w-100">
                                <p style="font-size: 0.95em; color: #333; word-wrap: break-word; overflow-wrap: break-word;">
                                    {{ $movie->description }}
                                </p>
                            </div>

                            <!-- Bottom bar -->
                            <div class="d-flex justify-content-between align-items-center" style="font-size: 0.9em;">
                                <div>
                                    <span style="color: {{ $hasLiked ? '#1cc741' : '#000000' }};">{{ $movie->getLikesCount() }} likes</span> | <span style="color: {{ $hasHated ? '#fc0a0a' : '#000000' }};">{{ $movie->getHatesCount() }} hates</span>
                                </div>
                                <div>
`                                    @if(auth()->check() && auth()->id() !== $movie->user_id)
                                        <form action="{{ route('movies.react', ['movie' => $movie->id, 'type' => 'like']) }}"
                                              method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit"
                                                    style="
                                                        cursor: pointer;
                                                        color: {{ $hasLiked ? '#fff' : '#00acee' }};
                                                        background: {{ $hasLiked ? '#00acee' : 'none' }};
                                                        border: none;
                                                        border-radius: {{ $hasLiked ? '20px' : '0' }};
                                                        padding: 4px 12px;
                                                        transition: all 0.2s ease-in-out;
                                                    ">
                                                Like
                                            </button>
                                        </form>
                                        |
                                        <form action="{{ route('movies.react', ['movie' => $movie->id, 'type' => 'hate']) }}"
                                              method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit"
                                                    style="
                                                        cursor: pointer;
                                                        color: {{ $hasHated ? '#fff' : '#00acee' }};
                                                        background: {{ $hasHated ? '#00acee' : 'none' }};
                                                        border: none;
                                                        border-radius: {{ $hasHated ? '20px' : '0' }};
                                                        padding: 4px 12px;
                                                        transition: all 0.2s ease-in-out;
                                                    ">
                                                Hate
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <div style="font-size: 0.8em;">
                                    Posted by
                                    <span style="color: #00acee;">
                                    @if(auth()->check() && auth()->id() === $movie->user_id)
                                            You
                                        @else
                                            {{ $movie->user->name  }}
                                        @endif
                                        </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div>
                    @auth
                        <div class="mb-2">
                            <a href="{{ route('movies.create') }}" class="btn btn-success">New Movie</a>
                        </div>
                    @endauth
                        <div class="align-items-center border border-4 border-black d-flex flex-column rounded-4 p-3">
                            <p class="mb-2 fw-bold">Sort by:</p>
                            <div class="d-flex flex-column align-items-start gap-2 w-100">
                                {{-- Likes --}}
                                <a href="{{ route('movies.index', ['sort' => 'likes']) }}"
                                   class="d-flex align-items-center gap-2 {{ request('sort') == 'likes' ? 'fw-bold text-primary' : 'text-decoration-none text-dark' }}">
                                    Likes
                                    <div style="
                width: 16px;
                height: 16px;
                border: 2px solid {{ request('sort') == 'likes' ? '#28a745' : '#aaa' }};
                background-color: {{ request('sort') == 'likes' ? '#28a745' : 'transparent' }};
                border-radius: 3px;
            "></div>
                                </a>
                                <hr class="my-1 w-100" style="border-top: 2px solid #444;">

                                {{-- Hates --}}
                                <a href="{{ route('movies.index', ['sort' => 'hates']) }}"
                                   class="d-flex align-items-center gap-2 {{ request('sort') == 'hates' ? 'fw-bold text-primary' : 'text-decoration-none text-dark' }}">
                                    Hates
                                    <div style="
                width: 16px;
                height: 16px;
                border: 2px solid {{ request('sort') == 'hates' ? '#28a745' : '#aaa' }};
                background-color: {{ request('sort') == 'hates' ? '#28a745' : 'transparent' }};
                border-radius: 3px;
            "></div>
                                </a>
                                <hr class="my-1 w-100" style="border-top: 2px solid #444;">

                                {{-- Dates --}}
                                <a href="{{ route('movies.index', ['sort' => 'date']) }}"
                                   class="d-flex align-items-center gap-2 {{ request('sort') == 'date' || !request('sort') ? 'fw-bold text-primary' : 'text-decoration-none text-dark' }}">
                                    Dates
                                    <div style="
                width: 16px;
                height: 16px;
                border: 2px solid {{ (request('sort') == 'date' || !request('sort')) ? '#28a745' : '#aaa' }};
                background-color: {{ (request('sort') == 'date' || !request('sort')) ? '#28a745' : 'transparent' }};
                border-radius: 3px;
            "></div>

                                </a>
                            </div>
                        </div>
                </div>
            </div>

        </div>
    </div>
@endsection
