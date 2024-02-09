/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
//id="BtnLookForPoste"  onclick="lookForPoste({{ refposte.id }}
const $ = require("jquery");





$('#recurrent_mailreservation').change(function() {
    if ($('#recurrentmail_reservation').val()==1){
        console.log("choix1");
    }
    if ($('#recurrent_mailreservation').val()==2){
        console.log("choix2");
    }
    if ($('#recurrent_mailreservation').val()==3){
        console.log("choix13");
    }
    console.log($('#recurrent_collectif').val());
    $.ajax({
        url: "/reservation/ajxlookMail",
        type: "POST",
        data: jQuery.param({id: $('#recurrent_mailreservation').val(),
                            collectif: $('#recurrent_collectif').val()}),
        dataType: "json",
        complete: function (r) {
            var tab = JSON.parse(r.responseText)
            console.log(tab);
            $("#recurrent_email").val(tab);
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




