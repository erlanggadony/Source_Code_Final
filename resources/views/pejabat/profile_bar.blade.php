<div class="col-md-4 profile">
  <div class="card hovercard">
      <div class="cardheader">

      </div>
      <div class="avatar">
          <img alt="" src="http://simpleicon.com/wp-content/uploads/user1.png">
      </div>
      <div class="info">
          <div class="title">
              {{ $user->nama_dosen }}
          </div>
          <div class="desc">{{ $user->nik }}</div>
          <div class="desc">{{ $user->jurusan->nama_jurusan }}</div>
      </div>
  </div>
</div>
