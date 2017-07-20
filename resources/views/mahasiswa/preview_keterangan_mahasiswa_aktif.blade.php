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
              <h4 style="font-weight:bold;text-decoration:underline">FORMULIR PERMOHONAN SURAT KETERANGAN</h4>
              <br>
              <p>
                Yang bertanda tangan di bawah ini :</p>
                <form action = "{{ url('/kirimFormulir') }}" method="post">
                  <div class="form-group">
                    <label class="col-sm-4 prevLabel">Nama</label>
                    <div class="col-sm-8 prev">
                      {{ $nama }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 prevLabel">NPM</label>
                    <div class="col-sm-8 prev">
                      {{ $npm }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 prevLabel">Program Studi</label>
                    <div class="col-sm-8 prev">
                      <span>{{ $user->jurusan->nama_jurusan }}</span>
                      <input type="hidden" name="prodi" value="{{ $prodi }}"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 prevLabel">Semester</label>
                    <div class="col-sm-8">
                      {{ $semester }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 prevLabel">Tempat, Tanggal Lahir</label>
                    <div class="col-sm-8 prev">
                      {{ $kota_lahir}}, {{ $tglLahir}}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 prevLabel">Alamat di Bandung</label>
                    <div class="col-sm-8">
                      {{ $alamat }}
                    </div>
                  </div>
                  <input type="hidden" value="{{ $formatsurat_id }}" name="idFormat">
                  <input type="hidden" value="{{ $dataSurat }}" name="dataSurat">
                  {!! csrf_field() !!}
                  <br>
                  <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-10">
                      <button class="btn btn-default" onclick="goBack()">Go Back</button>
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
