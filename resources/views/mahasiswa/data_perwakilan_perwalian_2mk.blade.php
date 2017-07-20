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
            <div class="col-md-8 content form-horizontal">
              <h1>Isi Data Diri Anda</h1>
              <br>
              <form class="form-horizontal" action="{{ url('/preview') }}" method="post">
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
                  <p class="col-sm-12" style="font-weight:bold">
                    IDENTITAS MAHASISWA YANG PERWALIANNYA DIWAKILKAN :
                  </p>
                    <div class="form-group">
                      <label for="nama" class="col-sm-3">Nama</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $user->nama_mahasiswa }}" readonly style="border: none" >
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="npm" class="col-sm-3">NPM</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" id="npm" name="npm" value="{{ $user->npm }}" readonly style="border: none">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="prodi" class="col-sm-3">Program studi</label>
                      <div class="col-sm-9">
                        <span type="text" class="form-control" readonly style="border: none" >{{ $user->jurusan->nama_jurusan }}</span>
                        <input type="hidden" id="prodi" name="prodi" value="{{ $user->jurusan_id }}" >
                      </div>
                    </div>
                  <p class="col-md-12" style="font-weight:bold">
                    IDENTITAS MAHASISWA YANG DIBERI KUASA PERWALIAN :
                  </p>
                  <div class="form-group">
                    <label for="namaWakil" class="col-sm-3">Nama</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="namaWakil" name="namaWakil" required/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="npmWakil" class="col-sm-3">NPM</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="npmWakil" name="npmWakil" required/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="prodiwakil" class="col-sm-3">Program studi</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="prodiWakil" required/>
                    </div>
                  </div>
                <div class="form-group">
                  <label for="dosenWali" class="col-sm-3">Dosen wali</label>
                  <div class="col-sm-9">
                    <span type="text" class="form-control" readonly style="border: none" >{{ $user->dosen->nama_dosen }}</span>
                      <input type="hidden" name="dosenWali" value="{{ $user->dosen_id }}" >
                  </div>
                </div>
                  <div class="form-group">
                    <label for="alasan" class="col-sm-3">Alasan tidak hadir perwalian</label>
                    <div class="col-sm-9">
                      <textarea class="form-control" row="5" id="alasan" name="alasan" required></textarea>
                    </div>
                  </div>
                  <div class=" form-group">
                    <label for="matkul" class="col-sm-3">Mata kuliah yang diambil</label>
                    <div class="row">
                      <div class="col-xs-2">
                        <input type="text" class="form-control" name="kodeMK1" placeholder="Kode" required>
                      </div>
                      <div class="col-xs-4">
                        <input type="text" class="form-control" name="matkul1" placeholder="Nama mata kuliah" required>
                      </div>
                      <div class="col-xs-2">
                        <input type="text" class="form-control" name="sks1" placeholder="sks" required>
                      </div>
                    </div>
                  </div>
                  <div class=" form-group">
                      <div class="col-xs-2 col-sm-offset-3 ">
                        <input type="text" class="form-control" name="kodeMK2" placeholder="Kode" required>
                      </div>
                      <div class="col-xs-4">
                        <input type="text" class="form-control" name="matkul2" placeholder="Nama mata kuliah" required>
                      </div>
                      <div class="col-xs-2">
                        <input type="text" class="form-control" name="sks2" placeholder="sks" required>
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
