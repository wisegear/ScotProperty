<!-- This is an insert so no layout required -->
<div class="comments-section">
    <h3 class="font-bold text-lg border-b mb-4 dark:text-white dark:border-b-gray-700">Comments ({{ $comments->count() }})</h3>
    <!-- Display the Comments -->
    @foreach($comments as $comment)
        <div class="comment my-4 dark:text-white">
            <strong><a href="/profile/{{ $comment->user->name_slug }}">{{ $comment->user->name }}</a></strong> says:
            <p>{{ $comment->comment_text }}</p>
            <span class="text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
        </div>
    @endforeach
    <!-- Comment Form -->
    @can('Member')
        <form action="{{ route('comments.store') }}" method="POST">
            @csrf
            <input type="hidden" name="commentable_type" value="{{ get_class($model) }}">
            <input type="hidden" name="commentable_id" value="{{ $model->id }}">
            <textarea name="comment_text" class="w-full border rounded" rows="2" placeholder="Add your comment"></textarea>
            <button type="submit" class="border rounded p-2 mt-2 bg-lime-500 hover:bg-lime-400 dark:border-gray-700">Submit Comment</button>
        </form>
    @endcan
    @guest
        <div class="mt-4 font-bold">
            <p class="dark:text-white">Want to comment on this page? <a href="/login" class="text-lime-700">Login</a> or <a href="/register" class="text-lime-700">Register</a>.</p>
        </div>
    @endguest
</div>