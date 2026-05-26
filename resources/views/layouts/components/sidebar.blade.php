    <div class="row m-1 mt-3">

        <div class="col-md-2">
            <div class="list-group">
                <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action active" aria-current="true">
                    Dashboard
                </a>

                <div class="dropdown">
                    <button class="list-group-item list-group-item-action" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Stock
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">See All</a></li>
                        <li><a class="dropdown-item" href="#">New item</a></li>
                    </ul>
                </div>

                <div class="dropdown">
                    <button class="list-group-item list-group-item-action" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Vehicles
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('vehicles.create') }}">New entry</a></li>
                        <li><a class="dropdown-item" href="#">See actives</a></li>
                    </ul>
                </div>




                <a href="{{ route('costumers.index') }}" class="list-group-item list-group-item-action">Customers</a>
                <a href="#" class="list-group-item list-group-item-action">-</a>
                <a href="#" class="list-group-item list-group-item-action">-</a>
                <a href="#" class="list-group-item list-group-item-action">-</a>
                <a href="#" class="list-group-item list-group-item-action">-</a>
                <a href="#" class="list-group-item list-group-item-action">-</a>
                <a href="#" class="list-group-item list-group-item-action">-</a>
                <a href="#" class="list-group-item list-group-item-action">-</a>



                <a href="#" class="list-group-item list-group-item-action">Bussines info</a>

                <a href="#" class="list-group-item list-group-item-action">Profile</a>
                <a href="#" class="list-group-item list-group-item-action">Settings</a>
                <a href="#" class="list-group-item list-group-item-action">Help</a>




                <form action="{{ route('logout') }}" method="POST" class="">
                    @csrf
                    @method('POST')

                    <button class="list-group-item list-group-item-action"
                        type="submit"><strong>Logout</strong></button>
                </form>
            </div>
        </div>

        <div class="col-md-10">
            @yield('content')
        </div>

    </div>
