
<!DOCTYPE html>
  <head>
      <title>Pilih Kategori Surat</title>
      <link href="{{ asset("/bootstrap-3.3.7-dist/css/bootstrap.css") }}" rel="stylesheet" type="text/css" />
      <link href="{{ asset("/css/styles_list_surat.css") }}" rel="stylesheet" type="text/css">

  </head>

  <body>
    <div>
        <img id=banner src="{{ asset("/images/banner ftis.png") }}" />
    </div>


    <!-- Navigation Here -->
    @include('mahasiswa.menu')

    <div class="container">
      <div class="main">
        <div class="row">
          <div class="col-md-8 content">
            <h1>Pilih Kategori Surat</h1>
            <br>
            <form class="form-horizontal" action="{{ url('/pilih_jenis_surat') }}" method="post">
              <div class="form-group">
                <div class="col-sm-9">
                    <div class="radio">
                      <label>
                        <input type="radio"  name="jenis_surat" value="surat_izin" required>
                        Surat Izin
                      </label>
                    </div>
                    <div class="radio">
                      <label>
                        <input type="radio"  name="jenis_surat" value="surat_keterangan" required>
                        Surat Keterangan
                      </label>
                    </div>
                    <div class="radio">
                      <label>
                        <input type="radio"  name="jenis_surat" value="surat_perwakilan" required>
                        Surat Perwakilan
                      </label>
                    </div>
                    <div class="radio">
                      <label>
                        <input type="radio"  name="jenis_surat" value="surat_pengantar" required>
                        Surat Pengantar
                      </label>
                    </div>
                </div>
              </div>
              {!! csrf_field() !!}
              <div class="form-group">
                <div class="col-sm-6">
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
