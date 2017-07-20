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
              <h4 style="font-weight:bold;">FORMULIR PERWALIAN YANG DIWAKILKAN</h4>
              <br>
              <form action = "{{ url('/kirimFormulir') }}" method="post">
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
                    <span>{{ $user->jurusan->nama_jurusan }}</span>
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
                    <span>{{ $user->dosen->nama_dosen }}</span>
                    <input type="hidden" value="{{ $dosenWali }}" name="dosenWali">
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
                      <tr>
                        <td>6</td>
                        <td>{{ $kodeMK6 }}</td>
                        <td>{{ $matkul6 }}</td>
                        <td>{{ $sks6 }}</td>
                      </tr>
                      <tr>
                        <td>7</td>
                        <td>{{ $kodeMK7 }}</td>
                        <td>{{ $matkul7 }}</td>
                        <td>{{ $sks7 }}</td>
                      </tr>
                      <tr>
                        <td>8</td>
                        <td>{{ $kodeMK8 }}</td>
                        <td>{{ $matkul8 }}</td>
                        <td>{{ $sks8 }}</td>
                      </tr>
                      <tr>
                        <td>9</td>
                        <td>{{ $kodeMK9 }}</td>
                        <td>{{ $matkul9 }}</td>
                        <td>{{ $sks9 }}</td>
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
