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
            <div class="col-md-8 content">
                <h3 style="font-weight:bold;">FORMULIR SURAT PENGANTAR PEMBUATAN VISA</h3>
                <br>
                <form class="form-horizontal" action="{{ url('/kirimFormulir') }}" method="post">
                  <div class="form-group">
                    <label class="col-sm-3 prevLabel">Nama</label>
                    <div class="col-sm-9">
                        {{ $nama }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 prevLabel">Tanggal Lahir</label>
                    <div class="col-sm-9">
                        {{ $tglLahir }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 prevLabel">Kewarganegaraan</label>
                    <div class="col-sm-9">
                        {{ $kewarganegaraan }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 prevLabel">Organisasi tujuan</label>
                    <div class="col-sm-9">
                        {{ $organisasiTujuan }}
                        <input type="hidden" value="{{ $organisasiTujuan }}" name="organisasiTujuan">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 prevLabel">Tahun Akademik</label>
                    <div class="col-sm-9">
                        {{ $thnAkademik }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 prevLabel">Negara tujuan</label>
                    <div class="col-sm-9">
                        {{ $negaraTujuan }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 prevLabel">Tanggal kunjungan</label>
                    <div class="col-sm-9">
                        {{ $tanggalKunjungan }}
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
