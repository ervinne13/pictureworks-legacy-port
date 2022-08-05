<x-layout>
    <section id="main">
        <header>
            <span class="avatar"><img src='{{asset("images/users/$id.jpg")}}' /></span>
            <h1>{{ $name }}</h1>
            <p>Comments here later</p>
        </header>
    </section>
    <footer id="footer">
        <ul class="copyright">
            <li>&copy; Pictureworks</li>
        </ul>
    </footer>
</x-layout>