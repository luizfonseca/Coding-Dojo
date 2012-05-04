$(document).ready(function()
{
  $("input[type=text]").focus(function()
  {
    if (this.value == this.defaultValue) this.value = "";
  });
  $("#join_it").submit(function(event)
  {
    event.preventDefault();

    var name  = $("*[name=name]").val();
    var mail  = $("*[name=mail]").val();
    var lang  = $("*[name=lang]").val();
    var city  = $("*[name=city]").val();

    $.post('./data/form.php', { name: name, mail: mail, lang: lang, city: city }, function(json){
      if (json.success)
      {
        $("#join_it").fadeOut("slow", function(){
          var img = '<div style="width:316px;margin:0 auto"><a href="http://facebook.com/theweblexia"><img src="./img/success.png" title="Dados enviados com sucesso"/></a></div>';
          $("#join_it").empty().fadeIn("slow").append(img);
        });
      }
      else
      {
        var data = "<ul>" + json.msg + "</ul>";
        $("#message").css({backgroundColor: "#ffd6d6", width: "800px",textIndent: "10px", color: "#000", margin: "20px 20px", padding: "10px 10px"});
        $("#message").empty().fadeIn("slow").append(data);
      }
      }, "json" );
    });
  });