
<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #9fc5e8;">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a class="navbar-brand">Список задач</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="nav navbar-nav navbar-left">
                <li><a href="/">Главная страница</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('signIn') }}">{{ __('Войти') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('signUp') }}">{{ __('Регистрация') }}</a>
                    </li>
                @endguest

                @auth
                    <li class="nav-item">
                        <span class="nav-link">{{ auth()->user()->name }}</span>
                    </li>
                    <li class="nav-item">
                        <form id="logout-form" action="{{ route('signOut') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">{{ __('Выйти') }}</button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
