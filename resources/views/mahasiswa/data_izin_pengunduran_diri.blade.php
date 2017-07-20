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
              <form class="form-horizontal" action="{{ url('/preview') }}" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="nirm" class="col-sm-3">NIRM</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="nirm" name="nirm" value="{{ $user->nirm }}" readonly style="border: none" />
                  </div>
                </div>
                <div class="form-group">
                  <label for="nama" class="col-sm-3">Nama</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="nama" name="nama" value="{{ $user->nama_mahasiswa }}" readonly style="border: none" />
                  </div>
                </div>
                <div class="form-group">
                  <label for="npm" class="col-sm-3">NPM</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="npm" name="npm" value="{{ $user->npm }}" readonly style="border: none" />
                  </div>
                </div>
                <div class="form-group">
                  <label for="alamat" class="col-sm-3">Alamat</label>
                  <div class="col-sm-9">
                    <textarea class="form-control" row="5" id="alamat" name="alamat" required></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label for="noTelepon" class="col-sm-3">Nomor telepon </label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="noTelepon" name="noTelepon" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="namaOrtu" class="col-sm-3">Nama orang tua</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="namaOrtu" name="namaOrtu" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="semester" class="col-sm-3">Semester</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="semester" value="{{ $user->semester }}" readonly style="border: none" />
                  </div>
                </div>
                <div class="form-group">
                  <label for="smstr" class="col-sm-3">Unggah surat lampiran</label>
                  <div class="col-sm-9">
                    <input type="file" class="form-control" id="lampiran_PengunduranDiri" name="lampiran_PengunduranDiri" required>
                  </div>
                </div>
                <input type="hidden" value="{{ $user->jurusan_id }}" name="prodi">
                <input type="hidden" value="{{ $formatsurat_id }}" name="jenis_surat">
                {!! csrf_field() !!}
                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-10">
                    <button type="submit" class="btn btn-primary">Lanjutkan</button>
                  </div>
                </div>
              </form>
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
