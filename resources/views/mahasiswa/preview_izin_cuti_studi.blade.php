<!DOCTYPE html>
  <head>
      <title>Preview</title>
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
            <div class="col-md-8 contentPreview form-horizontal">
              <h4 style="font-weight:bold">FORMULIR PERMOHONAN CUTI STUDI</h4>
              <br>
              <form action = "{{ url('/kirimFormulir') }}" method="post">
                <div class="form-group">
                  <label for="nama" class="col-sm-3 prevLabel">Nama</label>
                  <div class="col-sm-9" name="nama">
                    {{ $nama }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="npm" class="col-sm-3 prevLabel">NPM</label>
                  <div class="col-sm-9" name="npm">
                    {{ $npm }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="prodi" class="col-sm-3 prevLabel">Program Studi</label>
                  <div class="col-sm-9" name="prodi">
                    <span>{{ $user->jurusan->nama_jurusan }}</span>
                    <input type="hidden" name="prodi" value="{{ $prodi }}"/>
                  </div>
                </div>
                <div class="form-group">
                  <label for="fakultas" class="col-sm-3 prevLabel">Fakultas</label>
                  <div class="col-sm-9" name="fakultas">
                    <span>{{ $user->fakultas->nama_fakultas }}</span>
                    <input type="hidden" name="fakultas" value="{{ $fakultas }}"/>
                  </div>
                </div>
                <div class="form-group">
                  <label for="alamat" class="col-sm-3 prevLabel">Alamat</label>
                  <div class="col-sm-9" name="alamat">
                    {{ $alamat}}
                  </div>
                </div>
                <div class="form-group prev">
                  <label for="alasanCutiStudi" class="col-sm-3 prevLabel">Alasan cuti studi ke </label>
                  <div class="col-sm-9" name="alasanCutiStudi">
                    {{ $cutiStudiKe }}<br>
                    {{ $alasanCutiStudi }}
                  </div>
                </div>
                <div class="form-group prev">
                  <label for="catatanDosenWali" class="col-sm-3 prevLabel">Catatan dosen wali </label>
                  <div class="col-sm-9" name="catatanDosenWali">
                    Nama : <span>{{ $user->dosen->nama_dosen }}</span>
                    <input type="hidden" name="dosenWali" value="{{ $dosenWali }}"/><br>
                    {{ $persetujuanDosenWali }}<br>
                    {{ $catatanDosenWali }}
                  </div>
                </div>
                <div class="form-group prev">
                  <label for="catatanKaprodi" class="col-sm-3 prevLabel">Catatan Kaprodi </label>
                  <div class="col-sm-9" name="catatanKaprodi">
                    {{ $persetujuanKaprodi }}<br>
                    {{ $catatanKaprodi }}
                  </div>
                </div>
                <div class="form-group prev">
                  <label for="catatanWDII" class="col-sm-3 prevLabel">Catatan WD II</label>
                  <div class="col-sm-9" name="catatanWDII">
                    {{ $persetujuanWDII }}<br>
                    {{ $catatanWDII }}
                  </div>
                </div>
                <div class="form-group prev">
                  <label for="catatanWDI" class="col-sm-3 prevLabel">Catatan WD I</label>
                  <div class="col-sm-9" name="catatanWDI">
                    {{ $persetujuanWDI }}<br>
                    {{ $catatanWDI }}
                  </div>
                </div>
                <div class="form-group prev">
                  <label for="persetujuanDekan" class="col-sm-3 prevLabel">Persetujuan Dekan</label>
                  <div class="col-sm-9" name="persetujuanDekan" >
                    {{ $persetujuanDekan }}
                  </div>
                </div>
                <div class="form-group prev">
                  <label for="semester" class="col-sm-3 prevLabel">Semester</label>
                  <div class="col-sm-9" name="semester">
                    {{ $semester }}
                  </div>
                </div>
                <div class="form-group prev">
                  <label for="thnAkademik" class="col-sm-3 prevLabel">Tahun Akademik</label>
                  <div class="col-sm-9" name="thnAkademik">
                    {{ $thnAkademik }}
                  </div>
                </div>
                <input type="hidden" value="{{ $formatsurat_id }}" name="idFormat">
                <input type="hidden" value="{{ $dataSurat }}" name="dataSurat">
                {!! csrf_field() !!}
                <br>
                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-10">
                    <button class="btn btn-default" onclick="goBack()">Kembali</button>
                    <button type="submit" class="btn btn-success">Buat Surat</button>
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
    <script>
      function goBack() {
          window.history.back();
      }
    </script>
  </body>
</html>
