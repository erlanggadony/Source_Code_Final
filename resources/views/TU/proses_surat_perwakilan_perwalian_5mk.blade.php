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
                  <label for="nama" class="col-sm-3 prevLabel">Semester</label>
                  <div class="col-sm-9">
                    {{ $semester }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="npm" class="col-sm-3 prevLabel">Tahun AKademik</label>
                  <div class="col-sm-9">
                    {{ $thnAkademik }}
                  </div>
                </div>
                <p class="col-sm-12" style="font-weight:bold">
                  IDENTITAS MAHASISWA YANG PERWALIANNYA DIWAKILKAN :
                </p>
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
                <p class="col-md-12" style="font-weight:bold">
                  IDENTITAS MAHASISWA YANG DIBERI KUASA PERWALIAN :
                </p>
                <div class="form-group">
                  <label for="nama" class="col-sm-3 prevLabel">Nama</label>
                  <div class="col-sm-9">
                    {{ $namaWakil }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="npm" class="col-sm-3 prevLabel">NPM</label>
                  <div class="col-sm-9">
                    {{ $npmWakil }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="npm" class="col-sm-3 prevLabel">Program Studi</label>
                  <div class="col-sm-9">
                    {{ $prodiWakil }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="npm" class="col-sm-3 prevLabel">Dosen Wali</label>
                  <div class="col-sm-9">
                    <span>{{ $mhs->dosen->nama_dosen }}</span>
                    <input type="hidden" name="dosenWali" value="{{ $dosenWali }}"/>
                  </div>
                </div>
                <div class="form-group">
                  <label for="npm" class="col-sm-3 prevLabel">Alasan tidak bisa hadir perwalian</label>
                  <div class="col-sm-9">
                    {{ $alasan }}
                  </div>
                </div>
                <div class="form-group">
                  <label for="npm" class="col-sm-3 prevLabel">Mata kuliah yang diambil di FRS</label>
                  <div class="col-sm-9">
                    <table class="table table-bordered table-hover">
                      <tr>
                        <th style="text-align:center;background-color:#eee">No.</th>
                        <th style="text-align:center;background-color:#eee">Kode Mata Kuliah</th>
                        <th style="text-align:center;background-color:#eee">Nama Mata Kuliah</th>
                        <th style="text-align:center;background-color:#eee">SKS</th>
                      </tr>
                      <tr>
                        <td>1</td>
                        <td>{{ $kodeMK1 }}</td>
                        <td>{{ $matkul1 }}</td>
                        <td>{{ $sks1 }}</td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td>{{ $kodeMK2 }}</td>
                        <td>{{ $matkul2 }}</td>
                        <td>{{ $sks2 }}</td>
                      </tr>
                      <tr>
                        <td>3</td>
                        <td>{{ $kodeMK3 }}</td>
                        <td>{{ $matkul3 }}</td>
                        <td>{{ $sks3 }}</td>
                      </tr>
                      <tr>
                        <td>4</td>
                        <td>{{ $kodeMK4 }}</td>
                        <td>{{ $matkul4 }}</td>
                        <td>{{ $sks4 }}</td>
                      </tr>
                      <tr>
                        <td>5</td>
                        <td>{{ $kodeMK5 }}</td>
                        <td>{{ $matkul5 }}</td>
                        <td>{{ $sks5 }}</td>
                      </tr>
                    </table>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <p>
                      <p style="font-weight:bold;text-decoration:underline">LAMPIRAN</p>
                      1. Fotokopi KTM mahasiswa yanng menerima kuasa perwalian
                    </p>
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
    </script>
  </body>
</html>
