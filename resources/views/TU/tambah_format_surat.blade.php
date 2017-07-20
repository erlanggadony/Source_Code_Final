
<!DOCTYPE html>
  <head>
      <title>Input Format Surat</title>
      <link href="{{ asset("/bootstrap-3.3.7-dist/css/bootstrap.css") }}" rel="stylesheet" type="text/css" />
      <link href="{{ asset("/css/styles_list_surat.css") }}" rel="stylesheet" type="text/css">

  </head>

  <body>
    <div>
        <img id=banner src="{{ asset("/images/banner ftis.png") }}" />
    </div>

      @include('tu.menu')

    <div class="container">
      <div class="main">
          <div class="row">
            <div class="col-md-8 content">
              <h1>Input Format Surat</h1>
              <br><br>
              <form class="form-horizontal" action="{{ url('/uploadFormat')}}" method="post"  enctype="multipart/form-data">
                <div class="form-group">
                  <label for="idFormatSurat" class="col-sm-3">ID Format Surat</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="idFormatSurat" name="idFormatSurat" required>
                  </div>
                </div>
                <div class="form-group">
                  <label for="jenis_surat" class="col-sm-3">Jenis Surat</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="jenis_surat" name="jenis_surat" required />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3" for="keterangan">Keterangan</label>
                  <div class="col-sm-9">
                    <textarea class="form-control" row="3" id="keterangan" name="keterangan" required></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label for="nama" class="col-sm-3">Upload Format Surat</label>
                  <div class="col-sm-9">
                    <input type="file" class="form-control" name="uploadFormat" required />
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-3"></div>
                  <div class="col-sm-9 checkbox">
                    <label><input type="checkbox" required/> Saya sudah yakin</label>
                  </div>
                </div>
                {!! csrf_field() !!}
                <div class="form-group">
                  <div class="col-sm-3"></div>
                  <div class="col-sm-9">
                    <button type="submit" class="btn btn-primary">Upload format surat (.TeX)</button>
                  </div>
                </div>
              </form>
            </div>
            @include('tu.profile_bar')
          </div>
      </div>
    </div>
    <div class="footer">
        hahahahahahahahahahahahahahahhahahahahahaha
    </div>
  </body>
</html>
