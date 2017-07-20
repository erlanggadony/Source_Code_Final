<div class="col-md-4 profile">
  <div class="card hovercard">
      <div class="cardheader">

      </div>
      <div class="avatar">
          <img alt="" src="{{ $user->foto_mahasiswa }}" />
      </div>
      <div class="info">
          <div class="title">
              {{ $user->nama_mahasiswa }}
          </div>
          <div class="desc">{{ $user->npm }}</div>
          <div class="desc">{{ $user->jurusan->nama_jurusan }}</div>
      </div>
  </div>
</div>
