<!DOCTYPE html>
  <head>
      <title>History - Pejabat</title>
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
              <form class="form-inline" action= "{{ url('/history_pejabat') }}" method="get">
                <div class="form-group">
                  <label for="kategori_history_surat">Cari berdasarkan :</label><br>
                  <select name="kategori_history_surat" class="form-control">
                    <option value="">Cari semua surat</option>
                    <option value="no_surat">Nomor Surat</option>
                    <option value="jenis_surat">Jenis Surat</option>
                    <option value="perihal">Perihal</option>
                    <option value="pemohonSurat">Pemohon surat</option>
                    <option value="penerimaSurat">Penerima surat</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="searchBox_pejabat">Kata kunci :</label><br>
                  <input type="text" name="searchBox_pejabat" class="form-control" size="68" />
                  <button type="submit" name="findmail" class="btn btn-primary">Cari surat</button>
                </div>
              </form>
              <br>
              <table class="table table-striped table-hover">
                @if($historysurats != null)
                  @if(count($historysurats) == 0)
                      <tr>
                          <td colspan="5" align="center">Tidak ada history surat....</td>
                      </tr>
                  @else
                      <tr>
                        <th>NOMOR SURAT</th>
                        <th>JENIS SURAT</th>
                        <th>PERIHAL</th>
                        <th>PEMOHON</th>
                        <th>PENERIMA</th>
                        <th>TANGGAL PEMBUATAN</th>
                        <th>ARSIP</th>
                        <th>TANDA TANGAN</th>
                        <th>AMBIL</th>
                      </tr>
                      @foreach($historysurats as $historysurat)
                        <tr>
                          <td class="ctr">{{ $historysurat->no_surat }}</td>
                          <td class="ctr">{{ $historysurat->formatsurat->jenis_surat }}</td>
                          <td class="ctr">{{ $historysurat->perihal }}</td>
                          <td class="ctr">{{ $historysurat->mahasiswa->nama_mahasiswa  }}</td>
                          <td class="ctr">{{ $historysurat->penerimaSurat }}</td>
                          <td class="ctr">{{ $historysurat->created_at }}</td>
                          <td style="text-align:center">
                            <form action="/tampilkanPDF" class="form-horizontal" method="post">
                                <input type="hidden" value="{{ $historysurat->id }}" name="history_id">
                                <button type="submit" class="btn btn-link">Klik<br>disini</button>
                                {!! csrf_field() !!}
                            </form>
                          </td>
                          <td align="center">
                            @if($historysurat->penandatanganan)
                              <button type="submit" disabled class="btn btn-success" style="display:block">Sudah</button>
                            @else
                              <form action="{{url('/ubahStatusPenandatanganan')}}" method="post">
                                <input type="hidden" value="{{ $historysurat->id }}" name="id">
                                {!! csrf_field() !!}
                                <button type="submit" class="btn btn-default " style="display:block">Belum</button>
                              </form>
                            @endif
                          </td>
                          <td class="ctr">
                            @if($historysurat->pengambilan)
                              <p>Sudah</p>
                            @else
                              <p>Belum</p>
                            @endif
                          </td>
                        </tr>
                      @endforeach
                  @endif
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
