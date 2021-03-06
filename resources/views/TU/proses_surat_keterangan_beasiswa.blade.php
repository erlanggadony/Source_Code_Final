<!DOCTYPE html>
  <head>
      <title>Tambah Nomor Surat</title>
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
                <h3 style="font-weight:bold;">Preview Akhir dan Isi Nomor Surat</h3>
                <br>
                <form class="form-horizontal" action="{{ url('/generatePDF') }}" method="post">
                  <div class="form-group">
                    <label class="col-sm-3 prevLabel">Nama</label>
                    <div class="col-sm-9" name="nama">
                        {{ $nama }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 prevLabel">Program Studi</label>
                    <div class="col-sm-9" name="prodi">
                      <span>{{ $mhs->jurusan->nama_jurusan }}</span>
                      <input type="hidden" name="prodi" value="{{ $prodi }}"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 prevLabel">NPM</label>
                    <div class="col-sm-9" name="npm">
                        {{ $npm }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 prevLabel">Semester</label>
                    <div class="col-sm-9" name="semester">
                        {{ $semester }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 prevLabel">Tahun Akademik</label>
                    <div class="col-sm-9" name="thnAkademik">
                        {{ $thnAkademik }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 prevLabel">Jenis Beasiswa</label>
                    <div class="col-sm-9" name="penyediabeasiswa">
                        {{ $penyediabeasiswa }}
                    </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-3" for="noSurat">Nomor Surat</label>
                      <div class="col-sm-6">
                          <input type="text" class="form-control" name="noSurat" required />
                      </div>
                  </div>
                  <input type="hidden" value="{{ $dataSurat }}" id="format" name="data">
                  <input type="hidden" value="{{ $formatsurat_id }}" name="idFormatSurat">
                  <input type="hidden" value="{{ $tanggal }}" name="tanggal">
                  <input type="hidden" value="{{ $pemesan }}" name="pemesan">
                  {!! csrf_field() !!}
                  <br>
                  <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-10">
                      <button class="btn btn-default" onclick="goBack()">Kembali</button>
                      <button type="submit" class="btn btn-success">Buat Surat (PDF)</button>
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
    <script>
      function goBack() {
          window.history.back();
      }

      function compile(){
        var pdftex = new PDFTeX();
        var latex_code =
      }
    </script>
  </body>
</html>
                                                                                      