@extends('layouts.app')
@push('styles')
    <style>
        button.list-group-item{
            min-width: 125px;
            border-radius: 0;
        }
    </style>
@endpush
@section('content')
    <div class="container">
        <a href="{{ route('user.page.profile') }}" class="btn btn-block btn-outline-primary">Profile</a>
        <a href="{{ route('post.create') }}" class="btn btn-block btn-outline-primary">Create material</a>
        <div class="post-list">
            @forelse($posts as $post)
                <div class="post" data-id="{{$post->id}}">
                    <div class="list-group list-group-horizontal-sm">
                        <a href="{{route('post.show', ['post' => $post->slug])}}" class="list-group-item bg-light list-group-item-action">
                            <h4 class="mb-1">{{$post->title}}</h4>
                            <div class="postInfo font-weight-light">
                                <p><span class="createdAt"><i class="fa fa-calendar"></i> {{ $post->created_at->format('M d Y, H:i') }}</span>
                                    <span class="commentsCount"><i class="fa fa-comments"></i> {{ $post->comments->count() }}</span></p>
                            </div>
                            <p class="mb-1">{{$post->shortDesc(130)}}</p>
                        </a>
                        @if($post->published)
                            <button id="publicationButton" aria-hidden="true" data-status="published" data-id="{{$post->id}}" type="button" class="list-group-item btn btn-outline-success">Published</button>
                        @else
                            <button id="publicationButton" aria-hidden="true" data-status="not published" data-id="{{$post->id}}" type="button" class="list-group-item btn btn-outline-danger">Not published</button>
                        @endif
                    </div>
                </div>
            @empty
                <h3>Missing data</h3>
            @endforelse
            <div class="pagination">
                <ul class="pagination">
                    {{$posts->links()}}
                </ul>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('button#publicationButton').on('click', function () {
                let status = this.getAttribute("data-status");
                let postId = this.getAttribute("data-id");
                let element = this;

                element.disabled = true;
                element.innerHTML = `
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...`;
                $.ajax({
                    method: "POST",
                    url: "/post/publish",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": postId,
                        "status": status
                    }
                }).done(function( result ) {
                    if(result){
                        element.disabled = false;
                        result = JSON.parse(result);
                        element.setAttribute('data-status', result.status);
                        if(result.status === 'published'){
                            element.className = 'list-group-item btn btn-outline-success';
                            element.innerHTML = 'Published';
                        } else {
                            element.className = 'list-group-item btn btn-outline-danger';
                            element.innerHTML = 'Not published';
                        }
                    }
                })
                .fail(function() {
                    if(status === 'published'){
                        element.className = 'list-group-item btn btn-outline-success';
                        element.innerHTML = 'Published';
                    } else {
                        element.className = 'list-group-item btn btn-outline-danger';
                        element.innerHTML = 'Not published';
                    }
                    element.disabled = false;
                })
            })
        })
    </script>
@endpush