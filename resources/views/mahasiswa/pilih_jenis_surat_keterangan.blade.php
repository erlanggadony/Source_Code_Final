<!DOCTYPE html>
  <head>
      <title>Pilih Jenis Surat</title>
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
            <h1>Pilih Jenis Surat</h1>
            <br>
            <form class="form-horizontal" action="{{ url('/isi_data_diri') }}" method="post">
              <div class="form-group">
                <div class="col-sm-9">
                    @foreach($formatsurats as $formatsurat)
                      @if(($formatsurat->id == 1) || ($formatsurat->id == 2))
                        <div class="radio">
                          <label>
                            <input type="radio"  name="jenis_surat" value="{{ $formatsurat->id }}" required>
                            {{ $formatsurat->jenis_surat }}
                          </label>
                        </div>
                      @endif
                    @endforeach
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
