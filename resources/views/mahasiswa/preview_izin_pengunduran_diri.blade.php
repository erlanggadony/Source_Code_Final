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
              <h4 style="font-weight:bold;">SURAT PERMOHONAN PENGUNDURAN DIRI MAHASISWA</h4>
              <br>
              <p>
              Kepada Yth.<br>
              Dekan Fakultas Teknologi Informasi dan Sains<br>
              Universitas Katolik Parahyangan<br>
              Jl. Ciumbuleuit no. 94 Bandung<br>
              <br>
              Dengan ini saya,
              </p>
              <form action = "{{ url('/kirimFormulir') }}" method="post">
                <div class="form-group">
                  <label class="col-sm-3 prevLabel">NIRM</label>
                  <div class="col-sm-9" >
                    {{ $nirm }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="npm" class="col-sm-3 prevLabel">NPM</label>
                  <div class="col-sm-9">
                    {{ $npm }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="nama" class="col-sm-3 prevLabel">Nama</label>
                  <div class="col-sm-9">
                    {{ $nama }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="npm" class="col-sm-3 prevLabel">Alamat</label>
                  <div class="col-sm-9">
                    {{ $alamat }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="npm" class="col-sm-3 prevLabel">Nomor Telepon</label>
                  <div class="col-sm-9">
                    {{ $noTelepon }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="npm" class="col-sm-3 prevLabel">Nomor Telepon</label>
                  <div class="col-sm-9">
                    {{ $namaOrtu }}
                  </div>
                </div>
                <hr style="border-width: 2px;border-color:black">
                <div style="font-weight:bold;text-align:center">REKOMENDASI DAN PERSETUJUAN *)</div>
                <hr style="border-width: 2px;border-color:black">
                <div class="form-group prev">
                  <label for="npm" class="col-sm-3 prevLabel">DOSEN WALI</label>
                  <div class="col-sm-9">
                    {{ $persetujuanDosenWali }}<br>
                    {{ $catatanDosenWali }}
                  </div>
                </div>
                <div class="form-group prev">
                  <label for="npm" class="col-sm-3 prevLabel">KAPRODI</label>
                  <div class="col-sm-9">
                    {{ $persetujuanKaprodi }}<br>
                    {{ $catatanKaprodi }}
                  </div>
                </div>
                <div class="form-group prev">
                  <label for="npm" class="col-sm-3 prevLabel">WAKIL DEKAN II </label>
                  <div class="col-sm-9">
                    {{ $persetujuanWDII }}<br>
                    {{ $catatanWDII }}
                  </div>
                </div>
                <div class="form-group prev">
                  <label for="npm" class="col-sm-3 prevLabel">WAKIL DEKAN I</label>
                  <div class="col-sm-9">
                    {{ $persetujuanWDI }}<br>
                    {{ $catatanWDI }}
                  </div>
                </div>
                <div class="form-group prev">
                  <label for="npm" class="col-sm-3 prevLabel">DEKAN</label>
                  <div class="col-sm-9">
                    {{ $persetujuanDekan }}<br>
                    {{ $catatanDekan }}
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
