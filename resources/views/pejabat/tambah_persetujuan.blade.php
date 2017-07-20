<!DOCTYPE html>
  <head>
      <title>Isi Catatan dan Persetujuan</title>
      <link href="{{ asset("/bootstrap-3.3.7-dist/css/bootstrap.css") }}" rel="stylesheet" type="text/css" />
      <link href="{{ asset("/css/styles_list_surat.css") }}" rel="stylesheet" type="text/css">
  </head>

  <body>
    <div>
        <img id=banner src="{{ asset("/images/banner ftis.png") }}" />
    </div>


    <!-- Navigation Here -->
    @include('pejabat.menu')

    <div class="container">
      <div class="main">
          <div class="row">
            <div class="col-md-8 content">
              <h1>Isi Persetujuan & Catatan</h1>
              <br>
              <form action="/downloadLampiran" class="form-horizontal" method="post">
                <div class="form-group">
                  <label for="catatanDekan" class="col-sm-3">Lihat lampiran </label>
                  <div class="col-sm-9">
                    <input type="hidden" value="{{ $link }}" name="link">
                      <button type="submit" class="btn btn-link">Klik disini</button>
                      {!! csrf_field() !!}
                  </div>
                </div> 
              </form>
              <form class="form-horizontal" method="post" action="/previewCatatan">
                <div class="form-group">
                  <label for="persetujuan" class="col-sm-3">Persetujuan</label>
                  <div class="col-sm-9">
                    <label class="radio-inline">
                      <input type="radio" name="persetujuan" value="Setuju" checked required>Setuju
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="persetujuan" value="Tidak setuju">Tidak setuju
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label for="catatanDekan" class="col-sm-3">Catatan (Opsional)</label>
                  <div class="col-sm-9">
                    <textarea class="form-control" id="catatan" row="5" name="catatan"></textarea>
                  </div>
                </div>
                {!! csrf_field() !!}
                <input type="hidden" value="{{ $dataSurat }}" name="dataSurat">
                <input type="hidden" value="{{ $formatsurat_id }}" name="formatsurat_id">
                <input type="hidden" value="{{ $idPesananSurat }}" name="idPesananSurat">
                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-10">
                    <button type="submit" class="btn btn-primary">Lanjutkan</button>
                  </div>
                </div>
              </form>
            </div>
            @include('pejabat.profile_bar')
          </div>
      </div>
    </div>
    <div class="footer">
        hahahahahahahahahahahahahahahhahahahahahaha
    </div>
  </body>
</html>
