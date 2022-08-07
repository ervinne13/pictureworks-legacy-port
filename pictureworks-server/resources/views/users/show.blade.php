<x-layout>
    <section id="main">
        <header>
            <span class="avatar"><img src='{{asset("images/users/$id.jpg")}}' /></span>
            <h1>{{ $name }}</h1>
            @foreach($comments as $comment)
            <p>{{ $comment['comment'] }}</p>
            @endforeach
        </header>
    </section>
    <footer id="footer">
        <ul class="copyright">
            <li>&copy; Pictureworks</li>
        </ul>
    </footer>
</x-layout>