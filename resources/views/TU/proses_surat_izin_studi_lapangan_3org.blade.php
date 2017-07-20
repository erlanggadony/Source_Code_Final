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
                  <label for="nama" class="col-sm-3 prevLabel">Nama</label>
                  <div class="col-sm-9">
                    {{ $nama }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="npm" class="col-sm-3 prevLabel">NPM</label>
                  <div class="col-sm-9">
                    {{ $npm }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="npm" class="col-sm-3 prevLabel">Program Studi</label>
                  <div class="col-sm-9">
                    <span>{{ $mhs->jurusan->nama_jurusan }}</span>
                    <input type="hidden" name="prodi" value="{{ $prodi }}"/>
                  </div>
                </div>
                <div class="form-group">
                  <label for="npm" class="col-sm-3 prevLabel">Mata Kuliah</label>
                  <div class="col-sm-9">
                    {{ $matkul }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="npm" class="col-sm-3 prevLabel">Topik</label>
                  <div class="col-sm-9">
                    {{ $topik}}
                  </div>
                </div>
                <div class="form-group">
                  <label for="npm" class="col-sm-3 prevLabel">Organisasi Tujuan</label>
                  <div class="col-sm-9">
                    {{ $organisasi }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="npm" class="col-sm-3 prevLabel">Alamat Organisasi</label>
                  <div class="col-sm-9">
                    {{ $alamatOrganisasi}}
                  </div>
                </div>
                <div class="form-group">
                  <label for="npm" class="col-sm-3 prevLabel">Keperluan Kunjungan</label>
                  <div class="col-sm-9">
                    {{ $keperluanKunjungan }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="npm" class="col-sm-3 prevLabel">Anggota Kelompok</label>
                  <div class="col-sm-9">
                    <table class="table table-bordered table-hover">
                      <tr>
                        <th style="text-align:center;background-color:#eee">NPM</th>
                        <th style="text-align:center;background-color:#eee">Nama</th>
                      </tr>
                      <tr>
                        <td>{{ $npmAnggota1 }}</td>
                        <td>{{ $namaAnggota1 }}</td>
                      </tr>
                      <tr>
                        <td>{{ $npmAnggota2 }}</td>
                        <td>{{ $namaAnggota2 }}</td>
                      </tr>
                    </table>
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
    </div>'<script>
      function goBack() {
          window.history.back();
      }
    </script>'
  </body>
</html>
