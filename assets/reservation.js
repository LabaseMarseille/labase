/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
//id="BtnLookForPoste"  onclick="lookForPoste({{ refposte.id }}
const $ = require("jquery");



$(document).ready(function() {
    //alert("ready");
    fct_comm();
});
$('#reservation_comm').change(function() {
    fct_comm();
});
function fct_comm()
{

    var comm =$('#reservation_comm');
    console.log(comm);
    //si la case est cochée, on cache la liste des supports existants et on rends visible celle de la saisie
   // if (comm.is(':checked')){
    if ($("#reservation_comm").is(":checked")) {
        console.log('collectissss');
        $('#listecomm').hide()
        $('#listecomm').show()
    }else{
        console.log('sssss');
        $('#listecomm').show()
        $('#listecomm').hide()
    }
}

$('#reservation_datedebut').change(function() {
    console.log('test');

  // if  ($('#reservation_datefin').val()==null){

       $('#reservation_datefin').val($('#reservation_datedebut').val);
  // }

});

$('#reservation_mailreservation').change(function() {

    $.ajax({
        url: "/reservation/ajxlookMail",
        type: "POST",
        data: jQuery.param({id: $('#reservation_mailreservation').val(),
                            collectif: $('#reservation_collectif').val()}),
        dataType: "json",
        complete: function (r) {
            var tab = JSON.parse(r.responseText)
            console.log(tab);
            $("#reservation_email").val(tab);
        }
    })


});
$('#reservation_collectif').change(function() {
    $.ajax({
        url: "/reservation/ajxlookcollectif",
        type: "POST",
        data: jQuery.param({id: $('#reservation_collectif').val()}),
        dataType: "json",
        complete: function (r) {
            var tab = JSON.parse(r.responseText)
            $("#collectif_abreviation").val(tab[0].abreviation);
        }
    })
});

$('#demande_agent_listeagent').change(function() {

    //console.log($('#demande_agent_datedebutcontrat').val());

    var date = new Date();
    var currentDate = date.toISOString().substring(0,10);
//console.log(currentDate);

    $.ajax({
        url: "/demande/ajxlookagent",
        type: "POST",
        data: jQuery.param({id: $('#demande_agent_listeagent').val()}),
        dataType: "json",
        complete: function (r) {
            var tab = JSON.parse(r.responseText)

            //recuperation des dates
            var d1=new Date(Date.parse(tab[0].datdeb_contr.date));
            var d2=new Date(Date.parse(tab[0].datfin_contr.date));
            var d3=new Date(Date.parse(tab[0].datfinpre_contr.date));

            $("#demande_agent_matricule").val(tab[0].matcle);
            $("#demande_agent_nomuse").val(tab[0].nomuse);
            $("#demande_agent_prenom").val(tab[0].prenom);

            //formatage des dates (AAAA-MM-DD)
            $("#demande_agent_datedebutcontrat").val(d1.getFullYear()+'-'+ ("0" + (d1.getMonth() + 1)).slice(-2)+'-'+("0" + d1.getDate()).slice(-2));
            $("#demande_agent_datefincontratprevu").val(d2.getFullYear()+'-'+ ("0" + (d2.getMonth() + 1)).slice(-2)+'-'+("0" + d2.getDate()).slice(-2));
            $("#demande_agent_datefincontratreel").val(d3.getFullYear()+'-'+ ("0" + (d3.getMonth() + 1)).slice(-2)+'-'+("0" + d3.getDate()).slice(-2));

        }
    })
});


var $site = $('#demande_poste_site');
// When site gets selected ...
$site.change(function() {
    let site=$site.val();


    $('#demande_poste_batiment option').hide();
    console.log(site);
    console.log($('#demande_poste_batiment option[text|="'+site+'"]'));
    $('#demande_poste_batiment option:contains("'+site+'")').show();

});


