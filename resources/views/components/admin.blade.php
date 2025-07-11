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
            background-color: #013646;
            color: #ffc526;
            text-decoration: none;
            border-radius: 15px;
        }

        .menu-link-xerac:hover {
            background-color: #ffc526;
            color: rgb(17, 24, 39);
        }
    </style>
    <ul class="menu-xerac">
        <li class="menu-item-xerac"><a class="menu-link-xerac" href="/majors">رشته‌ها</a></li>
        <li class="menu-item-xerac"><a class="menu-link-xerac" href="/courses">دروس</a></li>
        <li class="menu-item-xerac"><a class="menu-link-xerac" href="/lessons">مباحث</a></li>
        <li class="menu-item-xerac"><a class="menu-link-xerac" href="/cards">سوالات</a></li>
    </ul>
    <br>
    <hr>
    <br>
    {{ $slot }}
</x-app-layout>