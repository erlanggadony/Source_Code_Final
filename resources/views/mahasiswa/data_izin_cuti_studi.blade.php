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
              <form class="form-horizontal" action = "{{ url('/preview') }}" method="post" enctype="multipart/form-data">
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
                  <label for="prodi" class="col-sm-3">Program studi</label>
                  <div class="col-sm-9">
                    <span type="text" class="form-control" readonly style="border: none" >{{ $user->jurusan->nama_jurusan }}</span>
                    <input type="hidden" name="prodi" value="{{ $user->jurusan_id }}"/>
                  </div>
                </div>
                <div class="form-group">
                  <label for="fakultas" class="col-sm-3">Fakultas</label>
                  <div class="col-sm-9">
                    <span type="text" class="form-control" readonly style="border: none" >{{ $user->fakultas->nama_fakultas }}</span>
                    <input type="hidden" name="fakultas" value="{{ $user->fakultas_id }}"/>
                  </div>
                </div>
                <div class="form-group">
                  <label for="alamat" class="col-sm-3">Alamat</label>
                  <div class="col-sm-9">
                    <textarea class="form-control" row="5" id="alamat" name="alamat" required></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label for="cutiStudiKe" class="col-sm-3">Cuti studi ke</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="cutiStudiKe" name="cutiStudiKe" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="alasanCutiStudi" class="col-sm-3">Alasan cuti studi</label>
                  <div class="col-sm-9">
                    <textarea class="form-control" row="5" id="alasanCutiStudi"name="alasanCutiStudi" required></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label for="dosenWali" class="col-sm-3">Dosen wali</label>
                  <div class="col-sm-9">
                    <span type="text" class="form-control" readonly style="border: none" >{{ $user->dosen->nama_dosen }}</span>
                    <input type="hidden" name="dosenWali" value="{{ $user->dosen_id }}"/>
                  </div>
                </div>
                <div class="form-group">
                  <label for="semester" class="col-sm-3">Semester</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="semester" value="{{ $user->semester }}" readonly style="border: none" />
                  </div>
                </div>
                <div class="form-group">
                  <label for="thnAkademik" class="col-sm-3">Tahun akademik</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="thnAkademik" value="{{ $user->thnAkademik }}" readonly style="border: none" />
                  </div>
                </div>
                <div class="form-group">
                  <label for="lampiran_CutiStudi" class="col-sm-3">Unggah surat lampiran</label>
                  <div class="col-sm-9">
                    <input type="file" class="form-control" id="lampiran_CutiStudi" name="lampiran_CutiStudi" required>
                  </div>
                </div>
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
