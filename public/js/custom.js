jQuery(document).ready(function($){
  var ctrMatkul = 8;
  var ctrAnggota = 4;

  $(".showText").css("display: none;");


  $("#btnAddTextField").click(function(){
      $(".addTextField").append(addMatkul);
      ctrMatkul--;
      if (ctrMatkul == 0) {
         document.getElementById("btnAddTextField").style.display = 'none';
       }
  });

  $("#pengambilan_belum").click(function(){
    var clickButton = $(this).val();
    if(clickButton == "belum"){
      document.getElementById("pengambilan_belum").style.display = 'none';
      document.getElementById("pengambilan_sudah").style.display = 'block';
    }
  });

  $(".addMember").click(function(){
    var clickButton = $(this).val();
      // console.log(clickButton);
    if(clickButton == "ya"){
      document.getElementById("showText").style.display = 'block';
      document.getElementById("showButton").style.display = 'block';
    }
  });

  $("#btnAddAnggota").click(function(){
      $(".addMember").append(addAnggota);
      ctrAnggota--;
      if (ctrAnggota == 0) {
         document.getElementById("btnAddAnggota").style.display = 'none';
       }
  });
});
