<!DOCTYPE html>
  <head>
      <title>Isi Data Diri</title>
      <link href="{{ asset("/bootstrap-3.3.7-dist/css/bootstrap.css") }}" rel="stylesheet" type="text/css" />
      <link href="{{ asset("/css/styles_list_surat.css") }}" rel="stylesheet" type="text/css">

  </head>

  <body>
    <div>
        <img id=banner src="{{ asset("/images/banner ftis.png") }}" />
    </div>


    <!-- Navigation here -->
    @include('mahasiswa.menu')

    <div class="container">
      <div class="main">
          <div class="row">
            <div class="col-md-8 content">
              <h1>Isi Data Diri Anda</h1>
              <br>
              <form class="form-horizontal" action="{{ url('/preview') }}" method="post">
                <div class="form-group">
                  <label for="nama" class="col-sm-3">Nama</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="nama" value="{{ $user->nama_mahasiswa }}" readonly style="border: none" />
                  </div>
                </div>
                <div class="form-group">
                  <label for="npm" class="col-sm-3">NPM</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="npm" value="{{ $user->npm }}" readonly style="border: none" />
                  </div>
                </div>
                <div class="form-group prev">
                  <label for="prodi" class="col-sm-3">Program studi</label>
                  <div class="col-sm-9">
                    <span type="text" class="form-control" readonly style="border: none" >{{ $user->jurusan->nama_jurusan }}</span>
                    <input type="hidden" name="prodi" value="{{ $user->jurusan_id }}"/>
                  </div>
                </div>
                <div class="form-group">
                  <label for="matkul" class="col-sm-3">Mata kuliah</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="matkul" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="topik" class="col-sm-3">Topik</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="topik" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="organisasi" class="col-sm-3">Organisasi tujuan</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="organisasi" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="alamatOrganisasi" class="col-sm-3">Alamat organisasi tujuan</label>
                  <div class="col-sm-9">
                    <textarea class="form-control" row="5" name="alamatOrganisasi" required></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label for="keperluanKunjungan" class="col-sm-3">Keperluan kunjungan</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="keperluanKunjungan" required>
                  </div>
                </div>
                <input type="hidden" value="{{ $formatsurat_id }}" name="jenis_surat">
                {!! csrf_field() !!}
                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-10">
                    <button type="submit" class="btn btn-primary">Lanjutkan</button>
                  </div>
                </div>
            </div>
            @include('mahasiswa.profile_bar')
          </div>
      </div>
    </div>
    <div class="footer">
        hahahahahahahahahahahahahahahhahahahahahaha
    </div>
  </body>
</html>
