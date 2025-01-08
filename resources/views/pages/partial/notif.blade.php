

<li class="nav-item dropdown">

    <a class="nav-link nav-icon" href="/data_walidata/notif" data-bs-toggle="dropdown">
      <i class="bi bi-bell"></i>
      <span class="badge bg-primary badge-number">5</span>
    </a><!-- End Notification Icon -->

    <ul class="dropdown-menu dropdown-menu-end notifications">
      <li class="dropdown-header">
        You have 5 new notifications
        <a href="/data_walidata/notif"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
      </li>
      @php

          $notif = DB::table("users")
            ->join("activity_log", function ($join) {
                $join->on("users.id", "=", "activity_log.causer_id");
            })
            ->join("data", function ($join) {
                $join->on("data.id", "=", "activity_log.subject_id");
            })
            ->select("users.name", "activity_log.description", "activity_log.created_at", "subject_id", "data.nama_data")
            ->orderby("activity_log.created_at", "DESC")
            ->paginate(5);
      @endphp

      @foreach($notif as $item)
      <li >
        <hr class="dropdown-divider">
      </li>
      <li class="notification-item ">
        <i class="bi bi-info-circle text-primary"></i>
        <div>
          <p style="color: black">{{ $item->description }} : </p>
          <p>"{{  $item->nama_data }}"</p>
          <p>{{ $item->name }}</p>
          <p>{{ Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</p>
        </div>
      </li>
      @endforeach

      <li>
        <hr class="dropdown-divider">
      </li>
      <li class="dropdown-footer">
        <a href="/data_walidata/notif">Show all notifications</a>
      </li>

    </ul><!-- End Notification Dropdown Items -->

  </li>