<!DOCTYPE html>
  <head>
      <title>Setting</title>
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
                <h1>Atur Semester dan Tahun Ajaran</h1>
                <br>
                <form class="form-horizontal" method="post" action="/updateSemester">
                  <div class="form-group">
                    <label for="persetujuan" class="col-sm-3">Semester</label>
                    <div class="col-sm-9">
                      <label class="radio-inline">
                        <input type="radio" name="semester" value="Ganjil" checked required>Ganjil
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="semester" value="Genap">Genap
                      </label>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="catatanDekan" class="col-sm-3">Tahun Akademik</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="thnAkademik">
                    </div>
                  </div>
                  {!! csrf_field() !!}
                  <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-10">
                      <button type="submit" class="btn btn-primary">Lanjutkan</button>
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
