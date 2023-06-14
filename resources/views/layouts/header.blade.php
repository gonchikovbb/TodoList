<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #9fc5e8;">
    <div class="container-fluid">
        <ul class="nav navbar-nav">
            <li class="active"><a href="/">Главная страница</a></li>

            <a class="btn btn-success" href="{{ route('tasks.showTasks') }}">Все задачи</a>

        </ul>

        <ul class="nav navbar-nav navbar-right">
            @auth
                <li class="nav-item">
                    <span class="nav-link">{{ auth()->user()->name }}</span>
                </li>
                <li class="nav-item">
                    <form id="logout-form" action="{{ route('signOut') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Выйти</button>
                    </form>
                </li>
            @endauth

            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('signIn') }}">{{ __('Войти') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('signUp') }}">{{ __('Регистрация') }}</a>
                </li>
            @endguest
        </ul>
    </div>
</nav>
