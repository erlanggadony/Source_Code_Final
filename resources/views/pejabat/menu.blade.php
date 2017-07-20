<div class="navigation">
     <div class="navbar text-center">
        <ul class="inline">
          <a href="/home_pejabat"><li>Home</li></a>
          <a href="/persetujuan_pejabat"><li>Persetujuan</li></a>
          <a href="/history_pejabat"><li>History Surat</li></a>

          <a href="{{ url('/logout') }}"
              onclick="event.preventDefault();
                       document.getElementById('logout-form').submit();"><li>
              Logout
          <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
              {{ csrf_field() }}
          </form>
          </li></a>
        </ul>
     </div>
</div>
<br>
<!-- <div class="row">
  <div class="col-md-offset-1 col-sm-offset-1 col-md-4 col-sm-5">
    <div style="color:white">
        Selamat Datang, {!! Auth::user()->name !!}
    </div>
  </div>
</div> -->
<div class="row">
  <div class="col-md-offset-1 col-sm-offset-1 col-sm-4 col-md-4">

  </div>
</div>
