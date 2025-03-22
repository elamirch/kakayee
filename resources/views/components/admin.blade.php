<x-app-layout>
    <style>
        .menu-xerac {
            list-style-type: none;
            padding: 0;
        }

        .menu-item-xerac {
            margin-top: 10px;
            display: inline-block;
        }

        .menu-link-xerac {
            padding: 10px;
            color: white;
            text-decoration: none;
        }

        .menu-link-xerac:hover {
            background-color: white;
            color: rgb(17, 24, 39);
        }
    </style>
    <ul class="menu-xerac">
        <li class="menu-item-xerac"><a class="menu-link-xerac" href="/cards">Cards</a></li>
        <li class="menu-item-xerac"><a class="menu-link-xerac" href="/lessons">Lessons</a></li>
        <li class="menu-item-xerac"><a class="menu-link-xerac" href="/chapters">Chapters</a></li>
        <li class="menu-item-xerac"><a class="menu-link-xerac" href="/courses">Courses</a></li>
        <li class="menu-item-xerac"><a class="menu-link-xerac" href="/majors">Majors</a></li>
    </ul>
    <br>
    {{ $slot }}
</x-app-layout>