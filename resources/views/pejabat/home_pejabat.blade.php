<!DOCTYPE html>
  <head>
      <title>Home - Pejabat</title>
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
              <form class="form-inline" action= "{{ url('/home_pejabat') }}" method="get">
                <div class="form-group">
                  <label for="kategori">Cari berdasarkan :</label><br>
                  <select name="kategori" class="form-control">
                    <option value="">Cari semua surat</option>
                    <option value="jenis_surat">Jenis surat</option>
                    <option value="pemohonSurat">Pemohon surat</option>
                    <option value="penerimaSurat">Penerima surat</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="searchBox">Kata kunci :</label><br>
                  <input type="text" name="searchBox" class="form-control" size="69" />
                  <button type="submit" name="findmail" class="btn btn-primary">Cari surat</button>
                </div>
              </form>
              <br>
              <table class="table table-striped">
                <tr>
                  @if(count($pesanansurats) == 0)
                    <tr>
                        <td colspan="5" align="center">Tidak ada pesanan surat ...</td>
                    </tr>
                @else
                    <tr>
                      <th>JENIS SURAT</th>
                      <th>PEMOHON</th>
                      <th>PENERIMA</th>
                      <th>TANGGAL PEMBUATAN</th>
                      <th>DATA SURAT</th>
                      <th>KONTROL</th>
                    </tr>
                    @foreach($pesanansurats as $pesanansurat)
                        <tr>
                          <td class="ctr">{{ $pesanansurat->formatsurat->jenis_surat }}</td>
                          <td class="ctr">{{ $pesanansurat->mahasiswa->nama_mahasiswa }}</td>
                          <td class="ctr">{{ $pesanansurat->penerimaSurat }}</td>
                          <td class="ctr">{{ $pesanansurat->created_at }}</td>
                          <td class="ctr"><textarea rows="5" cols="30" style="border: none" readonly>{{ $pesanansurat->dataSurat }}</textarea></td>
                          <td class="ctr">
                            @if($pesanansurat->formatsurat->id == 9 || $pesanansurat->formatsurat->id == 10)
                            <form action="/persetujuan" method="post">
                              <input type="hidden" value="{{ $pesanansurat->formatsurat_id }}" name="idFormatSurat">
                              <input type="hidden" value="{{ $pesanansurat->dataSurat }}" name="dataSurat">
                              <input type="hidden" value="{{ $pesanansurat->id }}" name="idPesananSurat">
                              {!! csrf_field() !!}
                              <button type="submit" class="btn btn-default">Tambah<br>Persetujuan</button>
                            </form>
                            @else
                              <!-- <span class="btn btn-default"></span> -->
                            @endif
                          </td>
                        </tr>
                    @endforeach
                  @endif
              </table>
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
