<ul class="nav nav-tabs nav-tabs-bordered d-flex text-center" id="borderedTabJustified" role="tablist">
    <li class="nav-item flex-fill" role="presentation">
        <a href="{{route('publikasi.organisasi', $data->id)}}" class="nav-link w-100 {{request()->routeIs('publikasi.organisasi') ? 'active' : ''}}">
            <i class="bi bi-building"></i> Organisasi
        </a>
    </li>
    <li class="nav-item flex-fill" role="presentation">
        <a href="{{route('publikasi.dataset', $data->id)}}" class="nav-link w-100 {{request()->routeIs('publikasi.dataset') ? 'active' : ''}}" id="data-tab">
           <i class="bi bi-sim"></i> Informasi Dataset
        </a>
    </li>

    <li class="nav-item flex-fill" role="presentation">
        <a href="{{route('publikasi.review', $data->id)}}" class="nav-link w-100 {{request()->routeIs('publikasi.review') ? 'active' : ''}}" id="data-tab">
           <i class="bi bi-check-circle"></i> Review
        </a>
    </li>

</ul>
