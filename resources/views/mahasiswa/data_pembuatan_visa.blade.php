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
              <form class="form-horizontal" action = "{{ url('/preview') }}" method="post">
                <div class="form-group">
                  <label for="nama" class="col-sm-3">Nama</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="nama" name="nama" value="{{ $user->nama_mahasiswa }}" readonly style="border: none" />
                  </div>
                </div>
                <div class="form-group">
                  <label for="tglLahir" class="col-sm-3">Tanggal Lahir</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="tglLahir" name="tglLahir" value="{{ date_create($user->tanggal_lahir)->format("j F Y") }}" readonly style="border: none" />
                  </div>
                </div>
                <div class="form-group">
                  <label for="kewarganegaraan" class="col-sm-3">Kewarganegaraan</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="kewarganegaraan" name="kewarganegaraan" value="{{ $user->kewarganegaraan }}" readonly style="border: none" />
                  </div>
                </div>
                <div class="form-group">
                  <label for="thnAkademik" class="col-sm-3">Tahun akademik</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="thnAkademik" value="{{ $user->thnAkademik }}" readonly style="border: none" />
                  </div>
                </div>
                <div class="form-group">
                  <label for="organisasiTujuan" class="col-sm-3">Organisasi tujuan</label>
                  <div class="col-sm-9">
                  <input type="text" class="form-control" id="organisasiTujuan" name="organisasiTujuan" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="negaraTujuan" class="col-sm-3">Negara tujuan</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="negaraTujuan" name="negaraTujuan" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="tanggalKunjungan" class="col-sm-3">Tanggal kunjungan</label>
                  <div class="col-sm-9">
                    <input type="date" class="form-control" id="tanggalKunjungan" name="tanggalKunjungan" required>
                  </div>
                </div>
                <input type="hidden" value="{{ $user->npm }}" name="npm">
                <input type="hidden" value="{{ $user->angkatan }}" name="angkatan">
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
