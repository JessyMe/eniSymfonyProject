
let $sortie_ville = $("#sortie_ville")

$sortie_ville.change(function()
    {
        let $form = $(this).closest('form')
        let data = {}


        data[$sortie_ville.attr('name')] = $sortie_ville.val()

       $.post($form.attr('action'), data).then(function (response)
           {
               $("#sortie_lieu").replaceWith(
                   $(response).find("#sortie_lieu")
               )
           }
       )
    })

// ajax proche de la doc symfony
/*let $sortie_ville = $("#sortie_ville")

$sortie_ville.change(function (){

    let $form = $(this).closest('form')
    let data = {}

    data[$sortie_ville.attr('name')] = $sortie_ville.val()

    $.ajax({
        url:$form.attr('action'),
        type: $form.attr('method'),
        data : data,
        success: function(html){
            $("#sortie_lieu").replaceWith(
                $(html).find("#sortie_lieu")
            )
        }
    })
})*/