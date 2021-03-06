@extends('layouts.app')

@section('title', $post->title)
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            @if ($post->thumbnail)
            <img style="height: 550px; object-fit: cover; object-position: top;" src="{{ asset($post->takeImage) }}"
                class="rounded w-100">
            @endif

            <h1>{{$post->title}}</h1>

            <div class="text-secondary mb-3">
                <a href="/categories/{{ $post->category->slug }}">{{$post->category->name}}</a>
                &middot; {{ $post->created_at->format('d F, Y')}}
                &middot;
                @foreach ($post->tags as $tag)
                <a href="/tags/{{ $tag->slug }}">{{ $tag->name }}</a>
                @endforeach

                <div class="media my-3">
                    <img width="60" class="rounded-circle mr-3" src="{{ $post->user->gravatar() }}" alt="">
                    <div class="media-body">
                        <div>
                            {{ $post->user->name }}
                        </div>
                        {{ '@' . $post->user->username }}
                    </div>
                </div>
            </div>

            <p>{!! nl2br($post->body) !!}</p>

            <div>
                @can('delete', $post)
                <!-- Button trigger modal -->
                <div class="d-flex mt-3">
                    <button type="button" class="btn btn-danger btn-sm mr-2" data-toggle="modal"
                        data-target="#exampleModal">
                        Delete
                    </button>
                    <a href="/posts/{{ $post->slug }}/edit" class="btn btn-sm btn-success">Edit</a>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Are you sure want to delete?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-2">
                                    <div>{{$post->title}}</div>
                                    <div class="text-secondary">
                                        <small>
                                            Published: {{$post->created_at->format('d F, Y')}}
                                        </small>
                                    </div>
                                </div>
                                <form action="/posts/{{ $post->slug }}/delete" method="POST">
                                    @csrf
                                    @method('delete')
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-success mr-2"
                                            data-dismiss="modal">No</button>
                                        <button type="submit" class="btn btn-danger">Yes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan
            </div>
        </div>

        <div class="col-md-4">
            @foreach ($posts as $post)
            <div class="card mb-4">
                <div class="card-body">
                    <div>
                        <a href="{{ route('categories.show', $post->category->slug) }}" class="text-secondary">
                            <small>{{ $post->category->name }} - </small>
                        </a>

                        @foreach ($post->tags as $tag)
                        <a href="{{ route('tags.show', $tag->slug) }}" class="text-secondary">
                            <small>{{ $tag->name }}</small>
                        </a>
                        @endforeach
                    </div>

                    <h5>
                        <a href="{{ route('posts.show', $post->slug) }}" class="card-title text-dark">
                            {{$post->title}}
                        </a>
                    </h5>

                    <div class="text-secondary my-3">
                        <p>{{Str::limit($post->body, 130)}}</p>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div class="media align-items-center">
                            <img width="60" class="rounded-circle mr-3" src="{{ $post->user->gravatar() }}" alt="">
                            <div class="media-body">
                                <div>
                                    {{ $post->user->name }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection