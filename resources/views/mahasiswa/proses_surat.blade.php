<!DOCTYPE html>
  <head>
      <title>Proses Surat</title>
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
            <div class="col-md-8 content">
              <form class="form-inline" action= "{{ url('/home_mahasiswa') }}" method="get">
                <div class="form-group">
                  <label for="kategori_mahasiswa">Cari berdasarkan :</label><br>
                  <select name="kategori_mahasiswa" class="form-control">
                    <option value="">Cari semua surat</option>
                    <option value="penerimaSurat">Penerima surat</option>
                    <option value="jenis_surat">Jenis surat</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="searchBox">Kata kunci :</label><br>
                  <input type="text" name="searchBox" class="form-control" size="80" />
                  <button type="submit" name="findmail" class="btn btn-primary">Cari surat</button>
                </div>
              </form>
              <br>
              <table class="table table-striped">
                @if($pesanansurats != null)
                  @if(count($pesanansurats) == 0)
                      <tr>
                          <td colspan="5" align="center">Tidak ada pesanan surat....</td>
                      </tr>
                  @else
                      <tr>
                        <th>TANGGAL PEMBUATAN</th>
                        <th>PENERIMA SURAT</th>
                        <th>JENIS SURAT</th>
                        <th>STATUS</th>
                      </tr>
                      @foreach($pesanansurats as $pesanansurat)
                        <tr>
                          <td class="ctr">{{ $pesanansurat->created_at }}</td>
                          <td class="ctr">{{ $pesanansurat->penerimaSurat }}</td>
                          <td class="ctr">{{ $pesanansurat->formatsurat->jenis_surat }}</td>
                          <td style="text-align:center">
                            @if($pesanansurat->count == 5)
                                "Surat sudah selesai dibuat"
                            @else
                                "Surat masih dalam proses pembuatan"
                            @endif
                          </td>
                        </tr>
                      @endforeach
                  @endif
                @endif
              </table>
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
