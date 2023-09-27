@if($show == 'true')
    {{-- Profile menu --}}
    <div class="dropdown d-inline-block user-dropdown">
        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{-- <img class="rounded-circle header-profile-user" src="assets/images/users/avatar-1.jpg"
                alt="Header Avatar"> --}}
            <span class="d-xl-inline-block ms-1">{{ request()->user()->name }}</span>
            <i class="mdi mdi-chevron-down  d-xl-inline-block"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
            <!-- item-->
            <a class="dropdown-item" href="{{ route('profile.edit')}}"><i class="ri-user-line align-middle me-1"></i> Profile</a>
            {{-- <a class="dropdown-item" href="#"><i class="ri-wallet-2-line align-middle me-1"></i> My Wallet</a>
            <a class="dropdown-item d-block" href="#"><span class="badge bg-success float-end mt-1">11</span><i class="ri-settings-2-line align-middle me-1"></i> Settings</a>
            <a class="dropdown-item" href="#"><i class="ri-lock-unlock-line align-middle me-1"></i> Lock screen</a> --}}
            <div class="dropdown-divider"></div>               
            <a class="dropdown-item text-danger" href="{{route('logout')}}">
                <i class="ri-shut-down-line align-middle me-1 text-danger"></i> Logout
            </a>
        </div>
    </div>

@endif