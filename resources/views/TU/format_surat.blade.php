
<!DOCTYPE html>
  <head>
      <title>Format Surat</title>
      <link href="{{ asset("/bootstrap-3.3.7-dist/css/bootstrap.css") }}" rel="stylesheet" type="text/css" />
      <link href="{{ asset("/css/styles_list_surat.css") }}" rel="stylesheet" type="text/css" />
      <script src="{{ asset("/js/jquery-3.2.0.min.js") }}" type="text/javascript"></script>

  </head>

  <body>
    <div>
        <img id=banner src="{{ asset("/images/banner ftis.png") }}" />
    </div>

    @include('tu.menu')

    <div id="myModal" class="modal">
      <div class="modal-content" id="show">
        <span class="close">&times;</span>
      </div>
    </div>
    <div class="container">
      <div class="main">
          <div class="row">
            <div class="col-md-8 content" id ="test">
              <a href="{{ URL::to('/tambah_format_surat') }}" class="btn btn-default">Tambah Format Surat</a>
              <form class="form-inline" action= "{{ url('/format_surat') }}" method="get">
                <div class="form-group">
                  <label for="kategori_format_surat">Cari berdasarkan :</label><br>
                  <select name="kategori_format_surat" class="form-control">
                    <option value="">Cari semua surat</option>
                    <option value="idFormatSurat">ID Format Surat</option>
                    <option value="jenis_surat">Jenis Surat</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="searchBox_format_surat">Kata kunci :</label><br>
                  <input type="text" name="searchBox_format_surat" class="form-control" size = "65">
                  <button type="submit" name="findmail" class="btn btn-primary">Cari format surat</button>
                </div>
              </form>
              <br>
              <table class="table table-striped table-hover">
                @if($formatsurats != null)
                  @if(count($formatsurats) == 0)
                      <tr>
                          <td colspan="5" align="center">Tidak ada format surat ...</td>
                      </tr>
                  @else
                      <tr>
                        <th>ID FORMAT SURAT</th>
                        <th>JENIS SURAT</th>
                        <th>KETERANGAN</th>
                        <th>FILE SURAT</th>
                        <th colspan ="2"> KONTROL</th>
                      </tr>
                      @foreach($formatsurats as $formatsurat)
                        <tr>
                          <td class="ctr">{{ $formatsurat->idFormatSurat }}</td>
                          <td class="ctr">{{ $formatsurat->jenis_surat }}</td>
                          <td class="ctr">{{ $formatsurat->keterangan }}</td>
                          <td class="ctr">
                              <button type="submit" onclick="showModal({{ $formatsurat->id }})" class="btn btn-link">Klik disini</button>
                          </td>
                          <td class="ctr">
                            <form action="/hapusFormatsurat" method="post">
                              <input type="hidden" value="{{ $formatsurat->id }}" name="deleteID">
                              {!! csrf_field() !!}
                              <button type="submit" class="btn btn-danger" aria-label="Remove" data-toggle="tooltip" title="Remove">
                                  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                              </button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                  @endif
                @endif
              </table>
              <div style="text-align:center">{!! $formatsurats->links() !!}</div>
            </div>
            @include('tu.profile_bar')
          </div>
      </div>
    </div>
    <div class="footer">
        hahahahahahahahahahahahahahahhahahahahahaha
    </div>
    <script type="text/javascript" src="{{ asset("js/jquery-3.2.0.min.js") }}"></script>
    <script>
        // Get the modal
        var modal = document.getElementById('myModal');
        var btn = document.getElementById("showFormatID");
        var span = document.getElementsByClassName("close")[0];

        function tutup() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        function showModal(id){
          var jqxhr = $.get( "http://127.0.0.1:8000/Api/showFormatSurat?id=" + id, function() {
          })
          .done(function(data) {
            document.getElementById("show").innerHTML = `<span onclick='tutup()' class='close'>&times;</span>` + data.stringFormat;
            // $('#myModal').css({'position' : 'fixed'});
            modal.style.display = "block";
            modal.style.position = "fixed";
            // $('#test').css('position' : 'fixed');
          })
          .fail(function(error) {
            alert( "Error : " + error);
          });
        }
    </script>
  </body>
</html>
